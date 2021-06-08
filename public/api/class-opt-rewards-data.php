<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://app.optculture.com/
 * @since      1.0.0
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api
 * @author     Optculture Team
 */
class Opt_Rewards_Data {

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

    public function __construct() {
        $this->opt_enable = get_option('optrewards_general_enable');
        $this->opt_username = get_option('optrewards_general_username');
        $this->opt_organization_id = get_option('optrewards_general_organization_id');
        $this->opt_token = get_option('optrewards_general_token');
        $this->opt_store_number = get_option('optrewards_general_store_number');
        $this->opt_active_developer_mode = get_option('optrewards_active_developer_mode');
        $this->opt_phone_setting_phone_active = get_option('optrewards_general_phone_setting_phone_active');
        $this->opt_address_setting_active = get_option('optrewards_general_address_settings_active');
        $this->opt_security_authentication = get_option('optrewards_general_security_authentication');
        $this->opt_verification_code_expire_time = get_option('optrewards_verification_code_expire_time');
        $this->opt_security_phone_failures = get_option('optrewards_security_phone_failures');
        $this->opt_security_phone_lockout_time = get_option('optrewards_security_phone_lockout_time');
        $this->opt_general_join_page_content = get_option('optrewards_general_join_page_content');
        $this->opt_enable_oc_emailreceipt = get_option('optrewards_enable_oc_emailreceipt');
    }


    /**
     * @return mixed
     */
    public function getActive() {

        return $this->opt_enable;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->opt_username;
    }

    /**
     * @return mixed
     */
    public function getOrganizationId() {
        return $this->opt_organization_id;
    }


    /**
     * @return mixed
     */
    public function getToken() {
        return $this->opt_token;
    }

    /**
     * @return mixed
     */
    public function getStoreNumber() {
        return $this->opt_store_number;
    }
    
    /**
     * @return mixed
     */
    public function checkActiveMode()
    {   
        $developer_mode = $this->opt_active_developer_mode;
        if($developer_mode == 1){
            $mode = 'qcapp';
        }else{
            $mode = 'app';
        } 
        return $mode;
    }

    
    /**
     * @return mixed
     */
    public function getAddUpdateContactUrlApi() {
        return 'http://'.$this->checkActiveMode().'.optculture.com/subscriber/updateContactsOPT.mqrm';
    }
    
    /**
     * @return mixed
     */
    public function getPromotionsInquiryUrlApi() {
        return 'http://'.$this->checkActiveMode().'.optculture.com/subscriber/CouponCodeEnquiryRequestOPT.mqrm';
    }

    /**
     * @return mixed
     */
    public function getDigitalReceiptUrlApi() {
        return 'http://'.$this->checkActiveMode().'.optculture.com/subscriber/processReceipt.mqrm';
    }

    /**
     * @return mixed
     */
    public function getVerificationCodeUrlApi() {
        return 'http://'.$this->checkActiveMode().'.optculture.com/subscriber/OCOTPService.mqrm';
    }

    /**
     * @return mixed
     */
    public function getCreateUpdateProductUrlApi() {
        return 'http://'.$this->checkActiveMode().'.optculture.com/subscriber/updateSkuOPT.mqrm';
    }

    /**
     * @return mixed
     */
    public function getEnablePhone() {
        return $this->opt_phone_setting_phone_active;
    }


    /**
     * @return mixed
     */
    public function getEnableAddress() {
        return $this->opt_address_setting_active;
    }
    
    /**
     * @return mixed
     */
    public function getAuthenticationMethod() {
        return $this->opt_security_authentication;
    }
    
    /**
     * @return mixed
     */
    public function getOtpValidTime() {
        return $this->opt_verification_code_expire_time;
    }
    
    /**
     * @return mixed
     */
    public function getPhoneFailLimit() {
        return $this->opt_security_phone_failures;
    }
    
    /**
     * @return mixed
     */
    public function getPhoneLockoutTime() {
        return $this->opt_security_phone_lockout_time;
    }
    
    /**
     * @return mixed
     */
    public function getHtmlContentJoin() {
        return $this->opt_general_join_page_content;
    }
    
