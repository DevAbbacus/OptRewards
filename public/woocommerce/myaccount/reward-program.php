<?php
/**
 * Reward Program
 *
 * @link       https://app.optculture.com/
 * @package    opt-rewards
 * @subpackage opt-rewards/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	<div class="opt_reward_points block" id="consult-loyalty">

	    <?php 

	    global $wp;
        $current_url = home_url( $wp->request );

        $phone = $city = $zip = $country = $state = $addressLine1 = $addressLine2 = "";

	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $opt_data = new Opt_Rewards_Data();

		$getEnablePhone = $opt_data->getEnablePhone();
		$getEnableAddress = $opt_data->getEnableAddress();
		$getAuthMethod = $opt_data->getAuthenticationMethod();

		$current_user = wp_get_current_user();
		$customerId = $current_user->ID;
		$enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);

		if($customerId != ''){ 
            $firstName = $current_user->user_firstname;
            $lastName = $current_user->user_lastname;
            $email = $current_user->user_email;
            $phone_no  = get_user_meta( $customerId, 'user_phone_no', true);
		}

		if(isset($_POST['join_reward'])){
            
            $phone = isset($_POST["billing_phone"]) ? $_POST["billing_phone"] : "";
            $addressLine1 = isset($_POST["billing_address_1"]) ? $_POST["billing_address_1"] : "";
            $addressLine2 = isset($_POST["billing_address_2"]) ? $_POST["billing_address_2"] : "";
            
            $city = isset($_POST["billing_city"]) ? $_POST["billing_city"] : "";
            $zip = isset($_POST["billing_postcode"]) ? $_POST["billing_postcode"] : "";

            $countryCode = isset($_POST["billing_country"]) ? $_POST["billing_country"] : "";
            $stateId = isset($_POST["billing_state"]) ? $_POST["billing_state"] : "";
            $country = "";
            $state = "";
            if ($countryCode !== "") {
                $country = WC()->countries->countries[$countryCode];
            }
            if ($countryCode != "" && $stateId != '') {
                $state = WC()->countries->get_states( $countryCode )[$stateId];
            }


            if (!empty($email)) {
	            $result = $opt_operation->newEnrollment(
	                $phone, $email, $addressLine1, $addressLine2,
	                $firstName, $lastName, $city, $state,
	                $zip, $country
	            );
    	        if(isset($result['status']) && $result['status']['status'] == 'Success') {

	                $membership_no = $result['membership']['cardNumber']; 
	                if($membership_no != ''){
	                	update_user_meta( $customerId, 'user_reward_program_enable', 1 );
	                	update_user_meta( $customerId, 'user_membership_no', $membership_no );
	                	update_user_meta( $customerId, 'user_phone_no', sanitize_text_field( $phone ) );

	                	if ( isset( $_POST['billing_country'] ) && $_POST['billing_country'] != '' ) {

				            update_user_meta( $customerId, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );

				        }


				        if ( isset( $_POST['billing_address_1'] ) && $_POST['billing_address_1'] != '' ) {

				            update_user_meta( $customerId, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );

				        }

				        if ( isset( $_POST['billing_address_2'] ) && $_POST['billing_address_2'] != '' ) {

				            update_user_meta( $customerId, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );

				        }


				        if ( isset( $_POST['billing_city'] ) && $_POST['billing_city'] != '' ) {

				            update_user_meta( $customerId, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );

				        }

				        if ( isset( $_POST['billing_postcode'] ) && $_POST['billing_postcode'] != '' ) {

				            update_user_meta( $customerId, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );

				        }

				        if ( isset( $_POST['billing_phone'] ) && $_POST['billing_phone'] != '' ) {
				            
				            update_user_meta( $customerId, 'user_phone_no', sanitize_text_field( $_POST['billing_phone'] ) );
				        } ?>
                        <script type="text/javascript"> window.location.href = "<?php echo $current_url; ?>"; </script> 
				        <?php
	                }
	            }
            }
		}

		if (empty($enrollmentOption) && $enrollmentOption != "1") { ?>

	        <?php echo $opt_data->getHtmlContentJoin(); ?>
	        <?php if($getEnablePhone == 1){ ?>
	            <form method="post" class="join_rewrd_form" id="join_rewrd_form">
                    <div class="rewards_program_phone_fields" style="display:none">

				        <p class="form-row form-row-wide">
					        <label for="opt_reg_billing_phone"><?php _e( 'Mobile Number', 'opt-rewards' ); ?> <span class="required">*</span></label>
					        <input type="text" class="input-text" name="billing_phone" id="opt_reg_billing_phone" maxlength='10' value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
					        <input type="hidden" class="input-text" name="opt_phone_enable" id="opt_phone_enable"  value="<?php echo $getEnablePhone; ?>" />
					        
				        </p>
				    
					        
			            <?php if($getEnableAddress == 1) {
			                wp_enqueue_script( 'wc-country-select' );
					        woocommerce_form_field( 'billing_country', array(
						        'type'      => 'country',
						        'class'     => array('chzn-drop'),
						        'label'     => __('Country'),
						        'placeholder' => __('Choose your country.'),
						        'required'  => true,
						        'id'         => 'opt_reg_billing_country',
						        'clear'     => true
						    ));
						     woocommerce_form_field( 'billing_state', array(
						        'type'      => 'state',
						        'class'     => array('chzn-drop'),
						        'label'     => __('State'),
						        'placeholder' => __('Choose your state.'),
						        'required'  => true,
						        'clear'     => true
						    ));
			                 ?>
					        <p class="form-row form-row-wide">
						        <label for="opt_reg_billing_address_1"><?php _e( 'Street address', 'opt-rewards' ); ?> <span class="required">*</span></label>
						        <input type="text" class="input-text" name="billing_address_1" id="opt_reg_billing_address_1" placeholder="House number and street name" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
					        </p>

					        <p class="form-row form-row-wide">
						        <input type="text" class="input-text" name="billing_address_2" id="opt_reg_billing_address_2" placeholder="Apartment, suite, unit etc. (optional)" value="<?php if ( ! empty( $_POST['billing_address_2'] ) ) esc_attr_e( $_POST['billing_address_2'] ); ?>" />
					        </p>

					        <p class="form-row form-row-wide">
						        <label for="opt_reg_billing_city"><?php _e( 'Town / City', 'opt-rewards' ); ?> <span class="required">*</span></label>
						        <input type="text" class="input-text" name="billing_city" id="opt_reg_billing_city"  value="<?php if ( ! empty( $_POST['billing_city'] ) ) esc_attr_e( $_POST['billing_city'] ); ?>" />
					        </p>

					        <p class="form-row form-row-wide">
						        <label for="opt_reg_billing_postcode"><?php _e( 'Postcode / ZIP', 'opt-rewards' ); ?> <span class="required">*</span></label>
						        <input type="text" class="input-text" name="billing_postcode" id="opt_reg_billing_postcode" value="<?php if ( ! empty( $_POST['billing_postcode'] ) ) esc_attr_e( $_POST['billing_postcode'] ); ?>" />
					        </p>
					        
			            <?php } ?>
			            <p class="form-row form-row-wide">
			            	<?php if($getAuthMethod == 'otp_code'){ ?>
                                <button type="submit" class="button" name="join_reward" id="join_reward" style="display: none;">Join</button>
                                <button type="submit" class="button" name="join_otp_reward" id="join_otp_reward">Join</button>
                            <?php } else { ?>
                                <button type="submit" class="button" name="join_reward" id="join_reward">Join</button>
                            <?php } ?>
                        </p>
		            </div>
		            <p class="form-row form-row-wide">
		            	<input type="hidden" class="input-text" name="opt_address_enable" id="opt_address_enable"  value="<?php echo $getEnableAddress; ?>" />
		            	<button type="button" class="button"  id="join_button">Join</button>
                    </p>
                </form>    
	        <?php } else {  ?>
	            <form method="post" action="<?php echo $current_url; ?>">	        
	      	        <?php if($getAuthMethod == 'otp_code'){ ?>
                        <button type="submit" class="button" name="join_reward" id="join_reward" style="display: none;">Join</button>
                        <button type="submit" class="button" name="join_otp_reward" id="join_otp_reward">Join</button>
                    <?php } else { ?>
                        <button type="submit" class="button" name="join_reward" id="join_reward">Join</button>
                    <?php } ?>
	      	    </form>    
	        <?php } ?>

		<?php }  else {

			$balancesInquiryResponse = $opt_operation->balancesInquiry($phone_no, $email);
            
            $loyaltyPoints = '';
		    $amountMoney = '';
		    $extraLoyaltyCode = '';
		    $extraLoyaltyAmount = 0;
			if (!empty($balancesInquiryResponse)) {
                $msg = "Your Reward Summary";

		        foreach ($balancesInquiryResponse as $loyaltyInfo) {
		            if($loyaltyInfo['value_code'] === 'Points' && $loyaltyInfo['amount'] !== '') {
		                $loyaltyPoints = $loyaltyInfo['amount'];
		            }
		            else if($loyaltyInfo['value_code'] === 'USD' && $loyaltyInfo['amount'] !== '') {
		                $amountMoney = $loyaltyInfo['amount'];
		            }
		            else if($loyaltyInfo['value_code'] != "" && $loyaltyInfo['amount'] != "")
	                {
	                	$extraLoyaltyCode = $loyaltyInfo['value_code'];
	                	$extraLoyaltyAmount = $loyaltyInfo['amount'];
	                }
		        }
		        $rewards = '<h2>'.$msg.'</h2>';
	            $rewards .= '<table style="max-width:300px";>
	                <tr>
	                    <td>Code</td>
	                    <td>Amount</td>
	                </tr>
	                <tr>
	                    <td>USD</td>
	                    <td>'.$amountMoney.'</td>
	                </tr>
	                <tr>
	                    <td>Points</td>
	                    <td>'.$loyaltyPoints.'</td>
	                </tr>';
	                if($extraLoyaltyAmount > 0)
            		{
					$rewards .= '<tr>
				                    <td>'.$extraLoyaltyCode.'</td>
				                    <td>'.$extraLoyaltyAmount.'</td>
				                </tr>';
	            		}
	            $rewards .= '</table>';
			}else{
				$msg = "You do not have loyalty points.";
                $rewards = '<h2>'.$msg.'</h2>';
			}
            
            echo $rewards;
	    
	       
		} ?>

    </div>

    <a class="reward-join-popup" href="#join-reward-popup"></a> 
	<div style="display: none;max-width: 700px;" id="join-reward-popup">
		<div class="guest-popup auth-otp">
	    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
	        <p class="message success" id="joinSMsg" style='display:none'></p>
	        <p class="message error" id="joinEMsg" style='display:none'></p>   
			<form id="otp_form" class="otp_form" action="" method="post">
				<label for="otp_code">Verification Code</label>
				<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
				<input id="join_pop" type="submit" name="otp_pop" value="Login"/>
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
        var authMethod = '<?php echo $getAuthMethod; ?>';
        var otpCountTime = 30;
	    var firstInterval = null;
	    jQuery('input[type=radio][name=inquiry]').change(function() {
		    if (this.value == 'email') {
		        jQuery(".byemail").show();
		        jQuery(".byphone").hide();
		    }
		    else if (this.value == 'phone') {
		        jQuery(".byemail").hide();
		        jQuery(".byphone").show();
		    }
	    });

	    jQuery("#join_button").click(function(){
	        jQuery(".rewards_program_phone_fields").show();
	        jQuery("#join_button").hide();
	    });  

	    jQuery("#join_reward").click(function() {
	        var valid = checkJoinValidation();
		    if(valid == 1){
		        jQuery("#join_rewrd_form").submit();
		    }else{
		    	return false;
		    }
		});	

	    jQuery("#join_otp_reward").click(function(){
            var valid = checkJoinValidation(); 
		    if (valid == 0) {
	            return false;
	        }else{
	        	if(authMethod == 'otp_code'){
                    jQuery('.opt-loader').show();
			        var inquiryway = 'email';
			        var email = '<?php echo $email; ?>'; 
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
			                    jQuery(".reward-join-popup").fancybox({
								    afterClose: function () {
									    if(firstInterval != null){
		                                    clearInterval(firstInterval);
		                                    otpCountTime = 30;
			                                jQuery('#joinSMsg').hide();
			                                jQuery('#joinEMsg').hide();
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
	        	}else{
	        		jQuery("#join_rewrd_form").submit();
	        	}
	        }    
	 
	    });	

	    jQuery("#resendLink").click(function(){
	        var action_data = "resend_otp";
	        var email = '<?php echo $email; ?>'; 
	        var phone =  '<?php echo $phone; ?>'; 
	        var inquiryway = 'email';    
	        jQuery.ajax({
	            url:"<?php echo admin_url('admin-ajax.php'); ?>",
	            type:'POST',
	            data: "email=" + email + "&phone=" + phone + "&action=" + action_data,

	            success: function(data){
	                if(data == 'Success'){										
	                	jQuery("#joinSMsg").text('Verification Code has been sent');
	                    jQuery("#joinSMsg").show();
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
	                            jQuery('#joinSMsg').text('');
	                            jQuery("#joinSMsg").hide();
	                        }
	                    }, 1000);  
	                }else{
	                	jQuery("#joinEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
	                    jQuery("#joinEMsg").show();
	                }    
	                   
	            }
	        });   
	    });
	});

	jQuery("#join_pop").click(function($){
		jQuery("#joinEMsg").text('');
        jQuery("#joinEMsg").hide();
        jQuery("#joinSMsg").text('');
        jQuery("#joinSMsg").hide();
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
                		jQuery("#join_reward").show();
                		jQuery("#join_otp_reward").hide();
    		            jQuery('button#join_reward').trigger('click');
                        jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
                	}else{
                		jQuery("#joinEMsg").text(data);
                        jQuery("#joinEMsg").show();
                	}
                }
            });  
        } 
        return false;
    });

    function checkJoinValidation(){
    	jQuery('.opterrmsg').remove();
        jQuery('form.join_rewrd_form input').removeClass('opterror');
        var bool = 1;
        
        if ( jQuery('#opt_phone_enable').val() == 1 ) {

        	if (jQuery.trim(jQuery('#opt_reg_billing_phone').val()) == '') {
	            jQuery('#opt_reg_billing_phone').after('<span class="opterrmsg">Please enter your mobile number.</span>');
	            jQuery('#opt_reg_billing_phone').addClass('error');
	            bool = 0;
	        } else {
		        if (jQuery.trim(jQuery('#opt_reg_billing_phone').val().length) != 10) {
			            jQuery('#opt_reg_billing_phone').after('<span class="opterrmsg">10 digits mandatory for mobile number.</span>');
			            jQuery('#opt_reg_billing_phone').addClass('error');
			            bool = 0;
		        }
		    }
        }

        if ( jQuery('#opt_address_enable').val() == 1 ) {

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
	   
        }
	    return bool;    
    }
</script>



