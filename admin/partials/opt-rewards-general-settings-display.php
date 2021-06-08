<?php
/**
 * General Settings Template
 *
 * @link       https://app.optculture.com/
 * @package    opt-rewards
 * @subpackage opt-rewards/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action_url = get_admin_url().'admin.php?page=opt-rewards-setting'; 

require_once OPT_REWARDS_DIR_PATH . 'admin/partials/opt-rewards-general-settings-action.php';

?>
<div class="wrap woocommerce" id="mwb_rwpr_setting_wrapper">
		<?php
			if(isset($_SESSION['success'])){ ?>
			
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e($_SESSION['success']); ?></strong></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notices.', 'opt-rewards' ); ?></span>
					</button>
				</div>
				<?php
				session_unset();
			}
					
	   ?>
	
<form enctype="multipart/form-data" action="<?php echo $action_url; ?>" id="mainform" method="post">
		<div class="opt_rw_table">
			<div class="opt_rw_general_wrapper">
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title">
						<h3>OptRewards Settings</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_general_enable">Enabled</label>
							</div>
							<div class="opt_rw_general_content">
								<label for="optrewards_general_enable">
									<select style="" value="Yes" name="optrewards_general_enable" id="optrewards_general_enable" class="input-text">
										<option value="1" <?php if(get_option('optrewards_general_enable') == 1) {echo 'selected';} ?>>Yes</option>
										<option value="0" <?php if(get_option('optrewards_general_enable') == 0) {echo 'selected';} ?>>No</option>
									</select>
									<p class="note"></p>
								</label>
							</div>
						</div>
						<div class="settings_enable">

							<div class="opt_rw_general_row">
								<div class="opt_wpr_general_label">
									<label for="optrewards_general_username">Username</label>
								</div>
								<div class="opt_rw_general_content">
									<label for="optrewards_general_username">
										<input type="text" style="" value="<?php if(get_option('optrewards_general_username') != ''){ ?><?php echo get_option('optrewards_general_username'); ?><?php } ?>" name="optrewards_general_username" id="optrewards_general_username" class="input-text" required="">
										<p class="note"></p>
									</label>
								</div>
							</div>
							<div class="opt_rw_general_row">
								<div class="opt_wpr_general_label">
									<label for="optrewards_general_organization_id">Organization Id</label>
								</div>
								<div class="opt_rw_general_content">
									<label for="optrewards_general_organization_id">
										<input type="text" style="" value="<?php if(get_option('optrewards_general_organization_id') != ''){ ?><?php echo get_option('optrewards_general_organization_id'); ?><?php } ?>" name="optrewards_general_organization_id" id="optrewards_general_organization_id" class="input-text" required="">
										<p class="note"></p>
									</label>
								</div>
							</div>
							<div class="opt_rw_general_row">
								<div class="opt_wpr_general_label">
									<label for="optrewards_general_token">Token</label>
								</div>
								<div class="opt_rw_general_content">
									<label for="optrewards_general_token">
										<input type="text" style="" value="<?php if(get_option('optrewards_general_token') != ''){ ?><?php echo get_option('optrewards_general_token'); ?><?php } ?>" name="optrewards_general_token" id="optrewards_general_token" class="input-text" required="">
										<p class="note"></p>
									</label>
								</div>
							</div>
							<div class="opt_rw_general_row">
								<div class="opt_wpr_general_label">
									<label for="optrewards_general_store_number">Store Number</label>
								</div>
								<div class="opt_rw_general_content">
									<label for="optrewards_general_store_number">
										<input type="text" style="" value="<?php if(get_option('optrewards_general_store_number') != ''){ ?><?php echo get_option('optrewards_general_store_number'); ?><?php } ?>" name="optrewards_general_store_number" id="optrewards_general_store_number" class="input-text" required="">
										<p class="note"></p>
									</label>
								</div>
							</div>
							<div class="opt_rw_general_row">
								<div class="opt_wpr_general_label">
									<label for="optrewards_active_developer_mode">Enable Developer Mode</label>
								</div>
								<div class="opt_rw_general_content">
									<label for="optrewards_active_developer_mode">
									<select name="optrewards_active_developer_mode" id="optrewards_active_developer_mode" class="input-text">
										<option value="1" <?php if(get_option('optrewards_active_developer_mode') == 1) {echo 'selected';} ?>>Yes</option>
										<option value="0" <?php if(get_option('optrewards_active_developer_mode') == 0) {echo 'selected';} ?>>No</option>
									</select>
									
								</label>
								</div>
							</div>

						</div>	
					</div>
				</div>
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title" id="togggle_2">
						<h3>Mobile Number For Enrollment</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel" id="sec_2">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_general_phone_setting_phone_active">Is Mandatory?</label>
							</div>
							<div class="opt_rw_general_content">
								<label for="optrewards_general_phone_setting_phone_active">
									<select name="optrewards_general_phone_setting_phone_active" id="optrewards_general_phone_setting_phone_active" class="input-text">
										<option value="1" <?php if(get_option('optrewards_general_phone_setting_phone_active') == 1) {echo 'selected';} ?>>Yes</option>
										<option value="0" <?php if(get_option('optrewards_general_phone_setting_phone_active') == 0) {echo 'selected';} ?>>No</option>
									</select>
									<p class="note">If you choose "NO" here, you need to disable "Restrict Membership per Customer with Mobile No" in Loyalty program.</p>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title" id="togggle_2">
						<h3>Address For Enrollment</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel" id="sec_2">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_general_address_settings_active">Is Mandatory?</label>
							</div>
							<div class="opt_rw_general_content">
								
									<select name="optrewards_general_address_settings_active" id="optrewards_general_address_settings_active" class="input-text">
										<option value="1" <?php if(get_option('optrewards_general_address_settings_active') == 1) {echo 'selected';} ?>>Yes</option>
										<option value="0" <?php if(get_option('optrewards_general_address_settings_active') == 0) {echo 'selected';} ?>>No</option>
									</select>
									<p class="note">If you choose "NO" here, then address fields not display in registration page.</p>
								
							</div>
						</div>
					</div>
				</div>
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title" id="togggle_3">
						<h3>Security</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel" id="sec_3">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_general_security_authentication">Authentication Method</label>
							</div>
							<div class="opt_rw_general_content">
								<label for="optrewards_general_security_authentication">
									<select style="" name="optrewards_general_security_authentication" id="optrewards_general_security_authentication" class="input-text">
										<option value="none" <?php if(get_option('optrewards_general_security_authentication') == 'none') {echo 'selected';} ?>>None</option>
										<option value="otp_code" <?php if(get_option('optrewards_general_security_authentication') == 'otp_code') {echo 'selected';} ?>>Verification Code</option>
										<option value="login" <?php if(get_option('optrewards_general_security_authentication') == 'login') {echo 'selected';} ?>>Check with Signed in details</option>
									</select>
									<p class="note">Types of authentication method and their working is explained below.
										<br>"None": no authentication will be done. "Verification Code": During enrollment and redemption, a verification code will be sent by email and SMS to user. User will need to enter the same on the screen.
										<br>"Check with Signed in details": During redemption, user's email address or phone number entered will be checked with the WooCommerce signed-in user's details. If not signed in, there will be a prompt. If they do not match, redemption will fail.</p>
								</label>
							</div>
						</div>
				
						<div class="opt_rw_general_row exp_time" style="display: none;">
							<div class="opt_wpr_general_label">
								<label for="optrewards_verification_code_expire_time">Verification Code Expire Time (minutes)</label>
							</div>
							<div class="opt_rw_general_content">
									<input type="text" style="" value="<?php if(get_option('optrewards_verification_code_expire_time') != ''){ ?><?php echo get_option('optrewards_verification_code_expire_time'); ?><?php } ?>" name="optrewards_verification_code_expire_time" id="optrewards_verification_code_expire_time" class="input-text">
									<p class="note">After this time duration need to generate new verification code.</p>
							</div>
						</div>
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_security_phone_failures">Maximum Phone Number Failures to Lockout</label>
							</div>
							<div class="opt_rw_general_content">
									<input type="text" style="" value="<?php if(get_option('optrewards_security_phone_failures') != ''){ ?><?php echo get_option('optrewards_security_phone_failures'); ?><?php } ?>" name="optrewards_security_phone_failures" id="optrewards_security_phone_failures" class="input-text" required="">
									<p class="note">Use 0 to disable account locking.</p>
							</div>
						</div>
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_security_phone_lockout_time">Lockout Time (minutes)</label>
							</div>
							<div class="opt_rw_general_content">
								<label for="optrewards_security_phone_lockout_time">
									<input type="text" style="" value="<?php if(get_option('optrewards_security_phone_lockout_time') != ''){ ?><?php echo get_option('optrewards_security_phone_lockout_time'); ?><?php } ?>" name="optrewards_security_phone_lockout_time" id="optrewards_security_phone_lockout_time" class="input-text" required="">
									<p class="note">Account will be unlocked after provided time.</p>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title" id="togggle_4">
						<h3>Content For Join Now Page</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel" id="sec_4">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_general_join_page_content">Html Content</label>
							</div>
							<div class="opt_rw_general_content">
								<label for="opt_wpr_general_text_points" class="opt_wpr_label">
									<textarea name="optrewards_general_join_page_content" id="optrewards_general_join_page_content" class="input-text" rows="7"><?php if(get_option('optrewards_general_join_page_content') != ''){ ?><?php echo get_option('optrewards_general_join_page_content'); ?><?php } ?></textarea>
								</label>
								<p class="note">Below is the deafult HTML content with structure: &lt;span class="content_head"&gt;New to the Rewards program? Join from here! Membership is FREE and allows you to:&lt;/span&gt; &lt;ul class="benifit_prog"&gt; &lt;li&gt;Earn 1 point for every $1 you spend&lt;/li&gt; &lt;li&gt;Receive a $2 Reward every 100 points&lt;/li&gt; &lt;li&gt;Check out faster&lt;/li&gt; &lt;li&gt;Redeem your rewards&lt;/li&gt; &lt;/ul&gt;</p>
							</div>
						</div>
					</div>
				</div>
				<div class="opt_rw_general_row_wrap">
					<div class="opt_wpr_general_sign_title" id="togggle_4">
						<h3>Receipt Options</h3>
						<i class="fa fa-angle-down"></i>
					</div>
					<div class="opt_row_pannel" id="sec_4">
						<div class="opt_rw_general_row">
							<div class="opt_wpr_general_label">
								<label for="optrewards_enable_oc_emailreceipt">Send Receipt by Email</label>
							</div>
							<div class="opt_rw_general_content">
								<select style="" value="Yes" name="optrewards_enable_oc_emailreceipt" id="optrewards_enable_oc_emailreceipt" class="input-text">
										<option value="1" <?php if(get_option('optrewards_enable_oc_emailreceipt') ==  1) {echo 'selected';} ?>>Yes</option>
										<option value="0" <?php if(get_option('optrewards_enable_oc_emailreceipt') == 0) {echo 'selected';} ?>>No</option>
								</select>
								<p class="note">This option, when enabled, stops WooCommerce Order success emails.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<p class="submit">
					<input type="submit" value="Save changes" class="button-primary opt_save_save_changes" name="opt_save_general">
				</p>
			</div>
		</div>
	</form>
</div>