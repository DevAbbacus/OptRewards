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

class Opt_Rewards_BalancesInquiry_RequestBuilder {
    /**
     * @var int
     */
    private $requestId;
    /**
     * @var int
     */
    private $phone;
    /**
     * @var int
     */
    private $email;

    /**
     * @throws Exception
     */
    public function build() {

        if($this->requestId < 0) {
            throw new Exception(__("There is no enough parameters."));
        }
        $opt_api = new Opt_Rewards_Api();
        $opt_data = new Opt_Rewards_Data();
        $requestmarge = array();
        $requestmarge["HEADERINFO"] = [
            "REQUESTID" => $this->requestId
        ];
        $requestmarge["COUPONCODEINFO"] = [
            "COUPONCODE" => 'BALANCES',
            "SUBSIDIARYNUMBER" => "",
            "STORENUMBER" => $opt_data->getStoreNumber(),
            "SOURCETYPE" => 'eComm',
            "DOCSID" => "",
            "RECEIPTNUMBER" => "1",
            "RECEIPTAMOUNT" => "",
            "DISCOUNTAMOUNT" => "",
            "CUSTOMERID" => "",
            "CARDNUMBER" => "",
            "PHONE" => $this->phone,
            "EMAIL" => $this->email,
            "APPLIEDCOUPONS" => [],
        ];
        $requestmarge["PURCHASEDITEMS"] = [];
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
