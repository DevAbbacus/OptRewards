<?php
/**
 * Exit if accessed directly
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
class Opt_Rewards_Api_Operations {

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


    /**
     * @param $phone
     * @param $email
     * @param $addressLine1
     * @param $addressLine2
     * @param $firstName
     * @param $lastName
     * @param $city
     * @param $state
     * @param $zip
     * @param $country
     * @return array|null
     * @throws Exception
     */


    public function newEnrollment(
        $phone, $email, $addressLine1, $addressLine2,
        $firstName, $lastName, $city, $state,
        $zip, $country
    ) { 
        $opt_api = new Opt_Rewards_Api();
        $this->opt_rewards_newenrollment_request();
        $this->opt_rewards_newenrollment_response();
        $opt_request = new Opt_Rewards_NewEnrollment_RequestBuilder();
        $opt_response = new Opt_Rewards_NewEnrollment_ResponseBuilder();


        $loyaltyEnrollmentResponse = [];
        $requestId = $opt_api->generateRandomString();
        $customerId = $opt_api->generateRandomString(12);
        try {
            $enrollmentRequest = $opt_request->setRequestId($requestId)
                ->setCustomerId($customerId)
                ->setPhone($phone)
                ->setEmail($email)
                ->setAddressLine1($addressLine1)
                ->setAddressLine2($addressLine2)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setCity($city)
                ->setState($state)
                ->setZip($zip)
                ->setCountry($country)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }

        if (!empty($enrollmentRequest)) {
       
            $enrollmentResponse = $opt_api->getAddUpdateContact($enrollmentRequest);
            $loyaltyEnrollmentResponse = $opt_response->setData($enrollmentResponse)->build();
        }
        return $enrollmentResponse;

    }

    /**
     * @param $memberShipNumber
     * @param $creationTime
     * @param $phone
     * @param $email
     * @param $addressLine1
     * @param $addressLine2
     * @param $firstName
     * @param $lastName
     * @param $city
     * @param $state
     * @param $zip
     * @param $country
     * @return array
     * @throws Exception
     */
    public function updateContact(
        $memberShipNumber, $creationTime,
        $phone, $email, $addressLine1, $addressLine2,
        $firstName, $lastName, $city, $state,
        $zip, $country
    ) {
        $loyaltyUpdateContactResponse = [];

        $opt_api = new Opt_Rewards_Api();
        $this->opt_rewards_updatecontact_request();
        $this->opt_rewards_updatecontact_response();
        $opt_request = new Opt_Rewards_UpdateContact_RequestBuilder();
        $opt_response = new Opt_Rewards_UpdateContact_ResponseBuilder();

        $requestId = $opt_api->generateRandomString();
        $customerId = $opt_api->generateRandomString(12);


        try {
            $updateContactRequest = $opt_request->setRequestId($requestId)
                ->setMembershipNumber($memberShipNumber)
                ->setCustomerId($customerId)
                ->setPhone($phone)
                ->setEmail($email)
                ->setAddressLine1($addressLine1)
                ->setAddressLine2($addressLine2)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setCity($city)
                ->setState($state)
                ->setZip($zip)
                ->setCountry($country)
                ->setCreationTime($creationTime)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        
        if (!empty($updateContactRequest)) {
            $updateContactResponse = $opt_api->getAddUpdateContact($updateContactRequest);
            try {
                $loyaltyUpdateContactResponse = $opt_response->setData($updateContactResponse)->build();
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $loyaltyUpdateContactResponse;
    }


    /**
     * @param $phone
     * @param $email
     * @return array
     * @throws Exception
     */
    public function promotionsInquiry($phone, $email) {
        $promoInquiryResponse = [];
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();
        
        $this->opt_rewards_promoinquiry_request();
        $opt_request = new Opt_Rewards_PromoInquiry_RequestBuilder();
        $this->opt_rewards_promoinquiry_response();
        $opt_response = new Opt_Rewards_PromoInquiry_ResponseBuilder();
        
        try {
            $promoInquiryRequest = $opt_request->setPhone($phone)
                ->setEmail($email)
                ->setRequestId($requestId)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        if (!empty($promoInquiryRequest)) {
            $promotionResponse = $opt_api->getPromotionsInquiry($promoInquiryRequest);
            try {
                $promoInquiryResponse = $opt_response->setData($promotionResponse)->build();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
       
        return $promoInquiryResponse;
    }

    /**
     * @param $phone
     * @param $email
     * @return array
     * @throws Exception
     */
    public function balancesInquiry($phone, $email) {
        $balancesInquiryResponse = [];
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();
        $this->opt_rewards_balancesinquiry_request();
        $opt_request = new Opt_Rewards_BalancesInquiry_RequestBuilder();
        $this->opt_rewards_balancesinquiry_response();
        $opt_response = new Opt_Rewards_BalancesInquiry_ResponseBuilder();
        try {
            $balancesInquiryRequest = $opt_request
                ->setPhone($phone)
                ->setEmail($email)
                ->setRequestId($requestId)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        if (!empty($balancesInquiryRequest)) {
            $balancesResponse = $opt_api->getPromotionsInquiry($balancesInquiryRequest);
            try {
                $balancesInquiryResponse = $opt_response->setData($balancesResponse)->build();
            } catch (Exception $e) {
                throw $e;
            }
        }
        
        return $balancesInquiryResponse;
    }

    /**
     * @param array $orderdetails
     * @param array $orderitems
     * @param array $customerdetails
     * @param array $redeemdetails
     * @param array $promotionsdetails
     * @param string $membershipNumber
     * @param int $joinEnorll
     * @throws Exception
     */
    public function digitalReceipt(
        $orderdetails,
        $orderitems,
        $customerdetails,
        $redeemdetails,
        $promotionsdetails,
        $membershipNumber,
        $joinEnorll
    ) {
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();

        $this->opt_rewards_digital_receipt_request();
        $opt_request = new Opt_Rewards_DigitalReceipt_RequestBuilder();
        $this->opt_rewards_digital_receipt_response();
        $opt_response = new Opt_Rewards_DigitalReceipt_ResponseBuilder();

        try {
            $receiptRequest = $opt_request->setRequestId($requestId)
                ->setOrderdetails($orderdetails)
                ->setOrderitems($orderitems)
                ->setCustomerdetails($customerdetails)
                ->setRedeemdetails($redeemdetails)
                ->setPromotionsdetails($promotionsdetails)
                ->setMemberShipNumber($membershipNumber)
                ->setJoinEnroll($joinEnorll)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        
        if (!empty($receiptRequest)) {
            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            
            fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
            fwrite($fp, 'Send The API Request For Digital Receipt'."\n");
            $receiptResponse = $opt_api->getDigitalReceipt($receiptRequest);
            if (!empty($receiptResponse)) {
                    
                fwrite($fp, '======= Start Digital Receipt Request ======'."\n");
                fwrite($fp, json_encode($receiptRequest)."\n");
                fwrite($fp, '======= End Digital Receipt Request ======'."\n\n");
                fwrite($fp, '======= Start Digital Receipt Response ======'."\n");
                fwrite($fp, json_encode($receiptResponse)."\n");
                fwrite($fp, '======= End Digital Receipt Response ======'."\n\n");
                
            }
            
            try {
                $receiptbuilderResponse = $opt_response->setData($receiptResponse)->build();
            } catch (Exception $e) {
                throw $e;
            }
            
        }
    }

    /**
     * @param array $orderdetails
     * @param array $orderitems
     * @param array $customerdetails
     * @param array $redeemdetails
     * @param array $promotionsdetails
     * @param string $membershipNumber
     * @throws Exception
     */
    public function cancelReceipt(
        $orderdetails,
        $orderitems,
        $customerdetails,
        $redeemdetails,
        $loyaltyRedeemReversal,
        $promotionsdetails,
        $membershipNumber
    ) {
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();

        $this->opt_rewards_cancel_receipt_request();
        $opt_request = new Opt_Rewards_CancelReceipt_RequestBuilder();
        $this->opt_rewards_digital_receipt_response();
        $opt_response = new Opt_Rewards_DigitalReceipt_ResponseBuilder();

        try {
            $cancelRequest = $opt_request->setRequestId($requestId)
                ->setOrderdetails($orderdetails)
                ->setOrderitems($orderitems)
                ->setCustomerdetails($customerdetails)
                ->setRedeemdetails($redeemdetails)
                ->setLoyaltyRedeemReversal($loyaltyRedeemReversal)
                ->setPromotionsdetails($promotionsdetails)
                ->setMemberShipNumber($membershipNumber)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        
        if (!empty($cancelRequest)) {
            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            
            fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
            fwrite($fp, 'Send The API Request For Cancel Receipt'."\n");
            $cancelResponse = $opt_api->getDigitalReceipt($cancelRequest);

            if (!empty($cancelResponse)) {
                    
                fwrite($fp, '======= Start Cancel Receipt Request ======'."\n");
                fwrite($fp, json_encode($cancelRequest)."\n");
                fwrite($fp, '======= End Cancel Receipt Request ======'."\n\n");
                fwrite($fp, '======= Start Cancel Receipt Response ======'."\n");
                fwrite($fp, json_encode($cancelResponse)."\n");
                fwrite($fp, '======= End Cancel Receipt Response ======'."\n\n");
            }
            
            try {
                $receiptbuilderResponse = $opt_response->setData($cancelResponse)->build();
            } catch (Exception $e) {
                throw $e;
            }
            
        }
    }


    /**
     * @param array $orderdetails
     * @param array $orderitems
     * @param array $customerdetails
     * @param array $redeemdetails
     * @param array $promotionsdetails
     * @param string $membershipNumber
     * @throws Exception
     */
    public function refundReceipt(
        $orderdetails,
        $orderitems,
        $customerdetails,
        $redeemdetails,
        $loyaltyRedeemReversal,
        $promotionsdetails,
        $membershipNumber
    ) {
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();

        $this->opt_rewards_refund_receipt_request();
        $opt_request = new Opt_Rewards_RefundReceipt_RequestBuilder();
        $this->opt_rewards_digital_receipt_response();
        $opt_response = new Opt_Rewards_DigitalReceipt_ResponseBuilder();

        try {
            $refundRequest = $opt_request->setRequestId($requestId)
                ->setOrderdetails($orderdetails)
                ->setOrderitems($orderitems)
                ->setCustomerdetails($customerdetails)
                ->setRedeemdetails($redeemdetails)
                ->setLoyaltyRedeemReversal($loyaltyRedeemReversal)
                ->setPromotionsdetails($promotionsdetails)
                ->setMemberShipNumber($membershipNumber)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        
        if (!empty($refundRequest)) {
            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            
            fwrite($fp, 'Request Come From Extension '.OPT_REWARDS_VERSION.' Version'."\n");
            fwrite($fp, 'Send The API Request For Refund Receipt'."\n");
            $refundResponse = $opt_api->getDigitalReceipt($refundRequest);
            if (!empty($refundResponse)) {
                    
                fwrite($fp, '======= Start Refund Receipt Request ======'."\n");
                fwrite($fp, json_encode($refundRequest)."\n");
                fwrite($fp, '======= End Refund Receipt Request ======'."\n\n");
                fwrite($fp, '======= Start Refund Receipt Response ======'."\n");
                fwrite($fp, json_encode($refundResponse)."\n");
                fwrite($fp, '======= End Refund Receipt Response ======'."\n\n");
            }
            
            try {
                $receiptbuilderResponse = $opt_response->setData($refundResponse)->build();
            } catch (Exception $e) {
                throw $e;
            }
            
        }
    }

    /**
     * @param int $phone
     * @param string $custEmail
     * @return array
     * @throws Exception
     */
    public function otpIssue($phone, $custEmail)
    {
        $otpIssueResponse = [];
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();
        $empId = $opt_api->generateRandomString();

        $this->opt_rewards_otp_issue_request();
        $opt_request = new Opt_Rewards_OtpIssue_RequestBuilder();
        

        $this->opt_rewards_otp_issue_response();
        $opt_response = new Opt_Rewards_OtpIssue_ResponseBuilder();

        try {
            $otpIssueRequest = $opt_request->setRequestId($requestId)
                ->setPhone($phone)
                ->setEmail($custEmail)
                ->setEmpId($empId)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        if (!empty($otpIssueRequest)) {

            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            
            $otpiResponse = $opt_api->getOtpIssue($otpIssueRequest);
            if (!empty($otpiResponse)) {
                    fwrite($fp, '======= Start Verification Code Issue Request ======'."\n");
                    fwrite($fp, json_encode($otpIssueRequest)."\n");
                    fwrite($fp, '======= End Verification Code Issue Request ======'."\n\n");
                    fwrite($fp, '======= Start Verification Code Issue Response ======'."\n");
                    fwrite($fp, json_encode($otpiResponse)."\n");
                    fwrite($fp, '======= End Verification Code Issue Response ======'."\n\n");
                try {
                    $otpIssueResponse = $opt_response->setData($otpiResponse)->build();
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        return $otpIssueResponse;
    }
    
    /**
     * @param int $phone
     * @param string $custEmail
     * @param int $verifyOtp
     * @return array
     * @throws Exception
     */
    public function otpAcknowledge($phone, $custEmail, $verifyOtp)
    {
        $otpAckResponse = [];
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();
        $empId = $opt_api->generateRandomString();
        
        $this->opt_rewards_otp_ack_request();
        $opt_request = new Opt_Rewards_OtpAck_RequestBuilder();
        $this->opt_rewards_otp_ack_response();
        $opt_response = new Opt_Rewards_OtpAck_ResponseBuilder(); 
        

        try {
            $otpAckRequest = $opt_request->setRequestId($requestId)
                ->setPhone($phone)
                ->setEmailAddress($custEmail)
                ->setOtpCode($verifyOtp)
                ->setEmpId($empId)
                ->build();
        } catch (Exception $e) {
            throw $e;
        }
        if (!empty($otpAckRequest)) {
            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            $otpsResponse = $opt_api->getOtpAck($otpAckRequest);
            if (!empty($otpsResponse)) {

                    fwrite($fp, '======= Start Verification Code Acknowledge Request ======'."\n");
                    fwrite($fp, json_encode($otpAckRequest)."\n");
                    fwrite($fp, '======= End Verification Code Acknowledge Request ======'."\n\n");
                    fwrite($fp, '======= Start Verification Code Acknowledge Response ======'."\n");
                    fwrite($fp, json_encode($otpsResponse)."\n");
                    fwrite($fp, '======= End Verification Code Acknowledge Response ======'."\n\n");
                
                try {
                    $otpAckResponse = $opt_response->setData($otpsResponse)->build();
                } catch (LocalizedException $e) {
                    throw $e;
                }
            }
        }
        return $otpAckResponse;
    }


    /**
     * @param int $productId
     * @param string $category
     * @param string $sku
     * @param string $productName
     * @param string $type
     * @param string $shortDescription
     * @param string $description
     * @param string $price
     * @param string $taxStatus
     * @param string $taxClass
     * @param string $colorValue
     * @param string $brandValue
     * @param string $sizeValue
     * @param string $xyzValue
     * @param string $parentSku
     * @throws Exception
     */
    public function addUpdateProduct(
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
        $parentSku
    ) {
        $opt_api = new Opt_Rewards_Api();
        $requestId = $opt_api->generateRandomString();

        $this->opt_rewards_add_update_product_request();
        $opt_request = new Opt_Rewards_Product_AddUpdate_RequestBuilder();
        
        try {
            $productRequest = $opt_request->setRequestId($requestId)
                ->setProductId($productId)
                ->setCategory($category)
                ->setProductName($productName)
                ->setsku($sku)
                ->setProductType($type)
                ->setShortDescription($shortDescription)
                ->setPrice($price)
                ->setTaxStatus($taxStatus)
                ->setTaxClass($taxClass)
                ->setColorValue($colorValue)
                ->setSizeValue($sizeValue)
                ->setBrandValue($brandValue)
                ->setParentSku($parentSku)
                ->build();

        } catch (Exception $e) {
            throw $e;
        }
       
        if (!empty($productRequest)) {
            $filepath = OPT_WP_ROOT_DIR.'/optrewards-log/opt-rewards-log.txt';
            $fp = fopen($filepath, "a") or die("Unable to open file!");
            $productResponse = $opt_api->getAddUpdateProduct($productRequest);
            
            if (!empty($productResponse)) {

                fwrite($fp, '======= Start Add/Update Product Request ======'."\n");
                fwrite($fp, json_encode($productRequest)."\n");
                fwrite($fp, '======= End Add/Update Product Request ======'."\n\n");
                fwrite($fp, '======= Start Add/Update Product Response ======'."\n");
                fwrite($fp, json_encode($productResponse)."\n");
                fwrite($fp, '======= End Add/Update Product Response ======'."\n\n");
            }
        }
        return $productRes;
    }


    public function opt_rewards_newenrollment_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_newenrollment_request.php';
    }

    public function opt_rewards_newenrollment_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_newenrollment_response.php';
    }

    public function opt_rewards_updatecontact_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_updatecontact_request.php';
    }

    public function opt_rewards_updatecontact_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_updatecontact_response.php';
    }

    public function opt_rewards_balancesinquiry_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_balancesinquiry_request.php';
    }

    public function opt_rewards_balancesinquiry_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_balancesinquiry_response.php';
    }

    public function opt_rewards_promoinquiry_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_promoinquiry_request.php';
    }
    
    public function opt_rewards_promoinquiry_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_promoinquiry_response.php';
    }
    
    public function opt_rewards_digital_receipt_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_digital_receipt_request.php';
    }

    public function opt_rewards_digital_receipt_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_digital_receipt_response.php';
    }

    public function opt_rewards_cancel_receipt_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_cancel_receipt_request.php';
    }

    public function opt_rewards_refund_receipt_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_refund_receipt_request.php';
    }
    
    public function opt_rewards_otp_issue_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_otp_issue_request.php';
    }
    
    public function opt_rewards_otp_issue_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_otp_issue_response.php';
    }

    public function opt_rewards_otp_ack_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_otp_ack_request.php';
    }

    public function opt_rewards_otp_ack_response() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/response/opt_rewards_otp_ack_response.php';
    }
    
    public function opt_rewards_add_update_product_request() {
        require_once OPT_REWARDS_DIR_PATH . '/public/api/request/opt_rewards_addupdate_product_request.php';
    }
    
     
}
