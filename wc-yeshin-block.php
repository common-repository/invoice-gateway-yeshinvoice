<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class WC_YESHIN_Gateway_Blocks extends AbstractPaymentMethodType {

    private $gateway;
    protected $name = 'yeshin';// your payment gateway name

    public function initialize() {
        $this->settings = get_option( 'woocommerce_yeshin_settings', [] );
        $this->gateway = new WC_YESHIN_Gateway();
    }

    public function is_active() {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles() {

        wp_register_script(
            'yeshin-blocks-integration',
            plugin_dir_url(__FILE__) . 'js/yeshin-block.js',
            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
                'wp-i18n',
            ],
            null,
            true
        );
        if( function_exists( 'wp_set_script_translations' ) ) {            
            wp_set_script_translations( 'yeshin-blocks-integration');
            
        }
        return [ 'yeshin-blocks-integration' ];
    }

    public function get_payment_method_data() {
        return [
            'title' => $this->gateway->title,
            'description' => $this->gateway->description,
        ];
    }

}
?>