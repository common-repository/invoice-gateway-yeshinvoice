<?php

/**

 * Settings for Yesh Invoice Gateway.

 */



defined( 'ABSPATH' ) || exit;



return array(

				'enabled' => array(

					'title'       => __('Enable/Disable','wc-yeshin'),

					'label'       => __('Enable YESH INVOICE Gateway','wc-yeshin'),

					'type'        => 'checkbox',

					'description' => '',

					'default'     => 'no'

				),
				
				'_yeshin_live_secret_key' => array(

					'title'       => __('Secret Key','wc-yeshin'),

					'description' => __('YeshInvoice Live SecretKey goes here.','wc-yeshin'),

					'type'        => 'text'

				),

				'_yeshin_live_user_key' => array(

					'title'       => __('User Key','wc-yeshin'),

					'description' => __('YeshInvoice Test UserKey goes here.','wc-yeshin'),

					'type'        => 'text',

				),

				'title' => array(

					'title'       => __('Title','wc-yeshin'),

					'type'        => 'text',

					'description' => __('This controls the title which the user sees during checkout.','wc-yeshin'),

					'default'     => __('Credit Card','wc-yeshin'),

					'desc_tip'    => true,

				),

				'description' => array(

					'title'       => __('Description','wc-yeshin'),

					'type'        => 'textarea',

					'description' => __('This controls the description which the user sees during checkout.','wc-yeshin'),

					'default'     => __('Yesh Invoice plugin allows you to send automatic invoices for any transaction on your WooCommerce. Enjoy a variety of useful features, such as Bit, PayPal and Credit Card without extra costs.','wc-yeshin'),

				),
				

					'_yeshin_paycon' => array(

						'title'       => __('Payment Mode ','wc-yeshin'),
	
						'type'        => 'select',
	
						'options'	=>  array(1 => "Iframe", 2 => "Api")
					
					),

			

				'_yeshin_payment_type' => array(

					'title'       => __('Payment Type','wc-yeshin'),

					'type'        => 'select',

					'options'		=> array(1=> __('Normal Billing','wc-yeshin'), 2 => __('J5 Billing','wc-yeshin'), 3 => __('Subscription','wc-yeshin')),

					'description' => __('Please select PaymentType here.','wc-yeshin'),
					
                    'default'     => 1
				),
				
				
				
				

				'_yeshin_currency_id' => array(

					'title'       => __('Currency ID ','wc-yeshin'),

					'type'        => 'select',

					'options'	=>  array(1 => "ILS (₪)", 2 => "USD ($)",3 => "EUR (€)",4 => "GBP (£)",5 => "AUD ($)",6 => "RUB (₽)",7 => "HKD ($)",8 => "TRY (₺)",9 => "CAD ($)",

10 => "JPY (¥)",11 => "INR (₹)"),  
					'default'     => 1,

					),



				'_yeshin_invoice_lang_id' => array(

					'title'       => __('Invoice Language ID','wc-yeshin'),

					'type'        => 'select',

					'options'	=>  array(139 => "English", 359 => "Hebrew",606 => "Russian",282 => "French",15 =>  "Arabic"), //Lang ID

					'default'     => 359,

					),
					
				'_yeshin_document_minpay' => array(

					'title'       => __('Min Payments','wc-yeshin'),

					'type'        => 'select',

					'options'	  => array(1 => "1",2 => "2",3 => "3",4 => "4",5 => "5",6 => "6",7 => "7",8 => "8",9 => "9",

10 => "10",11 => "11",12 => "12"),  

					'default'     => 1,

				),
				
				'_yeshin_document_maxpay' => array(

					'title'       => __('Max Payments','wc-yeshin'),

					'type'        => 'select',

					'options'	  => array(1 => "1",2 => "2",3 => "3",4 => "4",5 => "5",6 => "6",7 => "7",8 => "8",9 => "9",

10 => "10",11 => "11",12 => "12",13 => "13",14 => "14",13 => "13",14 => "14",15 => "15",16 => "16",17 => "17"
,18 => "18",19 => "19",20 => "20",21 => "21",22 => "22",23 => "23",24 => "24"
,25 => "25",26 => "26",27 => "27",27 => "27",28 => "28",29 => "29",30 => "30",31 => "31",32 => "32",33 => "33",34 => "34",35 => "35",36 => "36"),  

					'default'     => 1,
				),

				

				'_yeshin_send_invoice_sms' => array(

					'title'       => __('Send Invoice SMS','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Do you want to send Invoice SMS to customer?','wc-yeshin'),
				),
				
				'_yeshin_send_invoice_no_invoice' => array(

					'title'       => __('Dont Create an Invoice','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Do you want to Un create Invoice to customer?','wc-yeshin'),
				),

				

				'_yeshin_send_invoice_email' => array(

					'title'       => __('Send Invoice Email','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Do you want to send Invoice Email to customer?','wc-yeshin'),

				),
				
				'_yeshin_orderstatus' => array(

						'title'       => __('Order Status','wc-yeshin'),
	
						'type'        => 'select',
	
						'options'	=>  array(1 => "Completed", 2 => "Processing", 3 => "On hold", 4 => "Pending payment")
					
				),
				
				'_yeshin_button_bit' => array(

					'title'       => __('Show Bit','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Show button Bit','wc-yeshin'),
				),
				
				'_yeshin_button_googlepay' => array(

					'title'       => __('Show Google Pay','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Show button Google','wc-yeshin'),
				),
				
				'_yeshin_button_paypal' => array(

					'title'       => __('Show Paypal Pay','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('Show button Paypal','wc-yeshin'),
				),
				
				'_yeshin_button_paypal_fee' => array(

					'title'       => __('Paypal fee %','wc-yeshin'),

					'type'        => 'number',

					'description' => __('Write how much do you want to add as a percentage to the price','wc-yeshin'),

					'desc_tip'    => true,
				),
				
				'_yeshin_button_paypal_feec' => array(

					'title'       => __('Paypal fee currency','wc-yeshin'),

					'type'        => 'number',

					'description' => __('Write how much do you want to add as a currency to the price','wc-yeshin'),

					'desc_tip'    => true,
				),

				

			/*	'_yeshin_is_iframe' => array(

					'title'       => __('','wc-yeshin'),'Allow Iframe',

					'type'        => 'checkbox',

					'label'        => __('','wc-yeshin'),'Do you want to allow iframe on checkout page?'

				),*/
				
				'advanced_subs'              => array(

					'title'       => __( 'Subscription Settings', 'wc-yeshin' ),

					'type'        => 'title',

					'description' => '',
				),
				
				
				'_yeshin_subscription_month' => array(

					'title'       => __('Subscription months','wc-yeshin'),

					'type'        => 'select',

					'options'	  => array(1 => "1",2 => "2",3 => "3",4 => "4",5 => "5",6 => "6",7 => "7",8 => "8",9 => "9",

10 => "10",11 => "11",12 => "12",13 => "13",14 => "14",13 => "13",14 => "14",15 => "15",16 => "16",17 => "17"
,18 => "18",19 => "19",20 => "20",21 => "21",22 => "22",23 => "23",24 => "24"
,25 => "25",26 => "26",27 => "27",27 => "27",28 => "28",29 => "29",30 => "30",31 => "31",32 => "32",33 => "33",34 => "34",35 => "35",36 => "36"),  
					'default'     => 1,
				),
				
				'_yeshin_subscription_billingday' => array(

					'title'       => __('Subscription Billing Day','wc-yeshin'),

					'type'        => 'select',

					'options'	  => array(0 => "day of purchase", 1 => "1",2 => "2",3 => "3",4 => "4",5 => "5",6 => "6",7 => "7",8 => "8",9 => "9",

10 => "10",11 => "11",12 => "12",13 => "13",14 => "14",13 => "13",14 => "14",15 => "15",16 => "16",17 => "17"
,18 => "18",19 => "19",20 => "20",21 => "21",22 => "22",23 => "23",24 => "24"
,25 => "25",26 => "26",27 => "27",27 => "27",28 => "28"),  

					'default'     => 1,
				),
				
				'_yeshin_subscription_firstprice' => array(

					'title'       => __('Subscription First amount','wc-yeshin'),

					'type'        => 'number',

					'description' => __('You can write how much the first charge will be in the subscription','wc-yeshin'),

					'desc_tip'    => true,
				),
				
				
				'_yeshin_subscription_chargetoday' => array(

					'title'       => __('First subscription charge today','wc-yeshin'),

					'type'        => 'checkbox',

					'label'        => __('The first charge of the subscription fee will be made today','wc-yeshin'),
				),



				

				'advanced'              => array(

					'title'       => __( 'YeshInvoice Checkout Page', 'wc-yeshin' ),

					'type'        => 'title',

					'description' => '',

				),

				

				'_yeshin_invoice_title' => array(

					'title'       => __('Invoice Title','wc-yeshin'),

					'type'        => 'text',

				),

				

				'_yeshin_document_type' => array(

					'title'       => __('Invoice Type','wc-yeshin'),

					'type'        => 'select',

					'options'	  => array(6=>__('Reciept','wc-yeshin'), 11 => __('Donation','wc-yeshin'), 9 => __('Tax Invoice/Receipt','wc-yeshin')),

					'default'     => 6,

				),

				

				'_yeshin_title' => array(

					'title'       => __('Page Title','wc-yeshin'),

					'type'        => 'text',

					'description' => __('This controls the title which the user sees during yesh invoice payment page.','wc-yeshin'),

					'default'     => __('Pay with YeshInvoice','wc-yeshin'),

					'desc_tip'    => true,

				),

				'_yeshin_description' => array(

					'title'       => __('Page Description','wc-yeshin'),

					'type'        => 'textarea',

					'description' => __('This controls the description which the user sees during yesh invoice payment page.','wc-yeshin'),

					'default'     => __('Yesh Invoice allows you pay invoices for any transaction on your WooCommerce. Enjoy a variety of useful features, such as Bit, PayPal and Credit Card without extra costs.','wc-yeshin'),

					'desc_tip'    => true,

				),

				

				'_yeshin_logourl' => array(

					'title'       => __('Logo Url','wc-yeshin'),

					'type'        => 'text'

				),

				

				'_yeshin_bgcolor' => array(

					'title'       => __('Background Color','wc-yeshin'),

					'type'        => 'color',				

				),
				
				'_yeshin_btnbgcolor' => array(

					'title'       => __('Button Color','wc-yeshin'),

					'type'        => 'color',				

				),

				'_yeshin_height_iframe' => array(

					'title'       => __('Page Height','wc-yeshin'),

					'type'        => 'text',

					'default'     => __('660','wc-yeshin'),
					
				),
			);