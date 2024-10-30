<?php

/*

 * Plugin Name: Yesh Invoice Payment Gateway for WooCommerce

 * Plugin URI: https://yeshinvoice.co.il

 * Description: Yesh Invoice plugin allows you to send automatic invoices for any transaction on your yourWooCommerce. Enjoy a variety of useful features, such as Bit, PayPal and Credit Card without extra costs.

 * Author: Yesh Invoice

 * Author URI: https://yeshinvoice.co.il/contact.html

 * Author Email: support@yeshinvoice.co.il

 * Version: 1.3.4

 * Text domain - wc-yeshin



 /*

 * This action hook registers our PHP class as a WooCommerce payment gateway

 */


defined('ABSPATH') || exit;
define('YESHIN_URL', plugin_dir_url(__FILE__));
define('YESHIN_PATH', plugin_dir_path(__FILE__));
define('YESHIN_DEBUG', true);
define('YESHIN_LOG_FILE', YESHIN_URL . 'WOO_YESH_INVOICE_LOG.log');


//@FUNCTIONF FOR GENRATE ERROR LOGS
if (!function_exists('yeshin_plugin_log')) {
	function yeshin_plugin_log($entry, $mode = 'a', $file = 'WOO_YESH_INVOICE_LOG')
	{
		// Get WordPress uploads directory.
		$upload_dir = wp_upload_dir();
		$upload_dir = YESHIN_PATH;
		// If the entry is array, json_encode.
		if (is_array($entry)) {
			$entry = json_encode($entry);
		}

		// Write the log file.
		$file  = $upload_dir . '/' . $file . '.log';
		$file  = fopen($file, $mode);
		$bytes = fwrite($file, current_time('mysql') . "::" . $entry . "\n");
		fclose($file);
		return $bytes;
	}
}

add_action('init', 'yeshin_load_textdomain');
/**
 * Load plugin textdomain.
 */
