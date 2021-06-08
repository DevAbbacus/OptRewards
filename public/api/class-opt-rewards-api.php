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
class Opt_Rewards_Api {

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
	 * This function is used get the order total settings
	 *
	 * @name opt_rewards_callApi
     * @param $method
     * @param $url
     * @param $data
     * @return mixed
     */

    public $opt_data_obj;

    public function __construct() {
        $this->opt_data_obj = new Opt_Rewards_Data();
    }


    public function opt_rewards_callApi($method, $url, $data) {
        $headers[] = 'Content-Type:application/json, Accept:application/json';
        $curl = curl_init();
        switch ($method){
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);

        if(!$result){
            $result = json_encode(array('error' => 500));
        }
        
        curl_close($curl);
        return json_decode($result, true);
    }


    /**
     * The class responsible for defining all backend fields data.
     */


    /**
     * @param array $loyaltyUpdateContactRequest
     * @return mixed
     */
    public function getAddUpdateContact(array $loyaltyUpdateContactRequest) {
       $url = $this->opt_data_obj->getAddUpdateContactUrlApi();
       return $this->opt_rewards_callApi('post', $url, $loyaltyUpdateContactRequest);
    }
    

    /**
     * @param array $couponRequest
     * @return mixed
     */
    public function getPromotionsInquiry(array $couponRequest) {
        $url = $this->opt_data_obj->getPromotionsInquiryUrlApi();
        return $this->opt_rewards_callApi('post', $url, $couponRequest);
    }

    /**
     * @param array $loyaltyDigitalReceiptRequest
     * @return mixed
     */
    public function getDigitalReceipt(array $loyaltyDigitalReceiptRequest) {
        $url = $this->opt_data_obj->getDigitalReceiptUrlApi();
        return $this->opt_rewards_callApi('post', $url, $loyaltyDigitalReceiptRequest);
    }


    /**
     * @param array $otpIssueRequest
     * @return mixed
     */
    public function getOtpIssue(array $otpIssueRequest)
    {
        $url = $this->opt_data_obj->getVerificationCodeUrlApi();
        return $this->opt_rewards_callApi('post', $url, $otpIssueRequest);
    }
    
    /**
     * @param array $otpAskRequest
     * @return mixed
     */
    public function getOtpAck(array $otpAskRequest)
    {
        $url = $this->opt_data_obj->getVerificationCodeUrlApi();
        return $this->opt_rewards_callApi('post', $url, $otpAskRequest);
    }

    /**
     * @param array $otpAskRequest
     * @return mixed
     */
    public function getAddUpdateProduct(array $productData)
    {
        $url = $this->opt_data_obj->getCreateUpdateProductUrlApi();
        return $this->opt_rewards_callApi('post', $url, $productData);
    }
    

    public function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
