const yeshin_settings = window.wc.wcSettings.getSetting( 'yeshin_data', {} );


console.log('yeshin_settings',yeshin_settings);

const yeshin_label = window.wp.htmlEntities.decodeEntities( yeshin_settings.title ) || window.wp.i18n.__( 'Yesh Invoice Payment Gateway', 'wc-yeshin' );
const yeshin_Content = () => {
    return window.wp.htmlEntities.decodeEntities( yeshin_settings.description || '' );
};
const Yeshinvoice_Block_Gateway = {
    name: 'yeshin',
    label: yeshin_label,
    content: Object( window.wp.element.createElement )( yeshin_Content, null ),
    edit: Object( window.wp.element.createElement )( yeshin_Content, null ),
    canMakePayment: () => true,
    ariaLabel: yeshin_label,
    supports: {
        features: yeshin_settings.supports,
    },
};
window.wc.wcBlocksRegistry.registerPaymentMethod( Yeshinvoice_Block_Gateway );