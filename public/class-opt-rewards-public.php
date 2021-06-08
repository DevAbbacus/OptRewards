<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://app.optculture.com/
 * @since      1.0.0
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public
 * @author     Optculture Team
 */
class Opt_Rewards_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, OPT_REWARDS_DIR_URL . 'public/css/opt-rewards-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'fancybox', OPT_REWARDS_DIR_URL . 'public/css/jquery.fancybox.css', array(), 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, OPT_REWARDS_DIR_URL . 'public/js/opt-rewards-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'fancybox', OPT_REWARDS_DIR_URL . 'public/js/jquery.fancybox.min.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Including a File for api common function
	 *
	 * @name opt_rewards_public_api_setting()
	 */
	public function opt_rewards_public_api_setting() {
		require_once OPT_REWARDS_DIR_PATH . '/public/api/class-opt-rewards-api.php';
	}

	/**
	 * Including a File for api data
	 *
	 * @name opt_rewards_public_api_data()
	 */
	public function opt_rewards_public_api_data() {
		require_once OPT_REWARDS_DIR_PATH . '/public/api/class-opt-rewards-data.php';
	}

	/**
	 * Including a File for api operations
	 *
	 * @name opt_rewards_public_api_operations()
	 */
	public function opt_rewards_public_api_operations() {
		require_once OPT_REWARDS_DIR_PATH . '/public/api/class-opt-rewards-api-operations.php';
	}

	/**
	 * This function is used for override woocommerce template
	 * @name opt_rewards_woo_plugin_template
	 */


	function opt_rewards_woo_plugin_template( $template, $template_name, $template_path ) {
	    global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) 
		$template_path = $woocommerce->template_url;


	    $plugin_path  = OPT_REWARDS_WOO_TEMPLATES_DIR;
 
	    $template = locate_template(
		    array(
		      $template_path . $template_name,
		      $template_name
		    )
        );
 
	   if( ! $template && file_exists( $plugin_path . $template_name ) )
	    $template = $plugin_path . $template_name;
	 
	   if ( ! $template )
	    $template = $_template;

	   return $template;
    }

    /**
	 * This function is used for create log directory
	 * @name opt_rewards_create_log_directory
	 */


    public function  opt_rewards_create_log_directory(){
        
        $dirname = OPT_WP_ROOT_DIR.'/optrewards-log/';
		if ( ! is_dir( $dirname ) ) {
            @mkdir( $dirname, 0777, true ); 
            if ( ! file_exists( $dirname ) ) {
                return false;
            }
        }

        /*--If we still cannot write, bail. --*/
        if ( ! is_writable( $dirname ) ) {
            return false;
        }
    }

    /**
	 * This function is used for adding name fields in registration page
	 * @name opt_rewards_user_registration_add_name_fields
	 */

	public function opt_rewards_user_registration_add_name_fields() {
		?>

	    <p class="form-row form-row-first">
	        <label for="opt_reg_billing_first_name"><?php _e( 'First name', 'opt-rewards' ); ?><span class="required">*</span></label>
	        <input type="text" class="input-text" name="billing_first_name" id="opt_reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
        </p>

        <p class="form-row form-row-last">
	        <label for="opt_reg_billing_last_name"><?php _e( 'Last name', 'opt-rewards' ); ?><span class="required">*</span></label>
	        <input type="text" class="input-text" name="billing_last_name" id="opt_reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
        </p>
         <?php
         
	}	

	/**
	 * This function is used for adding extra fields in registration page
	 * @name opt_rewards_user_registration_add_extra_fields
	 */

	public function opt_rewards_user_registration_add_extra_fields() {
		global $woocommerce;
        $getEnablePhone = '';
	    $opt_data = new Opt_Rewards_Data();
		$getEnablePhone = $opt_data->getEnablePhone();
		$getEnableAddress = $opt_data->getEnableAddress();
		$getAuthMethod = $opt_data->getAuthenticationMethod();
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $url = site_url('/join-reward-program/');
        if($referer == $url){
	    ?>
            <p class="form-row form-row-wide reward-enable">
		        <input type="hidden" class="input-text" name="opt_join_reward" id="opt_join_reward" value="join_reward" />
		        <input type="checkbox" class="input-checkbox" name="user_reward_program_enable" id="opt_user_reward_program_enable" value="1" checked/><label for="opt_user_reward_program_enable">Join the Reward Program</label>
		        <input type="hidden" class="input-text" name="opt_phone_enable" id="opt_phone_enable" value="<?php echo $getEnablePhone ?>" />
		        <input type="hidden" class="input-text" name="opt_auth_method" id="opt_auth_method" value="<?php echo $getAuthMethod ?>" />
		        <input type="hidden" class="input-text" name="opt_address_enable" id="opt_address_enable" value="<?php echo $getEnableAddress ?>" />
	        </p>

	        <div class="opt_join_fields">
                
	        	<?php if($getEnablePhone == 1) { ?>

			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_phone"><?php _e( 'Mobile Number', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_phone" id="opt_reg_billing_phone" maxlength='10' value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
			        </p>
			    <?php } ?>    

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
				        <label for="opt_reg_billing_address_1"><?php _e( 'Street address', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_address_1" id="opt_reg_billing_address_1" placeholder="House number and street name" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <input type="text" class="input-text" name="billing_address_2" id="opt_reg_billing_address_2" placeholder="Apartment, suite, unit etc. (optional)" value="<?php if ( ! empty( $_POST['billing_address_2'] ) ) esc_attr_e( $_POST['billing_address_2'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_city"><?php _e( 'Town / City', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_city" id="opt_reg_billing_city"  value="<?php if ( ! empty( $_POST['billing_city'] ) ) esc_attr_e( $_POST['billing_city'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_postcode"><?php _e( 'Postcode / ZIP', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_postcode" id="opt_reg_billing_postcode" value="<?php if ( ! empty( $_POST['billing_postcode'] ) ) esc_attr_e( $_POST['billing_postcode'] ); ?>" />
			        </p>
			        
	            <?php } ?>
	        </div>
        
    <?php
        } else { ?>
            <p class="form-row form-row-wide reward-enable">
		        <input type="checkbox" class="input-checkbox" name="user_reward_program_enable" id="opt_user_reward_program_enable" value="1" /><label for="opt_user_reward_program_enable">Join the Reward Program</label>
		        <input type="hidden" class="input-text" name="opt_phone_enable" id="opt_phone_enable" value="<?php echo $getEnablePhone ?>" />
		        <input type="hidden" class="input-text" name="opt_auth_method" id="opt_auth_method" value="<?php echo $getAuthMethod ?>" />
		        <input type="hidden" class="input-text" name="opt_address_enable" id="opt_address_enable" value="<?php echo $getEnableAddress ?>" />
	        </p>

	        <div class="opt_phone_fields" style="display:none">

	        	<?php if($getEnablePhone == 1) { ?>
			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_phone"><?php _e( 'Mobile Number', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_phone" id="opt_reg_billing_phone" maxlength='10' value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
			        </p>
			    <?php } ?>    

			        
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
				        <label for="opt_reg_billing_address_1"><?php _e( 'Street address', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_address_1" id="opt_reg_billing_address_1" placeholder="House number and street name" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <input type="text" class="input-text" name="billing_address_2" id="opt_reg_billing_address_2" placeholder="Apartment, suite, unit etc. (optional)" value="<?php if ( ! empty( $_POST['billing_address_2'] ) ) esc_attr_e( $_POST['billing_address_2'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_city"><?php _e( 'Town / City', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_city" id="opt_reg_billing_city"  value="<?php if ( ! empty( $_POST['billing_city'] ) ) esc_attr_e( $_POST['billing_city'] ); ?>" />
			        </p>

			        <p class="form-row form-row-wide">
				        <label for="opt_reg_billing_postcode"><?php _e( 'Postcode / ZIP', 'opt-rewards' ); ?><span class="required">*</span></label>
				        <input type="text" class="input-text" name="billing_postcode" id="opt_reg_billing_postcode" value="<?php if ( ! empty( $_POST['billing_postcode'] ) ) esc_attr_e( $_POST['billing_postcode'] ); ?>" />
			        </p>
			        
	            <?php } ?>
	        </div>

    <?php    
       }
    }


	/**
	 * This function is used for validating name fields.
	 * @name opt_rewards_user_registration_validate_name_fields
	 */

	public function opt_rewards_user_registration_validate_name_fields( $validation_errors ){

        if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {

            $validation_errors->add( 'billing_first_name_error', __( 'Please enter a first name.', 'opt-rewards' ) );

        }

        if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {

            $validation_errors->add( 'billing_last_name_error', __( 'Please enter a last name.', 'opt-rewards' ) );

        }

        return $validation_errors;
    } 

    /**
	 * This function is used for validating extra fields.
	 * @name opt_rewards_user_registration_validate_extra_fields
	 */   

    public function opt_rewards_user_registration_validate_extra_fields(  $username, $email, $validation_errors ){
        $opt_data = new Opt_Rewards_Data();
		$getEnablePhone = $opt_data->getEnablePhone();
		$getEnableAddress = $opt_data->getEnableAddress();
		if($getEnablePhone == 1 && $_POST['user_reward_program_enable'] == 1){

	        if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {

	            $validation_errors->add( 'billing_phone_error', __( 'Please enter a phone number.', 'opt-rewards' ) );

	        }
        }
        if($getEnableAddress == 1 && $_POST['user_reward_program_enable'] == 1){

	        if ( isset( $_POST['billing_country'] ) && empty( $_POST['billing_country'] ) ) {

	            $validation_errors->add( 'billing_country_error', __( 'Please select a country.', 'opt-rewards' ) );

	        }


	        if ( isset( $_POST['billing_address_1'] ) && empty( $_POST['billing_address_1'] ) ) {

	            $validation_errors->add( 'billing_address_1_error', __( 'Please enter an addess details.', 'opt-rewards' ) );

	        }

	        if ( isset( $_POST['billing_city'] ) && empty( $_POST['billing_city'] ) ) {

	            $validation_errors->add( 'billing_city_error', __( 'Please enter a city name.', 'opt-rewards' ) );

	        }

	        if ( isset( $_POST['billing_postcode'] ) && empty( $_POST['billing_postcode'] ) ) {

	            $validation_errors->add( 'billing_postcode_error', __( 'Please enter a Postcode/ZIP.', 'opt-rewards' ) );

	        }
        }
	    return $validation_errors;
	}

	/**
	 * This function is used for save extra fields.
	 * @name opt_rewards_user_registration_save_extra_fields
	 */

	public function opt_rewards_user_registration_save_extra_fields(  $customer_id ){

        if ( isset( $_POST['billing_first_name'] ) && $_POST['billing_first_name'] != '' ) {

            update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
            update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

        }

        if ( isset( $_POST['billing_last_name'] ) &&  $_POST['billing_last_name'] != '') {

            update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
            update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

        }

        if ( isset( $_POST['billing_country'] ) && $_POST['billing_country'] != '' ) {

            update_user_meta( $customer_id, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );

        }


        if ( isset( $_POST['billing_address_1'] ) && $_POST['billing_address_1'] != '' ) {

            update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );

        }

        if ( isset( $_POST['billing_address_2'] ) && $_POST['billing_address_2'] != '' ) {

            update_user_meta( $customer_id, 'billing_address_2', sanitize_text_field( $_POST['billing_address_2'] ) );

        }


        if ( isset( $_POST['billing_city'] ) && $_POST['billing_city'] != '' ) {

            update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );

        }

        if ( isset( $_POST['billing_postcode'] ) && $_POST['billing_postcode'] != '' ) {

            update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );

        }

        if ( isset( $_POST['billing_phone'] ) && $_POST['billing_phone'] != '' ) {
            
            update_user_meta( $customer_id, 'user_phone_no', sanitize_text_field( $_POST['billing_phone'] ) );
            update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );

        }

        if ( isset( $_POST['user_reward_program_enable'] ) && $_POST['user_reward_program_enable'] != '' ) {

            update_user_meta( $customer_id, 'user_reward_program_enable', sanitize_text_field( $_POST['user_reward_program_enable'] ) );

        }

        $membership_no = get_option( 'user_membership_no' );

        if($membership_no != '' && $_POST['user_reward_program_enable'] != ''){
        	update_user_meta( $customer_id, 'user_membership_no', $membership_no );
        	delete_option( 'user_membership_no' );
        	
        }

	}

	/**
	 * This function is used for newenrollment on register page.
	 * @name opt_rewards_beforeCreateAccount
	 */

	public function opt_rewards_beforeCreateAccount($customer, $password = null, $redirectUrl = '') {

        $enrollmentRewardData = "";
        if (!empty($_POST["user_reward_program_enable"])) {
            $enrollmentRewardData = $_POST["user_reward_program_enable"];
        }
        $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
        $fp = fopen($filepath, "a") or die("Unable to open file!");
        if ($enrollmentRewardData === "1") {
            fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
            fwrite($fp, 'Start Create New Enrollment Process'."\n");
            $opt_api_data = new Opt_Rewards_Data();
            if ($opt_api_data->getEnablePhone()) {
                $expr = '/^[0-9]+$/';
                $phoneL = strlen($_POST["billing_phone"]);
                $minVal = 10;
                $mixVal = 10;
                if (!(preg_match($expr, $phoneL) && (($minVal == $phoneL) && ($phoneL == $mixVal)))) {
                    throw new Exception(__("Please specify a valid %1 digits phone number.", $mixVal));
                    return;
                }
            }
            if (!empty($_POST["email"])) {
                $email = $_POST["email"];
                $rewards = isset($_POST["user_reward_program_enable"]) ? $_POST["user_reward_program_enable"] : 0;
                $phone = isset($_POST["billing_phone"]) ? $_POST["billing_phone"] : "";
                $firstName = isset($_POST["billing_first_name"]) ? $_POST["billing_first_name"] : "";
	            $lastName = isset($_POST["billing_last_name"]) ? $_POST["billing_last_name"] : "";

	            $city = "";
	            $zip = "";
	            $country = "";
	            $state = "";
	            $addressLine = "";
	            

                if($opt_api_data->getEnableAddress() == 1){
                	$addressLine1 = [];
	                $addressLine1[] = isset($_POST["billing_address_1"]) ? $_POST["billing_address_1"] : [];
	                $addressLine2 = isset($_POST["billing_address_2"]) ? $_POST["billing_address_2"] : [];
	                
	                $city = isset($_POST["billing_city"]) ? $_POST["billing_city"] : "";
	                $zip = isset($_POST["billing_postcode"]) ? $_POST["billing_postcode"] : "";

	                $countryCode = isset($_POST["billing_country"]) ? $_POST["billing_country"] : "";
	                $stateId = isset($_POST["billing_state"]) ? $_POST["billing_state"] : "";
	                if ($countryCode !== "") {
	                    $country = WC()->countries->countries[$countryCode];
	                }
	                if ($countryCode != "" && $stateId != '') {
	                    $state = WC()->countries->get_states( $countryCode )[$stateId];
	                }

	                $addressLine = "";
	                $addressLine = isset($addressLine1[0]) ? (string)$addressLine1[0] : "";
                }
               

                $opt_operation = new Opt_Rewards_Api_Operations();
                fwrite($fp, 'Send The API Request For New Enrollment'."\n");
		        
                $result = $opt_operation->newEnrollment(
                    $phone, $email, $addressLine, $addressLine,
                    $firstName, $lastName, $city, $state,
                    $zip, $country
                );
                if(isset($result['status']) && $result['status'] == 'Failure') {
                    fwrite($fp, 'ErrorCode:- '.$result['errorCode'].' Message:- '.$result['message'].'Status:- '.$result['status'].' '."\n");
                    throw new CommandException(__("".$result['message']));
                    return;
                } else {
                	fwrite($fp, 'New Contact Created Successfully With Membership# '.$result['membership']['cardNumber'].' '."\n");
                    update_option('user_membership_no',$result['membership']['cardNumber']);
                    
                }
                $cardNumber = [];
                if (!empty($result)) {
                    if (is_array($result)) {
                        foreach ($result as $member) {
                            if (isset($member["cardNumber"])) {
                                $cardNumber[] = (string) $member["cardNumber"];
                            }
                        }
                    } else {
                        if (isset($result["cardNumber"])) {
                            $cardNumber[] = (string) $result["cardNumber"];
                        }
                    }
                   
                } 
            } else {
                fwrite($fp, 'Customer Email Is Not Found'."\n");
            }
            fwrite($fp, 'End Create New Enrollment Process'."\n");
            fwrite($fp, '----------------------------------------------------------------'."\n\n");
            
        }
        
        return [$password, $redirectUrl];
    }

    /**
	 * This function is used for add mobile number field in edit account form.
	 * @name opt_rewards_add_mobile_number_to_edit_account_form
	 */

	public function opt_rewards_add_mobile_number_to_edit_account_form() {
	    $user = wp_get_current_user();
	    $customerId = $user->ID;
	    $opt_api_data = new Opt_Rewards_Data();
	    $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);
        if ($opt_api_data->getEnablePhone()) {
        	
	    ?>
		    <p class="form-row form-row-wide">
		        <label for="user_mobile_phone"><?php _e( 'Mobile Number', 'opt-rewards' ); ?> <span class="required">*</span></label>
		        <input type="text" class="input-text" name="user_phone_no" id="user_mobile_phone" maxlength='10' value="<?php echo esc_attr( $user->user_phone_no ); ?>" />
		    </p>

	    <?php
	    }
	}

	/**
	 * This function is used for validate mobile number field in edit account form.
	 * @name opt_rewards_mobile_number_field_validation
	 */

	public function opt_rewards_mobile_number_field_validation( $errors ){

        $user = wp_get_current_user();
        $opt_api_data = new Opt_Rewards_Data();
        $expr = '/^[0-9]+$/';
        $phoneL = $_POST["user_phone_no"];
        if ($opt_api_data->getEnablePhone()) {
		    if ( isset($_POST['user_phone_no']) && empty($_POST['user_phone_no']) ){
		        $errors->add( 'error', __( 'Mobile number is a required field.', 'opt-rewards' ),'');
		    }else if(strlen($_POST['user_phone_no']) !== 10 ){
		    	$errors->add( 'error', __( 'Enter a valid 10 digit mobile number.', 'opt-rewards' ),'');
	        }else if (!(preg_match($expr, $phoneL))) {
                $errors->add( 'error', __( 'Please enter valid 10 digits mobile number.', 'opt-rewards' ),'');
            }

		}    
	}

	/**
	 * This function is used for update mobile number field in edit account form.
	 * @name opt_rewards_my_account_saving_mobile_number
	 */

	public function opt_rewards_my_account_saving_mobile_number( $user_id ) {
		$opt_api_data = new Opt_Rewards_Data();
		if ($opt_api_data->getEnablePhone()) {
		    if( isset($_POST['user_phone_no']) && ! empty($_POST['user_phone_no']) ){
		        update_user_meta( $user_id, 'user_phone_no', sanitize_text_field($_POST['user_phone_no']) );
		    }
	    }
    } 

    /**
	 * This function is used for check reawrd page.
	 * @name opt_rewards_reward_page_check
	 */

    public function opt_rewards_reward_page_check(){
        if(is_page('join-reward-program') && is_user_logged_in()){
            $url = site_url('/my-account/reward-program/');
            wp_redirect( $url );
        }

    } 

     

	/**
	 * This function is used for update account details.
	 * @name opt_rewards_updatecontact
	 */

	public function opt_rewards_updatecontact( $errors ){
        global $wpdb;
        $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
        $fp = fopen($filepath, "a") or die("Unable to open file!");

        fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
        fwrite($fp, 'Start Update Customer Account Details Process'."\n");   

        $customerId = get_current_user_id();
        $current_user = get_user_by('id', $customerId);
         
        $customerData = "";
        $enrollmentRewardData = "";

        $error_count = wc_notice_count( 'error' );
        $total_error_acount = $error_count; 
        if ( $errors->get_error_messages() ) {
        	$i=1;
			foreach ( $errors->get_error_messages() as $error ) {
				$i++;
			}
			$total_error_acount = $error_count + $i;
		}

        if($total_error_acount === 0){
	        if (!empty($customerId)) {

	            $creationTime = $current_user->user_registered;
	            $phone = isset($_POST["user_phone_no"]) ? $_POST["user_phone_no"] : "";
                $firstName = isset($_POST["account_first_name"]) ? $_POST["account_first_name"] : "";
	            $lastName = isset($_POST["account_last_name"]) ? $_POST["account_last_name"] : "";
	            $email = isset($_POST["account_email"]) ? $_POST["account_email"] : "";
                

	            $city = "";
	            $zip = "";
	            $country = "";
	            $state = "";
	            $addressLine1 = "";
                $addressLine2 = "";
     
                $opt_operation = new Opt_Rewards_Api_Operations();

	            $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);
	            
	            if (!empty($enrollmentOption) && $enrollmentOption === "1") {
	           
	                $memberShipNumber = get_user_meta( $customerId, 'user_membership_no', true);
	                if (empty($memberShipNumber)) {
	                    fwrite($fp, 'Membership Number Is Not Found '."\n");
	                }
	                fwrite($fp, 'Send The API Request For Update Customer Account Details Process '."\n"); 
	                
	                try{
	                	$resultResponse = $opt_operation->updateContact(
		                    $memberShipNumber, $creationTime,
		                    $phone, $email, $addressLine1, $addressLine2,
		                    $firstName, $lastName, $city, $state,
		                    $zip, $country
	                    );

	                    if(isset($resultResponse['status']) && $resultResponse['status'] === 'Failure') {
	                        fwrite($fp, 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].' '."\n");
	                    $errors->add( 'error', __( 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].'', 'opt-rewards' ),'');
	                  
		                } else {
	                        fwrite($fp, 'RequestId:- '.$resultResponse['requestId'].' Response:- '.$resultResponse['response'].''."\n");   
		                }
	                }catch(Exception $e) {
					    $errors->add( 'error', __($e->getMessage()),'');
					}
	                
	            }else{
	            	fwrite($fp, 'Customer Is Not An Rewards Program Member '."\n");
	            }
	            fwrite($fp, 'End Update Customer Account Details Process '."\n");
	            fwrite($fp, '---------------------------------------------------------------- '."\n\n");
	            
	        }
       
        }
    }

    /**
	 * This function is used for update address details.
	 * @name opt_rewards_update_billingaddress
	 */

    public function opt_rewards_update_billingaddress($user_id,$load_address,$address)
	{
	    if ( $user_id <= 0 )
	    {
	        return;
	    }
	    $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
        $fp = fopen($filepath, "a") or die("Unable to open file!");

	    if(isset($_POST['billing_phone']))
	    {
	        $account_billing_phone   = ! empty( $_POST['billing_phone'] ) ? wc_clean( $_POST['billing_phone'] ) : '';
	        $expr = '/^[0-9]+$/';
	        if($account_billing_phone != '' ){
		        if(strlen($account_billing_phone) != 10 ){
		            wc_add_notice( __( 'Enter a valid 10 digit mobile number', 'opt-rewards' ), 'error' );
		        }else if (!(preg_match($expr, $account_billing_phone))) {
	                wc_add_notice( __( 'Enter a valid 10 digit mobile number', 'opt-rewards' ), 'error' );
	            }
	        }
	    }

	    fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
        fwrite($fp, 'Start Update Customer Billing Address Details Process'."\n"); 
       

        $current_user = wp_get_current_user();
        $customerId = $current_user->ID;

        $customerData = "";
        $enrollmentRewardData = "";
        
        $error_count = wc_notice_count( 'error' );
        $total_error_acount = $error_count;
        
        if($total_error_acount === 0){
	        if (!empty($customerId)) {

	            $creationTime = $current_user->user_registered;

	            $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);
	           
	            if (!empty($enrollmentOption) && $enrollmentOption === "1") {
	           
	                $memberShipNumber = get_user_meta( $customerId, 'user_membership_no', true);
	                if (empty($memberShipNumber)) {
	                    fwrite($fp, 'Membership Number Is Not Found '."\n");
	                }
	                $phone = get_user_meta( $customerId, 'user_phone_no', true);
	                $firstName = $current_user->user_firstname;
		            $lastName = $current_user->user_lastname;
		            $email = $current_user->user_email;

		            $country = '';
	                $state = '';
		            $countryCode = isset($_POST["billing_country"]) ? $_POST["billing_country"] : "";
	                $stateId = isset($_POST["billing_state"]) ? $_POST["billing_state"] : "";
	                if ($countryCode !== "") {
	                    $country = WC()->countries->countries[$countryCode];
	                }
	                if ($countryCode != "" && $stateId != '') {
	                    $state = WC()->countries->get_states( $countryCode )[$stateId];
	                }

		            $addressLine1 = isset($_POST["billing_address_1"]) ? $_POST["billing_address_1"] : "";
		            $addressLine2 = isset($_POST["billing_address_2"]) ? $_POST["billing_address_2"] : "";
	                $city = isset($_POST["billing_city"]) ? $_POST["billing_city"] : "";
	                $zip = isset($_POST["billing_postcode"]) ? $_POST["billing_postcode"] : "";

	                $opt_operation = new Opt_Rewards_Api_Operations();
	                
                    fwrite($fp, 'Send The API Request For Update Customer Billing Address Details Process'."\n");
	                $resultResponse = $opt_operation->updateContact(
	                    $memberShipNumber, $creationTime,
	                    $phone, $email, $addressLine1, $addressLine2,
	                    $firstName, $lastName, $city, $state,
	                    $zip, $country
	                );

	                if(isset($resultResponse['status']) && $resultResponse['status'] === 'Failure') {
                        fwrite($fp, 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].' '."\n");
	                    wc_add_notice( __( 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].'', 'opt-rewards' ), 'error' );                 
	                } else {
	                  fwrite($fp, 'RequestId:- '.$resultResponse['requestId'].' Response:- '.$resultResponse['response'].''."\n"); 
	                }
	                
	            }else{
	            	fwrite($fp, 'Customer Is Not An Rewards Program Member'."\n");
	            }
	            fwrite($fp, 'End Update Customer Billing Address Details Process'."\n");
	            fwrite($fp, '---------------------------------------------------------------- '."\n\n");
	        }
	    }    
	}

	/**
	 * This function is used for add maxlength attribute.
	 * @name opt_rewards_wc_form_field_args
	 */

	public function opt_rewards_wc_form_field_args( $args, $key, $value ){

	    if( is_wc_endpoint_url( 'edit-account' ) || is_checkout() ) return $args;

	    if($key == 'billing_phone'){
           $args['maxlength'] = 10;
	    }
	    
	    return $args;
	}
    
    /**
	 * This function is used for override checkout fields.
	 * @name opt_rewards_override_checkout_fields
	 */
	
	public function opt_rewards_override_checkout_fields( $fields )
	{        
	    $fields['billing']['billing_phone']['custom_attributes'] = array( "minlength" => "10", "maxlength" => "10" );      
	    return $fields;    
	}

    /**
	 * This function is used for add endpoint in myaccount tab.
	 * @name opt_rewards_add_reward_program_endpoint
	 */

	public function opt_rewards_add_reward_program_endpoint() {
         add_rewrite_endpoint( 'reward-program', EP_ROOT | EP_PAGES );
    }
    
    /**
	 * This function is used for add query var in myaccount tab.
	 * @name opt_rewards_reward_program_query_vars
	 */
  
	public function opt_rewards_reward_program_query_vars( $vars ) {
	    $vars[] = 'reward-program';
	    return $vars;
	}

    /**
	 * This function is used for add reward program tab in myaccount.
	 * @name opt_rewards_add_reward_program_link_my_account
	 */

	public function opt_rewards_add_reward_program_link_my_account( $items ) {


        $item_position = ( array_search( 'orders', array_keys( $items ) ) );

        $items_part1 = array_slice( $items, 0, $item_position + 4 );
        $items_part2 = array_slice( $items, $item_position );

        $items_part1['reward-program'] = 'Reward Program';

        $items = array_merge( $items_part1, $items_part2 );


        return $items;
    }

    /**
	 * This function is used for my reward page content.
	 * @name opt_rewards_reward_program_content
	 */

    public function opt_rewards_reward_program_content() {
        wc_get_template( 'myaccount/reward-program.php',
            array(),
            '',
            trailingslashit( OPT_REWARDS_WOO_TEMPLATES_DIR ) );
    }

    
    /**
	 * This function is used for enrollment from thankyou page.
	 * @name opt_rewards_enrollment_from_thankyou
	 */
 
	public function opt_rewards_enrollment_from_thankyou($order_id) {
        global $woocommerce;
		if( ! $order_id ) return;


        $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
        $fp = fopen($filepath, "a") or die("Unable to open file!"); 
        
        $loyalty_promotion_data = ''; 
        if (  WC()->session->__isset( 'loyalty_promotion_data' ) ){
            $promotion_details = WC()->session->get( 'loyalty_promotion_data' );
            $loyalty_promotion_data = serialize($promotion_details);
            update_post_meta( $order_id, 'loyalty_promotion_data', $loyalty_promotion_data );
            WC()->session->__unset( 'loyalty_promotion_data' );
        }

        if (  WC()->session->__isset( 'promotion_label' ) ){
           $promotion_labels = WC()->session->get( 'promotion_label' );

           foreach ($promotion_labels as $key => $coupon) {
           	    WC()->cart->remove_coupon( $coupon );
	            WC()->session->__unset( $coupon );
           }
        }

        if (  WC()->session->__isset( 'loyalty_balances' ) ){
       	    WC()->cart->remove_coupon( 'loyalty_balances' );
            WC()->session->__unset( 'loyalty_balances' );
        }

        $order = wc_get_order( $order_id );
             
        $billingFirstName = get_post_meta($order_id, '_billing_first_name', TRUE);
        $billingLastName = get_post_meta($order_id, '_billing_last_name', TRUE);
        $billingEmail = get_post_meta($order_id, '_billing_email', TRUE);
        $billingPhone = get_post_meta($order_id, '_billing_phone', TRUE);

        $addressLine1 = get_post_meta($order_id, '_billing_address_1', TRUE);
        $addressLine2 = get_post_meta($order_id, '_billing_address_2', TRUE);
        $city = get_post_meta($order_id, '_billing_city', TRUE);
        $zip = get_post_meta($order_id, '_billing_postcode', TRUE);
        $join_reward_check = get_post_meta($order_id, 'join_rewards_program', TRUE);

        $enrollmentOption = '';
        $country = '';
        $state = '';
        $stateId = get_post_meta($order_id, '_billing_state', TRUE);
        $countryCode = get_post_meta($order_id, '_billing_country', TRUE);
	           
        if ($countryCode !== "") {
            $country = WC()->countries->countries[$countryCode];
        }
        if ($countryCode != "" && $stateId != '') {
            $state = WC()->countries->get_states( $countryCode )[$stateId];
        }

        $opt_operation = new Opt_Rewards_Api_Operations();
        $opt_api_data = new Opt_Rewards_Data();
        
        if(is_user_logged_in()){
        	
            $current_user = wp_get_current_user();
            $customerId = $current_user->ID;

            $firstName = $current_user->user_firstname;
            $lastName = $current_user->user_lastname;
            $email = $current_user->user_email;
            $phone = $billingPhone;
            $creationTime = $current_user->user_registered;

            $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);

            if ($enrollmentOption == 1 && $opt_api_data->getEnablePhone() == 1 && $opt_api_data->getEnableAddress() == 1) { 

			    fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
		        fwrite($fp, 'Start Update Customer Billing Address Details Process - Thank You Page'."\n");

        	    $memberShipNumber = get_user_meta( $customerId, 'user_membership_no', true);
                if (empty($memberShipNumber)) {
                    fwrite($fp, 'Membership Number Is Not Found '."\n");
                }
                fwrite($fp, 'Send The API Request For Update Customer Billing Address Details Process'."\n");
                $resultResponse = $opt_operation->updateContact(
                    $memberShipNumber, $creationTime,
                    $phone, $email, $addressLine1, $addressLine2,
                    $firstName, $lastName, $city, $state,
                    $zip, $country
                );

                if(isset($resultResponse['status']) && $resultResponse['status'] === 'Failure') {
                    fwrite($fp, 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].' '."\n");
                    $errors->add( 'error', __( 'ErrorCode:- '.$resultResponse['errorCode'].' Message:- '.$resultResponse['message'].'Status:- '.$resultResponse['status'].'', 'opt-rewards' ),'');
                  
                } else {
                    fwrite($fp, 'RequestId:- '.$resultResponse['requestId'].' Response:- '.$resultResponse['response'].''."\n");   
                }

                fwrite($fp, 'End Update Customer Billing Address Details Process - Thank You page'."\n");
                fwrite($fp, '---------------------------------------------------------------- '."\n\n");
            }
	    }        

        if(isset($_POST['join_reward_program'])){
            
        	if(is_user_logged_in()){

	            if (empty($enrollmentOption) && $enrollmentOption != "1") { 
	            	fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
                    fwrite($fp, 'Start Create New Enrollment Process - Thank You Page'."\n");
                    fwrite($fp, 'Send The API Request For New Enrollment'."\n");
		            $result = $opt_operation->newEnrollment(
			            $phone, $email, $addressLine1, $addressLine2,
			            $firstName, $lastName, $city, $state,
			            $zip, $country
			        );
			        if(isset($result['status']) && $result['status'] == 'Failure') {
	                    fwrite($fp, 'ErrorCode:- '.$result['errorCode'].' Message:- '.$result['message'].'Status:- '.$result['status'].' '."\n");
	                    throw new CommandException(__("".$result['message']));
	                    return;
	                } else {
	                	fwrite($fp, 'New Contact Created Successfully With Membership# '.$result['membership']['cardNumber'].' '."\n");
	                	$membership_no = $result['membership']['cardNumber'];
	                	fwrite($fp, 'End Create New Enrollment Process - Thank You Page'."\n");
                        fwrite($fp, '----------------------------------------------------------------'."\n\n");
	                    if($membership_no != ''){
		                	update_user_meta( $customerId, 'user_reward_program_enable', 1 );
		                	update_user_meta( $customerId, 'user_membership_no', $membership_no );
		                	update_user_meta( $customerId, 'user_phone_no', sanitize_text_field( $phone ) );
                        
		                	update_user_meta( $customerId, 'billing_first_name', $billingFirstName );
						    update_user_meta( $customerId, 'billing_last_name', $billingLastName );
						    update_user_meta( $customerId, 'billing_email', $billingEmail );
						    update_user_meta( $customerId, 'billing_phone', $billingPhone );

		                	update_user_meta( $customerId, 'billing_address_1', $addressLine1 );
						    update_user_meta( $customerId, 'billing_address_2', $addressLine2 );
						    update_user_meta( $customerId, 'billing_city', $city );
						    update_user_meta( $customerId, 'billing_postcode', $zip );
						    update_user_meta( $customerId, 'billing_country', $countryCode );
		                    update_user_meta( $customerId, 'billing_state', $stateId );
		                    wp_redirect( site_url('/my-account/') );    
	                    }
	                }

	            }

            } else {
	            $userName = $billingFirstName.'_'.$billingLastName;
	            $fullName = $billingFirstName.' '.$billingLastName;

	            $email_exist = email_exists( $billingEmail );  
	            $username_exist = username_exists( $userName );
	            $userRole = 'customer';

                
            	if( $username_exist == false && $email_exist == false ){
            
                    fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
                    fwrite($fp, 'Start Create New Enrollment Process - Thank You Page'."\n");
                    fwrite($fp, 'Send The API Request For New Enrollment'."\n"); 
	            	$result = $opt_operation->newEnrollment(
			            $billingPhone, $billingEmail, $addressLine1, $addressLine2,
			            $billingFirstName, $billingLastName, $city, $state,
			            $zip, $country
			        );

			        if(isset($result['status']) && $result['status'] == 'Failure') {
	                    fwrite($fp, 'ErrorCode:- '.$result['errorCode'].' Message:- '.$result['message'].'Status:- '.$result['status'].' '."\n");
		                echo '<p class="woocommerce-notice woocommerce-notice--error">'.$result['message'].'</p>';
		                return;
		            } else {
		            	fwrite($fp, 'New Contact Created Successfully With Membership# '.$result['membership']['cardNumber'].' '."\n");
		                $membership_no = $result['membership']['cardNumber'];
		                fwrite($fp, 'End Create New Enrollment Process - Thank You Page'."\n");
                        fwrite($fp, '----------------------------------------------------------------'."\n\n");
		                if($membership_no != ''){

		                	$userPass = wp_generate_password();

						    $userdata = array(
					                        'user_login'    => strtolower($userName),
					                        'user_nicename' => strtolower($userName),
					                        'user_email'    => $billingEmail,
					                        'user_pass'    => $userPass,
					                        'first_name'   => $billingFirstName,
					                        'last_name'   => $billingLastName,
					                        'role'   => $userRole,
					                        'display_name'  => ucwords($fullName)
						                );
                            $customerId = wp_insert_user( $userdata ) ;

                            $emails = WC()->mailer()->get_emails();
                            $emails['WC_Email_Customer_New_Account']->trigger( $customerId, $userPass, true );
                            if($customerId != ""){
			                	update_user_meta( $customerId, 'user_reward_program_enable', 1 );
			                	update_user_meta( $customerId, 'user_membership_no', $membership_no );
			                	update_user_meta( $customerId, 'user_phone_no', sanitize_text_field( $billingPhone ) );

			                	update_user_meta( $customerId, 'billing_first_name', $billingFirstName );
							    update_user_meta( $customerId, 'billing_last_name', $billingLastName );
							    update_user_meta( $customerId, 'billing_email', $billingEmail );
							    update_user_meta( $customerId, 'billing_phone', $billingPhone );

			                	update_user_meta( $customerId, 'billing_address_1', $addressLine1 );
							    update_user_meta( $customerId, 'billing_address_2', $addressLine2 );
							    update_user_meta( $customerId, 'billing_city', $city );
							    update_user_meta( $customerId, 'billing_postcode', $zip );
							    update_user_meta( $customerId, 'billing_country', $countryCode );
	                            update_user_meta( $customerId, 'billing_state', $stateId );

	                            wp_set_current_user($customerId, $userName);
						        wp_set_auth_cookie($customerId);
						        wp_redirect( site_url('/my-account/') );
						    }
		                }	
	                }

                }else{ ?>
                	
                	<p class="woocommerce-notice woocommerce-notice--error"><?php echo _e( 'UserName/EmailId  already exists.', 'opt-rewards' ); ?></p>
                <?php 	
                }
            }    
        }    
        if (empty($enrollmentOption) && $enrollmentOption != "1" && $join_reward_check != "1") { 

        ?>
            <div class="enroll_reward_program">
	            <form method="post" name="thk_join_form" id="thk_join_form">
	            	<?php 
	            	$authenticationMethod = $opt_api_data->getAuthenticationMethod();
	            	if($authenticationMethod == 'otp_code'){ ?>
                        <button class="thk_btn" id="thk_btn" >Join Reward Program</button>
                        <button type="submit" name="join_reward_program" class="thk_join_btn" id="thk_join_btn" style="display:none;">Join Reward Program</button>
	            	<?php } else { ?>
                        <button type="submit" name="join_reward_program" class="thk_join_btn" id="thk_join_btn" >Join Reward Program</button>
	            	<?php } ?>
		    	    
		        </form>
	        </div>

	        <a class="thk-popup" href="#thk-popup"></a> 
			<div style="display: none;max-width: 700px;" id="thk-popup">
				<div class="guest-popup auth-otp">
			    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
			        <p class="message success" id="accountSMsg" style='display:none'></p>
			        <p class="message error" id="accountEMsg" style='display:none'></p>   
					<form id="otp_form" class="otp_form" action="" method="post">
							<label for="otp_code">Verification Code</label>
							<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
							<input id="thk_pop" type="submit" name="otp_pop" value="Login"/>
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

            		var otpCountTime = 30;
				    var firstInterval = null;

	        	    jQuery(".thk_btn").click(function() {
				        jQuery('.opt-loader').show();
				        var inquiryway = 'email';
				        var email = '<?php echo $billingEmail; ?>'; 
				        var phone = '<?php echo $billingPhone; ?>';  
				        var action = 'verification_api';
				        jQuery.ajax({
				            url:"<?php echo admin_url('admin-ajax.php'); ?>",
				            type:'POST',
				            data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
				            success: function(data){

				                jQuery('.opt-loader').hide();
				                if(data == 'Success'){
				                    jQuery("#resendLink").hide();
				                    jQuery(".thk-popup").fancybox({
									    afterClose: function () {
										    if(firstInterval != null){
			                                    clearInterval(firstInterval);
			                                    otpCountTime = 30;
				                                jQuery('#accountEMsg').hide();
				                                jQuery('#accountSMsg').hide();
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
					});

					jQuery("#thk_pop").click(function($){
			    		jQuery("#accountEMsg").text('');
	                    jQuery("#accountEMsg").hide();
	                    jQuery("#accountSMsg").text('');
	                    jQuery("#accountSMsg").hide();
		                jQuery('.opterrmsg').remove();
				        var email = jQuery("#email").val();
				        var password = jQuery("#password").val();
				        
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
				                		jQuery(".thk_btn").hide();
				                		jQuery(".thk_join_btn").show();
	                		            jQuery('button.thk_join_btn').trigger('click');
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
				        var email = '<?php echo $billingEmail; ?>'; 
				        var phone = '<?php echo $billingPhone; ?>'; 

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
		    </script>	
        <?php
        }    
		
	}

    /**
	 * This function is used for checkout field validation.
	 * @name opt_rewards_checkout_field_validation
	 */

	public function opt_rewards_checkout_field_validation() {
	    global $woocommerce;

	    $account_billing_phone   = ! empty( $_POST['billing_phone'] ) ? wc_clean( $_POST['billing_phone'] ) : '';
        $expr = '/^[0-9]+$/';
        if(strlen($account_billing_phone) != 10 ){
            wc_add_notice( __( 'Enter a 10 digit mobile number', 'opt-rewards' ), 'error' );
        }else if (!(preg_match($expr, $account_billing_phone))) {
        	wc_add_notice( __( 'Enter a valid 10 digit mobile number', 'opt-rewards' ), 'error' );
        }
	}

	/**
	 * This function is used for add field on checkout page.
	 * @name opt_rewards_join_rewards_checkout_field
	 */

	public function opt_rewards_join_rewards_checkout_field( $checkout ) {
        $enrollmentOption =  "";
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $customerId = $current_user->ID;
            $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);
		}        
             
        if (empty($enrollmentOption) && $enrollmentOption != "1") { 
		    echo '<div id="join-reward-field" class="join-reward-field">';
		 
		    woocommerce_form_field( 'join_rewards_program', array(
		        'type'          => 'checkbox',
		        'class'         => array('input-checkbox'),
		        'label'         => __('Join Reward Program?'),
		        ), $checkout->get_value( 'join_rewards_program' ));
		 
		    echo '</div>';
	   }
	}

    /**
	 * This function is used for save field in order meta on checkout page.
	 * @name opt_rewards_join_rewards_checkout_field_update_order_meta
	 */
	
	public function opt_rewards_join_rewards_checkout_field_update_order_meta( $order_id ) {
	    if (isset($_POST['join_rewards_program']) && $_POST['join_rewards_program'] == 1){
	    	update_post_meta( $order_id, 'join_rewards_program', esc_attr($_POST['join_rewards_program']));
	    } 
	}
    
    /**
	 * This function is used for get order details.
	 * @name opt_rewards_get_order_details
	 */

    public function opt_rewards_get_order_details( $order_id ) {
    	global $woocommerce;
		$order = wc_get_order($order_id);
        $dp = (isset($filter['dp'])) ? intval($filter['dp']) : 2;
        $order_status = $order->get_status();
        $increment_id = get_post_meta( $order_id, 'oc_new_increment_id', true );
        $opt_data = new Opt_Rewards_Data();
	    $orderdetails = array(
            'order_id' => $order->get_id(),
            'store_id' => $opt_data->getStoreNumber(),
            'increment_id' => (string)$increment_id,
            'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
            'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
            'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
            'status' => $order->get_status(),
            'currency' => $order->get_currency(),
            'total' => wc_format_decimal($order->get_total(), $dp),
            'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
            'total_line_items_quantity' => $order->get_item_count(),
            'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
            'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
            'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
            'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
            'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
            'shipping_methods' => $order->get_shipping_method(),
            'order_key' => $order->get_order_key(),
            'shipping_lines' => array(),
            'tax_lines' => array(),
            'fee_lines' => array(),
            'coupon_lines' => array(),
            'payment_details' => array(
                'method_id' => $order->get_payment_method(),
                'method_title' => $order->get_payment_method_title(),
                'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
            ),
        );

        foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
            $orderdetails['shipping_lines'][] = array(
                'id' => (string)$shipping_item_id,
                'method_id' => $shipping_item['method_id'],
                'method_title' => $shipping_item['name'],
                'total' => wc_format_decimal($shipping_item['cost'], $dp),
            );
        }

        foreach ($order->get_tax_totals() as $tax_code => $tax) {
            $orderdetails['tax_lines'][] = array(
                'id' => (string)$tax->id,
                'rate_id' => (string)$tax->rate_id,
                'code' => $tax_code,
                'title' => $tax->label,
                'total' => wc_format_decimal($tax->amount, $dp),
                'compound' => (bool) $tax->is_compound,
            );
        }

        foreach ($order->get_fees() as $fee_item_id => $fee_item) {
            $orderdetails['fee_lines'][] = array(
                'id' => (string)$fee_item_id,
                'title' => $fee_item['name'],
                'tax_class' => (!empty($fee_item['tax_class']) ) ? $fee_item['tax_class'] : null,
                'total' => wc_format_decimal($order->get_line_total($fee_item), $dp),
                'total_tax' => wc_format_decimal($order->get_line_tax($fee_item), $dp),
            );
        }

        foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {
            $orderdetails['coupon_lines'][] = array(
                'id' => (string)$coupon_item_id,
                'code' => $coupon_item['name'],
                'amount' => wc_format_decimal($coupon_item['discount_amount'], $dp),
            );
        }

        return $orderdetails;
    }

    /**
	 * This function is used for get refund order details.
	 * @name opt_rewards_get_refund_order_details
	 */

    public function opt_rewards_get_refund_order_details( $order_id, $refund_id ) {
    	global $woocommerce;

		$order = wc_get_order($order_id);
		$dp = (isset($filter['dp'])) ? intval($filter['dp']) : 2;
		$refund = new WC_Order_Refund( $refund_id );

		$increment_id = get_post_meta( $refund_id, 'oc_refund_increment_id', true );
        $opt_data = new Opt_Rewards_Data();
	    $orderdetails = array(
            'order_id' => $order->get_id(),
            'store_id' => $opt_data->getStoreNumber(),
            'increment_id' => (string)$increment_id,
            'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
            'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
            'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
            'status' => $order->get_status(),
            'currency' => $order->get_currency(),
            'total' => wc_format_decimal($order->get_total(), $dp),
            'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
            'total_line_items_quantity' => $order->get_item_count(),
            'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
            'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
            'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
            'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
            'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
            'shipping_methods' => $order->get_shipping_method(),
            'order_key' => $order->get_order_key(),
            'shipping_lines' => array(),
            'tax_lines' => array(),
            'fee_lines' => array(),
            'coupon_lines' => array(),
            'payment_details' => array(
                'method_id' => $order->get_payment_method(),
                'method_title' => $order->get_payment_method_title(),
                'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
            ),
            'refund_details' => array(
            	'refund_id'   => $refund_id,
                'refund_status'   => $refund->get_status(),
		        'refund_amount'   => $refund->get_amount(),
		        'refund_reason'   => $refund->get_reason(),
		        'refund_by'   => $refund->get_refunded_by(),
		        'refund_status'   => $refund->get_status(),
		    ),
        );

        foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
            $orderdetails['shipping_lines'][] = array(
                'id' => $shipping_item_id,
                'method_id' => $shipping_item['method_id'],
                'method_title' => $shipping_item['name'],
                'total' => wc_format_decimal($shipping_item['cost'], $dp),
            );
        }

        foreach ($order->get_tax_totals() as $tax_code => $tax) {
            $orderdetails['tax_lines'][] = array(
                'id' => $tax->id,
                'rate_id' => $tax->rate_id,
                'code' => $tax_code,
                'title' => $tax->label,
                'total' => wc_format_decimal($tax->amount, $dp),
                'compound' => (bool) $tax->is_compound,
            );
        }

        foreach ($order->get_fees() as $fee_item_id => $fee_item) {
            $orderdetails['fee_lines'][] = array(
                'id' => $fee_item_id,
                'title' => $fee_item['name'],
                'tax_class' => (!empty($fee_item['tax_class']) ) ? $fee_item['tax_class'] : null,
                'total' => wc_format_decimal($order->get_line_total($fee_item), $dp),
                'total_tax' => wc_format_decimal($order->get_line_tax($fee_item), $dp),
            );
        }

        foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {
            $orderdetails['coupon_lines'][] = array(
                'id' => $coupon_item_id,
                'code' => $coupon_item['name'],
                'amount' => wc_format_decimal($coupon_item['discount_amount'], $dp),
            );
        }

        return $orderdetails;
    }

    /**
	 * This function is used for get customer details from order.
	 * @name opt_rewards_get_customer_from_order
	 */

    public function opt_rewards_get_customer_from_order( $order_id ) {
    	global $woocommerce;
		$order = wc_get_order($order_id);
	    $customerdetails = array(
	        'customer_id' => (string)$order->get_user_id(), 
	        'email' => $order->get_billing_email(),
	        'first_name' => $order->get_billing_first_name(),
            'last_name' => $order->get_billing_last_name(),
	        'note' => $order->get_customer_note(),
	        'billing_address' => array(
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'company' => $order->get_billing_company(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'postcode' => $order->get_billing_postcode(),
                'country' => $order->get_billing_country(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone()
            ),
        );

        return $customerdetails;
    }

    /**
	 * This function is used for get product item details from order.
	 * @name opt_rewards_get_product_item_from_order
	 */

    public function opt_rewards_get_product_item_from_order( $order_id ) {

    	global $wpdb;
    	global $woocommerce;
		$order = wc_get_order($order_id);

		$dp = (isset($filter['dp'])) ? intval($filter['dp']) : 2;
	    $itemsData = array(); 
        foreach ($order->get_items() as $item_id => $item) {

            $product = $item->get_product();

            $product_id = null;
            $product_sku = null;
            if (is_object($product)) {
                $product_id = $product->get_id();
                $product_sku = $product->get_sku();
            }
            
            $product_id = (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id;
            $variation_id = (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0;

            $discount_amount = $order->get_item_coupon_amount( $item );

            $itemsData[] = array(
                'id' => (string)$item_id,
                'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
                'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
                'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
                'total_tax' => wc_format_decimal($item['line_tax'], $dp),
                'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
                'quantity' => wc_stock_amount($item['qty']),
                'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
                'name' => $item['name'],
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'sku' => $product_sku,
                'meta' => wc_display_item_meta($item, ['echo' => false]),
                'discount_amount' => round($discount_amount,2),
            );
        }

        $orderitems = $itemsData;
        
        return $orderitems;
    }

    /**
	 * This function is used for get product item details from refund order.
	 * @name opt_rewards_get_product_item_for_refund_order
	 */

    public function opt_rewards_get_product_item_for_refund_order( $order_id, $refund_id ) {
            
    	global $woocommerce;
		$order = wc_get_order($order_id);
		$dp = (isset($filter['dp'])) ? intval($filter['dp']) : 2;
	    $itemsData = array(); 
	    $refundData = array();
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
        	$qty = 0;

            $product_id = null;
            $product_sku = null;
            if (is_object($product)) {
                $product_id = $product->get_id();
                $product_sku = $product->get_sku();
            }
            $discount_amount = $order->get_item_coupon_amount( $item );
            $itemsData[] = array(
                'id' => $item_id,
                'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
                'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
                'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
                'total_tax' => wc_format_decimal($item['line_tax'], $dp),
                'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
                'quantity' => wc_stock_amount($item['qty']),
                'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
                'name' => $item['name'],
                'product_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id,
                'variation_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0,
                'sku' => $product_sku,
                'meta' => wc_display_item_meta($item, ['echo' => false]),
                'discount_amount' => round($discount_amount,2),
            );

            $item_type = 'line_item';

			foreach ( $order->get_refunds() as $refund ) {
				
				foreach ( $refund->get_items( $item_type ) as $refunded_item ) {
					$order_refund_id = $refunded_item->get_order_id();
					if ( absint( $refunded_item->get_meta( '_refunded_item_id' ) ) === $item_id && $order_refund_id == $refund_id) {
						$qty = $refunded_item->get_quantity();
						
                        $refundData[] = array(
                            'refund_item_id' => $item_id,
                            'refund_order_id' => $refund_id,
                            'qty' => $qty,
                            
                        );
					}
				}
			}
        }
        
        $orderitems = array("orderdata"=> $itemsData, "refunddata"=> $refundData);
        return $orderitems;
    }

	/**
	 * This function is used for generate digital receipt for new order.
	 * @name opt_rewards_digital_receipt_for_neworder
	 */
 
	public function opt_rewards_digital_receipt_for_neworder( $order_id ) {

        global $woocommerce; 

		if( ! $order_id ) return;

	    if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

	    	$opt_data = new Opt_Rewards_Data();
	    	$opt_operation = new Opt_Rewards_Api_Operations();
	    	$membershipNumber = '';

	    	if(is_user_logged_in()){
                $current_user = wp_get_current_user();
	            $customerId = $current_user->ID; 
	            $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);
	            if($enrollmentOption == "1"){
	                $membershipNumber = get_user_meta( $customerId, 'user_membership_no', true);
	                add_post_meta($order_id, 'membershipnumber',$membershipNumber, true );
	            }    

	    	}else{

	    		if (  WC()->session->__isset( 'inquiry_way' ) ){

		            $inquiry_way = WC()->session->get( 'inquiry_way' );

		            if($inquiry_way == "email"){
			    		$custEmail = WC()->session->get( 'email' );
			    		$phone = "";	
			    	} else {
			    		$phone = WC()->session->get( 'phone' );
			    		$custEmail = "";
			    	}

		        }
		        if (  WC()->session->__isset( 'membershipnumber' ) ){
                    $membershipNumber = WC()->session->get( 'membershipnumber' );
                    add_post_meta($order_id, 'membershipnumber',$membershipNumber, true );
		        }	
	    	}	
			$order = wc_get_order($order_id);
			$joinEnrollment = get_post_meta( $order_id, 'join_rewards_program', true );
			if($joinEnrollment == 1){
                $joinEnorll = 1;
			}else{
				$joinEnorll = 0;
			}
	    	

	        $increment_no = $opt_data->generateRandomNumber();
	        add_post_meta($order_id, 'oc_new_increment_id',$increment_no, true );

	    	$orderdetails = $this->opt_rewards_get_order_details($order_id);
            $orderitems = $this->opt_rewards_get_product_item_from_order($order_id);
	        $customerdetails = $this->opt_rewards_get_customer_from_order($order_id);

	        $order_items = $order->get_items('coupon');
	        $redeemdetails = array();
	        
	        foreach( $order_items as $item_id => $item ){  
		        $coupon_name = $item->get_name();
		        $discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true ); 
		        $order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );

		        if (strpos($coupon_name, 'loyalty_points') !== false ){
		          $redeemdetails = array('DiscountAmount' => $discount_amount);
		        } 
	        }
	        $promotionsdetails = array();
            
            $promotionsdata = get_post_meta($order_id, 'loyalty_promotion_data', TRUE);
            if($promotionsdata != ''){
            	$promotionsdetails = unserialize($promotionsdata);
            }
            if($membershipNumber != ''){
	            $opt_operation->digitalReceipt(
		            $orderdetails,
		            $orderitems,
		            $customerdetails,
		            $redeemdetails,
		            $promotionsdetails,
		            $membershipNumber,
		            $joinEnorll
		        );   
		    }    
            if (  WC()->session->__isset( 'membershipnumber' ) ){
                WC()->session->__unset( 'membershipnumber' );
            }
            if (  WC()->session->__isset( 'email' ) ){
            	WC()->session->__unset( 'email' );
            }
            if (  WC()->session->__isset( 'phone' ) ){
            	WC()->session->__unset( 'phone' );
            }
            
            $order->update_meta_data( '_thankyou_action_done', true );
            $order->save();
	    }	
    }	


	/**
	 * This function is used for generate digital receipt for cancel order.
	 * @name opt_rewards_digital_receipt_for_cancelorder
	 */
 
	public function opt_rewards_digital_receipt_for_cancelorder( $order_id ) {

		if( ! $order_id ) return;
		$opt_data = new Opt_Rewards_Data();
	    $opt_operation = new Opt_Rewards_Api_Operations();

        $membershipNumber = get_post_meta($order_id, 'membershipnumber', TRUE);

		$orderdetails = $this->opt_rewards_get_order_details($order_id);
        $orderitems = $this->opt_rewards_get_product_item_from_order($order_id);
        $customerdetails = $this->opt_rewards_get_customer_from_order($order_id);
        
        $order = wc_get_order($order_id);
        $order_items = $order->get_items('coupon');
        $redeemdetails = array();
        $discount_amount = 0;
        foreach( $order_items as $item_id => $item ){  
	        $coupon_name = $item->get_name();
	        
	        if (strpos($coupon_name, 'loyalty_points') !== false ){
	        	$discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true ); 
	            $order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
	            $redeemdetails = array('DiscountAmount' => $discount_amount);
	        }
        }
 
        $loyaltyRedeemReversal = $discount_amount;
        $promotionsdetails = array();
            
        $promotionsdata = get_post_meta($order_id, 'loyalty_promotion_data', TRUE);
        if($promotionsdata != ''){
        	$promotionsdetails = unserialize($promotionsdata);
        }
         
        $opt_operation->cancelReceipt(
            $orderdetails,
            $orderitems,
            $customerdetails,
            $redeemdetails,
            $loyaltyRedeemReversal,
            $promotionsdetails,
            $membershipNumber
        );

    }	


    /**
	 * This function is used for generate digital receipt for refund order.
	 * @name opt_rewards_digital_receipt_for_refundorder
	 */
 
	public function opt_rewards_digital_receipt_for_refundorder( $order_id, $refund_id ) {

		if( ! $order_id && !$refund_id) return;

        $opt_data = new Opt_Rewards_Data();
        $opt_operation = new Opt_Rewards_Api_Operations();

		$increment_no = $opt_data->generateRandomNumber();   
        add_post_meta($refund_id, 'oc_refund_increment_id', $increment_no, true );

		$orderdetails = $this->opt_rewards_get_refund_order_details($order_id,$refund_id);
        $orderitems = $this->opt_rewards_get_product_item_for_refund_order($order_id,$refund_id);
        $customerdetails = $this->opt_rewards_get_customer_from_order($order_id);

        $membershipNumber = get_post_meta($order_id, 'membershipnumber', TRUE);
        $order = wc_get_order($order_id);
        $orderQty = $order->get_item_count();
        $order_items = $order->get_items('coupon');
        $redeemdetails = array();
        
        foreach( $order_items as $item_id => $item ){  
	        $coupon_name = $item->get_name();
	        if (strpos($coupon_name, 'loyalty_points') !== false ){
	        	$discount_amount = wc_get_order_item_meta( $item_id, 'discount_amount', true ); 
	            $order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
	            $redeemdetails = array('DiscountAmount' => $discount_amount);
	        }
        }
        $singleItemRedeem = 0;
        if(isset($redeemdetails['DiscountAmount'])) {
        	$singleItemRedeem =  round($redeemdetails['DiscountAmount']/$orderQty,2);
        }
        
        
        $order_line_items = $order->get_items('line_item'); 
        $refund_item_amt = array();
        $loyaltyRedeemReversal = 0;
        foreach( $order_line_items as $item_id => $item ){  
            
            $itmSubtotal = $item->get_subtotal();
		    $itmQty = $item->get_quantity();
		    $single_itm_price = round($itmSubtotal/$itmQty,2);

	        $item_type = 'line_item';
	        $item_total_discount = $order->get_item_coupon_amount( $item );
            
	        foreach ( $order->get_refunds() as $refund ) {
				foreach ( $refund->get_items( $item_type ) as $refunded_item ) {
					$order_refund_id = $refunded_item->get_order_id();
                    $refunded_item->get_meta( '_refunded_item_id' );  
					if ( absint( $refunded_item->get_meta( '_refunded_item_id' ) ) === $item_id && $order_refund_id == $refund_id) {
                        
						$qty = $refunded_item->get_quantity();
						$product_id = $item->get_product_id();
						$refund_item_amt[] =  abs($single_itm_price*$qty);
	                    $refundData[] = array(
	                        'refund_item_id' => $item_id,
	                        'refund_order_id' => $refund_id,
	                        'qty' => $qty,
	                    );
	             
					}
				}
			}
        }
        $refund_reversal = get_post_meta($order_id,'loyalty_redeem_reversal',true);
        $redeem_discount_amount = (float)$discount_amount - (float)$refund_reversal;
        $refund_item_amount = array_sum($refund_item_amt);
        if($redeem_discount_amount > 0 ){
        	if($refund_item_amount < $redeem_discount_amount){
                 $loyaltyRedeemReversal = $refund_item_amount;
	        }else{
	        	$loyaltyRedeemReversal = $redeem_discount_amount;
	        }  
        }
        
        $promotionsdetails = array();    
        $promotionsdata = get_post_meta($order_id, 'loyalty_promotion_data', TRUE);
        if($promotionsdata != ''){
        	$promotionsdetails = unserialize($promotionsdata);
        }
    
        $opt_operation->refundReceipt(
            $orderdetails,
            $orderitems,
            $customerdetails,
            $redeemdetails,
            $loyaltyRedeemReversal,
            $promotionsdetails,
            $membershipNumber
        );
        $totalReversal =  (float)$loyaltyRedeemReversal + (float)$refund_reversal;
        update_post_meta( $order_id, 'loyalty_redeem_reversal', $totalReversal );
    }

    /**
	 * This function is used for loyalty inquiry API call.
	 * @name opt_rewards_get_loyalty_inquiry
	 */

    public function opt_rewards_get_loyalty_inquiry(){
    	global $woocommerce;
    	$inquiry = $_POST['inquiry'];
    	$is_validate = $_POST['is_validate'];

    	if(is_user_logged_in()){
    		$current_user = wp_get_current_user();
    		$customerId = $current_user->ID;
    		if($inquiry == 'email'){
    			$email = !empty($_POST['loyalty_account_email']) ? trim($_POST['loyalty_account_email']) : '';
                $phone  = get_user_meta( $customerId, 'user_phone_no', true);
    		}else{
    			$email = $current_user->user_email;
    			$phone = !empty($_POST['loyalty_account_phone']) ? trim($_POST['loyalty_account_phone']) : '';
    		}
    	}else{
    		if($inquiry == 'email'){
    			$email = !empty($_POST['loyalty_account_email']) ? trim($_POST['loyalty_account_email']) : '';
                $phone  = '';
    		}else{
    			$email = '';
    			$phone = !empty($_POST['loyalty_account_phone']) ? trim($_POST['loyalty_account_phone']) : '';
    		}
    	}
       
        WC()->session->set( 'inquiry_way',  $inquiry );
        WC()->session->set( 'email',  $email );
        WC()->session->set( 'phone',  $phone );
        
        if($inquiry == 'email'){
			$email = !empty($_POST['loyalty_account_email']) ? trim($_POST['loyalty_account_email']) : '';
            $phone  = '';
		}else{
			$email = '';
			$phone = !empty($_POST['loyalty_account_phone']) ? trim($_POST['loyalty_account_phone']) : '';
		}

        $opt_operation = new Opt_Rewards_Api_Operations();
    	$loyaltyInquiryResponse = [];
        $loyaltyInquiryResponse = $opt_operation->promotionsInquiry($phone, $email);
        
        $opt_data = new Opt_Rewards_Data(); 
        $authenticationMethod = $opt_data->getAuthenticationMethod(); 
        $lockTime = $opt_data->getPhoneLockoutTime();
        $customerId = 0;  
        $user_email = '';
        $user_phone_no = '';
        if(is_user_logged_in() ) {  
            $current_user = wp_get_current_user();
            $user_email = $current_user->user_email;
            $customerId = $current_user->ID;
            $user_phone_no  = get_user_meta( $customerId, 'user_phone_no', true); 
		} 

		$failedRequest = 1;
        $custId = get_current_user_id();    
        if($custId > 0){
	        $remianTiming = $opt_data->updateLockStatus($custId); 

	        if ($remianTiming > 0) {
	            $error = "<p class='opterrmsg'>This facility is locked because of too many incorrect attempts. Please try after ".$lockTime." minutes.</p>";
	            echo $error;
	            exit;
	        }  
	    }        
       
        try{
	        if (!empty($loyaltyInquiryResponse)) {

	        	$loyaltyPoints = '';
		        $amountMoney = '';
		        $disabled = '';
		        $extraLoyaltyCode = '';
		        $extraLoyaltyAmount = '';

		        if (isset($loyaltyInquiryResponse['loyaltyinfo']['membershipnumber'])) {
		        	$membershipnumber  = $loyaltyInquiryResponse['loyaltyinfo']['membershipnumber'];
		        	WC()->session->set( 'membershipnumber',  $membershipnumber );
		        }	
		        if (isset($loyaltyInquiryResponse['loyaltyinfo']['balances'])) {
		            foreach ($loyaltyInquiryResponse['loyaltyinfo']['balances'] as $loyaltyInfo) {
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
		            $opt_data->releaseLock($custId);
		        } else {
		        	if($custId > 0){
                        $lockStatus = $opt_data->saveLockStatus($custId, $failedRequest);
	                    if ($lockStatus == 'locked') {
	                        throw new Exception("<p class='opterrmsg'>This facility is locked because of too many incorrect attempts. Please try after ".$lockTime." minutes.</p>");
	                    }else{
	                    	throw new Exception("<p class='opterrmsg'>You are not an enrolled customer, Please register with us.</p>");
	                    }
		        	}else{
		        		throw new Exception("<p class='opterrmsg'>You are not an enrolled customer, Please register with us.</p>");
		        	}
		        }

	            if($extraLoyaltyAmount > 0)
	            {
	               	$html_data =  '<p>You have rewards balance of '.$loyaltyPoints.' points, '. $extraLoyaltyAmount .' ' . $extraLoyaltyCode .'  and $'.$amountMoney.'</p>';	
	            } else {
	         	   	$html_data =  '<p>You have rewards balance of '.$loyaltyPoints.' points  and $'.$amountMoney.'</p>';
	        	}
	            $coupon_code = 'loyalty_points';  
	            if($amountMoney == 0)
	            {
	            	$disabled = 'disabled="disabled"';
	            }
	            if($amountMoney != '') { 
		    	$html_data .=  '<form class="apply-loyalty" id="apply-loyalty" action="" method="post">';
					if (  WC()->session->__isset( 'loyalty_balances' ) ){
						$html_data .=  '<div class="loyalty-input" id="loyalty-input" style="display:none;">';
					} else {
						$html_data .=  '<div class="loyalty-input" id="loyalty-input" style="display:block;">';
					}
						$html_data .= '<p class="form-row form-row-wide">
							<label for="loyalty_balances">Redeemable Balance<span class="required">*</span></label>
							<input type="text" class="input-text" name="loyalty_balances" id="loyalty_balances"  value="" '.$disabled.'>
							<input type="hidden" class="input-text" name="max_loyalty_balances" id="max_loyalty_balances"  value="'.$amountMoney.'">
						</p>
						<p>'; 
                            if($is_validate == 0 ){
								if ($authenticationMethod == 'otp_code'){
									$html_data .=  '<button type="button" class="button apply_otp_loyalty" id="apply_otp_loyalty" name="apply_otp_loyalty" value="Save changes">Apply</button>';
								    $html_data .=  '<button type="button" class="button apply_loyalty" id="apply_loyalty" name="apply_loyalty" value="Save changes" style="display:none;">Apply</button>';
								}  else {
									$html_data .=  '<button type="button" class="button apply_loyalty" id="apply_loyalty" name="apply_loyalty" value="Save changes">Apply</button>';
								}
							}else{
								$html_data .=  '<button type="button" class="button apply_loyalty" id="apply_loyalty" name="apply_loyalty" value="Save changes">Apply</button>';
							}	

							
						$html_data .= '</p>
						</div>';
						if (  WC()->session->__isset( 'loyalty_balances' ) ){
							$html_data .=  '<p class="rm_btn" id="rm_btn" style="display:block;">';
						}  else {
							$html_data .=  '<p class="rm_btn" id="rm_btn" style="display:none;">';
						}
						$html_data .=  '<button type="button" class="button remove_loyalty" id="remove_loyalty" name="remove_loyalty" value="Remove Discount">Remove Discount</button>';
						$html_data .= '</p>
						</form>';
		        }
		        $couponData = $this->opt_rewards_get_couponInfo($loyaltyInquiryResponse);
		        if($couponData != ''){
			        $html_data .= '<h4>Eligible Discounts:</h4><div class="serach-promo"><input type="text" name="sercah_promo_code" id="sercah_promo_code" placeholder="Search your discount code.." ></div><div class="promo_stat" id="promotion-status"></div><form class="apply-promotion" id="apply-promotion" action="" method="post"><ul class="promotion_data">'.$couponData.'</ul><div class="promo_btn">';
	                    if($is_validate == 0){

		                    if ($authenticationMethod == 'otp_code'){
								$html_data .=  ' <button type="button" class="button apply_otp_promotion" id="apply_otp_promotion" name="apply_otp_promotion" value="Save changes">Apply</button>';
							    $html_data .=  '<button type="button" class="button apply_promotion" id="apply_promotion" name="apply_promotion" value="Save changes" style="display:none;">Apply</button>';
							}  else {
								$html_data .=  '<button type="button" class="button apply_promotion" id="apply_promotion" name="apply_promotion" value="Save changes">Apply</button>';
							}
					    }else{
					    	$html_data .=  '<button type="button" class="button apply_promotion" id="apply_promotion" name="apply_promotion" value="Save changes">Apply</button>';
					    }
					    $html_data .=  '</form>';
		        }  
	        } else {
                if($custId > 0){
                    $lockStatus = $opt_data->saveLockStatus($custId, $failedRequest);
                    if ($lockStatus == 'locked') {
                        throw new Exception("This facility is locked because of too many incorrect attempts. Please try after ".$lockTime." minutes.");
                    }else{
                    	throw new Exception("There is no reward balance. Please check the phone number entered.");
                    }
	        	}else{
	        		throw new Exception("There is no reward balance. Please check the phone number entered.");
	        	}
	        } 
        } catch (Exception $e ) {
        	echo $e->getMessage();
        }

        $signin_inquiry_way = $signin_email = $signin_phone = '';
        if (  WC()->session->__isset( 'inquiry_way' ) ){
           $signin_inquiry_way =  WC()->session->get('inquiry_way');
        }
        if (  WC()->session->__isset( 'email' ) ){
           $signin_email =  WC()->session->get('email');
        }
        if (  WC()->session->__isset( 'phone' ) ){
           $signin_phone =  WC()->session->get('phone');
        }
        
        echo $html_data;	?>

        <a class="login-pop" href="#login-pop"></a> 
	    <div style="display: none;max-width: 700px;" id="login-pop">
	    	<div class="guest-popup">
		    	<h4>Please sign in with same account for further process.</h4>
               
				<form id="login_form" class="form_login" action="" method="post">
					<label for="email">Email</label>
					<input name="email" id="email" class="required" type="text" required=""/>

                    <label for="password">Password</label>
					<input name="password" id="password" class="required" type="password" required=""/>

					<input type="hidden" name="login_nonce" value="<?php echo wp_create_nonce('login_nonce'); ?>;"/>
					<input id="opt_login" type="submit" name="opt_login" value="Login"/>
				</form>

		    </div>		
	    </div>

	    <a class="otp-popup" href="#otp-popup"></a> 
	    <div style="display: none;max-width: 700px;" id="otp-popup">
	    	<div class="guest-popup auth-otp">
		    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
                <p class="message success" id="modelSMsg" style='display:none'></p>
                <p class="message error" id="modelEMsg" style='display:none'></p>   
				<form id="otp_form" class="otp_form" action="" method="post">

						<label for="otp_code">Verification Code</label>
						<input name="otp_code" id="otp_code" class="required" type="text" required="required"/>
						<input id="otp_pop" type="submit" name="otp_pop" value="Login"/>
						<input id="resend_time" type="hidden" name="resend_time" value="1"/>
				</form>
                <div class="repop">
					<div id="dvresendotp" style="display: none">
		                <?php /* @escapeNotVerified */ echo __('You can request another verification code in ') ?> <span id="otpCount"></span><?php echo __(' secs') ?>
		            </div>
		            <a id="resendLink" class="resendotp" href="javascript:void(0)" style="display: none"><?php /* @escapeNotVerified */ echo __('RESEND CODE') ?></a>
		        </div>    
		    </div>		
	    </div>
        
        <a class="otp-promotion-popup" href="#otp-promotion-popup"></a> 
	    <div style="display: none;max-width: 700px;" id="otp-promotion-popup">
	    	<div class="guest-popup auth-otp">
		    	<h4>Please Enter the Verification Code Received on Email and/or SMS.</h4>
                <p class="message success" id="promoSMsg" style='display:none'></p>
                <p class="message error" id="promoEMsg" style='display:none'></p>
				<form id="otp_promotion_form" class="otp_promotion_form" action="" method="post">

						<label for="otp_promotion_code">Verification Code</label>
						<input name="otp_promotion_code" id="otp_promotion_code" class="required" type="text" required="required"/>
						<input id="otp_promotion_pop" type="submit" name="otp_promotion_pop" value="Login"/>
						<input id="promo_resend_time" type="hidden" name="promo_resend_time" value="1"/>
				</form>
                <div class="repop">
					<div id="promoresendotp" style="display: none">
	                <?php /* @escapeNotVerified */ echo __('You can request another verification code in ') ?> <span id="promoOtpCount"></span><?php echo __(' secs') ?>
		            </div>
		            <a id="promoresendLink" href="javascript:void(0)" style="display: none"><?php /* @escapeNotVerified */ echo __('RESEND CODE') ?></a>
		        </div>    
		    </div>		
	    </div>


        <script type="text/javascript">

        	jQuery('input[name="sercah_promo_code"]').keyup(function() {
                var keyword = jQuery(this).val().toLowerCase();
               
                if (keyword.length > 1) {
                    jQuery('ul.promotion_data li').hide();
                    jQuery('ul.promotion_data li').each(function() {
                        var label = jQuery(this).find('label').text().toLowerCase();
                        if (label.indexOf(keyword) >= 0) {
                            jQuery(this).closest('li').removeAttr('style');
                        }
                        var chk = jQuery(this).find('input[type="checkbox"]');
                        if (chk.is(":checked")) {
                            jQuery(this).closest('li').removeAttr('style');
                        }
                    });
                } else {
                    jQuery('ul.promotion_data li').removeAttr('style');
                }
            });

			function apply_opt_loyalty(){
		    	var loyalty_balances = jQuery("#loyalty_balances").val();
		        var max_loyalty_balances = jQuery("#max_loyalty_balances").val();
		        var cart_balance = jQuery("#loyalty_cart_subtotal").val();
		        var cart_discount_balance = jQuery("#loyalty_discount_total").val();
		        jQuery('.opterrmsg').remove();
		        jQuery('form.apply-loyalty input').removeClass('opterror');

		        var flag = 1;
		        if(loyalty_balances == ''){
		        	jQuery('.opterrmsg').html('');
		            jQuery('#loyalty_balances').after('<span class="opterrmsg">Please enter your Redeemable Balance.</span>');
		            jQuery('#loyalty_balances').addClass('opterror');
		            flag = 0;
		        }
		        if(parseFloat(loyalty_balances) > parseFloat(max_loyalty_balances)){
		        	jQuery('.opterrmsg').html('');
		        	jQuery('#loyalty_balances').after('<span class="opterrmsg">Redeemable balance not more than loyalty balance.</span>');
		            jQuery('#loyalty_balances').addClass('opterror');
		            flag = 0;
		        }
		        if(parseFloat(loyalty_balances) > parseFloat(cart_balance)){
		        	jQuery('.opterrmsg').html('');
		        	jQuery('#loyalty_balances').after('<span class="opterrmsg">You can not use loyalty balance more than cart balance.</span>');
		        	jQuery('#loyalty_balances').addClass('opterror');
		            flag = 0;
		        }
		        if(parseFloat(loyalty_balances) > parseFloat(cart_discount_balance)) 
		        {
		        	jQuery('.opterrmsg').html('');
		        	jQuery('#loyalty_balances').after("<span class='opterrmsg'>Redemption/Discount can't be applied. It exceeds Order Total.</span>");
		        	jQuery('#loyalty_balances').addClass('opterror');
		            flag = 0;
		        }
		        
		        if(flag == 0){
		            return false;
		        }else{ 
		            var action_data = "loyalty_apply";
		            jQuery.ajax({
		                url:"<?php echo admin_url('admin-ajax.php'); ?>",
		                type:'POST',
		                data: jQuery('#apply-loyalty').serialize() + "&action=" + action_data,
		                success: function(data){
		                	jQuery('#loyalty-input').hide();
		                    jQuery(document.body).trigger( 'added_to_cart' );
		                    jQuery(document.body).trigger( 'update_checkout' );
                            
		                    jQuery('#rm_btn').show();
		                }
		            });  
		        } 
		    }

		    function apply_opt_promotion(){
                var cart_balance = jQuery("#loyalty_cart_subtotal").val();
		        var cart_discount_balance = jQuery("#loyalty_discount_total").val();
		        var flag = 1;
		        if(cart_discount_balance == 0)
		        {
		        	jQuery('.promo_stat').html('');
		            jQuery('#promotion-status').html("Eligible/Discount can't be applied. It exceeds Order Total.");
		            jQuery('#promotion-status').addClass('opterrmsg');
		            flag = 0;
		        }
		        if(jQuery('#apply-promotion').find('input[type=checkbox]:checked').length == 0)
			    {
			    	jQuery('.promo_stat').html('');
		            jQuery('#promotion-status').html('Please select a discount code.');
		            jQuery('#promotion-status').addClass('opterrmsg');
		            flag = 0;
			    }
			    
		        if(flag == 0){
		            return false;
		        }else{
		            var action_data = "promotion_apply";
		            jQuery.ajax({
		                url:"<?php echo admin_url('admin-ajax.php'); ?>",
		                type:'POST',
		                data: jQuery('#apply-promotion').serialize() + "&action=" + action_data,
		                success: function(data){
		                    jQuery(document.body).trigger( 'added_to_cart' );
		                    jQuery(document.body).trigger( 'update_checkout' );
		                    jQuery('.promo_stat').html('');
		                }
		            });  
		        }
		    }

		    jQuery("#remove_loyalty").on('click',function(){
				jQuery('#rm_btn').hide();
				jQuery('.coupon-loyalty_points a').trigger('click');
				jQuery('#loyalty-input').show();
			});	

			jQuery( document ).ready(function() {

				/*-- Scripts for apply Loyalty Redeem --*/
				
	        	jQuery("#apply_loyalty").click(function(){
	                
	                var authMethod = '<?php echo $authenticationMethod; ?>';
	                var customerId  = '<?php echo $customerId; ?>';
	                var signin_inquiry_way  = '<?php echo $signin_inquiry_way; ?>';
	                var signin_email  = '<?php echo $signin_email; ?>';
	                var signin_phone  = '<?php echo $signin_phone; ?>';
	                var user_email  = '<?php echo $user_email; ?>';
	                var user_phone_no  = '<?php echo $user_phone_no; ?>';

	                if(authMethod != 'login' ){
	                    apply_opt_loyalty();
				    }else {
				    	if(authMethod == 'login'){
				    		if(customerId > 0){
	                            if(signin_inquiry_way == 'phone'){
	                                if(user_phone_no === signin_phone){
	                                    apply_opt_loyalty();
	                                }else{
							            jQuery('#loyalty_balances').after('<span class="opterrmsg">Please login with same member details.</span>');
							            jQuery('#loyalty_balances').addClass('opterror');
	                                } 	
	                            }else{
	                                if(user_email === signin_email){
	                                    apply_opt_loyalty();
	                                }else{
	                                    jQuery('#loyalty_balances').after('<span class="opterrmsg">Please login with same member details.</span>');
							            jQuery('#loyalty_balances').addClass('opterror'); 
	                                }
	                            }
				    		}else{
					            jQuery("#login-pop").fancybox().trigger('click');
					        }
					    }    
				    }

			    });

	            /*-- Scripts for apply Loyalty Promotion --*/

			    jQuery("#apply_promotion").click(function(){
	                
	                var authMethod = '<?php echo $authenticationMethod; ?>';
	                var customerId  = '<?php echo $customerId; ?>';
	                var signin_inquiry_way  = '<?php echo $signin_inquiry_way; ?>';
	                var signin_email  = '<?php echo $signin_email; ?>';
	                var signin_phone  = '<?php echo $signin_phone; ?>';
	                var user_email  = '<?php echo $user_email; ?>';
	                var user_phone_no  = '<?php echo $user_phone_no; ?>';
	                
	                if(authMethod != 'login'){
	                    apply_opt_promotion();
				    }else {
				    	if(authMethod == 'login'){
				    		if(customerId > 0){
	                            if(signin_inquiry_way == 'phone'){
	                                if(user_phone_no === signin_phone){
	                                    apply_opt_promotion();
	                                }else{
							            jQuery('#promotion-status').html('Please login with same member details.');
			                            jQuery('#promotion-status').addClass('opterrmsg');
	                                } 	
	                            }else{
	                                if(user_email === signin_email){
	                                    apply_opt_promotion();
	                                }else{
	                                    jQuery('#promotion-status').html('Please login with same member details.');
			                            jQuery('#promotion-status').addClass('opterrmsg');
	                                }
	                            }
				    		}else{
					            jQuery("#login-pop").fancybox().trigger('click');
					        }
					    }    
				    }    
			    });

			    /*-- OTP authentication methods open OTP popup scripts for Loyalty Redeem --*/

			    var otpCountTime = 30;
	            var firstInterval = null;
	            
	            jQuery("#apply_otp_loyalty").click(function(){
	        	    var loyalty_balances = jQuery("#loyalty_balances").val();
			        var max_loyalty_balances = jQuery("#max_loyalty_balances").val();
			        var cart_balance = jQuery("#loyalty_cart_subtotal").val();
			        var cart_discount_balance = jQuery("#loyalty_discount_total").val();
			        jQuery('.opterrmsg').remove();
			        jQuery('form.apply-loyalty input').removeClass('opterror');
			        var flag = 1;
			        if(loyalty_balances == ''){
			        	jQuery('.opterrmsg').html('');
			            jQuery('#loyalty_balances').after('<span class="opterrmsg">Please enter your Redeemable Balance.</span>');
			            jQuery('#loyalty_balances').addClass('opterror');
			            flag = 0;
			        }
			        if(parseFloat(loyalty_balances) > parseFloat(max_loyalty_balances)){
			        	jQuery('.opterrmsg').html('');
			        	jQuery('#loyalty_balances').after('<span class="opterrmsg">Redeemable balance not more than loyalty balance.</span>');
			            jQuery('#loyalty_balances').addClass('opterror');
			            flag = 0;
			        }
			        if(parseFloat(loyalty_balances) > parseFloat(cart_balance)){
			        	jQuery('.opterrmsg').html('');
			        	jQuery('#loyalty_balances').after('<span class="opterrmsg">You can not use loyalty balance more than cart balance.</span>');
			        	jQuery('#loyalty_balances').addClass('opterror');
			            flag = 0;
			        }
			        if(parseFloat(loyalty_balances) > parseFloat(cart_discount_balance)) 
			        {
			        	jQuery('.opterrmsg').html('');
			        	jQuery('#loyalty_balances').after("<span class='opterrmsg'>Redemption/Discount can't be applied. It exceeds Order Total.</span>");
			        	jQuery('#loyalty_balances').addClass('opterror');
			            flag = 0;
			        }
			        
			        if(flag == 0){
			            return false;
			        }else{ 
			        	jQuery('.opt-loader').show();
	                    var inquiryway = '<?php echo WC()->session->get( 'inquiry_way' ); ?>';
	                    var email = '<?php echo WC()->session->get( 'email' ); ?>';
	                    var phone = '<?php echo WC()->session->get( 'phone' ); ?>';
	                    var action = 'verification_api';
			        	jQuery.ajax({
			                url:"<?php echo admin_url('admin-ajax.php'); ?>",
			                type:'POST',
			                data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
			                success: function(data){
			                	jQuery('.opt-loader').hide();
			                	if(data == 'Success'){
				                	jQuery("#resendLink").hide();
				                	jQuery(".otp-popup").fancybox({
									    afterClose: function () {
										    if(firstInterval != null){
			                                    clearInterval(firstInterval);
			                                    otpCountTime = 30;
				                                jQuery('#modelSMsg').hide();
				                                jQuery('#modelEMsg').hide();
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
	                    
	                }    
	            }); 	
			    
			    /*-- OTP authentication methods verification popup scripts for Loyalty Redeem --*/
		            
			    jQuery("#otp_pop").click(function($){
		    		jQuery("#modelEMsg").text('');
	                jQuery("#modelEMsg").hide();
	                jQuery("#modelSMsg").text('');
	                jQuery("#modelSMsg").hide();
	                jQuery('.opterrmsg').remove();
			        var email = jQuery("#email").val();
			        var password = jQuery("#password").val();
			        
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
	            		            jQuery('button#apply_loyalty').trigger('click');
	                                jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
			                	}else{
			                		jQuery("#modelEMsg").text(data);
	                                jQuery("#modelEMsg").show();
			                	}
			                }
			            });  
			        } 
			        return false;
			    });  
	            
	            /*-- OTP authentication methods verification popup scripts for Loyalty Redeem Resend OTP--*/

			    jQuery("#resendLink").click(function(){
	                var action_data = "resend_otp_code";
		            jQuery.ajax({
		                url:"<?php echo admin_url('admin-ajax.php'); ?>",
		                type:'POST',
		                data: jQuery('#reward-program').serialize() + "&action=" + action_data,
		                success: function(data){
		                    if(data == 'Success'){										
			                	jQuery("#modelSMsg").text('Verification Code has been sent');
	                            jQuery("#modelSMsg").show();
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
	                                    jQuery('#modelSMsg').text('');
	                                    jQuery("#modelSMsg").hide();
	                                }
	                            }, 1000);  
	                        }else{
	                        	jQuery("#modelEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
	                            jQuery("#modelEMsg").show();
	                        }    
	                           
			            }
		            });   
	    	    });

	            /*-- OTP authentication methods open OTP popup scripts for Loyalty Promotion --*/

	            var promoOtpCountTime = 30;
                var secondInterval = null;

			    jQuery("#apply_otp_promotion").click(function(){

			        var cart_balance = jQuery("#loyalty_cart_subtotal").val();
			        var cart_discount_balance = jQuery("#loyalty_discount_total").val();
			        var flag = 1;
			        if(cart_discount_balance == 0)
			        {
			        	jQuery('.promo_stat').html('');
			            jQuery('#promotion-status').html("Eligible/Discount can't be applied. It exceeds Order Total.");
			            jQuery('#promotion-status').addClass('opterrmsg');
			            flag = 0;
			        }
			        if(jQuery('#apply-promotion').find('input[type=checkbox]:checked').length == 0)
				    {
				    	jQuery('.promo_stat').html('');
			            jQuery('#promotion-status').html('Please select a discount code.');
			            jQuery('#promotion-status').addClass('opterrmsg');
			            flag = 0;
				    }
				    
			        if(flag == 0){
			            return false;
			        }else{
			            jQuery('.opt-loader').show();
			            var inquiryway = '<?php echo WC()->session->get( 'inquiry_way' ); ?>';
	                    var email = '<?php echo WC()->session->get( 'email' ); ?>';
	                    var phone = '<?php echo WC()->session->get( 'phone' ); ?>';
	                    var action = 'verification_api';
			        	jQuery.ajax({
			                url:"<?php echo admin_url('admin-ajax.php'); ?>",
			                type:'POST',
			                data: { 'inquiryway' : inquiryway, 'email' : email, 'phone' : phone, 'action' : action },
			                success: function(data){
			                	jQuery('.opt-loader').hide();
	                            if(data == 'Success'){
				                	jQuery("#promoresendLink").hide();
				                	jQuery(".otp-promotion-popup").fancybox({
									    afterClose: function () {
			                                if(secondInterval != null){
			                                    clearInterval(secondInterval);
			                                    promoOtpCountTime = 30;
			                                    jQuery('#promoSMsg').hide();
			                                    jQuery('#promoEMsg').hide();
			                                }
										}
									}).trigger('click');
				                	var seconds = promoOtpCountTime;
		                            jQuery("#promoresendotp").show();
		                            jQuery("#promoOtpCount").html(seconds);
		                            secondInterval = setInterval(function () {
		                                seconds--;
		                                jQuery("#promoOtpCount").html(seconds);
		                                if (seconds == 0) {
		                                    clearInterval(secondInterval);
		                                    promoOtpCountTime = promoOtpCountTime + 30;
		                                    jQuery("#promoresendotp").hide();
		                                    jQuery("#promoresendLink").show();
		                                }
		                            }, 1000);
		                        }    
				                	
			                }
			            });  
			        }
				     
			    });

			    /*-- OTP authentication methods verification popup scripts for Loyalty Promotions--*/

	    	    jQuery("#otp_promotion_pop").click(function($){
		    		jQuery("#promoEMsg").text('');
		            jQuery("#promoEMsg").hide();
		            jQuery("#promoSMsg").text('');
		            jQuery("#promoSMsg").hide();
		            jQuery('.opterrmsg').remove();
			        var email = jQuery("#email").val();
			        var password = jQuery("#password").val();
			        
			        jQuery('.opterrmsg').remove();
			        jQuery('form#otp_promotion_form input').removeClass('opterror');
			        var bool = 1;

			        if (jQuery.trim(jQuery('#otp_promotion_code').val()) == '') {
			            jQuery('#otp_promotion_code').after('<span class="opterrmsg">Please enter Verification Code.</span>');
			            jQuery('#otp_promotion_code').addClass('opterror');
			            bool = 0;
			        }
			        if(bool == 0){
				        return false;
			        }else{
			        	
			            var action_data = "otp_promotion_code";
			            
			            jQuery.ajax({
			                url:"<?php echo admin_url('admin-ajax.php'); ?>",
			                type:'POST',
			                data: jQuery('#otp_promotion_form').serialize() + "&action=" + action_data,
			                
			                success: function(data){

		    		            if(data == 'Success'){
		        		            jQuery('button#apply_promotion').trigger('click');
		    		                jQuery('button.fancybox-button.fancybox-close-small').trigger('click');
			                	}else{
			                		jQuery("#promoEMsg").text(data);
		                            jQuery("#promoEMsg").show();
			                	}
		                        
			                }
			            });  
			        } 
			        return false;
			    });  

			    /*-- OTP authentication methods verification popup scripts for Loyalty Promotions Resend OTP--*/

		    	jQuery("#promoresendLink").click(function(){
		            jQuery("#promoEMsg").text('');
		            jQuery("#promoEMsg").hide();
		    		var action_data = "resend_otp_code";
		                 
		            jQuery.ajax({
		                url:"<?php echo admin_url('admin-ajax.php'); ?>",
		                type:'POST',
		                data: jQuery('#reward-program').serialize() + "&action=" + action_data,

		                success: function(data){
		                    if(data == 'Success'){										
			                	jQuery("#promoSMsg").text('Verification Code has been sent');
		                        jQuery("#promoSMsg").show();
		                        var newseconds = promoOtpCountTime;
		                        jQuery("#promoresendLink").hide();
		                        jQuery("#promoresendotp").show();
		                        jQuery("#promoOtpCount").html(newseconds);
		                        secondInterval = setInterval(function () {
		                            newseconds--;
		                            jQuery("#promoOtpCount").html(newseconds);
		                            if (newseconds == 0) {
		                                clearInterval(secondInterval);
		                                promoOtpCountTime = promoOtpCountTime + 30;
		                                jQuery("#promoresendotp").hide();
		                                jQuery("#promoresendLink").show();
		                                jQuery('#promoSMsg').text('');
		                                jQuery("#promoSMsg").hide();
		                            }
		                        }, 1000);       
		                    }else{
		                    	jQuery("#promoEMsg").text('Connectivity failure. Verification code not sent. Please try again.');
		                        jQuery("#promoEMsg").show();
		                    }    
		                       
			            }
		            });   
		    	});
                
                /*-- OTP login methods scripts--*/

				jQuery("#opt_login").click(function(){
		            jQuery('.opterrmsg').remove();
			        var email = jQuery("#email").val();
			        var password = jQuery("#password").val();
			        
			        jQuery('.opterrmsg').remove();
			        jQuery('form.form_login').removeClass('opterror');
			        var bool = 1;

			        if (jQuery.trim(jQuery('#password').val()) == '') {
			            jQuery('#password').after('<span class="opterrmsg">Please enter password.</span>');
			            jQuery('#password').addClass('opterror');
			            bool = 0;
			        }
			        
					if (jQuery.trim(jQuery('#email').val()) == '') {
			            jQuery('#email').after('<span class="opterrmsg">Please enter your email address.</span>');
			            jQuery('#email').addClass('opterror');
			            bool = 0;
			        }
			        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			        if (jQuery.trim(jQuery('#reg_email').val()) != '') {
			            if (!jQuery('#email').val().match(mailformat)) {
			                jQuery('#email').after('<span class="opterrmsg">Please enter valid email address.</span>');
			                jQuery('#email').addClass('opterror');
			                bool = 0;
			            }
			        }
		          
			        if(bool == 0){
				        return false;
			        }else{
			            var action_data = "opt_login";
			            var redirect_url = "<?php echo site_url('/my-account/'); ?>";
			            jQuery.ajax({
			                url:"<?php echo admin_url('admin-ajax.php'); ?>",
			                type:'POST',
			          
			                data: jQuery('#login_form').serialize() + "&action=" + action_data,
			                success: function(data){
		                        if(data == 0){
		                            jQuery('#login_form').prepend('<span class="opterrmsg">Username/Password incorrect.</span>'); 
		                        }else{
		                             window.location.href =  redirect_url;
		                        }
			                }
			            });  
			        } 
			    });  

            });
        </script>
        
        <?php
        exit();
    }

    /**
	 * This function is used for login authentication method.
	 * @name optrewards_login_authentication
	 */
 
    public function optrewards_login_authentication() {
        
		    if ( isset($_POST['email'])) {
		        $email =  trim($_POST['email']);
		    }
		    
		    if ( isset($_POST['password']) ) {
		        $password = trim($_POST['password']);
		    }

	        $user_info = get_user_by( 'email', $email );
	        
	        if( $user_info ) {
	            $user_id = $user_info->ID; 
	            if( wp_check_password( $password, $user_info->user_pass, $user_id ) ) {
	                wp_set_auth_cookie( $user_id, $user_remember );
				    wp_set_current_user( $user_id, $email );
				    echo "1";
	            } else {
	                echo "0";
	            }
	        } else {
	        	echo "0";
	        }
		    
		exit;    
    }


    /**
	 * This function is used verification code API
	 * @name optrewards_verification_code_api_call
	 */
 
    public function optrewards_verification_code_api_call(){

    	$inquiryway = !empty($_POST['inquiryway']) ? $_POST['inquiryway'] : '';
        $custEmail = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : '';
        
    	WC()->session->set( 'inquiry_way', $inquiryway );
    	WC()->session->set( 'email', $custEmail );
    	WC()->session->set( 'phone', $phone );
	    
	    $opt_data = new  Opt_Rewards_Data();
	    $valid_time = $opt_data->getOtpValidDuration();
	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $response  = $opt_operation->otpIssue($phone, $custEmail);
        

        if($response['status'] == 'Success'){
	        $otp = $response['otpCode'];
	        if (  WC()->session->__isset( 'otp_code' ) ){
        	    WC()->session->__unset( 'otp_code' );
            }
	        if($otp != ''){
	        	WC()->session->set( 'otp_code', $otp );
	        	WC()->session->set( 'valid_time', $valid_time );
	        	echo "Success";
	        }
	    }else{
            echo "Error";
	    }

	    exit;
    }

    /**
	 * This function is used verification code API
	 * @name optrewards_create_verification_code_api_call
	 */
 
    public function optrewards_create_verification_code_api_call(){
    	session_start();

        $inquiryway = !empty($_POST['inquiryway']) ? $_POST['inquiryway'] : '';
        $custEmail = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : '';
        
        $_SESSION['inquiry_way'] = $inquiryway;
        $_SESSION['email'] = $custEmail;
        $_SESSION['phone'] = $phone;
	    
	    $opt_data = new  Opt_Rewards_Data();
	    $valid_time = $opt_data->getOtpValidDuration();
	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $response  = $opt_operation->otpIssue($phone, $custEmail);
        
        if($response['status'] == 'Success'){
	        $otp = $response['otpCode'];
	        if ($_SESSION['otp_code']){
        	    unset($_SESSION['otp_code']);
            }
	        if($otp != ''){
	        	$_SESSION['otp_code'] = $otp;
	        	$_SESSION['valid_time'] = $valid_time;
	        	echo "Success";
	        }
	    }else{
            echo "Error";
	    }

	    exit;
    }

    /**
	 * This function is used resend verification code API
	 * @name optrewards_resend_verification_code_api_call
	 */
 
    public function optrewards_resend_verification_code_api_call(){

        $inquiryway = !empty($_POST['inquiry']) ? $_POST['inquiryway'] : '';
        $custEmail = !empty($_POST['loyalty_account_email']) ? trim($_POST['loyalty_account_email']) : '';
        $phone = !empty($_POST['loyalty_account_phone']) ? trim($_POST['loyalty_account_phone']) : '';

	    WC()->session->set( 'inquiry_way', $inquiryway );
	    WC()->session->set( 'email', $custEmail );
	    WC()->session->set( 'phone', $phone );

	    $opt_data = new  Opt_Rewards_Data();
	    $valid_time = $opt_data->getOtpValidDuration();
	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $response  = $opt_operation->otpIssue($phone, $custEmail);
        

        if($response['status'] == 'Success'){
	        $otp = $response['otpCode'];
	        if($otp != ''){
                if (  WC()->session->__isset( 'otp_code' ) ){
	        	    WC()->session->__unset( 'otp_code' );
	            }
	        	WC()->session->set( 'otp_code', $otp );
	        	WC()->session->set( 'valid_time', $valid_time );
	        	echo "Success";
	        }
	        
	    }else{
            echo "Error";
	    }
	    exit;
    }

    /**
	 * This function is used resend verification code API
	 * @name optrewards_enroll_resend_verification_code_api_call
	 */
 
    public function optrewards_enroll_resend_verification_code_api_call(){
    	session_start();

        $inquiryway = !empty($_POST['inquiryway']) ? $_POST['inquiryway'] : '';
        $custEmail = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $phone = !empty($_POST['billing_phone']) ? trim($_POST['billing_phone']) : '';

	    $opt_data = new  Opt_Rewards_Data();
	    $valid_time = $opt_data->getOtpValidDuration();
	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $response  = $opt_operation->otpIssue($phone, $custEmail);
        

        if($response['status'] == 'Success'){
	        $otp = $response['otpCode'];
	        if($otp != ''){
                if ($_SESSION['otp_code']){
	        	    unset($_SESSION['otp_code']);
	            }
		        if($otp != ''){
		        	$_SESSION['otp_code'] = $otp;
		        	$_SESSION['valid_time'] = $valid_time;
		        	echo "Success";
		        }
	        }
	    }else{
            echo "Error";
	    }
	    exit;
    }

    /**
	 * This function is used resend verification code API for edit user details and on thankyou page
	 * @name optrewards_resend_otp_code_api_call
	 */
 
    public function optrewards_resend_otp_code_api_call(){
      
    	$custEmail = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : '';

        WC()->session->set( 'inquiry_way', 'email' );
    	WC()->session->set( 'email', $custEmail );
    	WC()->session->set( 'phone', $phone );

	    $opt_data = new  Opt_Rewards_Data();
	    $valid_time = $opt_data->getOtpValidDuration();
	    $opt_operation = new Opt_Rewards_Api_Operations();
	    $response  = $opt_operation->otpIssue($phone, $custEmail);
        

        if($response['status'] == 'Success'){
	        $otp = $response['otpCode'];
	        if($otp != ''){
                if (  WC()->session->__isset( 'otp_code' ) ){
	        	    WC()->session->__unset( 'otp_code' );
	            }
	        	WC()->session->set( 'otp_code', $otp );
	        	WC()->session->set( 'valid_time', $valid_time );
	        	echo "Success";
	        }
	        
	    }else{
            echo "Error";
	    }
	    exit;
    }

        /**
	 * This function is used for verification code authentication method.
	 * @name optrewards_create_verification_code_authentication
	 */
 
    public function optrewards_create_verification_code_authentication() {

        session_start();
        $inquiryway = ''; 
    	$custEmail = '';
        $phone = '';

	    if ($_SESSION['inquiry_way']){
            $inquiry_way = $_SESSION['inquiry_way'];
        }
        if ($_SESSION['email']){
            $custEmail = $_SESSION['email'];
        }
        if ($_SESSION['phone']){
            $phone = $_SESSION['phone'];
        }

        $verifyOtp = '';
        $otpValue =  $_POST['otp_code'];

        $opt_operation = new Opt_Rewards_Api_Operations();
        $opt_data = new  Opt_Rewards_Data();

        $lenCheck =  strlen($otpValue);
        if(empty($otpValue)){
            echo   "Verification failed. Please check and reenter the code.";
            exit;
        }
        if (!preg_match('/^[0-9]*$/', $otpValue)) {
        	echo  "Please enter only numbers, not alphabets or special characters.";
        	exit;
        }
        if ($lenCheck != 4) {
        	echo  "Verification failed. Please check and reenter the 4 digit code.";
        	exit;
        }
        if($_SESSION['otp_code']){
            $verifyOtp = $_SESSION['otp_code'];
        }
        if($_SESSION['valid_time']){
        	$validOtpTime = $_SESSION['valid_time'];
        }

	    if (!empty($otpValue)) {
            $otpVerification = $opt_data->verifyOtpTime($validOtpTime);
            if ($otpVerification == 1) {
                if ($verifyOtp == $otpValue) {
                    $res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
                    echo  "Success";
                    exit;
                }else {
                    echo  "Verification failed. Please check and reenter the code.";
                    exit;
                }
            }else{
            	$res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
            	echo  "Verification failed. Expired code used. Please check and reenter the code.";
            	exit;
            }    	
        }
        
        echo  "Success";
        exit;    
    }

    /**
	 * This function is used for verification code authentication method.
	 * @name optrewards_redeem_verification_code_authentication
	 */
 
    public function optrewards_redeem_verification_code_authentication() {
        
        $inquiry_way = '';
        $custEmail = '';
        $phone = '';

	    if (  WC()->session->__isset( 'inquiry_way' ) ){
            $inquiry_way = WC()->session->get( 'inquiry_way' );
        }
        if (  WC()->session->__isset( 'email' ) ){
            $custEmail = WC()->session->get( 'email' );
        }
        if (  WC()->session->__isset( 'phone' ) ){
            $phone = WC()->session->get( 'phone' );
        }    

        $verifyOtp = '';
        $otpValue =  $_POST['otp_code'];

        $opt_operation = new Opt_Rewards_Api_Operations();
        $opt_data = new  Opt_Rewards_Data();

        $lenCheck =  strlen($otpValue);
        if(empty($otpValue)){
            echo   "Verification failed. Please check and reenter the code.";
            exit;
        }
        if (!preg_match('/^[0-9]*$/', $otpValue)) {
        	echo  "Please enter only numbers, not alphabets or special characters.";
        	exit;
        }
        if ($lenCheck != 4) {
        	echo  "Verification failed. Please check and reenter the 4 digit code.";
        	exit;
        }

        if (  WC()->session->__isset( 'otp_code' ) ){
            $verifyOtp = WC()->session->get( 'otp_code' );
        }
        if (  WC()->session->__isset( 'valid_time' ) ){
            $validOtpTime = WC()->session->get( 'valid_time' );
        }	
 
	    if (!empty($otpValue)) {
            $otpVerification = $opt_data->verifyOtpTime($validOtpTime);
            if ($otpVerification == 1) {
                if ($verifyOtp == $otpValue) {
                    $res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
                    echo  "Success";
                    exit;
                }else {
                    echo  "Verification failed. Please check and reenter the code.";
                    exit;
                }
            }else{
            	$res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
            	echo  "Verification failed. Expired code used. Please check and reenter the code.";
            	exit;
            }    	
        }
        
        echo  "Success";
        exit;    
    }

    /**
	 * This function is used for verification code authentication method.
	 * @name optrewards_promotion_verification_code_authentication
	 */
 
    public function optrewards_promotion_verification_code_authentication() {

        $inquiry_way = '';
        $custEmail = '';
        $phone = '';

	    if (  WC()->session->__isset( 'inquiry_way' ) ){
            $inquiry_way = WC()->session->get( 'inquiry_way' );
        }
        if (  WC()->session->__isset( 'email' ) ){
            $custEmail = WC()->session->get( 'email' );
        }
        if (  WC()->session->__isset( 'phone' ) ){
            $phone = WC()->session->get( 'phone' );
        }
        
        $verifyOtp = '';
        $otpValue =  $_POST['otp_promotion_code'];

        $opt_operation = new Opt_Rewards_Api_Operations();
        $opt_data = new  Opt_Rewards_Data();

        $lenCheck =  strlen($otpValue);
        if(empty($otpValue)){
            echo   "Verification failed. Please check and reenter the code.";
            exit;
        }
        if (!preg_match('/^[0-9]*$/', $otpValue)) {
        	echo  "Please enter only numbers, not alphabets or special characters.";
        	exit;
        }
        if ($lenCheck != 4) {
        	echo  "Verification failed. Please check and reenter the 4 digit code.";
        	exit;
        }
        if (  WC()->session->__isset( 'otp_code' ) ){
            $verifyOtp = WC()->session->get( 'otp_code' );
        }
        if (  WC()->session->__isset( 'valid_time' ) ){
            $validOtpTime = WC()->session->get( 'valid_time' );
        }	

	    if (!empty($otpValue)) {
            $otpVerification = $opt_data->verifyOtpTime($validOtpTime);
            if ($otpVerification == 1) {
                if ($verifyOtp == $otpValue) {
                    $res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
                    echo  "Success";
                    exit;
                }else {
                    echo  "Verification failed. Please check and reenter the code.";
                    exit;
                }
            }else{	
            	$res = $opt_operation->otpAcknowledge($phone, $custEmail, $verifyOtp);
            	echo  "Verification failed. Expired code used. Please check and reenter the code.";
            	exit;
            }    	
        }

        echo  "Success";
        exit;

    }


    /**
	 * This function is used for get coupon information
	 * @name opt_rewards_get_couponInfo
	 */

    public function opt_rewards_get_couponInfo($loyaltyInquiryResponse) {
        $htmldata = '';
        if (isset($loyaltyInquiryResponse['couponinfo'])) {
            $checkboxId = 1;
            $customerId = 0;
            if(is_user_logged_in()){
	    		$current_user = wp_get_current_user();
	    		$customerId = $current_user->ID;
	    	}
            $cart_applied_coupon = array();
            $apply_item_sku = array();
            foreach ($loyaltyInquiryResponse['couponinfo'] as $couponDetails) {

                if($couponDetails['couponCode'] !== '') {
                    $codeLabel = $couponDetails['couponCode'];
                    $codeType = $couponDetails['couponType'];
                    $discountCriteria = $couponDetails['discountCriteria'];
                    $itemSku = isset($couponDetails['itemSku']) ? $couponDetails['itemSku'] : 'empty';
                    
                    $codeValue = '';
                    $codeDetails = '';
                    $nudgeDetails = '';
                    $ruleType = '';
                    if($discountCriteria == 'VALUE-MVP' || $discountCriteria == 'PERCENTAGE-MVP'){
                        if($couponDetails['nudgepromocode'] == 'NO'){
                            $receiptDisc = (int)$couponDetails['receiptDisc'];
                            $discValueCode = $couponDetails['discValueCode'];
                            if($couponDetails['minPurchaseVal'] != ''){
                                $minValue = $couponDetails['minPurchaseVal'];
                            } else {
                                $minValue = 0;
                            }
                            $ruleType = 'cart';
                            $codeValue = 'Cart|'.$codeLabel.'|'.$receiptDisc.'|'.$discValueCode;
                            $codeDetails = $couponDetails['description'];
                        } else {
                            $codeValue = 'Nudge|'.$codeLabel;
                            $codeDetails = $couponDetails['description'];
                            $nudgeDetails = $couponDetails['nudgedescription'];
                        }
                    }
                    if($discountCriteria == 'VALUE-I' || $discountCriteria == 'PERCENTAGE-IC'){
                        if($couponDetails['nudgepromocode'] == 'NO'){
                            $itemDisc = (int)$couponDetails['itemDisc'];
                            $discValueCode = $couponDetails['discValueCode'];
                            if($couponDetails['itemQty'] != ''){
                                $itemQty = number_format($couponDetails['itemQty']);
                            } else {
                                $itemQty = 0;
                            }
                            if($couponDetails['rewardRatio'] != ''){
                                $rewardRatio = $couponDetails['rewardRatio'];
                            } else {
                                $rewardRatio = '';
                            }
                            $ruleType = 'item';
                            $codeValue = 'Item|'.$codeLabel.'|'.$itemDisc.'|'.$discValueCode.'|'.$couponDetails['itemSku'].'|'.$itemQty.'|'.$rewardRatio;
                            $codeDetails = $couponDetails['description'];
                        } else {
                            $codeValue = 'Nudge|'.$codeLabel;
                            $codeDetails = $couponDetails['description'];
                            $nudgeDetails = $couponDetails['nudgedescription'];
                        }
                    }
                    if($nudgeDetails != ''){
                        $inputvar = '';
                    } else {
                    	
                        $proLable = strtolower($codeLabel);

                    	if($ruleType == 'item'){
		                    $loyalty_para = 'loyalty_promotions';
		                }else{
		                	$loyalty_para = 'loyalty_receipts';
		                }
				        $pro_code = $loyalty_para.'_'.$proLable;
				        
             
                        if($couponDetails['appliedCoupons'] == 'YES'){	
                        	
                        	$apply_item_sku[] = $itemSku;
                            $inputvar = '<input type="checkbox" value='.$codeValue.' data-type='.$ruleType.' class="code_check '.$ruleType.' '.$codeType.'" id="codecheck_'.$codeLabel.'" name="coupons[]" checked>';
                        } else {
                            $inputvar = '<input type="checkbox" value='.$codeValue.' data-type='.$ruleType.' class="code_check '.$ruleType.' '.$codeType.'" id="codecheck_'.$codeLabel.'" name="coupons[]">';
                        }
                    }
                    if($customerId > 0){
                    	$htmldata .= '<li class="rule_data"><div class="rule_left">'.$inputvar.'<label for="codecheck_'.$codeLabel.'" class="code_lable">'.$codeLabel.'</label></div><div class="rule_right"><p class="code_details">'.$codeDetails.'<span class="nudgedescription">'.$nudgeDetails.'</span></p></div></li>';
                    }else{
                    	if($codeType == 'REWARDS' || $codeType == 'LOYALTY'){
                    		$htmldata .= '<li class="rule_data"><div class="rule_left">'.$inputvar.'<label for="codecheck_'.$codeLabel.'" class="code_lable">'.$codeLabel.'</label></div><div class="rule_right"><p class="code_details">'.$codeDetails.'<span class="nudgedescription">'.$nudgeDetails.'</span></p></div></li>';
                    	}
                    }
                    
                }
                $checkboxId++;
                WC()->session->set( 'apply_item_sku', $apply_item_sku );
            }
            
        }
        
        return $htmldata;
    }

    /**
	 * This function is used for loyalty balance
	 * @name opt_rewards_apply_loyalty_balance
	 */	

	public function opt_rewards_apply_loyalty_balance(){
	    if( isset($_POST['loyalty_balances']) ){
	        WC()->session->set( 'loyalty_balances', esc_attr( $_POST['loyalty_balances'] ) );
	        echo true;
	    }
	    exit();
	}

	/**
	 * This function is used for apply promotions
	 * @name opt_rewards_apply_loyalty_promotions
	 */	

	public function opt_rewards_apply_loyalty_promotions(){
	    if( isset($_POST['coupons']) ) {
	    	$coupons = $_POST['coupons'];
	    	$allPromos = array();
            $allcouponCodes = [];
            $promotion_label = array();
	    	foreach ($coupons as $coupon) {
	    		$applyPromo = explode('|', $coupon);
	    		
	    		if ($applyPromo[0] == 'Item') {
                    $sku = $applyPromo[4];
                    $qty = $applyPromo[5];
                    $ratio = $applyPromo[6];
                    $productApplySku[] = $sku;
                } else {
                    $sku = '';
                    $qty = '';
                    $ratio = '';
                    $applyCartcoupenCode = $applyPromo[1];
                }
 
                $rcode = strtolower($applyPromo[1]);
                if($applyPromo[0] == 'Item'){
                    $loyalty_para = 'loyalty_promotions';
                }else{
                	$loyalty_para = 'loyalty_receipts';
                }
				$pro_code = $loyalty_para.'_'.$rcode;

				$promotion_label[] = $loyalty_para.'_'.$rcode;
                
                $allPromos = array(
                    "type" => $applyPromo[0],
                    "code" => $applyPromo[1],
                    "discountValue" => $applyPromo[2],
                    "discountType" => $applyPromo[3],
                    "sku" => $sku,
                    "qty" => $qty,
                    "ratio" => $ratio
                );
	            
                WC()->session->set( $pro_code,  $allPromos );

	        	echo true;
	    	}   
	
	    	WC()->session->set( 'promotion_label',  $promotion_label );

	    }
	    exit();
	}

	/**
	 * This function is used for add coupon
	 * @name opt_rewards_add_loyalty_coupon
	 */	
     
    public function opt_rewards_add_loyalty_coupon(WC_Cart $cart){
        global $woocommerce;

	    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	        return;
	    $cartItems = WC()->cart->get_cart();
	    $cart_session = WC()->session;
	    $total = $cart_session->cart_totals;
	    $subtotal = $total['subtotal'];
	  
    	if(is_cart() || is_checkout()) {
            $cartItems = WC()->cart->get_cart();
		    if (  WC()->session->__isset( 'loyalty_balances' ) ){
				  $coupon_code = 'loyalty_points'; 
				  if (!in_array($coupon_code, WC()->cart->get_applied_coupons())) {
				      $cart->apply_coupon($coupon_code);

				  }
			}
			if (  WC()->session->__isset( 'promotion_label' ) ){
	            $promotion_labels = WC()->session->get( 'promotion_label' );
                 
	            if(!empty($promotion_labels)) {
	            	
	            	$apply_coupons = WC()->cart->get_applied_coupons();
	            	WC()->session->set( 'cart_applied_coupon',  $apply_coupons );
	            	$promotion_data = array();
					foreach ($promotion_labels as $label) {
		                if (  WC()->session->__isset( $label ) ){
                            
		                	$loyalty_balances_data = WC()->session->get( $label );
		                	$DiscountType =  array();
	                        $ItemDiscount = array();
	                        $ItemCode = array();
	                        $QuantityDiscounted = array();
	                        $ratio = array();

		                	if($loyalty_balances_data['type'] == 'Item'){

		                		foreach ($cartItems as $item => $values) {
				    				$_product =  wc_get_product( $values['product_id'] ); 
				    				$type = $_product->get_type();
                                    $cartItemQty = $values['quantity'];

                                    if($type == 'variable'){
                                        $_variation =  wc_get_product( $values['variation_id'] ); 
						                $sku = $_variation->get_sku();
						                $itemPrice = $_variation->get_price();
                                    }else{
                                        $sku = $_product->get_sku();
						                $itemPrice = $_product->get_price();
                                    }	
					                if ($sku == $loyalty_balances_data['sku']) {

					                    if ($loyalty_balances_data['discountType'] == 'P') {
					                        $singleQtyDiscount = ($itemPrice * $loyalty_balances_data['discountValue']) / 100;
					                    } else {
					                        $singleQtyDiscount = $loyalty_balances_data['discountValue'];
					                    }
                                        if($loyalty_balances_data['qty'] == 0 || $loyalty_balances_data['qty'] == ''){
                                        	$QtyDiscounted = $cartItemQty;
                                        }else {
                                            if($cartItemQty  <= $loyalty_balances_data['qty']){
						                    	$QtyDiscounted =  $cartItemQty;
						                    }else if($cartItemQty  > $loyalty_balances_data['qty']){
	                                            $QtyDiscounted =  $loyalty_balances_data['qty'];
	                                        }
                                        }

					                    $DiscountType[] =  'Item';
				                        $ItemDiscount[] = $singleQtyDiscount;
				                        $ItemCode[] = $loyalty_balances_data['sku'];
				                        $QuantityDiscounted[] = $QtyDiscounted;
				                        $ratio[] = $loyalty_balances_data['ratio'];
						            }
						        }

			                    foreach ($DiscountType as $key => $value) {
	                             	$promotion_data[] = array('DiscountType' => $DiscountType[$key],'ItemCode'=> $ItemCode[$key], 'ItemDiscount'=> $ItemDiscount[$key], 'QuantityDiscounted' => $QuantityDiscounted[$key], 'CouponCode' => $loyalty_balances_data['code'], 'RewardRatio' => $ratio[$key]);
	                            }

	                            if(!empty($apply_coupons)){ 
	                            	$pro_label = array();
	                                foreach ($apply_coupons as $coupon) {
	                                	if (strpos($coupon, 'loyalty_receipts') !== false ){
	                                        $cart->remove_coupon( $coupon );
	                                        WC()->session->__unset( $coupon );
	                                	}
	                                	if (!in_array($coupon, $promotion_labels) ) {
	                                		if ( (strpos($coupon, 'loyalty_promotions') !== false || strpos($coupon, 'loyalty_receipts') !== false)){
		                                        $cart->remove_coupon( $coupon );
		                                        WC()->session->__unset( $coupon );
		                                    }    
	                                	}
	                                	
	                                }
	                            }

					            if (!in_array($label, WC()->cart->get_applied_coupons()) ) { 
			                        $cart->apply_coupon($label);
			                    }
					                 
		                	}else{

		                		if ($loyalty_balances_data['discountType'] == 'P') {
					                $discount = ($subtotal * $loyalty_balances_data['discountValue']) / 100;
					            } else {
					                $discount = $loyalty_balances_data['discountValue'];
					            }
					            
		                        $DiscountType[] =  'Receipt';
		                        $DiscountAmount[] = $discount;


		                        foreach ($DiscountType as $key => $value) {
	                             	$promotion_data[] = array('DiscountType' => $DiscountType[$key],'DiscountAmount'=> $DiscountAmount[$key], 'CouponCode' => $loyalty_balances_data['code']);
	                            }

                                if(!empty($apply_coupons)){ 
                                	$pro_label = array();
	                                foreach ($apply_coupons as $coupon) {
	                                	if ( (strpos($coupon, 'loyalty_promotions') !== false || strpos($coupon, 'loyalty_receipts') !== false) && $label != $coupon) {
	                                        $cart->remove_coupon( $coupon );
	                                        WC()->session->__unset( $coupon );
	                                	}
	                                	if (!in_array($coupon, $promotion_labels) ) {
	                                		if ( (strpos($coupon, 'loyalty_promotions') !== false || strpos($coupon, 'loyalty_receipts') !== false)){
		                                        $cart->remove_coupon( $coupon );
		                                        WC()->session->__unset( $coupon );
		                                    }    
	                                	}
	                                }
	                                
	                            }    
                                if (!in_array($label, WC()->cart->get_applied_coupons())) { 
			                        $cart->apply_coupon($label);
			                    } 
		                	}
		                	
		                }	
					}
					$loyalty_promotion_data = WC()->session->set( 'loyalty_promotion_data',  $promotion_data );
				}	
			}	
        }
        
    }

    /**
	 * This function is used for set coupon
	 * @name opt_rewards_set_loyalty_coupon
	 */	
    
    public function opt_rewards_set_loyalty_coupon($false, $data, $coupon){
    	
    	global $woocommerce;
    	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
	        return;
		$cartItems = WC()->cart->get_cart();
    	$discount = 0;
    	$subtotal = WC()->cart->get_displayed_subtotal();

    	$discount_total = WC()->cart->get_discount_total();
    	$sum_total = $subtotal - $discount_total;
    	    	
    	if(is_cart() || is_checkout()){
        
			if (  WC()->session->__isset( 'loyalty_balances' ) ){
				switch($data) {
				    case 'loyalty_points':
				    	$discount_type = 'fixed_cart';
				        $discount_amount = (float) WC()->session->get( 'loyalty_balances' );
				        $coupon->set_discount_type($discount_type);
				        $coupon->set_amount($discount_amount);
				        return $coupon;
				}
			} 
			if (  WC()->session->__isset( 'promotion_label' ) ){
				$promotion_labels = WC()->session->get( 'promotion_label' );

	            if(!empty($promotion_labels)) {
	            	
			    	foreach ($promotion_labels as $promo_label) {
            		    switch($data) {
			            case $promo_label:
                            
				            if (  WC()->session->__isset( $promo_label ) ){
	                            $loyalty_balances_data = WC()->session->get( $promo_label );
	                            
					    		if($loyalty_balances_data['type'] == 'Item')
					    		{	
					    			
					    			$applied_coupons = WC()->cart->get_applied_coupons();

					    			foreach ($cartItems as $item => $values) {
                                        
					    				$_product =  wc_get_product( $values['product_id'] ); 
					    				$type = $_product->get_type();
	                                    $cartItemQty = $values['quantity'];

	                                    if($type == 'variable'){
	                                        $_variation =  wc_get_product( $values['variation_id'] ); 
							                $sku = $_variation->get_sku();
							                $itemPrice = $_variation->get_price();
	                                    }else{
	                                        $sku = $_product->get_sku();
							                $itemPrice = $_product->get_price();
	                                    }
		
						                if ($sku == $loyalty_balances_data['sku']) {
                                            $product_id = $values['product_id']; 
						                	$codeLabel = $loyalty_balances_data['code'];
	                                        $proLable = strtolower($codeLabel);

	                    	                $loyalty_para = 'loyalty_promotions';  
					                        $pro_code = $loyalty_para.'_'.$proLable;

						                    $totalItemPrice = $itemPrice * $cartItemQty;

						                    if ($loyalty_balances_data['discountType'] == 'P') {
						                        $singleQtyDiscount = ($itemPrice * $loyalty_balances_data['discountValue']) / 100;
						                    } else {
						                        $singleQtyDiscount = $loyalty_balances_data['discountValue'];
						                    }

						                    if ($loyalty_balances_data['qty'] > 0 && $cartItemQty > $loyalty_balances_data['qty']) {
						                    	$apply_qty = $loyalty_balances_data['qty'];
						                        $discount = $singleQtyDiscount * $loyalty_balances_data['qty'];
						                    } else {
						                    	$apply_qty = $cartItemQty;
						                        $discount = $singleQtyDiscount * $cartItemQty;
						                    }
						                    if ($discount > $totalItemPrice) {
						                        $discount = $totalItemPrice;
						                    }
                                            
							                $discount_type = 'fixed_product';
									        $coupon->set_discount_type($discount_type);
									        $coupon->set_product_ids($product_id);
									        $coupon->set_limit_usage_to_x_items($apply_qty);
									        $coupon->set_amount($singleQtyDiscount);
						                }
		                                
						            }

					    		} else {
					    			
					    			if ($loyalty_balances_data['discountType'] == 'P') {
						                $discount = ($subtotal * $loyalty_balances_data['discountValue']) / 100;
						            } else {
						                $discount = $loyalty_balances_data['discountValue'];
						            }
						            $discount_type = 'fixed_cart';
							        $coupon->set_discount_type($discount_type);
							        $coupon->set_amount($discount);
					    		}
	                        }
                          return $coupon; 
			    	    }	
			    	}
			    	
			    } 
			    
			}   
			 
		}
	
    }

    /**
	 * This function is used for remove coupon
	 * @name opt_rewards_removed_loyalty_coupon
	 */	

    public function opt_rewards_removed_loyalty_coupon($coupon_code){
    	
        if (  WC()->session->__isset( 'loyalty_balances' )  && $coupon_code == 'loyalty_points' ){
            WC()->session->__unset( 'loyalty_balances' );
            ?>
            <script> 
               jQuery( document ).ready(function() {
				    jQuery('#loyalty-input').show();  
				    jQuery('#rm_btn').hide();    
				});
            </script>
            <?php
        }
        if (  WC()->session->__isset( 'promotion_label' ) ) {
        	$promotion_labels = WC()->session->get( 'promotion_label' );
            if(!empty($promotion_labels)){
            	$pro_label = array();
	        	foreach ($promotion_labels as $key => $label) {
	                if (  WC()->session->__isset( $label ) ){
	                	if ( $coupon_code == $label ){
	                		WC()->session->__unset( $label );
	                	}else{
	                		$pro_label[] = $label;
	                	}	
	                    
	                }            
	        	}
	        	$promotion_labels = $pro_label;
	        	WC()->session->set( 'promotion_label',$promotion_labels );
	        }		
        }    	       
    }

    /**
	 * This function is used for set coupon label
	 * @name opt_rewards_set_loyalty_label
	 */	

    public function opt_rewards_set_loyalty_label($label, $coupon){
    	if(is_cart() || is_checkout()){
	    	if (  WC()->session->__isset( 'loyalty_balances' ) ){
				switch ($coupon->get_code()) {
				    case 'loyalty_points':
				    return 'Loyalty Discount';
				}
		    }
		    $promotion_labels = WC()->session->get( 'promotion_label' );
            if(!empty($promotion_labels)){
	        	foreach ($promotion_labels as $pro_label) {
	                if (  WC()->session->__isset( $pro_label ) ){
	                	switch ($coupon->get_code()) {
						    case $pro_label:
						    $coupon_code = $coupon->get_code();
						    if (strpos($coupon, 'loyalty_promotions_') !== false) {
                                $code = explode("loyalty_promotions_",$coupon_code);
                                return 'Loyalty Promotion : '.$code[1];
						    }else if(strpos($coupon, 'loyalty_receipts_') !== false){
						    	$code = explode("loyalty_receipts_",$coupon_code); 
						    	return 'Loyalty Receipts : '.$code[1];
						    }else{
						    	return $pro_label;
						    }	
						}
	                }            
	        	}
	        }		
		}    
	    return $label;
	}

	/**
	 * This function is used for loader
	 * @name opt_rewards_add_loader
	 */

	public function opt_rewards_add_loader(){
    	
	    echo '<div class="opt-loader" style="display:none"><img src="'.OPT_REWARDS_DIR_URL .'public/images/loader.svg"></div>';
	       
    }

    
    /**
	 * This function is used for get api call on removed cart item
	 * @name opt_rewards_get_api_on_update_cart
	 */

    public function opt_rewards_get_api_on_update_cart($phone, $email){
        global $woocommerce;
        $cartItems = $woocommerce->cart->get_cart(); 
    	$opt_operation = new Opt_Rewards_Api_Operations();
    	$loyaltyInquiryResponse = [];
        $loyaltyInquiryResponse = $opt_operation->promotionsInquiry($phone, $email);
       
        
        $cart_applied = WC()->cart->get_applied_coupons();    
        $apply_item_sku = array();

        if (  WC()->session->__isset( 'apply_item_sku' ) ){
	        $apply_item_sku = WC()->session->get( 'apply_item_sku' );
	    }
        
        if(!empty($loyaltyInquiryResponse)){
         
	        if (isset($loyaltyInquiryResponse['couponinfo'])) {
	            $checkboxId = 1;
	            $api_applied_coupon = array();
	            foreach ($loyaltyInquiryResponse['couponinfo'] as $couponDetails) {
                   
	                if($couponDetails['couponCode'] !== '') {
	                    $codeLabel = $couponDetails['couponCode'];
	                    $codeType = $couponDetails['couponType'];
	                    $discountCriteria = $couponDetails['discountCriteria'];
	                    
	                    $codeValue = '';
	                    $codeDetails = '';
	                    $nudgeDetails = '';
	                    $ruleType = '';
	                    if($discountCriteria == 'VALUE-MVP' || $discountCriteria == 'PERCENTAGE-MVP'){
	                        if($couponDetails['nudgepromocode'] == 'NO'){
	                            $receiptDisc = (int)$couponDetails['receiptDisc'];
	                            $discValueCode = $couponDetails['discValueCode'];
	                            if($couponDetails['minPurchaseVal'] != ''){
	                                $minValue = $couponDetails['minPurchaseVal'];
	                            } else {
	                                $minValue = 0;
	                            }
	                            $ruleType = 'cart';
	                            $codeValue = 'Cart|'.$codeLabel.'|'.$receiptDisc.'|'.$discValueCode;
	                            $codeDetails = $couponDetails['description'];
	                        } else {
	                            $codeValue = 'Nudge|'.$codeLabel;
	                            $codeDetails = $couponDetails['description'];
	                            $nudgeDetails = $couponDetails['nudgedescription'];
	                        }
	                    }
	                    if($discountCriteria == 'VALUE-I' || $discountCriteria == 'PERCENTAGE-IC'){
	                        if($couponDetails['nudgepromocode'] == 'NO'){
	                            $itemDisc = (int)$couponDetails['itemDisc'];
	                            $discValueCode = $couponDetails['discValueCode'];
	                            if($couponDetails['itemQty'] != ''){
	                                $itemQty = number_format($couponDetails['itemQty']);
	                            } else {
	                                $itemQty = 0;
	                            }
	                            if($couponDetails['rewardRatio'] != ''){
	                                $rewardRatio = $couponDetails['rewardRatio'];
	                            } else {
	                                $rewardRatio = 0;
	                            }
	                            $ruleType = 'item';
	                            $codeValue = 'Item|'.$codeLabel.'|'.$itemDisc.'|'.$discValueCode.'|'.$couponDetails['itemSku'].'|'.$itemQty.'|'.$rewardRatio;
	                            $codeDetails = $couponDetails['description'];
	                        } else {
	                            $codeValue = 'Nudge|'.$codeLabel;
	                            $codeDetails = $couponDetails['description'];
	                            $nudgeDetails = $couponDetails['nudgedescription'];
	                        }
	                    }

	                    if($nudgeDetails != ''){
	                        $inputvar = '';
	                    } else {
	                    	$itemSku = "";
	                    	if(isset($couponDetails['itemSku'])){
	                    		$itemSku = $couponDetails['itemSku'];
	                    	}
	                    	
	                        $proLable = strtolower($codeLabel);

	                    	if($ruleType == 'item'){
			                    $loyalty_para = 'loyalty_promotions';
			                }else{
			                	$loyalty_para = 'loyalty_receipts';
			                }
					        $pro_code = $loyalty_para.'_'.$proLable;

	                        if(in_array($pro_code, $cart_applied)){	
                                	
	                        	if(in_array($itemSku, $apply_item_sku )){
	                        	    $api_applied_coupon[] = $pro_code;
	                        	
	                        	}
	                        }

	                    }
	                    
	                }
	             
	                $checkboxId++;
	            }

	            if(!empty($cart_applied) && is_array($cart_applied)){
	            	$pro_label = array();
		            foreach($cart_applied as $kay => $val){
		            	if(!in_array($val, $api_applied_coupon)){	
	                        
	                        WC()->cart->remove_coupon( $val );
				            WC()->session->__unset( $val );
	                    }else{
	                    	$pro_label[] = $val;
	                    }

		            }
		            $promotion_labels = $pro_label;
	        	    WC()->session->set( 'promotion_label',$promotion_labels );
	            }
           
	        }
	    }    
    }

    /**
	 * This function is used for api call on removed cart item
	 * @name opt_rewards_on_removed_cart_item
	 */

    public function opt_rewards_on_removed_cart_item( $cart_item_key, $instance ) {
    	global $woocommerce;

    	if (  WC()->session->__isset( 'inquiry_way' ) ){

            $inquiry_way = WC()->session->get( 'inquiry_way' );

            if($inquiry_way == "email"){
	    		$email = WC()->session->get( 'email' );
	    		$phone = "";	
	    	} else {
	    		$phone = WC()->session->get( 'phone' );
	    		$email = "";
	    	}

            $this->opt_rewards_get_api_on_update_cart($phone,$email);  

        }
	}
    
    /**
	 * This function is used for remove coupon on update cart.
	 * @name opt_rewards_on_cart_updated
	 */

	public function opt_rewards_on_cart_updated( $cart_updated ){
        global $woocommerce;
    	$apply_coupons = WC()->cart->get_applied_coupons();
    	

        if (  WC()->session->__isset( 'cart_applied_coupon' ) ){
	        $cart_applied_coupon = WC()->session->get( 'cart_applied_coupon' );
	    }
         

        if(!empty($apply_coupons)){ 
            foreach ($apply_coupons as $coupon) {
            	if(!in_array($coupon, $cart_applied_coupon)){
	                WC()->cart->remove_coupon( $coupon );
	                WC()->session->__unset( $coupon );
	            }    
            }
            WC()->session->__unset( 'promotion_label' );
        }     
    }	

    
    /**
	 * This function is used for display promotions.
	 * @name opt_rewards_checkout_promotions
	 */

	public function opt_rewards_checkout_promotions( $wccs_custom_checkout_field_pro ) { 

	    $email = '';
	    $phone_no = '';
	    if(is_user_logged_in()){

			$current_user = wp_get_current_user();
			$customerId = $current_user->ID;
			 
	        $firstName = $current_user->user_firstname;
	        $lastName = $current_user->user_lastname;
        
    	    $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);

	        if($enrollmentOption == "1"){
	        	$email = $current_user->user_email;
		        $phone_no  = get_user_meta( $customerId, 'user_phone_no', true);
		    }
        } 
        ?>
		   <div class="loyalty-points checkout_loyalty">
				<h2>Enter Email/Phone Number</h2>	
				<p><b>(to check, earn and redeem rewards)</b></p>

				<form class="reward-program" id="reward-program" action="" method="post">

	            	<p class="form-row form-row-first reward-enable">
					    <label><input type="radio" id="emailradio" checked="checked" name="inquiry" value="email">By Email</label>
				    </p>

				    <p class="form-row form-row-last reward-enable">
					   <label><input type="radio" id="phoneradio" name="inquiry" value="phone">By Phone</label>
					</p>   
					
					<p class="form-row form-row-wide byemail">
						<label for="loyalty_account_email">Email address<span class="required">*</span></label>
						<input type="email" class="input-text" name="loyalty_account_email" id="loyalty_account_email" autocomplete="email" value="<?php echo $email; ?>">
					</p>

				    <p class="form-row form-row-wide byphone" style="display: none;">
					    <label for="loyalty_account_phone">Mobile Number <span class="required">*</span></label>
					    <input type="text" class="input-text" name="loyalty_account_phone" id="loyalty_account_phone" maxlength="10" value="<?php echo $phone_no; ?>">
				    </p>
						    		    
					<p class="loyaltybtn-row">
						<input type="hidden" name="is_validate" value="0" id="is_validate">
						<button type="button" class="button get_loyalty" id="chkoutloyalty" name="get_loyalty" value="Save changes">Submit</button>
					</p>
                    
				</form>

				<div class="redeem-points"></div>	
		    </div>

		    <script>
	            var email = '<?php echo WC()->session->get( 'email' ); ?>';
                var phone = '<?php echo WC()->session->get( 'phone' ); ?>';
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
                var pageload = 1;
                jQuery( document.body ).on( 'update_checkout', function(){
                    if(pageload <= 2 ){
                    	pageload++;
                    }else{
                    	jQuery('#chkoutloyalty').trigger('click');
                    }
				});

				jQuery("#loyalty_account_email").on("keypress", function (e) {
			           // Number 13 is the "Enter" key on the keyboard    
			        if (e.keyCode === 13) {
			            // Trigger the button element with a click
			            jQuery("#chkoutloyalty").trigger("click");
			        }
			    });
			    jQuery("#loyalty_account_phone").on("keypress", function (e) {
			           // Number 13 is the "Enter" key on the keyboard    
			        if (e.keyCode === 13) {
			            // Trigger the button element with a click
			            jQuery("#chkoutloyalty").trigger("click");
			        }
			    });
                
				jQuery('html').on('click', '.code_check', function () {
				    applyCoupons(jQuery(this));
				});

				function applyCoupons(object)
				{
				    if (object.prop('checked') == true) {
				        var type = object.data('type');
				        if (type == 'cart') {
				            object.closest('li').siblings().find('input').attr('disabled', true);
				            jQuery('.code_check.item').attr('disabled', true);
				        } else {
				            jQuery('.code_check.cart').attr('disabled', true);
				        }
				    } else {
				        checked = jQuery('.code_check:checked').length;
				        if (checked == 0) {
				            jQuery('.code_check').removeAttr('disabled');
				        }
				    }
				}

			    jQuery("#chkoutloyalty").click(function(){
			        
			        var inquiryway = jQuery("input[type=radio][name=inquiry]:checked").val();
			        var email = jQuery("#loyalty_account_email").val();
			        var phone = jQuery("#loyalty_account_phone").val();
                    
			        
			        jQuery('.opterrmsg').remove();
			        jQuery('form.reward-program input').removeClass('opterror');
			        var flag = 1;
			        if(inquiryway == 'email' && email == ''){
			            jQuery('#loyalty_account_email').after('<span class="opterrmsg">Please enter your email address.</span>');
			            jQuery('#loyalty_account_email').addClass('opterror');
			            flag = 0;
			        }
			        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
				    if (inquiryway == 'email' && jQuery.trim(jQuery('#loyalty_account_email').val()) != '') {
				        if (!jQuery('#loyalty_account_email').val().match(mailformat)) {
				            jQuery('#loyalty_account_email').after('<span class="opterrmsg">Please enter valid email address.</span>');
				            jQuery('#loyalty_account_email').addClass('opterror');
				            flag = 0;
				        }
				    }
					if(inquiryway == 'phone' && phone == ''){
			        	jQuery('#loyalty_account_phone').after('<span class="opterrmsg">Please enter your phone number.</span>');
			            jQuery('#loyalty_account_phone').addClass('opterror');
			            flag = 0;
			        }

			        if(flag == 0){
			        	return false;
			        }else{
			        	jQuery('.opt-loader').show();
			        	var action_data = "loyalty_inquiry";
			        	jQuery.ajax({
			                url:"<?php echo admin_url('admin-ajax.php'); ?>",
			                type:'POST',
			                data: jQuery('#reward-program').serialize() + "&action=" + action_data,
			                success: function(data){
			                	jQuery('.opt-loader').hide();
			                    jQuery(".redeem-points").html(data);

			                    var check = jQuery('[name="coupons[]"]:checked').length;
			                    if(check > 0){
			                    	checked_cart = jQuery('.code_check.cart:checked').length;
			                    	checked_item = jQuery('.code_check.item:checked').length;
			                    	if(checked_cart > 0){
			                            jQuery('.code_check.item').attr('disabled', true);
			                            jQuery('.code_check.cart').parent().parent().find('input').attr('disabled', true);
                                        jQuery('.code_check.cart:checked').attr('disabled', false);
			                    	}
			                    	if(checked_item > 0){
			                            jQuery('.code_check.cart').attr('disabled', true);
			                    	}
			                    }
			                    jQuery(".woocommerce-billing-fields .form-row").removeClass("woocommerce-invalid");
			                    jQuery(".woocommerce-billing-fields .form-row").removeClass("woocommerce-invalid-required-field");

			                    jQuery(".woocommerce-shipping-fields .form-row").removeClass("woocommerce-invalid");
			                    jQuery(".woocommerce-shipping-fields .form-row").removeClass("woocommerce-invalid-required-field");
			                    
			                }
			            });  
			        }
			        
			    });  
			</script>    

	    <?php 
	}
	  
    /**
	 * This function is used for Add/Update product sku API.
	 * @name opt_rewards_on_add_update_product
	*/
	
	public function opt_rewards_on_add_update_product($productId, $post, $update){

	    if ($post->post_status != 'publish' || $post->post_type != 'product') {
	        return;
	    }

	    if (!$product = wc_get_product( $post )) {
	        return;
	    }

	    $product = wc_get_product( $productId);
        $category = $colorValue = $sizeValue = $brandValue = '';
	    $categories = wp_get_post_terms( $productId, 'product_cat', array( 'fields' => 'names' ) );
    	if(is_array($categories)){
    		$category = implode(",",$categories);
    	}
    	
    	$sku = $product->get_sku();
    	$productName = $product->get_title();
    	$type = $product->get_type();
    	$shortDescription = $product->get_short_description(); 
		$description = $product->get_description();
		$price = $product->get_price();

		$taxStatus = $product->get_tax_status();
        $taxClass = $product->get_tax_class();
		$parentId = $product->get_parent_id();

		$attributes = $product->get_attributes();

		foreach ($attributes as $taxonomy => $attribute_obj ) {
            
            $attribute_name = wc_attribute_label($taxonomy);
    		if (strpos($taxonomy, 'color') !== false ){
    			$colorValue = $product->get_attribute($taxonomy);
    		}
    		if (strpos($taxonomy, 'size') !== false ){
    			$sizeValue = $product->get_attribute($taxonomy);
    		}
    		if (strpos($taxonomy, 'brand') !== false ){
    			$brandValue = $product->get_attribute($taxonomy);
    		}	
		}

		$opt_operation = new Opt_Rewards_Api_Operations();

		if($type == 'variable'){
			if( $product->has_child() ) { 
				$variations = $product->get_children();

				foreach ($variations as $key => $variationId) {

					wc_delete_product_transients( $variationId );
					$variation = wc_get_product( $variationId);
					$variation_parentId = $variation->get_parent_id();
					$variation_categories = wp_get_post_terms( $variation_parentId, 'product_cat', array( 'fields' => 'names' ) );
			    	if(is_array($variation_categories)){
			    		$variation_category = implode(",",$variation_categories);
			    	}
			    	
			    	$variation_sku = $variation->get_sku();
			    	$variation_productName = $variation->get_title();
			    	$variation_type = $variation->get_type();
			    	$variation_shortDescription = $variation->get_description(); 
					$variation_price = $variation->get_price(); 

					$variation_taxStatus = $variation->get_tax_status();
			        $variation_taxClass = $variation->get_tax_class();

			        $variation_colorValue = "";
			        $variation_sizeValue = "";
			        $variation_brandValue = "";


					$opt_operation->addUpdateProduct(
				        $variationId,
				        $variation_category,
				        $variation_sku,
				        $variation_productName,
				        $variation_type,
				        $variation_shortDescription,
				        $variation_price,
				        $variation_taxStatus,
				        $variation_taxClass,
				        $variation_colorValue,
				        $variation_sizeValue,
				        $variation_brandValue,
				        $sku
				    );

				}
			}
		}else{
			$opt_operation->addUpdateProduct(
		        $productId,
		        $category,
		        $sku,
		        $productName,
		        $type,
		        $shortDescription,
		        $price,
		        $taxStatus,
		        $taxClass,
		        $colorValue,
		        $sizeValue,
		        $brandValue,
		        $parentId
		    );
		}
	}

	/**
	 * This function is used for stops WooCommerce default email.
	 * @name opt_rewards_stops_woo_emails
	*/  

	public function opt_rewards_stops_woo_emails( $email_class ) {
        
        $opt_data = new Opt_Rewards_Data();
        if ($opt_data->getEnableReceiptEmail() == '1') {
			/**
			 * Hooks for sending emails during store events
			 **/
			remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
			remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );
			remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );
			
			/*-- New order emails --*/
			remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_pending_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_failed_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_cancelled_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );

			/*-- On hold order emails --*/
			remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_On_Hold_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_On_Hold_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_On_Hold_Order'], 'trigger' ) );

			
			/*-- Processing order emails --*/
			remove_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
			
			/*-- Completed order emails --*/
			remove_action( 'woocommerce_order_status_completed_notification', array( $email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger' ) );

			/*-- Refund order emails --*/
			remove_action( 'woocommerce_order_fully_refunded_notification', array( $email_class->emails['WC_Email_Customer_Refunded_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_partially_refunded_notification', array( $email_class->emails['WC_Email_Customer_Refunded_Order'], 'trigger' ) );

			/*-- Cancel order emails --*/
			remove_action( 'woocommerce_order_status_processing_to_cancelled_notification', array( $email_class->emails['WC_Email_Cancelled_Order'], 'trigger' ) );
			remove_action( 'woocommerce_order_status_on-hold_to_cancelled_notification', array( $email_class->emails['WC_Email_Cancelled_Order'], 'trigger' ) );


	   }		
    }

}