function yeshin_load_textdomain()
{
	load_plugin_textdomain('wc-yeshin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

/**
 * Load payment gatway class.
 */
add_filter('woocommerce_payment_gateways', 'yeshin_add_gateway_class');

function yeshin_add_gateway_class($gateways)
{
	$gateways[] = 'WC_YESHIN_Gateway'; // your class name is here
	return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */

 function custom_add_iframe_to_order_receipt($order_id) {
    $order = wc_get_order($order_id);
    $payment_method = $order->get_payment_method();

    // Check if the payment method matches the one you want to target
    if ($payment_method === 'yeshin') {
        // Output the iframe content
		   $yeshinredirect = $_GET['yeshinredirect'];
		   $yeshindata = get_option( 'woocommerce_yeshin_settings' );

		   if(!empty($yeshindata['_yeshin_height_iframe'])){
		   		$yeshinheight = $yeshindata['_yeshin_height_iframe'];
			}
			else{
				$yeshinheight = '660';
			}
	echo '<iframe id="custom-iframe" src="'.$yeshinredirect.'"  style="width: 100%;height: '.$yeshinheight.'px; border: none;"></iframe>';
			echo "<script>
			window.addEventListener('message', receiveMessage, false);
           function receiveMessage(event) {
			if(event.data.response == 'ok'){
				window.location = event.data.url;
			}
			var hosturl =  window.location.protocol + '//' + window.location.host; 
           if (event.origin !== hosturl) {
			return;
			}
		}
        </script>";

    }
}
add_action('woocommerce_receipt_yeshin', 'custom_add_iframe_to_order_receipt');
add_action('plugins_loaded', 'yeshin_init_gateway_class');

function yeshin_init_gateway_class()
{
	if (!class_exists('WC_Payment_Gateway')) return;
	/**
	 * Gateway class
	 */
	class WC_YESHIN_Gateway extends WC_Payment_Gateway
	{
		/** @var boolean Whether or not logging is enabled */
		public static $log_enabled = false;
		/** @var WC_Logger Logger instance */
		public static $log = false;
		/**
		 * Class constructor, more about it in Step 3
		 */
		public function __construct()
		{
			$this->id = 'yeshin'; // payment gateway plugin ID
			$this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
			$this->has_fields = false; // in case you need a custom credit card form
			$this->method_title = esc_attr__('Yesh Invoice - Gateway', 'wc-yeshin');
			$this->method_description = esc_html__('Yesh Invoice plugin allows you to send automatic invoices for any transaction on your WooCommerce. Enjoy a variety of useful features, such as Bit, PayPal and Credit Card without extra costs.', 'wc-yeshin'); // will be displayed on the options page
			// gateways can support subscriptions, refunds, saved payment methods,
			$this->supports = array(
				'products',
			);
			// Method with all the options fields
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();
			$this->title = $this->get_option('title');
			$this->description = $this->get_option('description');
			$this->enabled = $this->get_option('enabled');
			$this->environment_url = 'https://api.yeshinvoice.co.il/api/v1/wccreatePayment';
			$this->SecretKey =  $this->get_option('_yeshin_live_secret_key');
			$this->UserKey  =   $this->get_option('_yeshin_live_user_key');
			$this->PaymentType  = $this->get_option('_yeshin_payment_type') ? $this->get_option('_yeshin_payment_type') : '';
			$this->SubscriptionMonth  = $this->get_option('_yeshin_subscription_month') ? $this->get_option('_yeshin_subscription_month') : '';
			$this->SubscriptionBillingDay  = $this->get_option('_yeshin_subscription_billingday') ? $this->get_option('_yeshin_subscription_billingday') : '';
			$this->SubscriptionFirstPrice  = $this->get_option('_yeshin_subscription_firstprice') ? $this->get_option('_yeshin_subscription_firstprice') : '';
			$this->SubscriptionFirstChargeToday = $this->get_option('_yeshin_subscription_chargetoday') ? $this->get_option('_yeshin_subscription_chargetoday') : '';
			
			
			$this->CurrencyID = $this->get_option('_yeshin_currency_id') ? $this->get_option('_yeshin_currency_id') : '';
			$this->InvoiceLangID = $this->get_option('_yeshin_invoice_lang_id') ? $this->get_option('_yeshin_invoice_lang_id') : '';
			$this->MinPayments = $this->get_option('_yeshin_document_minpay') ? $this->get_option('_yeshin_document_minpay') : '';
			$this->MaxPayments = $this->get_option('_yeshin_document_maxpay') ? $this->get_option('_yeshin_document_maxpay') : '';
			$this->DocumentType = $this->get_option('_yeshin_document_type') ? $this->get_option('_yeshin_document_type') : '';
			$this->SendInvoiceSMS = $this->get_option('_yeshin_send_invoice_sms') ? $this->get_option('_yeshin_send_invoice_sms') : '';
			$this->NoCreateInvoice = $this->get_option('_yeshin_send_invoice_no_invoice') ? $this->get_option('_yeshin_send_invoice_no_invoice') : '';
			$this->SendInvoiceEmail = $this->get_option('_yeshin_send_invoice_email') ? $this->get_option('_yeshin_send_invoice_email') : '';
			
			$this->ButtonBit = $this->get_option('_yeshin_button_bit') ? $this->get_option('_yeshin_button_bit') : '';
			$this->ButtonGoogle = $this->get_option('_yeshin_button_googlepay') ? $this->get_option('_yeshin_button_googlepay') : '';
			$this->ButtonPaypal = $this->get_option('_yeshin_button_paypal') ? $this->get_option('_yeshin_button_paypal') : '';
			$this->PaypalFee = $this->get_option('_yeshin_button_paypal_fee') ? $this->get_option('_yeshin_button_paypal_fee') : '';
			$this->PaypalFeeC = $this->get_option('_yeshin_button_paypal_feec') ? $this->get_option('_yeshin_button_paypal_feec') : '';
		
			
			$this->LogoUrl = $this->get_option('_yeshin_logourl') ? $this->get_option('_yeshin_logourl') : '';
			$this->InvoiceTitle = $this->get_option('_yeshin_invoice_title') ? $this->get_option('_yeshin_invoice_title') : '';
			$this->Title = $this->get_option('_yeshin_title') ? $this->get_option('_yeshin_title') : '';
			$this->Body = $this->get_option('_yeshin_description') ? $this->get_option('_yeshin_description') : '';
			$this->BgColor = $this->get_option('_yeshin_bgcolor') ? $this->get_option('_yeshin_bgcolor') : '';
			$this->notify_url = WC()->api_request_url('WC_YESHIN_Gateway');
			// This action hook saves the settings
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			// You can also register a webhook here
			add_action('woocommerce_api_wc_yeshin_gateway', array(&$this, 'yeshin_notify_webhook'));
			add_action('woocommerce_receipt_yeshin', array(&$this, 'yeshin_receipt_page'));
			add_action('admin_enqueue_scripts', array($this, 'yeshin_admin_enqueue_scripts'));
			//add_action('woocommerce_thankyou','QuadLayers_change_order_status');
			add_action( 'woocommerce_thankyou', array($this, 'custom_woocommerce_auto_complete_paid_order'), 1, 1 );
		}

		public function custom_woocommerce_auto_complete_paid_order( $order_id ) {
			if ( ! $order_id )
				return;

			$order = new WC_Order( $order_id );

           // $order->update_status( 'completed' );
		   
		   $statusorder = 'completed';
		
			try{
				
				$settingdata = $this->settings;
				if($settingdata['_yeshin_orderstatus'] == 1){
					$statusorder = 'completed';
				}
				
				if($settingdata['_yeshin_orderstatus'] == 2){
					$statusorder = 'processing';
				}
				
				if($settingdata['_yeshin_orderstatus'] == 3){
					$statusorder = 'on-hold';
				}
				
				if($settingdata['_yeshin_orderstatus'] == 4){
					$statusorder = 'pending';
				}	
			}
			catch(Exception $e){
				
			}
			
			$method = get_post_meta($order_id, '_payment_method', true);
			
			$urlcallback = 'https://api.yeshinvoice.co.il/api/payresult/cardcomHook?method=' . urlencode($method) . '&status=' . urlencode($statusorder);
			
			try{
			    $response = wp_remote_get( $urlcallback );	
			}catch(Exception $e){
				
			}
			
			// No updated status for orders delivered with Bank wire, Cash on delivery and Cheque payment methods.
			if ( get_post_meta($order_id, '_payment_method', true) == 'bacs' || get_post_meta($order_id, '_payment_method', true) == 'cod' || get_post_meta($order_id, '_payment_method', true) == 'cheque' ) {
				 // $order->update_status( 'processing' );
				return;
			}elseif ( get_post_meta($order_id, '_payment_method', true) == 'yeshin' ) {
				$order->update_status( $statusorder );
			   return;
			}else {
				  $order->update_status( $statusorder );
				return;
			}
		}

        public function QuadLayers_change_order_status( $order_id ) {  
                if ( ! $order_id ) {return;}            
                $order = wc_get_order( $order_id );
                
				// we received the payment
				$order->payment_complete();
				$order->reduce_order_stock();
				
        }

		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
		public function yeshin_admin_enqueue_scripts()
		{
			//SHOW ONLY FOR YESH INVOICE SETTING PAGE 

			if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == "checkout" && isset($_REQUEST['section'])  && $_REQUEST['section'] == 'yeshin') {
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				wp_enqueue_script('media-upload');
				wp_register_script('woocommerce_yeshin_custom_js', plugin_dir_url(__FILE__) . 'js/yeshin-custom.js', array(), '1.0.2', true);
				wp_enqueue_style('woocommerce_yeshin_custom_css', plugin_dir_url(__FILE__) . 'css/yeshin-custom.css', array(), '1.0.2');
				wp_enqueue_script('woocommerce_yeshin_custom_js');
				wp_localize_script(
					'woocommerce_yeshin_custom_js',
					'yeshin_object',
					array(
						'data_var_1' => __('UPLOAD LOGO', 'wc-yeshin'),
					)
				);
			}
		}

		/**

		 * Plugin options, we deal with it in Step 3 too

		 */

		public function init_form_fields()
		{
			$this->form_fields = include __DIR__ . '/includes/settings-yeshinvoice.php';
			// echo "<pre>";
			// print_r($this->form_fields);
			// die("okk");
		}

		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
		// public function payment_fields()
		// {
		// 	$description = $this->description;
		// 	if ($description) {
		// 		echo wpautop(wptexturize($description)); // @codingStandardsIgnoreLine.
		// 	}
		// }

		/*
 		 * Fields validation, more in Step 5
		 */
		public function validate_fields()
		{
		}

		/*

		 * We're processing the payments here, everything about it is in Step 5

		 */

		public function process_payment($order_id)
		{
			global $woocommerce;
			// we need it to get any order detailes
			$order = wc_get_order($order_id);

			$first_name =  $order->get_billing_first_name();
			$last_name = $order->get_billing_last_name();
			$fullname =  $first_name . ' ' . $last_name;
			
			$paymentType = esc_attr($this->PaymentType);
			
			$items = array(); 
			
			foreach ($order->get_items() as $item_id => $itm ) {
				
				$product = $itm->get_product();
				$ttyp   = $product->get_type();
				$sku   = $product->get_sku();
				$price   = $product->get_price();
				$quantity  = $itm->get_quantity();
				$taxes     = $itm->get_total_tax();
				if($ttyp == 'variation'){
					$title = $product->get_title(); 
					$variations = $product->get_variation_attributes();
					$desc = $title . ' ' . implode(', ', $variations);
					
				}else{
					$desc   = $product->get_title();
				}
				
				if($ttyp == 'yeshsubscription'){
					
					$paymentType = 3;
		
				}
				
				if($ttyp == 'yesh_subs'){
					$paymentType = 3;
				}
				
				if($ttyp == 'Yesh Subscription'){
					$paymentType = 3;
				}
				
				$items[] = [
					"sku"			=>			$sku,
					"description"	=>			$desc,
					"price"			=>			$price,
					"quantity"		=>			$quantity,
					"tax"			=>			$taxes
				];
			}
           
			$settingdata = $this->settings;
			if($settingdata['_yeshin_paycon'] == 1){
				$iframe = true;
			}else{
				$iframe = false;
			}
			
			$SecretKey = $settingdata['_yeshin_live_secret_key'];
			$UserKey = $settingdata['_yeshin_live_user_key'];
			
			$order_data = $order->get_data();
			  
			$final_data = array(
				"SecretKey" => $SecretKey,
				"UserKey" => $UserKey,
				"Title" => esc_attr($this->Title),
				"Body" => esc_textarea($this->Body),
				"NumPayments" => 1,
				"PluginID" => 14,
				"SuccessUrl" => esc_url(add_query_arg('thank-you', $order->id, add_query_arg('key', $order->get_order_key(), $order->get_checkout_order_received_url(true)))),
				"ErrorUrl" => esc_url(add_query_arg('order', $order->id, add_query_arg('key', $order->get_order_key(), $order->get_checkout_payment_url(true)))),
				"NotifyUrl" => esc_url($this->notify_url),
				"MaxNumPayments" => 10,
				"TotalPrice" =>  esc_attr($order->get_total()),
				"OrderNumber" => esc_attr($order->get_id()),
				"PaymentType" => $paymentType,
				"SubscriptionMonth" => esc_attr($this->SubscriptionMonth),
				"SubscriptionBillingDay" => esc_attr($this->SubscriptionBillingDay),
				"SubscriptionFirstPrice" => esc_attr($this->SubscriptionFirstPrice),
				"SubscriptionFirstChargeToday" => esc_attr($this->SubscriptionFirstChargeToday),
				"isIframe" =>  $iframe,
				"BgColor" => esc_attr($this->BgColor),
				"InvoiceName" => esc_attr($fullname),
				"InvoiceTitle" => esc_attr($this->InvoiceTitle),
				"InvoiceNumberID" => esc_attr($order->get_customer_id()),
				"InvoiceEmailAddress" => esc_attr($order->get_billing_email()),
				"InvoiceAddress" => esc_attr($order->get_billing_address_1() . ' ' . $order->get_billing_address_2()),
				"InvoiceCity" => esc_attr($order->get_billing_city()),
				"InvoicePhone" => esc_attr($order->get_billing_phone()),
				"UniqueID" => esc_attr($order->get_id()),
				"Fields1" => esc_attr($order->get_order_key()),
				"CurrencyID" => esc_attr($this->CurrencyID),
				"LogoUrl" => esc_attr($this->LogoUrl),
				"InvoiceLangID" => esc_attr($this->InvoiceLangID),
				"SendInvoiceSMS" => esc_attr($this->SendInvoiceSMS),
				"NoCreateInvoice" => esc_attr($this->NoCreateInvoice),
				"MinPayments" => esc_attr($this->MinPayments),
				"MaxPayments" => esc_attr($this->MaxPayments),
				"SendInvoiceEmail" => esc_attr($this->SendInvoiceEmail),
				"DocumentType" =>  esc_attr($this->DocumentType),
				"items" =>  $items,
				"OrderDetails" => $order_data,
				"showBit" => esc_attr($this->ButtonBit),
                "showApple" => esc_attr($this->ButtonGoogle),
                "showGoogle" => esc_attr($this->ButtonGoogle),
                "showPaypal" => esc_attr($this->ButtonPaypal),
				"PaypalFee" => esc_attr($this->PaypalFee),
				"PaypalFeeC" => esc_attr($this->PaypalFeeC), 
			);
			$response = wp_remote_post($this->environment_url, array(
				'method'    => 'POST',
				'headers'   => array('content-type' => 'application/json'),
				'body'      => json_encode($final_data),
				'timeout'   => 9000,
				'sslverify' => false,
			));

			if (!is_wp_error($response)) {
				
				$body = json_decode($response['body'], true);
				if ($body['Success'] == true) {
					 if($final_data['isIframe'] ==  1){
						$redirect_url = add_query_arg('yeshinredirect', $body['ReturnValue'], $order->get_checkout_payment_url(true));
                    	return array(
						 'result' => 'success',
						 'redirect' => $redirect_url
					   );
					}else{
						return array(
							'result' => 'success',
							'redirect' => $body['ReturnValue']
						);
					}
				} else {
					if ($body['ErrorMessage'] != '') {
						throw new Exception('<strong>Yesh Invoice - </strong>' . esc_html($body['ErrorMessage']));
					}
				}
			} else {
				throw new Exception(esc_attr(__('Something went wrong. Please try again later.', 'wc-yeshin')));
			}
		}

		/**
		 * Receipt Page
		 **/
		public function yeshin_receipt_page($order_id)
		{
			if (isset($_REQUEST['Response']) && $_REQUEST['Response'] == 120) {
				global $woocommerce;
				$order = new WC_Order($order_id);

				if (get_post_meta($order_id, '_yeshin_response_code', true) == '') {
					update_post_meta($order_id, '_yeshin_response_code', sanitize_text_field($_REQUEST['Response']));
					$order->add_order_note(__(sprintf("Payment goes failed from Yesh Invoice. <br /> RESPONSE CODE - %s", esc_html($_REQUEST['Response'])), 'wc-yeshin'));
				}
			}
		}


		/*
		 * In case you need a webhook, like PayPal IPN etc
		 */
		public function yeshin_notify_webhook()
		{
			// get the raw POST data
			$notifyData = file_get_contents("php://input");
			if (!empty($notifyData)) {
				parse_str($notifyData, $notify_array);
				if (YESHIN_DEBUG == true) {
					yeshin_plugin_log("START ORDER HERE");
					yeshin_plugin_log($notify_array);
					yeshin_plugin_log("END ORDER HERE");
				}
				$orderID = sanitize_text_field($notify_array['UniqueID']);
				$transaction_id = $notify_array['transaction_id'];
				$PelecardStatusCode = $notify_array['PelecardStatusCode'];
				update_post_meta($orderID, '_yeshin_payment_json_res', array_map('esc_attr', $notify_array));
				update_post_meta($orderID, '_yeshin_taxn_id', sanitize_text_field($transaction_id));
				global $woocommerce;
				$order = new WC_Order($orderID);

				// we received the payment
				$order->payment_complete();
				$order->reduce_order_stock();

				//add notes to order
				$order->add_order_note(__(sprintf("Payment accepted by Yesh Invoice. <br /> STATUS CODE - %s <br /> TRANSCATION ID - %s", esc_attr($PelecardStatusCode), esc_attr($transaction_id)), 'wc-yeshin'), 'success');
			}
		}
	}
}

add_action( 'woocommerce_thankyou','custom_woocommerce_auto_complete_cws', 100, 1 );
function custom_woocommerce_auto_complete_cws($order_id){
	if ( ! $order_id )
		return;

	$order = new WC_Order( $order_id );

	// No updated status for orders delivered with Bank wire, Cash on delivery and Cheque payment methods.
	if ( get_post_meta($order_id, '_payment_method', true) == 'bacs' || get_post_meta($order_id, '_payment_method', true) == 'cod' || get_post_meta($order_id, '_payment_method', true) == 'cheque' ) {
		return;
	}elseif ( get_post_meta($order_id, '_payment_method', true) == 'yeshin' ) {
		$order->update_status( 'completed' );
	}else {
		
		return;
	}
}



/**
 * Custom function to declare compatibility with cart_checkout_blocks feature 
*/
function wc_yeshin_declare_cart_checkout_blocks_compatibility() {
    // Check if the required class exists
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        // Declare compatibility for 'cart_checkout_blocks'
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
}
// Hook the custom function to the 'before_woocommerce_init' action
add_action('before_woocommerce_init', 'wc_yeshin_declare_cart_checkout_blocks_compatibility');


// Hook the custom function to the 'woocommerce_blocks_loaded' action
add_action( 'woocommerce_blocks_loaded', 'wc_yesin_register_order_approval_payment_method_type' );

/**
 * Custom function to register a payment method type

 */
function wc_yesin_register_order_approval_payment_method_type() {
    // Check if the required class exists
    if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
        return;
    }

    // Include the custom Blocks Checkout class
    require_once plugin_dir_path(__FILE__) . 'wc-yeshin-block.php';

    // Hook the registration function to the 'woocommerce_blocks_payment_method_type_registration' action
    add_action(
        'woocommerce_blocks_payment_method_type_registration',
        function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
            // Register an instance of My_Custom_Gateway_Blocks
            $payment_method_registry->register( new WC_YESHIN_Gateway_Blocks );
        }
    );
}






