<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>
<?php
	$opt_data = new Opt_Rewards_Data(); 
	$authenticationMethod = $opt_data->getAuthenticationMethod();
	$current_user = wp_get_current_user();
	$customerId = $current_user->ID;
	$user_email = $current_user->user_email;
	$phone  = get_user_meta( $customerId, 'user_phone_no', true);
?>

	<form method="post" id="edit_address">

		<h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3><?php // @codingStandardsIgnoreLine ?>

		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="woocommerce-address-fields__field-wrapper">
				<?php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<p>
			<?php if( 'billing' === $load_address  && $authenticationMethod == 'otp_code'){ ?>	
				<button type="submit" class="button edit_address" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>" style="display: none;"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
				<button class="button edit_otp_address"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>

			<?php } else { ?>
                 <button type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
			<?php } ?>	
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
		</div>

	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>

<a class="edit-addr-popup" href="#edit-addr-popup"></a> 
<div style="display: none;max-width: 700px;" id="edit-addr-popup">
	<div class="guest-popup auth-otp">
    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
        <p class="message success" id="addrSMsg" style='display:none'></p>
        <p class="message error" id="addrEMsg" style='display:none'></p>   
		<form id="otp_form" class="otp_form" action="" method="post">
				<label for="otp_code">Verification Code</label>
				<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
				<input id="addr_pop" type="submit" name="otp_pop" value="Login"/>
		</form>
        <div class="repop">
			<div id="dvresendotp" style="display: none">
                <?php /* @escapeNotVerified */ echo __('You can request another verification code in ') ?> <span id="otpCount"></span><?php echo __(' secs') ?>
            </div>
            <a id="resendLink" class="resendotp" href="javascript:void(0)" style="display: none"><?php /* @escapeNotVerified */ echo __('RESEND CODE') ?></a>
        </div>    
    </div>		
</div>