    /**
     * @return mixed
     */
    public function getEnablePromoSection() {
        return $this->opt_general_single_promotion;
    }

    /**
     * @return mixed
     */
    public function getEnableReceiptEmail() {
        return $this->opt_enable_oc_emailreceipt;
    }

    /**
     * Get the current Date And Time Based on Wordpress server time
     * @return string
     */
    public function getCurrentDate() {
        $current_date = current_time('Y-m-d H:i:s');
        //$current_date = date('Y-m-d H:i:s');
        return $current_date;
    }

     /**
     * @return string
     */

    public function generateRandomNumber() {
        $digits = 4;
        $randomNo =  rand(pow(10, $digits-1), pow(10, $digits)-1);
        $time = time();
        $ranString = $time.$randomNo;
        return $ranString;
    }
    
    /**
     * @return array
     */
    public function getCartItemsRequestArray() {
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        
        foreach($items as $item => $values) { 
            
            $_product =  wc_get_product( $values['product_id'] ); 
            $title = $_product->get_title();
            $type = $_product->get_type();
            $qty = $values['quantity'];
            if($type == 'variable'){
                $_variation =  wc_get_product( $values['variation_id'] ); 
                $sku = $_variation->get_sku();
                $price = $_variation->get_price();
                $regular_price = $_variation->get_regular_price();
                $sale_price = $_variation->get_sale_price();
            }else{
                $sku = $_product->get_sku();
                $price = $_product->get_price();
                $regular_price = $_product->get_regular_price();
                $sale_price = $_product->get_sale_price();
            }    

            $itemmerge[] = [
                "ITEMPRICE" => $price,
                "ITEMDISCOUNT" => $sale_price,
                "QUANTITY" => (string)$qty,
                "ITEMCODE" => $sku
            ];
        } 

        return $itemmerge;
    }

    /**
     * @return string
     */
    public function getCartItemsSubTotal() {
        global $woocommerce;
        $RequestSubTotal = WC()->cart->get_displayed_subtotal();
        
        return $RequestSubTotal;
    }

    /**
     * @return string
     */
    public function getCartTotal() {
        global $woocommerce;
        $RequestCartTotal = WC()->cart->total;
        
        return $RequestCartTotal;
    }

     /**
     * @return array
     */
    public function getloyaltyAppliedCoupons() {
        global $woocommerce;
        $RequestcouponsLabels = array();
        $RequestAppliedCoupons = WC()->cart->get_applied_coupons();
        
        
        if (  WC()->session->__isset( 'promotion_label' ) ) {
            $promotion_labels = WC()->session->get( 'promotion_label' );

            foreach ($promotion_labels as $applied_label) {
               
                if (strpos($applied_label, 'loyalty_promotions_') !== false) {
                    $code = explode("loyalty_promotions_",$applied_label); 
                    $coupon_label = strtoupper($code[1]); 
                }
                if (strpos($applied_label, 'loyalty_receipts_') !== false) {
                    $code = explode("loyalty_receipts_",$applied_label);    
                    $coupon_label = strtoupper($code[1]);    
                }                    
                $RequestcouponsLabels[] = $coupon_label;
            }

        }    
        
        
        $merge_coupon_labels = array_merge($RequestAppliedCoupons,$RequestcouponsLabels);
        return $merge_coupon_labels;
    }

    /**
     * Get the future date and time based on system setting for OTP time duration
     * @return string
     */
    public function getOtpValidDuration()
    {
        $increaseTime = $this->getOtpValidTime();
        $exsisttime = $this->getCurrentDate();
        $currentDate = strtotime($exsisttime);
        $futureDate = $currentDate + (60 * $increaseTime);
        $validtime = date("Y-m-d H:i:s", $futureDate);
        return $validtime;
    }


    /**
     * Verify OTP is expire or not as per time duration
     * @return int
     */
    public function verifyOtpTime($verifyOtpTime)
    {
        $expireFlag = 0;
        $exsisttime = $this->getCurrentDate();
        $currentDate = strtotime($exsisttime);
        $checkDate = strtotime($verifyOtpTime);
        if ($currentDate <= $checkDate) {
            $expireFlag = 1;
        }
        return $expireFlag;
    }

