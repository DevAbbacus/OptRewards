<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>
<?php
	$opt_data = new Opt_Rewards_Data(); 
	$authenticationMethod = $opt_data->getAuthenticationMethod();
	$current_user = wp_get_current_user();
	$customerId = $current_user->ID;
	$user_email = $current_user->user_email;
	$phone  = get_user_meta( $customerId, 'user_phone_no', true);
?>

<form class="woocommerce-EditAccountForm edit-account" id="edit-my-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<fieldset>
		<legend><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<?php if ($authenticationMethod == 'otp_code'){ ?>
            <button type="submit" class="woocommerce-Button button opt-main-btn"  name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" style="display: none;"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
            <button class="woocommerce-Button button opt-btn"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<?php } else { ?>
            <button type="submit" class="woocommerce-Button button opt-main-btn" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<?php } ?>
		
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

<a class="account-popup" href="#account-popup"></a> 
<div style="display: none;max-width: 700px;" id="account-popup">
	<div class="guest-popup auth-otp">
    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
        <p class="message success" id="accountSMsg" style='display:none'></p>
        <p class="message error" id="accountEMsg" style='display:none'></p>   
		<form id="otp_form" class="otp_form" action="" method="post">
				<label for="otp_code">Verification Code</label>
				<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
				<input id="edit_pop" type="submit" name="otp_pop" value="Login"/>
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

	jQuery(".woocommerce-Button.opt-main-btn").click(function() {
        var valid = optCheckMyAccountValidation();

	    if(valid == 1){
	        return true;
	    }else{
	    	return false;
	    }
	});	

	var otpCountTime = 30;
    var firstInterval = null;
	jQuery(".woocommerce-Button.opt-btn").click(function() {
	    var valid = optCheckMyAccountValidation();
	    if(valid == 0){
	        return false;
	    }else if(valid == 1){
	        jQuery('.opt-loader').show();
	        var inquiryway = 'email';
	        var email = '<?php echo $user_email; ?>'; 
	        var phone = '<?php echo $phone; ?>';
	        var action = 'verification_api';
	        jQuery.ajax({
	            url:"<?php echo admin_url('admin-ajax.php'); ?>",
	            type:'POST',
	            data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
	            success: function(data){
	                jQuery('.opt-loader').hide();
	                if(data == 'Success'){
	                    jQuery("#resendLink").hide();
	                    jQuery(".account-popup").fancybox({
						    afterClose: function () {
							    if(firstInterval != null){
                                    clearInterval(firstInterval);
                                    otpCountTime = 30;
	                                jQuery('#accountSMsg').hide();
	                                jQuery('#accountEMsg').hide();
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

	jQuery("#edit_pop").click(function($){
		jQuery("#accountEMsg").text('');
        jQuery("#accountEMsg").hide();
        jQuery("#accountSMsg").text('');
        jQuery("#accountSMsg").hide();
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
                		jQuery(".opt-btn").hide();
                		jQuery(".woocommerce-Button.opt-main-btn").show();
    		            jQuery('button.woocommerce-Button.opt-main-btn').trigger('click');
                        jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
                	}else{
                		jQuery("#accountEMsg").text(data);
                        jQuery("#accountEMsg").show();
                	}
                   
                }
            });  
        } 
        return false;
    });  

    jQuery("#resendLink").click(function(){
        var action_data = "resend_otp";
        var email = '<?php echo $user_email; ?>'; 
        var phone = '<?php echo $phone; ?>';
        var inquiryway = 'email';    
        jQuery.ajax({
            url:"<?php echo admin_url('admin-ajax.php'); ?>",
            type:'POST',
            data: "email=" + email + "&phone=" + phone + "&action=" + action_data,

            success: function(data){
                if(data == 'Success'){										
                	jQuery("#accountSMsg").text('Verification Code has been sent');
                    jQuery("#accountSMsg").show();
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
                            jQuery('#accountSMsg').text('');
                            jQuery("#accountSMsg").hide();
                        }
                    }, 1000);  
                }else{
                	jQuery("#accountEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
                    jQuery("#accountEMsg").show();
                }    
                   
            }
        });   
    });
});

function optCheckMyAccountValidation () {

    jQuery('.opterrmsg').remove();
    jQuery('form.woocommerce-EditAccountForm.edit-account input').removeClass('opterror');
    var bool = 1;
    if (jQuery.trim(jQuery('#account_first_name').val()) == '') {
        jQuery('#account_first_name').after('<span class="opterrmsg">Please enter your first name.</span>');
        jQuery('#account_first_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#account_last_name').val()) == '') {
        jQuery('#account_last_name').after('<span class="opterrmsg">Please enter your last name.</span>');
        jQuery('#account_last_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#account_display_name').val()) == '') {
        jQuery('#account_display_name').after('<span class="opterrmsg">Please enter your display name.</span>');
        jQuery('#account_display_name').addClass('opterror');
        bool = 0;
    }

    if (jQuery.trim(jQuery('#account_email').val()) == '') {
        jQuery('#account_email').after('<span class="opterrmsg">Please enter your email address.</span>');
        jQuery('#account_email').addClass('opterror');
        bool = 0;
    }
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (jQuery.trim(jQuery('#account_email').val()) != '') {
        if (!jQuery('#account_email').val().match(mailformat)) {
            jQuery('#account_email').after('<span class="opterrmsg">Please enter valid email address.</span>');
            jQuery('#account_email').addClass('opterror');
            bool = 0;
        }
    }

    if (jQuery.trim(jQuery('#user_mobile_phone').val()) == '') {
            jQuery('#user_mobile_phone').after('<span class="opterrmsg">Please enter your phone number.</span>');
            jQuery('#user_mobile_phone').addClass('error');
            bool = 0;
    } else if (jQuery.trim(jQuery('#user_mobile_phone').val().length) != 10) {
            jQuery('#user_mobile_phone').after('<span class="opterrmsg">Please enter 10 digits phone number.</span>');
            jQuery('#user_mobile_phone').addClass('error');
            bool = 0; 
    }else{    
        var numbers = /^[0-9]+$/;
        
        if (jQuery.trim(jQuery('#user_mobile_phone').val().match(numbers))) {
            
        }else{
            jQuery('#user_mobile_phone').after('<span class="opterrmsg">Please enter valid 10 digits phone number.</span>');
            jQuery('#user_mobile_phone').addClass('error');
            bool = 0; 
        }
    }

    
    return bool;
}
</script>
