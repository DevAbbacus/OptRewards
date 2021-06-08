<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://app.optculture.com/
 * @package    opt-rewards
 * @subpackage opt-rewards/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/admin
 * @author     Optculture Team
 */

class Opt_Rewards_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param  string $hook    The name of page.
	 */
	public function enqueue_styles( $hook ) {
		if ( isset( $_GET['page'] ) && 'opt-rewards-setting' == $_GET['page'] ) {
			wp_enqueue_style( $this->plugin_name, OPT_REWARDS_DIR_URL . 'admin/css/opt-rewards-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param  string $hook  The name of page.
	 */
	public function enqueue_scripts( $hook ) {

			if ( isset( $_GET['page'] ) && 'opt-rewards-setting' == $_GET['page'] ) {
			
				wp_enqueue_script( 'admin_js', OPT_REWARDS_DIR_URL . 'admin/js/opt-rewards-admin.js', array( 'jquery' ), WC_VERSION, true );
				
			}

	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 *
	 * @name opt_rewards_admin_menu()
	 */
	public function opt_rewards_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'OptRewards', 'opt-rewards' ), __( 'OptRewards', 'popt-rewards' ), 'manage_options', 'opt-rewards-setting', array( $this, 'opt_rewards_admin_setting' ) );
	}

	/**
	 * This is function is used for the validating the data.
	 *
	 * @name opt_rewards_allowed_html
	 */
	public function opt_rewards_allowed_html() {
		$allowed_tags = array(
			'span' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
				'data-tip' => array(),
			),
			'min' => array(),
			'max' => array(),
			'class' => array(),
			'style' => array(),
			'<br>'  => array(),
		);
		return $allowed_tags;

	}

	/**
	 * Including a File for displaying the required setting page for setup the plugin
	 *
	 * @name opt_rewards_admin_setting()
	 */
	public function opt_rewards_admin_setting() {
		require_once OPT_REWARDS_DIR_PATH . 'admin/partials/opt-rewards-general-settings-display.php';
	}

	/**
	 * This function is used for add extra fields in user profile.
	 * @name opt_rewards_custom_user_profile_fields
	 */
	public function opt_rewards_custom_user_profile_fields($user){
	  ?>
	    <h3>Reward Program Information</h3>
	    
	    <table class="form-table">
	    	<tr>
	            <th><label for="user_reward_program_enable">Reward Program</label></th>
	            <td>
	                <input type="checkbox" class="switch_chk" name="user_reward_program_enable" value="<?php echo get_user_meta( $user->ID, 'user_reward_program_enable', true ); ?>" id="user_reward_program_enable" <?php if(get_user_meta( $user->ID, 'user_reward_program_enable', true) == 1) { echo "checked='checked'"; }?> /><br />
	            </td>
	        </tr>
	        <tr>
	            <th><label for="user_phone_no">Mobile No</label></th>
	            <td>
	                <input type="text" class="regular-text" name="user_phone_no" value="<?php echo get_user_meta( $user->ID, 'user_phone_no', true); ?>" id="user_phone_no" /><br />
	            </td>
	        </tr>
	        <tr>
	            <th><label for="user_membership_no">Membership Number</label></th>
	            <td>
	                <input type="text" class="regular-text" name="user_membership_no" value="<?php echo get_user_meta( $user->ID, 'user_membership_no', true); ?>" id="user_membership_no" /><br />
	            </td>
	        </tr>
	       
	    </table>
	  <?php
	}

}
