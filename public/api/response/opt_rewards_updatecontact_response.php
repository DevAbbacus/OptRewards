<?php
/**
 * Exit if accessed directly
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/response
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/response
 * @author     Optculture Team
 */
class Opt_Rewards_UpdateContact_ResponseBuilder {
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var int
     */
    private $cardNumber = 0;
    /**
     * @var int
     */
    private $cardPin = 0;
    /**
     * @var string
     */
    private $phoneNumber = "";
    /**
     * @var int
     */
    private $tierLevel = 0;
    /**
     * @var string
     */
    private $tierName = "";
    /**
     * @var string
     */
    private $expiry = "";

    /**
     * @throws Exception
     */
    public function build() {
        
        if(!isset($this->data['status'])) {
            throw new Exception(__('An error occurred, please try again'));
        }
        $loyaltyUpdateContact = $this->data;
        if( $loyaltyUpdateContact['status']['status'] === 'Failure' ) {
            return $loyaltyUpdateContact['status'];
        }
        if(isset($loyaltyUpdateContact['membership']) && $loyaltyUpdateContact['status']['status'] === 'Success') {
            $membership = [];
            foreach ($loyaltyUpdateContact['membership'] as $memberData) {
                $cardNumber = isset($memberData['cardNumber']) ? $memberData['cardNumber'] : 0;
                $cardPin = isset($memberData['cardPin']) ? $memberData['cardPin'] : 0;
                $phoneNumber = isset($memberData['phoneNumber']) ? $memberData['phoneNumber'] : "";
                $tierLevel = isset($memberData['tierLevel']) ? $memberData['tierLevel'] : 0;
                $tierName = isset($memberData['tierName']) ? $memberData['tierName'] : "";
                $expiry = isset($memberData['expiry']) ? $memberData['expiry'] : "";
                $this->setCardNumber($cardNumber)
                    ->setCardPin($cardPin)
                    ->setPhoneNumber($phoneNumber)
                    ->setTierLevel($tierLevel)
                    ->setTierName($tierName)
                    ->setExpiry($expiry);
                $membership[] = $this->getResData();
            }
            if (!empty($membership)) {
                return $membership;
            }
        } elseif ($loyaltyUpdateContact['status']['status'] === 'Success') {
            return [
                "requestId" => $loyaltyUpdateContact['header']['requestId'],
                "response" => $loyaltyUpdateContact['status']['message']
            ];
        }

        return ["status" => "Bad response data"];
    }

    /**
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }
    
    public function getResData() {
        return [
            "cardNumber" => $this->cardNumber,
            "cardPin" => $this->cardPin,
            "phoneNumber" => $this->phoneNumber,
            "tierLevel" => $this->tierLevel,
            "tierName" => $this->tierName,
            "expiry" => $this->expiry,
        ];
    }

    /**
     * @return int
     */
    public function getCardNumber(): int {
        return $this->cardNumber;
    }

    /**
     * @param int $cardNumber
     * @return $this
     */
    public function setCardNumber(int $cardNumber) {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getCardPin(): int {
        return $this->cardPin;
    }

    /**
     * @param int $cardPin
     * @return $this
     */
    public function setCardPin(int $cardPin) {
        $this->cardPin = $cardPin;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber) {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getTierLevel(): int {
        return $this->tierLevel;
    }

    /**
     * @param int $tierLevel
     * @return $this
     */
    public function setTierLevel(int $tierLevel) {
        $this->tierLevel = $tierLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getTierName(): string {
        return $this->tierName;
    }

    /**
     * @param string $tierName
     * @return $this
     */
    public function setTierName(string $tierName) {
        $this->tierName = $tierName;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiry(): string {
        return $this->expiry;
    }

    /**
     * @param string $expiry
     * @return $this
     */
    public function setExpiry(string $expiry) {
        $this->expiry = $expiry;
        return $this;
    }
}
