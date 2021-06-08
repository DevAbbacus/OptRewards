<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>
<?php
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$url = site_url('/join-reward-program/');
$opt_data = new Opt_Rewards_Data(); 
$authenticationMethod = $opt_data->getAuthenticationMethod();
?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">
    
	<div class="u-column1 col-1">

<?php endif; ?>
        <?php 
        $adcls = ''; 
        if($referer == $url) {$adcls = 'fullreg';}
        if($referer != $url) : 
         ?>
		<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>
        <?php endif; ?>
<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>
   

	<div class="u-column2 col-2 <?php echo $adcls; ?>">

		<h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> id="woocommerce-form-register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>

			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
				</p>

			<?php else : ?>

				<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="woocommerce-form-row form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<?php if ($authenticationMethod == 'otp_code'){ ?>
				   <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit nml-button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
				
                   <button class="woocommerce-Button woocommerce-button button otp-button" style="display:none;"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>

			    <?php } else { ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
			    <?php } ?>	
			    
			</p>
			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<a class="enroll-popup" href="#enroll-popup"></a> 
<div style="display: none;max-width: 700px;" id="enroll-popup">
	<div class="guest-popup auth-otp">
    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
        <p class="message success" id="enrollSMsg" style='display:none'></p>
        <p class="message error" id="enrollEMsg" style='display:none'></p>   
		<form id="otp_form" class="otp_form" action="" method="post">
				<label for="otp_code">Verification Code</label>
				<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
				<input id="enroll_pop" type="submit" name="otp_pop" value="Login"/>
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
    var authMethod = '<?php echo $authenticationMethod; ?>'; 
    getAuthmethod(authMethod);

	jQuery(".woocommerce-Button.woocommerce-form-register__submit").click(function() {
        var valid = optCheckUserValidation();
	    if(valid == 1){
	        return true;
	    }else{
	    	return false;
	    }
	});	

	jQuery("#opt_user_reward_program_enable").click(function () {
		var authMethod = jQuery('#opt_auth_method').val();
        getAuthmethod(authMethod);
    });

    var otpCountTime = 30;
	var firstInterval = null;
	jQuery(".woocommerce-Button.otp-button").click(function() {
	    var valid = optCheckUserValidation();

	    if(valid == 0){
	        return false;
	    }else if(valid == 1){
	        jQuery('.opt-loader').show();
	        var inquiryway = 'email';
	        var email = jQuery('#reg_email').val(); 
	        var phone = jQuery('#opt_reg_billing_phone').val(); 
	        var action = 'create_verification_api';
	        jQuery.ajax({
	            url:"<?php echo admin_url('admin-ajax.php'); ?>",
	            type:'POST',
	            data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
	            success: function(data){

	                jQuery('.opt-loader').hide();
	                if(data == 'Success'){
	                    jQuery("#resendLink").hide();
	                    jQuery(".enroll-popup").fancybox({
						    afterClose: function () {
							    if(firstInterval != null){
                                    clearInterval(firstInterval);
                                    otpCountTime = 30;
	                                jQuery('#enrollEMsg').hide();
	                                jQuery('#enrollSMsg').hide();
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

	jQuery("#enroll_pop").click(function($){
		jQuery("#enrollEMsg").text('');
	    jQuery("#enrollEMsg").hide();
	    jQuery("#enrollSMsg").text('');
	    jQuery("#enrollSMsg").hide();
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
	    	
	        var action_data = "create_otp_code";
	        
	        jQuery.ajax({
	            url:"<?php echo admin_url('admin-ajax.php'); ?>",
	            type:'POST',
	            data: jQuery('#otp_form').serialize() + "&action=" + action_data,
	            
	            success: function(data){
	                											
	            	if(data == 'Success'){
	            		jQuery(".otp-button").hide();
	            		jQuery(".woocommerce-form-register__submit").show();
			            jQuery('button.woocommerce-Button.woocommerce-form-register__submit').trigger('click');
			            jQuery("#is_validate").val(1);
	                    jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
	            	}else{
	            		jQuery("#enrollEMsg").text(data);
	                    jQuery("#enrollEMsg").show();
	            	}
	               
	            }
	        });  
	    } 
	    return false;
	});  

	jQuery("#resendLink").click(function(){
	    var action_data = "enroll_resend_otp_code";
	    var inquiryway = 'email';    
	    jQuery.ajax({
	        url:"<?php echo admin_url('admin-ajax.php'); ?>",
	        type:'POST',
	        data: jQuery('#woocommerce-form-register').serialize() + "&inquiryway=" + inquiryway + "&action=" + action_data,

	        success: function(data){
	            if(data == 'Success'){										
	            	jQuery("#enrollSMsg").text('Verification Code has been sent');
	                jQuery("#enrollSMsg").show();
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
	                        jQuery('#enrollSMsg').text('');
	                        jQuery("#enrollSMsg").hide();
	                    }
	                }, 1000);  
	            }else{
	            	jQuery("#enrollEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
	                jQuery("#enrollEMsg").show();
	            }    
	               
	        }
	    });   
	});
});

function getAuthmethod(authMethod){
	if (jQuery('#opt_user_reward_program_enable').is(":checked") && authMethod == 'otp_code') {
	    jQuery(".otp-button").show();
	    jQuery(".nml-button").hide();
	} else {
	    jQuery(".otp-button").hide();
	    jQuery(".nml-button").show();
	}
}

function optCheckUserValidation () {

    jQuery('.opterrmsg').remove();
    jQuery('form.woocommerce-form-register input').removeClass('opterror');
    var bool = 1;
    if (jQuery.trim(jQuery('#opt_reg_billing_first_name').val()) == '') {
        jQuery('#opt_reg_billing_first_name').after('<span class="opterrmsg">Please enter your first name.</span>');
        jQuery('#opt_reg_billing_first_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#opt_reg_billing_last_name').val()) == '') {
        jQuery('#opt_reg_billing_last_name').after('<span class="opterrmsg">Please enter your last name.</span>');
        jQuery('#opt_reg_billing_last_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#reg_email').val()) == '') {
        jQuery('#reg_email').after('<span class="opterrmsg">Please enter your email address.</span>');
        jQuery('#reg_email').addClass('opterror');
        bool = 0;
    }
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (jQuery.trim(jQuery('#reg_email').val()) != '') {
        if (!jQuery('#reg_email').val().match(mailformat)) {
            jQuery('#reg_email').after('<span class="opterrmsg">Please enter valid email address.</span>');
            jQuery('#reg_email').addClass('opterror');
            bool = 0;
        }
    }

    if ( (jQuery('#opt_user_reward_program_enable').is(":checked") || jQuery('#opt_join_reward').val() == 'join_reward' ) && jQuery('#opt_phone_enable').val() == 1 ) {

        if (jQuery.trim(jQuery('#opt_reg_billing_phone').val()) == '') {
            jQuery('#opt_reg_billing_phone').after('<span class="opterrmsg">Please enter your phone number.</span>');
            jQuery('#opt_reg_billing_phone').addClass('error');
            bool = 0;
        } else if (jQuery.trim(jQuery('#opt_reg_billing_phone').val().length) != 10) {
                jQuery('#opt_reg_billing_phone').after('<span class="opterrmsg">Please enter 10 digits phone number.</span>');
                jQuery('#opt_reg_billing_phone').addClass('error');
                bool = 0; 
        }else{    
            var numbers = /^[0-9]+$/;
            
            if (jQuery.trim(jQuery('#opt_reg_billing_phone').val().match(numbers))) {
                
            }else{
                jQuery('#opt_reg_billing_phone').after('<span class="opterrmsg">Please enter valid 10 digits phone number.</span>');
                jQuery('#opt_reg_billing_phone').addClass('error');
                bool = 0; 
            }
        }
    }

    if ( (jQuery('#opt_user_reward_program_enable').is(":checked") || jQuery('#opt_join_reward').val() == 'join_reward')&& jQuery('#opt_address_enable').val() == 1 ) {

        if (jQuery.trim(jQuery('#opt_reg_billing_country').val()) == '') {
            jQuery('#opt_reg_billing_country').after('<span class="opterrmsg">Please select your country.</span>');
            jQuery('#opt_reg_billing_country').addClass('error');
            bool = 0;
        }

         if (jQuery.trim(jQuery('#billing_state').val()) == '') {
            jQuery('#billing_state').after('<span class="opterrmsg">Please select your state.</span>');
            jQuery('#billing_state').addClass('error');
            bool = 0;
        }

        if (jQuery.trim(jQuery('#opt_reg_billing_address_1').val()) == '') {
            jQuery('#opt_reg_billing_address_1').after('<span class="opterrmsg">Please enter your address details.</span>');
            jQuery('#opt_reg_billing_address_1').addClass('error');
            bool = 0;
        }

        if (jQuery.trim(jQuery('#opt_reg_billing_city').val()) == '') {
            jQuery('#opt_reg_billing_city').after('<span class="opterrmsg">Please enter your city name.</span>');
            jQuery('#opt_reg_billing_city').addClass('error');
            bool = 0;
        }

        if (jQuery.trim(jQuery('#opt_reg_billing_postcode').val()) == '') {
            jQuery('#opt_reg_billing_postcode').after('<span class="opterrmsg">Please enter your Postcode/Zip.</span>');
            jQuery('#opt_reg_billing_postcode').addClass('error');
            bool = 0;
        }
        
        var numbers = /^[0-9]+$/;
        if (jQuery.trim(jQuery('#opt_reg_billing_postcode').val()) != '') {
        	if (jQuery.trim(jQuery('#opt_reg_billing_postcode').val().match(numbers))) {
            
	        }else{
	            jQuery('#opt_reg_billing_postcode').after('<span class="opterrmsg">Please enter only digits.</span>');
	            jQuery('#opt_reg_billing_postcode').addClass('error');
	            bool = 0; 
	        }
        }    
        
    } 
    return bool;
}
</script>

