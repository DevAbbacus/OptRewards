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

class Opt_Rewards_PromoInquiry_RequestBuilder {

     /**
     * @var int
     */
    private $requestId;
    /**
     * @var int
     */
    private $phone;
    /**
     * @var string
     */
    private $email;

    /**
     * @return array
     * @throws Exception
     */
    public function build() {

        if($this->requestId < 0) {
            throw new Exception(__("There is no enough parameters."));
        }
        $opt_api = new Opt_Rewards_Api();
        $opt_data = new Opt_Rewards_Data();
        $requestmarge = array();
        $itemmerge = $opt_data->getCartItemsRequestArray();
        $applied_coupons = $opt_data->getloyaltyAppliedCoupons();
        /*$applied_coupons = array('Receipt-Promotion');*/
        
        $receiptAmount = $opt_data->getCartItemsSubTotal();
        $requestmarge["HEADERINFO"] = [
            "REQUESTID" => $this->requestId
        ];
        $requestmarge["COUPONCODEINFO"] = [
            "COUPONCODE" => "ALL",
            "SUBSIDIARYNUMBER" => "",
            "STORENUMBER" => $opt_data->getStoreNumber(),
            "SOURCETYPE" => "eComm",
            "DOCSID" => "",
            "RECEIPTNUMBER" => "1",
            "RECEIPTAMOUNT" => $receiptAmount,
            "DISCOUNTAMOUNT" => "",
            "CUSTOMERID" => "",
            "CARDNUMBER" => "",
            "PHONE" => $this->phone,
            "EMAIL" => $this->email,
            "APPLIEDCOUPONS" => $applied_coupons,
        ];
        $requestmarge["PURCHASEDITEMS"] = $itemmerge;
        $requestmarge["USERDETAILS"] = [
            "USERNAME" => $opt_data->getUsername(),
            "ORGID" => $opt_data->getOrganizationId(),
            "TOKEN" => $opt_data->getToken()
        ];
        $request["COUPONCODEENQREQ"] = $requestmarge;
        
        return $request;
    }

    /**
     * @return int
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param int $requestId
     * @return $this
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return int
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
}
