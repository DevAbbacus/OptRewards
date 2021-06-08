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
class Opt_Rewards_OtpIssue_RequestBuilder
{
    /**
     * @var int
     */
    private $requestId;
    /**
     * @var String
     */
    private $phone;
    /**
     * @var String
     */
    private $email;

    /**
     * @return array
     * @throws Exception
     */
    public function build()
    {
        if ($this->requestId < 0 && empty($this->empId) && empty($this->phone) &&
                empty($this->email)) {
            throw new Exception(__("There is no enough parameters."));
        }

        $opt_data = new Opt_Rewards_Data();
        $date = $opt_data->getCurrentDate();

        $request["header"] = [
            "requestId" => $this->requestId,
            "requestType"=> 'Issue',
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
        $request["customer"] = [
            "customerId" => "",
            "firstName" => "",
            "lastName" => "",
            "phone" => $this->phone,
            "emailAddress" => $this->email,
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
     * @return String
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * @param String $custEmail
     * @return $this
     */
    public function setEmail(String $custEmail)
    {
        $this->email = $custEmail;
        return $this;
    }
}