/**
 * Register the custom product type after init
 */
function register_yesh_subs_product_type() {

	/**
	 * This should be in its own separate file.
	 */
	class WC_Product_yesh_subs extends WC_Product {

		public function __construct( $product ) {

			$this->product_type = 'yesh_subs';

			parent::__construct( $product );

		}
		
		public function add_to_cart_url() {
			$url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );

			return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
		}

	}

}
add_action( 'plugins_loaded', 'register_yesh_subs_product_type' );


/**
 * Add to product type drop down.
 */
function add_yesh_subs_product( $types ){

	// Key should be exactly the same as in the class
	$types[ 'yesh_subs' ] = __( 'Yesh Subscription' );

	return $types;

}
add_filter( 'product_type_selector', 'add_yesh_subs_product' );


/**
 * Show pricing fields for yesh_subs product.
 */
function yesh_subs_custom_js() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>
		jQuery( document ).ready( function() {
			jQuery( '.options_group.pricing' ).addClass( 'show_if_yesh_subs' ).show();
		});

	</script><?php

}
add_action( 'admin_footer', 'yesh_subs_custom_js' );


/**
 * Add a custom product tab.
 */
function custom_product_tabs( $tabs) {

	$tabs['rental'] = array(
		'label'		=> __( 'Rental', 'woocommerce' ),
		'target'	=> 'rental_options',
		'class'		=> array( 'show_if_yesh_subs', 'show_if_variable_rental'  ),
	);

	return $tabs;

}
//add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );


