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
 * Otp Issue Request
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/request
 * @author     Optculture Team
 */
class Opt_Rewards_OtpAck_RequestBuilder
{
    /**
     * @var int
     */
    public $requestId;
    /**
     * @var int
     */
    public $customerId;
    /**
     * @var String
     */
    public $phone;
    /**
     * @var String
     */
    public $emailAddress = '';
    /**
     * @var int
     */
    public $otpCode;
    /**
     * @var Data
     */


    /**
     * @return array
     * @throws Exception
     */
    public function build()
    {
        if ($this->requestId < 0 && empty($this->empId) && empty($this->phone)) {
            throw new Exception(__("There is no enough parameters."));
        }
        $opt_data = new Opt_Rewards_Data();
        $date = $opt_data->getCurrentDate();
        $request["header"] = [
            "requestId" => $this->requestId,
            "requestType"=> 'Acknowledge',
            "pcFlag" => "",
            "requestDate" => $date,
            "subsidiaryNumber" => "1",
            "storeNumber" => $opt_data->getStoreNumber(),
            "sourceType" => 'eComm',
            "employeeId" => $this->empId,
            "terminalId" => "1",
            "receiptNumber" => "1",
            "docSID" => "10"
        ];
        $request["otpCode"] = $this->otpCode;
        $request["customer"] = [
            "customerId" => "",
            "firstName" => "",
            "lastName" => "",
            "phone" => $this->phone,
            "emailAddress" => $this->emailAddress,
            "addressLine1" => "",
            "addressLine2" => "",
            "city" => "",
            "state" => "",
            "postal" => "",
            "country" => "",
            "birthday" => "",
            "anniversary" => "",
            "gender" => ""
        ];
        $request["user"] = [
            "userName" => $opt_data->getUsername(),
            "organizationId" => $opt_data->getOrganizationId(),
            "token" => $opt_data->getToken()
        ];
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
    public function getOtpCode()
    {
        return $this->otpCode;
    }

    /**
     * @param $otpCode
     * @return $this
     */
    public function setOtpCode($otpCode)
    {
        $this->otpCode = $otpCode;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getEmpId()
    {
        return $this->empId;
    }

    /**
     * @param $empId
     * @return $this
     */
    public function setEmpId($empId)
    {
        $this->empId = $empId;
        return $this;
    }

    /**
     * @return String
     */
    public function getPhone(): String
    {
        return $this->phone;
    }

    /**
     * @param String $phone
     * @return $this
     */
    public function setPhone(String $phone)
    {
        $this->phone = $phone;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    
     /**
     * @param $emailAddress
     * @return $this
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }
}