<script>
jQuery(document).ready(function($){

	jQuery(".edit_address").click(function() {
        var valid = optCheckAddressValidation();
	    if(valid == 1){
	        return true;
	    }else{
	    	return false;
	    }
	});	
	var otpCountTime = 30;
    var firstInterval = null;
	jQuery(".edit_otp_address").click(function() {
	    var valid = optCheckAddressValidation();
	    if(valid == 0){
	        return false;
	    }else if(valid == 1){
	        jQuery('.opt-loader').show();
	        var inquiryway = 'email';
	        var email = '<?php echo $user_email; ?>'; 
	        var phone =  '<?php echo $phone; ?>'; 
	        var action = 'verification_api';
	        jQuery.ajax({
	            url:"<?php echo admin_url('admin-ajax.php'); ?>",
	            type:'POST',
	            data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
	            success: function(data){

	                jQuery('.opt-loader').hide();
	                if(data == 'Success'){
	                    jQuery("#resendLink").hide();
	                    jQuery(".edit-addr-popup").fancybox({
						    afterClose: function () {
							    if(firstInterval != null){
                                    clearInterval(firstInterval);
                                    otpCountTime = 30;
	                                jQuery('#addrSMsg').hide();
	                                jQuery('#addrEMsg').hide();
                                }
							}
						}).trigger('click');
	                    var seconds = otpCountTime;
	                    jQuery("#dvresendotp").show();
	                    jQuery("#otpCount").html(seconds);
	                    firstInterval = setInterval(function () {
	                        seconds--;
	                        jQuery("#otpCount").html(seconds);
	                        if (seconds == 0) {
	                            clearInterval(firstInterval);
	                            otpCountTime = otpCountTime + 30;
	                            jQuery("#dvresendotp").hide();
	                            jQuery("#resendLink").show();
	                        }
	                    }, 1000);
	                 }   
	            }
	        });  
	        
	        return false;  
	    }
	   
	});

	jQuery("#addr_pop").click(function($){
		jQuery("#addrEMsg").text('');
        jQuery("#addrEMsg").hide();
        jQuery("#addrSMsg").text('');
        jQuery("#addrSMsg").hide();
        jQuery('.opterrmsg').remove();
        jQuery('form#otp_form input').removeClass('opterror');
        var bool = 1;

        if (jQuery.trim(jQuery('#otp_code').val()) == '') {
            jQuery('#otp_code').after('<span class="opterrmsg">Please enter Verification Code.</span>');
            jQuery('#otp_code').addClass('opterror');
            bool = 0;
        }
        if(bool == 0){
	        return false;
        }else{
        	
            var action_data = "otp_code";
            
            jQuery.ajax({
                url:"<?php echo admin_url('admin-ajax.php'); ?>",
                type:'POST',
                data: jQuery('#otp_form').serialize() + "&action=" + action_data,
                
                success: function(data){
                    											
                	if(data == 'Success'){
                		jQuery(".edit_address").show();
                		jQuery(".edit_otp_address").hide();
    		            jQuery('button.edit_address').trigger('click');
                        jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
                	}else{
                		jQuery("#addrEMsg").text(data);
                        jQuery("#addrEMsg").show();
                	}
                   
                }
            });  
        } 
        return false;
    });  

    jQuery("#resendLink").click(function(){
        var action_data = "resend_otp";
        var email = '<?php echo $user_email; ?>'; 
        var phone =  '<?php echo $phone; ?>'; 
        var inquiryway = 'email';    
        jQuery.ajax({
            url:"<?php echo admin_url('admin-ajax.php'); ?>",
            type:'POST',
            data: "email=" + email + "&phone=" + phone + "&action=" + action_data,

            success: function(data){
                if(data == 'Success'){										
                	jQuery("#addrSMsg").text('Verification Code has been sent');
                    jQuery("#addrSMsg").show();
                    var newseconds = otpCountTime;
                    jQuery("#resendLink").hide();
                    jQuery("#dvresendotp").show();
                    jQuery("#otpCount").html(newseconds);
                    firstInterval = setInterval(function () {
                        newseconds--;
                        jQuery("#otpCount").html(newseconds);
                        if (newseconds == 0) {
                            clearInterval(firstInterval);
                            otpCountTime = otpCountTime + 30;
                            jQuery("#dvresendotp").hide();
                            jQuery("#resendLink").show();
                            jQuery('#addrSMsg').text('');
                            jQuery("#addrSMsg").hide();
                        }
                    }, 1000);  
                }else{
                	jQuery("#addrEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
                    jQuery("#addrEMsg").show();
                }    
                   
            }
        });   
    });
});

function optCheckAddressValidation () {

    jQuery('.opterrmsg').remove();
    jQuery('form#edit_address input').removeClass('opterror');
    var bool = 1;
    if (jQuery.trim(jQuery('#billing_first_name').val()) == '') {
        jQuery('#billing_first_name').after('<span class="opterrmsg">Please enter your first name.</span>');
        jQuery('#billing_first_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#billing_last_name').val()) == '') {
        jQuery('#billing_last_name').after('<span class="opterrmsg">Please enter your last name.</span>');
        jQuery('#billing_last_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#billing_country').val()) == '') {
        jQuery('#billing_country').after('<span class="opterrmsg">Please select your country.</span>');
        jQuery('#billing_country').addClass('error');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#billing_address_1').val()) == '') {
        jQuery('#billing_address_1').after('<span class="opterrmsg">Please enter your address details.</span>');
        jQuery('#billing_address_1').addClass('error');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#billing_city').val()) == '') {
        jQuery('#billing_city').after('<span class="opterrmsg">Please enter your city name.</span>');
        jQuery('#billing_city').addClass('error');
        bool = 0;
    }


    if (jQuery.trim(jQuery('#billing_email').val()) == '') {
        jQuery('#billing_email').after('<span class="opterrmsg">Please enter your email address.</span>');
        jQuery('#billing_email').addClass('opterror');
        bool = 0;
    }
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (jQuery.trim(jQuery('#billing_email').val()) != '') {
        if (!jQuery('#billing_email').val().match(mailformat)) {
            jQuery('#billing_email').after('<span class="opterrmsg">Please enter valid email address.</span>');
            jQuery('#billing_email').addClass('opterror');
            bool = 0;
        }
    }

    if (jQuery.trim(jQuery('#billing_phone').val()) == '') {
            jQuery('#billing_phone').after('<span class="opterrmsg">Please enter your phone number.</span>');
            jQuery('#billing_phone').addClass('error');
            bool = 0;
    } else if (jQuery.trim(jQuery('#billing_phone').val().length) != 10) {
            jQuery('#billing_phone').after('<span class="opterrmsg">Please enter 10 digits phone number.</span>');
            jQuery('#billing_phone').addClass('error');
            bool = 0; 
    }else{    
        var numbers = /^[0-9]+$/;
        
        if (jQuery.trim(jQuery('#billing_phone').val().match(numbers))) {
            
        }else{
            jQuery('#billing_phone').after('<span class="opterrmsg">Please enter valid 10 digits phone number.</span>');
            jQuery('#billing_phone').addClass('error');
            bool = 0; 
        }
    }

    if (jQuery.trim(jQuery('#billing_postcode').val()) == '') {
        jQuery('#billing_postcode').after('<span class="opterrmsg">Please enter your Postcode/Zip.</span>');
        jQuery('#billing_postcode').addClass('error');
        bool = 0;
    }

    return bool;
}
</script>