/**
 * Contents of the rental options product tab.
 */
function rental_options_product_tab_content() {

	global $post;

	?><div id='rental_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php

		

		

		?></div>

	</div><?php


}
add_action( 'woocommerce_product_data_panels', 'rental_options_product_tab_content' );


/**
 * Save the custom fields.
 */
function save_rental_option_field( $post_id ) {

	$rental_option = isset( $_POST['_enable_renta_option'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_enable_renta_option', $rental_option );

	if ( isset( $_POST['_text_input_y'] ) ) :
		update_post_meta( $post_id, '_text_input_y', sanitize_text_field( $_POST['_text_input_y'] ) );
	endif;

}
add_action( 'woocommerce_process_product_meta_yesh_subs', 'save_rental_option_field'  );
add_action( 'woocommerce_process_product_meta_variable_rental', 'save_rental_option_field'  );


/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {

	$tabs['attribute']['class'][] = 'hide_if_yesh_subs hide_if_variable_rental';

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );


add_action( 'woocommerce_single_product_summary', 'custom_product_add_to_cart', 60 );
function custom_product_add_to_cart () {
    global $product;

    // Make sure it's our custom product type
    if ( 'yesh_subs' == $product->get_type() ) {
        do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <p class="cart">
            <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" rel="nofollow" class="single_add_to_cart_button button alt">
                <?php echo "Add to cart"; ?>
            </a>
        </p>

        <?php do_action( 'woocommerce_after_add_to_cart_button' );
    }
}