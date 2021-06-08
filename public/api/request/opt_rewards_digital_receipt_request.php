<?php
/**
 * Exit if accessed directly
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/request
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/request
 * @author     Optculture Team
 */

class Opt_Rewards_DigitalReceipt_RequestBuilder {
      /**
     * @var int
     */
    private $requestId;
    /**
     * @var array
     */
    private $orderdetails;
    /**
     * @var array
     */
    private $orderitems;
    /**
     * @var array
     */
    private $customerdetails;
    /**
     * @var array
     */
    private $redeemdetails;
    /**
     * @var array
     */
    private $promotionsdetails;
    /**
     * @var int
     */
    private $memberShipNumber;
    /**
     * @var int
     */
    private $joinenroll;
    

    /**
     * @throws Exception
     */
    
     public function build() {

        if($this->requestId < 0 && empty($this->orderdetails)) {
            throw new Exception(__("There is no enough parameters."));
        }
        $opt_api = new Opt_Rewards_Api();
        $opt_data = new Opt_Rewards_Data();
        $date = $opt_data->getCurrentDate();
        
        $email = '';
        $phone = '';
        if (  WC()->session->__isset( 'email' ) ){
            $email = WC()->session->get( 'email' );
        }
        if (  WC()->session->__isset( 'phone' ) ){
            $phone = WC()->session->get( 'phone' );
        }     

        $enrollCheck = "N";
        if($this->joinenroll == '1'){
            $enrollCheck = "Y";
        }
        $isLoyaltyCheck = "N";
        if($this->memberShipNumber != ''){
            $isLoyaltyCheck = "Y";
        }

        $emailReceiptCheck = "N";
        if ($opt_data->getEnableReceiptEmail() == '1') {
            $emailReceiptCheck = "Y";
        }


        $headuser = [
            "userName" => $opt_data->getUsername(),
            "organizationId" => $opt_data->getOrganizationId(),
            "token" => $opt_data->getToken()
        ];
        $request["Head"] = [
            "user" => $headuser,
            "requestId" => $this->requestId,
            "requestDate" => $date,
            "enrollCustomer" => $enrollCheck,
            "isLoyaltyCustomer" => $isLoyaltyCheck,
            "emailReceipt" => $emailReceiptCheck,
            "printReceipt" => "N",
            "requestEndPoint" => "/processReceipt.mqrm ",
            "requestType" => "New",
            "requestSource" => "wooCommerce",
            "requestFormat" => "JSON",
            "receiptType" => "Sale",
        ];
        $request["Body"]["orderdetails"] = $this->orderdetails;
        $request["Body"]["orderitems"] = $this->orderitems;
        $request["Body"]["customerdetails"] = $this->customerdetails;
        
        if(!empty($this->memberShipNumber)){
            $request["OptcultureDetails"]["MembershipNumber"] = $this->memberShipNumber;

            $request["OptcultureDetails"]["Email"] = $email;
            $request["OptcultureDetails"]["Phone"] = $phone;
        }
        if(!empty($this->redeemdetails)){
            $request["OptcultureDetails"]["LoyaltyRedeem"] = $this->redeemdetails;
        }
        if(!empty($this->promotionsdetails)){
            $request["OptcultureDetails"]["Promotions"] = $this->promotionsdetails;
        }
        return $request;
    }

    /**
     * @return int
     */
    public function getRequestId(): int {
        return $this->requestId;
    }

    /**
     * @param int $requestId
     * @return $this
     */
    public function setRequestId(int $requestId) {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderdetails() {
        return $this->orderdetails;
    }

    /**
     * @param $orderdetails
     * @return $this
     */
    public function setOrderdetails($orderdetails) {
        $this->orderdetails = $orderdetails;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getOrderitems() {
        return $this->orderitems;
    }

    /**
     * @param $orderitems
     * @return $this
     */
    public function setOrderitems($orderitems) {
        $this->orderitems = $orderitems;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCustomerdetails() {
        return $this->customerdetails;
    }

    /**
     * @param $customerdetails
     * @return $this
     */
    public function setCustomerdetails($customerdetails) {
        $this->customerdetails = $customerdetails;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getRedeemdetails() {
        return $this->redeemdetails;
    }

    /**
     * @param $redeemdetails
     * @return $this
     */
    public function setRedeemdetails($redeemdetails) {
        $this->redeemdetails = $redeemdetails;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getPromotionsdetails() {
        return $this->promotionsdetails;
    }

    /**
     * @param $promotionsdetails
     * @return $this
     */
    public function setPromotionsdetails($promotionsdetails) {
        $this->promotionsdetails = $promotionsdetails;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getMemberShipNumber() {
        return $this->memberShipNumber;
    }

    /**
     * @param $membershipNumber
     * @return $this
     */
    public function setMemberShipNumber($membershipNumber) {
        $this->memberShipNumber = $membershipNumber;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getJoinenroll() {
        return $this->joinenroll;
    }

    /**
     * @param $joinEnorll
     * @return $this
     */
    public function setJoinenroll($joinEnorll) {
        $this->joinenroll = $joinEnorll;
        return $this;
    }
}
