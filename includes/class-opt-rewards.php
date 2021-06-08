<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://app.optculture.com/
 * @package    opt-rewards
 * @subpackage opt-rewards/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Opt_Rewards
 * @subpackage Opt_Rewards/includes
 * @author     Optculture Team
 */
class Opt_Rewards {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Points_Rewards_For_Woocommerce_Loader  $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'OPT_REWARDS_VERSION' ) ) {

			$this->version = OPT_REWARDS_VERSION;
			
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'opt-rewards';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_opt_rewards_tables();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Opt_Rewards_Loader. Orchestrates the hooks of the plugin.
	 * - Opt_Rewards_Admin. Defines all hooks for the admin area.
	 * - Opt_Rewards_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
        
        /**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-opt-rewards-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-opt-rewards-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-opt-rewards-public.php';

		$this->loader = new Opt_Rewards_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Opt_Rewards_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'opt_rewards_admin_menu', 10, 2 );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'opt_rewards_custom_user_profile_fields', 15 );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'opt_rewards_custom_user_profile_fields', 15 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Opt_Rewards_Public( $this->get_plugin_name(), $this->get_version() );
		

		if ( $this->opt_rewards_is_plugin_enable() ) {

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'init', $plugin_public, 'opt_rewards_public_api_setting' );
			$this->loader->add_action( 'init', $plugin_public, 'opt_rewards_public_api_data' );
			$this->loader->add_action( 'init', $plugin_public, 'opt_rewards_public_api_operations' );
			$this->loader->add_action( 'init', $plugin_public, 'opt_rewards_create_log_directory' );
			$this->loader->add_action( 'template_redirect', $plugin_public, 'opt_rewards_reward_page_check' );
			$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'opt_rewards_woo_plugin_template', 10, 3);
			$this->loader->add_action( 'woocommerce_register_form_start', $plugin_public, 'opt_rewards_user_registration_add_name_fields', 15 );
			$this->loader->add_action( 'woocommerce_register_form', $plugin_public, 'opt_rewards_user_registration_add_extra_fields', 15 );
            $this->loader->add_filter( 'woocommerce_process_registration_errors', $plugin_public, 'opt_rewards_user_registration_validate_name_fields');
            $this->loader->add_action( 'woocommerce_register_post', $plugin_public, 'opt_rewards_user_registration_validate_extra_fields', 10, 3 );
			$this->loader->add_action( 'woocommerce_created_customer', $plugin_public, 'opt_rewards_user_registration_save_extra_fields');
			$this->loader->add_action( 'woocommerce_register_post', $plugin_public, 'opt_rewards_beforeCreateAccount');
			$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'opt_rewards_add_mobile_number_to_edit_account_form');
			$this->loader->add_action( 'woocommerce_save_account_details_errors', $plugin_public, 'opt_rewards_mobile_number_field_validation', 20, 1);
			$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'opt_rewards_my_account_saving_mobile_number', 20, 1);
			$this->loader->add_action( 'woocommerce_save_account_details_errors', $plugin_public, 'opt_rewards_updatecontact', 30, 1);
			$this->loader->add_action( 'woocommerce_after_save_address_validation', $plugin_public, 'opt_rewards_update_billingaddress', 10, 3);
			$this->loader->add_action( 'woocommerce_form_field_args', $plugin_public, 'opt_rewards_wc_form_field_args', 10, 3);
			$this->loader->add_filter( 'woocommerce_checkout_fields', $plugin_public, 'opt_rewards_override_checkout_fields');
            $this->loader->add_action( 'init', $plugin_public, 'opt_rewards_add_reward_program_endpoint');
            $this->loader->add_filter( 'query_vars', $plugin_public, 'opt_rewards_reward_program_query_vars');
            $this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public, 'opt_rewards_add_reward_program_link_my_account');
            $this->loader->add_action( 'woocommerce_account_reward-program_endpoint', $plugin_public, 'opt_rewards_reward_program_content');
            $this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'opt_rewards_enrollment_from_thankyou');
            $this->loader->add_action( 'woocommerce_before_order_notes', $plugin_public, 'opt_rewards_join_rewards_checkout_field');
            $this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'opt_rewards_join_rewards_checkout_field_update_order_meta');
            $this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'opt_rewards_checkout_field_validation');
            $this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'opt_rewards_digital_receipt_for_neworder');
            $this->loader->add_action( 'woocommerce_order_status_cancelled', $plugin_public, 'opt_rewards_digital_receipt_for_cancelorder', 10, 1);
            $this->loader->add_action( 'woocommerce_order_refunded', $plugin_public, 'opt_rewards_digital_receipt_for_refundorder', 10, 2);
            $this->loader->add_action('wp_ajax_loyalty_inquiry', $plugin_public, 'opt_rewards_get_loyalty_inquiry');
            $this->loader->add_action('wp_ajax_nopriv_loyalty_inquiry', $plugin_public, 'opt_rewards_get_loyalty_inquiry');
            $this->loader->add_action('wp_ajax_loyalty_apply', $plugin_public, 'opt_rewards_apply_loyalty_balance');
            $this->loader->add_action('wp_ajax_nopriv_loyalty_apply', $plugin_public, 'opt_rewards_apply_loyalty_balance');
            $this->loader->add_action('wp_ajax_promotion_apply', $plugin_public, 'opt_rewards_apply_loyalty_promotions');
            $this->loader->add_action('wp_ajax_nopriv_promotion_apply', $plugin_public, 'opt_rewards_apply_loyalty_promotions');
            $this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'opt_rewards_add_loyalty_coupon', 10, 3);   
            $this->loader->add_filter( 'woocommerce_get_shop_coupon_data', $plugin_public, 'opt_rewards_set_loyalty_coupon', 10, 3);
            $this->loader->add_filter( 'woocommerce_cart_totals_coupon_label', $plugin_public, 'opt_rewards_set_loyalty_label', 10, 3);
            $this->loader->add_action( 'woocommerce_removed_coupon', $plugin_public, 'opt_rewards_removed_loyalty_coupon', 10, 1);
            $this->loader->add_action( 'wp_head', $plugin_public, 'opt_rewards_add_loader', 10, 2); 
            $this->loader->add_action( 'woocommerce_update_cart_action_cart_updated', $plugin_public, 'opt_rewards_on_cart_updated', 20, 1);
            $this->loader->add_action( 'woocommerce_cart_item_removed', $plugin_public, 'opt_rewards_on_removed_cart_item', 10, 2);
            $this->loader->add_action( 'woocommerce_after_checkout_form', $plugin_public, 'opt_rewards_checkout_promotions', 10, 1);
            $this->loader->add_action('wp_ajax_opt_login', $plugin_public, 'optrewards_login_authentication');
            $this->loader->add_action('wp_ajax_nopriv_opt_login', $plugin_public, 'optrewards_login_authentication');
            $this->loader->add_action('wp_ajax_otp_code', $plugin_public, 'optrewards_redeem_verification_code_authentication');
            $this->loader->add_action('wp_ajax_nopriv_otp_code', $plugin_public, 'optrewards_redeem_verification_code_authentication');
            $this->loader->add_action('wp_ajax_create_otp_code', $plugin_public, 'optrewards_create_verification_code_authentication');
            $this->loader->add_action('wp_ajax_nopriv_create_otp_code', $plugin_public, 'optrewards_create_verification_code_authentication');
            $this->loader->add_action('wp_ajax_otp_promotion_code', $plugin_public, 'optrewards_promotion_verification_code_authentication');
            $this->loader->add_action('wp_ajax_nopriv_otp_promotion_code', $plugin_public, 'optrewards_promotion_verification_code_authentication');
            $this->loader->add_action('wp_ajax_verification_api', $plugin_public, 'optrewards_verification_code_api_call');
            $this->loader->add_action('wp_ajax_nopriv_verification_api', $plugin_public, 'optrewards_verification_code_api_call');
            $this->loader->add_action('wp_ajax_create_verification_api', $plugin_public, 'optrewards_create_verification_code_api_call');
            $this->loader->add_action('wp_ajax_nopriv_create_verification_api', $plugin_public, 'optrewards_create_verification_code_api_call');
            $this->loader->add_action('wp_ajax_resend_otp_code', $plugin_public, 'optrewards_resend_verification_code_api_call');
            $this->loader->add_action('wp_ajax_nopriv_resend_otp_code', $plugin_public, 'optrewards_resend_verification_code_api_call');
            $this->loader->add_action('wp_ajax_enroll_resend_otp_code', $plugin_public, 'optrewards_enroll_resend_verification_code_api_call');
            $this->loader->add_action('wp_ajax_nopriv_enroll_resend_otp_code', $plugin_public, 'optrewards_enroll_resend_verification_code_api_call');
            $this->loader->add_action('wp_ajax_resend_otp', $plugin_public, 'optrewards_resend_otp_code_api_call');
            $this->loader->add_action('wp_ajax_nopriv_resend_otp', $plugin_public, 'optrewards_resend_otp_code_api_call');
            $this->loader->add_action( 'save_post', $plugin_public, 'opt_rewards_on_add_update_product', 10, 3);
            $this->loader->add_action( 'woocommerce_email', $plugin_public, 'opt_rewards_stops_woo_emails' );

		}
	}


	/**
	 * Check is plugin is enable.
	 *
	 * @return true/false
	 * @since    1.0.0
	 */
	public function opt_rewards_is_plugin_enable() {
		$is_enable = false;
		$opt_rewards_enable = '';
		$opt_rewards_enable = get_option( 'optrewards_general_enable' );
	
		if ( ! empty( $opt_rewards_enable ) && 1 == $opt_rewards_enable ) {
			$is_enable = true;
		}
		return $is_enable;
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Rewardeem_woocommerce_Points_Rewards_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Add custom tables for plugins.
	 *
	 * @since    1.0.0
	 */
	public function define_opt_rewards_tables() {
		global $wpdb;
		$loyalty_table = $wpdb->prefix . "loyalty_lock";

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `$loyalty_table` (
            `lock_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`failed_requests` int(11) NOT NULL,
			`lock_time` text DEFAULT NULL,
			`lock_status` varchar(255) NOT NULL DEFAULT 'unlocked',
			PRIMARY KEY (`lock_id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );
	       
	}

}
