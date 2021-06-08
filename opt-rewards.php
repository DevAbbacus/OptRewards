<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://app.optculture.com/
 * @package           opt-rewards
 *
 * @wordpress-plugin
 * Plugin Name:       OptRewards
 * Description:       This woocommerce extension allow customer to reward their customers with loyalty points.
 * Version:           1.0
 * Author:            Optculture Team
 * Author URI:        https://app.optculture.com/
 * Plugin URI:        https://app.optculture.com/
 * Text Domain:       opt-rewards
 *
 */

/*-- If this file is called directly, abort. --*/
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/*-- To Activate plugin only when WooCommerce is active. --*/
$activated = false;

/*-- Check if WooCommerce is active. --*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	$activated = true;
}
if ( $activated ) {
	/**
	 * Define the constatant of the plugin.
	 *
	 * @name define_opt_rewards_constants.
	 */
	function define_opt_rewards_constants() {

		opt_rewards_constants( 'OPT_REWARDS_VERSION', '1.0.0' );
		opt_rewards_constants( 'OPT_REWARDS_DIR_PATH', plugin_dir_path( __FILE__ ) );
		opt_rewards_constants( 'OPT_REWARDS_DIR_URL', plugin_dir_url( __FILE__ ) );
		opt_rewards_constants( 'OPT_REWARDS_URL', admin_url() );
		opt_rewards_constants( 'OPT_REWARDS_WOO_TEMPLATES_DIR', OPT_REWARDS_DIR_PATH . 'public/woocommerce/' );
		opt_rewards_constants( 'OPT_REWARDS_WP_CONTENT_NAME', '/' . wp_basename( WP_CONTENT_DIR ) );
		opt_rewards_constants( 'OPT_WP_ROOT_DIR', substr( WP_CONTENT_DIR, 0, strlen( WP_CONTENT_DIR ) - strlen( OPT_REWARDS_WP_CONTENT_NAME ) ) );
		
	}


	/**
	 * Callable function for defining plugin constants.
	 *
	 * @name opt_rewards_constants.
	 * @param string $key key of the constant.
	 * @param string $value value of the constant.
	 */
	function opt_rewards_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}



	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-opt-rewards.php';

	
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 */
	function run_opt_rewards() {
		define_opt_rewards_constants();
		$plugin = new Opt_Rewards();
		$plugin->run();

	}
	run_opt_rewards();

	/*-- Add settings link on plugin page. --*/
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'opt_rewards_settings_link' );

	/**
	 * Settings tab of the plugin.
	 *
	 * @name opt_rewards_settings_link
	 * @param array $links array of the links.
	 * @since    1.0.0
	 */
	function opt_rewards_settings_link( $links ) {

		$my_link = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=opt-rewards-setting' ) . '">' . esc_html__( 'Settings', 'opt-rewards' ) . '</a>',
		);

		return array_merge( $my_link, $links );
	}

	if ( ! function_exists( 'array_key_first' ) ) {
		/**
		 * This function is used to return the first key
		 *
		 * @name array_key_first
		 * @param array $arr optional parameter.
		 */
		function array_key_first( array $arr ) {
			foreach ( $arr as $key => $unused ) {
				return $key;
			}
			return null;
		}
	}

} else {


	/*-- WooCommerce is not active so deactivate this plugin.--*/
	add_action( 'admin_init', 'opt_rewards_activation_failure' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name opt_rewards_activation_failure
	 * 
	 */
	function opt_rewards_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}


	/*-- Add admin error notice. --*/
	add_action( 'admin_notices', 'opt_rewards_activation_failure_admin_notice' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name opt_rewards_activation_failure_admin_notice
	 *
	 */
	function opt_rewards_activation_failure_admin_notice() {
			
		unset( $_GET['activate'] );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to activate OptRewards.', 'opt-rewards' ); ?></p>
			</div>

			<?php
		}

	}

}
