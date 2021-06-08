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

class Opt_Rewards_NewEnrollment_RequestBuilder {
    /**
     * @var int
     */
    private $requestId;
    /**
     * @var String
     */
    private $firstName;
    /**
     * @var String
     */
    private $lastName;
    /**
     * @var String
     */
    private $phone;
    /**
     * @var String
     */
    private $email;
    /**
     * @var String
     */
    private $addressLine1;
    /**
     * @var String
     */
    private $addressLine2;
    /**
     * @var String
     */
    private $city;
    /**
     * @var String
     */
    private $state;
    /**
     * @var String
     */
    private $zip;
    /**
     * @var String
     */
    private $country;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var int
     */
    private $customerId;


    /**
     * LoyaltyNewEnrollmentRequestBuilder constructor.
     * @param Data $helper
     */
    

    /**
     * @throws Exception
     */
    public function build() {

        if($this->requestId < 0 && empty($this->phone) && empty($this->email) && empty($this->customerId)) {
            throw new Exception(__("There is no enough parameters."));
        }
        $opt_api = new Opt_Rewards_Api();
        $opt_data = new Opt_Rewards_Data();

        $date = $opt_data->getCurrentDate();
        $request["header"] = [
            "requestId" => $this->requestId,
            "requestDate" => $date,
            "contactList" => 'POS',
            "sourceType" => 'E_COMM',
        ];
        $request["customer"] = [
            "customerId" => $this->customerId,
            "membershipNumber" => "",
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "phone" => $this->phone,
            "emailAddress" => $this->email,
            "addressLine1" => $this->addressLine1,
            "addressLine2" => $this->addressLine2,
            "city" => $this->city,
            "state" => $this->state,
            "postal" => $this->zip,
            "country" => $this->country,
            "homeStore" => "",
            "creationDate" => $date,
            "loyalty" => [
                "enrollCustomer" => "Y",
                "mobileAppPreferences" => [
                    "language" => "English",
                    "pushNotifications" => "False"
                ],
                "fingerprintValidation" => "False",
                "password" => ""
            ],
            "suppress" => [
                "email" => [
                    "isTrue" => "N",
                    "reason" => "",
                    "timestamp" => $date,
                ],
                "phone" => [
                    "isTrue" => "N",
                    "reason" => "",
                    "timestamp" => $date,
                ]
            ]
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
     * @return String
     */
    public function getFirstName(): String {
        return $this->firstName;
    }

    /**
     * @param String $firstName
     * @return $this
     */
    public function setFirstName(String $firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return String
     */
    public function getLastName(): String {
        return $this->lastName;
    }

    /**
     * @param String $lastName
     * @return $this
     */
    public function setLastName(String $lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return String
     */
    public function getPhone(): String {
        return $this->phone;
    }

    /**
     * @param String $phone
     * @return $this
     */
    public function setPhone(String $phone) {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return String
     */
    public function getEmail(): String {
        return $this->email;
    }

    /**
     * @param String $email
     * @return $this
     */
    public function setEmail(String $email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return String
     */
    public function getAddressLine1(): String {
        return $this->addressLine1;
    }

    /**
     * @param String $addressLine1
     * @return $this
     */
    public function setAddressLine1(String $addressLine1) {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * @return String
     */
    public function getAddressLine2(): String {
        return $this->addressLine2;
    }

    /**
     * @param String $addressLine2
     * @return $this
     */
    public function setAddressLine2(String $addressLine2) {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * @return String
     */
    public function getCity(): String {
        return $this->city;
    }

    /**
     * @param String $city
     * @return $this
     */
    public function setCity(String $city) {
        $this->city = $city;
        return $this;
    }

    /**
     * @return String
     */
    public function getState(): String {
        return $this->state;
    }

    /**
     * @param String $state
     * @return $this
     */
    public function setState(String $state) {
        $this->state = $state;
        return $this;
    }

    /**
     * @return String
     */
    public function getZip(): String {
        return $this->zip;
    }

    /**
     * @param String $zip
     * @return $this
     */
    public function setZip(String $zip) {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return String
     */
    public function getCountry(): String {
        return $this->country;
    }

    /**
     * @param String $country
     * @return $this
     */
    public function setCountry(String $country) {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId) {
        $this->customerId = $customerId;
        return $this;
    }
}
