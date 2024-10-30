jQuery(document).ready(function(){



	const data_var_1 = yeshin_object.data_var_1;


	jQuery("#woocommerce_yeshin__yeshin_logourl").after('<a class="trigger-logourl button-primary" href="javascript:void(0);">'+data_var_1+'</a><img scr="" clsss="logourl_img">');

	

	jQuery( '.trigger-logourl' ).on( 'click', function() {

		tb_show('YeshInvoice Checkout Page Logo', 'media-upload.php?type=image&TB_iframe=1');

		window.send_to_editor = function( html ) 
		{

			var imgurl = jQuery(html).prop('src') ;

			console.log(imgurl);

			jQuery( '#woocommerce_yeshin__yeshin_logourl' ).val(imgurl);

			tb_remove();

		}

	

		return false;

	});

		jQuery('#woocommerce_yeshin__yeshin_paycon').change(function() {
		    var value = jQuery(this).val();
		    if (value == 1) {
		        jQuery('label[for="woocommerce_yeshin__yeshin_height_iframe"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_height_iframe').show();
		    } else {
		        jQuery('label[for="woocommerce_yeshin__yeshin_height_iframe"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_height_iframe').hide();
		    }
		});
		
		
		/*
		jQuery('#woocommerce_yeshin__yeshin_payment_type').change(function() {
		    var value = jQuery(this).val();
		    if (value == 3) { // הוראת קבע
		        jQuery('label[for="woocommerce_yeshin__yeshin_subscription_month"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_month').show();
				jQuery('label[for="woocommerce_yeshin__yeshin_document_minpay"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_document_minpay').hide();
				jQuery('label[for="woocommerce_yeshin__yeshin_document_maxpay"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_document_maxpay').hide();
				jQuery('label[for="woocommerce_yeshin__yeshin_subscription_billingday"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_billingday').show();
				jQuery('label[for="woocommerce_yeshin__yeshin_subscription_firstprice"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_firstprice').show();
				
		    } else {
		        jQuery('label[for="woocommerce_yeshin__yeshin_subscription_month"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_month').hide();
				
				jQuery('label[for="woocommerce_yeshin__yeshin_document_minpay"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_document_minpay').show();
				
				jQuery('label[for="woocommerce_yeshin__yeshin_document_maxpay"]').show();
		        jQuery('#woocommerce_yeshin__yeshin_document_maxpay').show();
				
				jQuery('label[for="woocommerce_yeshin__yeshin_subscription_billingday"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_billingday').hide();
				
				jQuery('label[for="woocommerce_yeshin__yeshin_subscription_firstprice"]').hide();
		        jQuery('#woocommerce_yeshin__yeshin_subscription_firstprice').hide();
				
		    }
		});
		*/
		
		jQuery('#woocommerce_yeshin__yeshin_button_paypal').change(function() {
			if(this.checked) {
					jQuery('label[for="woocommerce_yeshin__yeshin_button_paypal_fee"]').show();
		            jQuery('#woocommerce_yeshin__yeshin_button_paypal_fee').show();
			}
			else{
				jQuery('label[for="woocommerce_yeshin__yeshin_button_paypal_fee"]').hide();
		            jQuery('#woocommerce_yeshin__yeshin_button_paypal_fee').hide();
			}
		});
		
});