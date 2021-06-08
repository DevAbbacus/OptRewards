<?php
/**
 * General Settings Action Template
 *
 * @link       https://app.optculture.com/
 * @package    opt-rewards
 * @subpackage opt-rewards/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
session_start();
$action_url = get_admin_url().'admin.php?page=opt-rewards-setting'; 

function opt_rewards_join_reward_page($plugin_enable) {
     
    $post_id = -1;
    
    $current_user = wp_get_current_user();
    $author_id = $current_user->ID;
    $page_slug = 'join-reward-program';
    $page_title = 'Reward Program';
    $page_con = isset($_POST['optrewards_general_join_page_content']) ? $_POST['optrewards_general_join_page_content'] : '';
    $myaccount_url = site_url('/my-account/');
    $page_btn  = '<p><a href="'.$myaccount_url.'" class="button optjoin-btn">Join</a></p>';
    $page_content = $page_con."".$page_btn;
    /*--Check if doen't exists a page with page slug. --*/
    $page_id = post_exists_by_slug( $page_slug );
    if(!$page_id && $plugin_enable == 1) {
        $post_id = wp_insert_post(
            array(
                
                'post_name'         =>   $page_slug,
                'post_title'        =>   $page_title,
                'post_content'      =>   $page_content,
                'post_status'       =>   'publish',
                'post_type'         =>   'page',
                'comment_status'    =>   'closed',
                'ping_status'       =>   'closed',
                'post_author'       =>   $author_id,
            )
        );
    } else {
        if($plugin_enable == 0){
            wp_update_post(array(
		        'ID'    =>  $page_id,
		        'post_status'   =>  'draft'
            ));
        }else { 
            if(get_post_status ( $page_id ) == 'draft'){
               wp_update_post(array(
		        'ID'    =>  $page_id,
		        'post_status'   => 'publish',
               ));
            }	
        }
    }
 
} 

 /**
 * post_exists_by_slug.
 *
 * @return mixed boolean false if no post exists; post ID otherwise.
 */
function post_exists_by_slug( $page_slug ) {
    $args_posts = array(
        'post_type'      => 'page',
        'post_status' => array('publish', 'draft', 'auto-draft'),
        'name'           => $page_slug,
        'posts_per_page' => 1,
    );
    $loop_posts = new WP_Query( $args_posts );
    if ( ! $loop_posts->have_posts() ) {
        return false;
    } else {
        $loop_posts->the_post();
        return $loop_posts->post->ID;
    }
}

if(isset($_POST['opt_save_general'])){
	
	
	$optrewards_general_enable	= $_POST['optrewards_general_enable'];
	$optrewards_general_username	= $_POST['optrewards_general_username'];			
	$optrewards_general_organization_id	= $_POST['optrewards_general_organization_id'];		
	$optrewards_general_token		= $_POST['optrewards_general_token'];
	$optrewards_general_store_number = $_POST['optrewards_general_store_number'];
    $optrewards_active_developer_mode		= $_POST['optrewards_active_developer_mode'];
	
	$optrewards_general_phone_setting_phone_active = $_POST['optrewards_general_phone_setting_phone_active'];
	$optrewards_general_address_settings_active = $_POST['optrewards_general_address_settings_active'];

	$optrewards_general_security_authentication = $_POST['optrewards_general_security_authentication'];
	$optrewards_verification_code_expire_time = $_POST['optrewards_verification_code_expire_time'];
	$optrewards_security_phone_failures = $_POST['optrewards_security_phone_failures'];
	$optrewards_security_phone_lockout_time = $_POST['optrewards_security_phone_lockout_time'];
	$optrewards_general_join_page_content = $_POST['optrewards_general_join_page_content'];
	
	$optrewards_enable_oc_emailreceipt = $_POST['optrewards_enable_oc_emailreceipt'];
    
    $update_option = 'false';

	if($optrewards_general_enable != ''){
		$opt_enable = update_option('optrewards_general_enable', $optrewards_general_enable);
		$update_option = 'true';

	    opt_rewards_join_reward_page($optrewards_general_enable);
		
	}

	if($optrewards_general_username != ''){
		$opt_username = update_option('optrewards_general_username', $optrewards_general_username);	
		$update_option = 'true';
	}

	if($optrewards_general_organization_id != ''){
		$opt_organization_id = update_option('optrewards_general_organization_id', $optrewards_general_organization_id);
		$update_option = 'true';
	}

	if($optrewards_general_token != ''){
		$opt_token = update_option('optrewards_general_token', $optrewards_general_token);
		$update_option = 'true';
	}

	if($optrewards_general_store_number != ''){
		$opt_store_number = update_option('optrewards_general_store_number', $optrewards_general_store_number);
		$update_option = 'true';
	}
    
    if($optrewards_active_developer_mode != ''){
		$opt_active_developer_mode = update_option('optrewards_active_developer_mode', $optrewards_active_developer_mode);
		$update_option = 'true';
	}

	if($optrewards_general_phone_setting_phone_active != ''){
		$opt_phone_setting_phone_active = update_option('optrewards_general_phone_setting_phone_active', $optrewards_general_phone_setting_phone_active);
		$update_option = 'true';
	}

	if($optrewards_general_address_settings_active != ''){
		$opt_address_settings_active = update_option('optrewards_general_address_settings_active', $optrewards_general_address_settings_active);
		$update_option = 'true';
	}


	if($optrewards_general_security_authentication != ''){
		$opt_security_authentication = update_option('optrewards_general_security_authentication', $optrewards_general_security_authentication);
		$update_option = 'true';
	}

	if($optrewards_verification_code_expire_time != ''){
		$opt_verification_code_expire_time = update_option('optrewards_verification_code_expire_time', $optrewards_verification_code_expire_time);
		$update_option = 'true';
	}
	

	if($optrewards_security_phone_failures != ''){
		$opt_security_phone_failures = update_option('optrewards_security_phone_failures', $optrewards_security_phone_failures);
		$update_option = 'true';
	}

	if($optrewards_security_phone_failures != ''){
		$opt_security_phone_failures = update_option('optrewards_security_phone_failures', $optrewards_security_phone_failures);
		$update_option = 'true';
	}

	if($optrewards_security_phone_lockout_time != ''){
		$opt_security_phone_lockout_time = update_option('optrewards_security_phone_lockout_time', $optrewards_security_phone_lockout_time);
		$update_option = 'true';
	}

	if($optrewards_general_join_page_content != ''){
		$opt_join_content = stripcslashes( $optrewards_general_join_page_content );
		$opt_join_page_content = update_option('optrewards_general_join_page_content', $opt_join_content);
		$update_option = 'true';
	}


	if($optrewards_enable_oc_emailreceipt != ''){
		$opt_single_promotion = update_option('optrewards_enable_oc_emailreceipt', $optrewards_enable_oc_emailreceipt);
		$update_option = 'true';
	}

	if($update_option == 'true'){
		$_SESSION['success'] = "OptRewards Settings Updated.";
		wp_redirect($action_url);
		exit;
	}else{
		wp_redirect($action_url);
	}	
	
}


?>