    /**
     * Get the time duration for lockout
     * @return string
     */
    public function getLockTimeDuration($locktime)
    {
        $exsisttime = $this->getCurrentDate();
        $timeduration = $this->getPhoneLockoutTime();
        $currenttimeStr = strtotime($exsisttime);
        $locktimeStr = strtotime($locktime);
        $minutes = 0;
        $futureTime = $locktimeStr + (60 * $timeduration);
        if ($currenttimeStr < $futureTime) {
            $diff = abs($futureTime - $currenttimeStr);
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
            $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 -
                $hours*60*60)/ 60);
            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 -
                $hours*60*60 - $minutes*60));
        }
        return $minutes;
    }

    /**
     * @param $custId
     * @param $failedRequest
     * @throws \Exception
     */
    public function saveLockStatus($custId, $failedRequest)
    {   
        global $wpdb;
        $lstatus = 'unlocked';
        if (!empty($custId) && !empty($failedRequest)) {
            $phoneLimit = $this->getPhoneFailLimit();
            $lockTime = $this->getCurrentDate();
            
            $table = $wpdb->prefix . "loyalty_lock";
            $where = array( 'customer_id' => $custId );
            $row = $wpdb->get_row("SELECT * FROM $table WHERE customer_id = $custId");
            $lockid = $row->lock_id;
            $failRequest = $row->failed_requests;

            if ($lockid) {
                $failedRequest = $failRequest + 1;
                $data = array('failed_requests' => $failedRequest);    
                $updated = $wpdb->update( $table, $data, $where );

                if ($phoneLimit != 0) {
                    if ($failedRequest > $phoneLimit) {
                        $lstatus = 'locked';
                        $up_data = array('failed_requests' => $failedRequest, 'lock_time' => $lockTime, 'lock_status' => $lstatus);
                        
                        $updated = $wpdb->update( $table, $up_data, $where );
                    }
                }
                
            } else {
                $data = array('customer_id' => $custId, 'failed_requests' => $failedRequest, 'lock_status' => $lstatus);
                $wpdb->insert($table,$data);
            }
        }
        return $lstatus;
    }

    /**
     * @param $custId
     * @throws \Exception
     */
    public function releaseLock($custId)
    {   
        global $wpdb;
        if (!empty($custId)) {
            $table = $wpdb->prefix . "loyalty_lock";
            $where = array( 'customer_id' => $custId );

            $lockid = $wpdb->get_var("SELECT lock_id FROM $table WHERE customer_id = $custId");
            if ($lockid) {

                $failedRequest = 0;
                $lockTime = null;
                $lstatus = 'unlocked';

                $up_data = array('failed_requests' => $failedRequest, 'lock_time' => $lockTime, 'lock_status' => $lstatus);
                
                $updated = $wpdb->update( $table, $up_data, $where );
            }
        }
    }

    /**
     * @param $custId
     * @throws \Exception
     */
    public function updateLockStatus($custId)
    {   
        global $wpdb;
        $remainTime = 0;
        if (!empty($custId)) {

            $table = $wpdb->prefix . "loyalty_lock";
            $where = array( 'customer_id' => $custId );

            $lockid = $wpdb->get_var("SELECT lock_id FROM $table WHERE customer_id = $custId");

            if ($lockid) {
                $row = $wpdb->get_row("SELECT * FROM $table WHERE customer_id = $custId");
                $failRequest = $row->failed_requests;
                $lockstatus = $row->lock_status;
                if ($lockstatus == 'locked') {
                    $locktime = $row->lock_time;
                    $remainTime = $this->getLockTimeDuration($locktime);
                    if ($remainTime == 0) {
                        $failedRequest = 0;
                        $lockTime = null;
                        $lstatus = 'unlocked';

                        $up_data = array('failed_requests' => $failedRequest, 'lock_time' => $lockTime, 'lock_status' => $lstatus);
                        
                        $updated = $wpdb->update( $table, $up_data, $where );
                        
                    }
                }
            }
        }
        return $remainTime;
    }
 
}
