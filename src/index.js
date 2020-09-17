import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';
import icons from './icons/icons.js'
 
registerBlockType( 'klarna/onsite-messaging', {
    title: 'Klarna On-Site Messaging',
    icon: icons.kosm,
    category: 'widgets',
    name: 'klarna/onsite-messaging',
    description: 'Add Klarnas On-Site messaging to the page',
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
        const { attributes: { dataKey, dataTheme, dataAmount, editing }, setAttributes, className } = props;
        const changeKey = ( newKey ) => {
            setAttributes( { dataKey: newKey } );
        };
        const changeTheme = ( newTheme ) => {
            setAttributes( { dataTheme: newTheme } );
        };
        const changeAmount = ( newAmount ) => {
            setAttributes( { dataAmount: newAmount } );
        };

        return ( 
            <div class={ className + " kosm-block-settings" }>
                <h4>Klarna On-site Messaging</h4>
                <TextControl
                    value={ dataKey }
                    label="Placement Key"
                    onChange={ ( nextValue ) => changeKey( nextValue ) }
                />
                 <TextControl
                    value={ dataTheme }
                    label="Theme"
                    onChange={ ( nextValue ) => changeTheme( nextValue ) }
                />
                 <TextControl
                    value={ dataAmount }
                    label="Amount"
                    onChange={ ( nextValue ) => changeAmount( nextValue ) }
                />
            </div>
        );
    },
} );