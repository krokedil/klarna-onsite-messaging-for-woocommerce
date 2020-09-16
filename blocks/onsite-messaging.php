<?php
/**
 * Gutenberg block functions for On-site messaging.
 *
 * @package Klarna_OnSite_Messaging/Blocks
 */

/**
 * The server side callback function.
 *
 * @param array $block_attributes The block attributes.
 * @param array $content The content.
 * @return string
 */
function onsite_messaging_render_callback( $block_attributes, $content ) {
	$args = array(
		'data-key'             => ( isset( $block_attributes['dataKey'] ) ) ? $block_attributes['dataKey'] : '',
		'data-theme'           => ( isset( $block_attributes['dataTheme'] ) ) ? $block_attributes['dataTheme'] : '',
		'data-purchase-amount' => ( isset( $block_attributes['dataAmount'] ) ) ? $block_attributes['dataAmount'] : '',
	);
	ob_start();
	kosm_klarna_placement( $args );
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}

/**
 * Registers the dynamic block for OSM.
 *
 * @return void
 */
function onsite_messaging_dynamic() {
	$asset_file = include WC_KLARNA_ONSITE_MESSAGING_PLUGIN_PATH . '/build/index.asset.php';

	wp_register_script(
		'onsite-messaging',
		WC_KLARNA_ONSITE_MESSAGING_PLUGIN_URL . '/build/index.js',
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_register_style(
		'onsite-messaging-editor-style',
		WC_KLARNA_ONSITE_MESSAGING_PLUGIN_URL . '/build/editor.css',
		array( 'wp-edit-blocks' ),
		$asset_file['version']
	);

	register_block_type(
		'klarna/onsite-messaging', array(
			'editor_script'   => 'onsite-messaging',
			'editor_style'    => 'onsite-messaging-editor-style',
			'render_callback' => 'onsite_messaging_render_callback',
		)
	);

}
add_action( 'init', 'onsite_messaging_dynamic' );
