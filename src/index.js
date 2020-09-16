import { registerBlockType } from '@wordpress/blocks';
import { RichText } from '@wordpress/block-editor';
 
registerBlockType( 'klarna/onsite-messaging', {
    title: 'Example: last post',
    icon: 'megaphone',
    category: 'widgets',
    name: 'klarna/onsite-messaging',
    attributes: {
        dataKey: {
            type:'text'
        },
        dataTheme: {
            type:'text'
        },
        dataAmount: {
            type:'text'
        }
    },
    edit: function( props ) {
        const { attributes: { dataKey, dataTheme, dataAmount }, setAttributes, className } = props;
        const changeKey = ( newKey ) => {
            setAttributes( { dataKey: newKey } );
        };
        const changeTheme = ( newTheme ) => {
            setAttributes( { dataKey: newTheme } );
        };
        const changeAmount = ( newAmount ) => {
            setAttributes( { dataAmount: newAmount } );
        };
        return (
            <div class={ className + " kosm-block-settings" }>
                <h4>Klarna On-site Messaging</h4>
                <label class={ className + " components-placeholder__label" }>Placement key</label>
                <RichText
                    tagName="p"
                    className= { className + " block-editor-plain-text input-control" }
                    onChange={ changeKey }
                    value={ dataKey }
                />
                <label class={ className + " components-placeholder__label" }>Theme</label>
                <RichText
                    tagName="p"
                    className= { className + " block-editor-plain-text input-control" }
                    onChange={ changeTheme }
                    value={ dataTheme }
                />
                <label class={ className + " components-placeholder__label" }>Amount</label>
                <RichText
                    tagName="p"
                    className= { className + " block-editor-plain-text input-control" }
                    onChange={ changeAmount }
                    value={ dataAmount }
                />
            </div>
        );
    },
} );