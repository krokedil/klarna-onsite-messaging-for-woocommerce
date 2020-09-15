<?php
/**
 * File for the Klarna_OnSite_Messaging_Widget class.
 *
 * @package Klarna_OnSite_Messaging/Classes
 */

/**
 * Class for the widget for OSM.
 */
class Klarna_OnSite_Messaging_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		parent::__construct(
			'klarna_osm', // Base ID.
			__( 'Klarna On-Site Messaging', 'woocommerce-gateway-klarna' ), // Name.
			array( 'description' => __( 'Displays a Klarna banner in your store.', 'woocommerce-gateway-klarna' ) ) // Description.
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_script( 'klarna-onsite-messaging' );
		wp_enqueue_script( 'klarna_onsite_messaging' );

		$default_args = array(
			'data-key'             => 'homepage-promotion-wide',
			'data-theme'           => '',
			'data-purchase-amount' => '',
		);
		$title        = apply_filters( 'widget_title', $instance['title'] );
		$instance     = wp_parse_args( $instance, $default_args );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		echo esc_attr( kosm_klarna_placement( $instance ) );

		echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$data_key   = ! empty( $instance['data-key'] ) ? $instance['data-key'] : '';
		$data_theme = ! empty( $instance['data-theme'] ) ? $instance['data-theme'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data-key' ) ); ?>"><?php esc_html_e( 'Placement Key' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data-key' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'data-key' ) ); ?>" type="text"
				value="<?php echo esc_attr( $data_key ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data-theme' ) ); ?>"><?php esc_html_e( 'Placement Theme:' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data-theme' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'data-theme' ) ); ?>">
				<option value="default" <?php selected( $data_theme, 'default' ); ?>>Default</option>
				<option value="dark" <?php selected( $data_theme, 'dark' ); ?>>Dark</option>
			</select>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['data-theme'] = ( ! empty( $new_instance['data-theme'] ) ) ? wp_strip_all_tags( $new_instance['data-theme'] ) : '';
		$instance['data-key']   = ( ! empty( $new_instance['data-key'] ) ) ? wp_strip_all_tags( $new_instance['data-key'] ) : '';

		return $instance;
	}
}

new Klarna_OnSite_Messaging_Widget();
