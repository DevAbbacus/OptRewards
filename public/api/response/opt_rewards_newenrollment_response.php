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
 *
 * @package    opt-rewards
 * @subpackage opt-rewards/public/api/response
 * @author     Optculture Team
 */
class Opt_Rewards_NewEnrollment_ResponseBuilder {
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var int
     */
    private $amount = 0;
    /**
     * @var string
     */
    private $exchangeRate = '';
    /**
     * @var int
     */
    private $difference = 0;
    /**
     * @var string
     */
    private $valueCode  = '';

    /**
     * @return array|null
     * @throws Exception
     */
    public function build() {
        try{
            if(!isset($this->data['status'])) {
                throw new Exception(__('An error occurred, please try again'));
            }
            $loyaltyInquiry = $this->data;
            if( $loyaltyInquiry['status']['status'] == 'Failure') {
                throw new Exception(__($loyaltyInquiry['status']['message']));
            }
            if(isset($loyaltyInquiry['balances']) && is_array($loyaltyInquiry['balances'])) {
                $balances = [];
                foreach ($loyaltyInquiry['balances'] as $balance) {
                    $amount = isset($balance['amount']) ? $balance['amount'] : 0;
                    $exchangeRate = isset($balance['type']) ? $balance['type'] : '';
                    $difference = isset($balance['difference']) ? (int)$balance['difference'] : 0;
                    $valueCode = isset($balance['valueCode']) ? $balance['valueCode'] : '';
                    $this->setAmount($amount)->setDifference($difference)->setValueCode($valueCode)->setExchangeRate($exchangeRate);
                    $balances[] = $this->getInquiryData();
                }
                return $balances;
            }
        }catch (Exception $e ) {
            echo '<p>'.$e->getMessage().'</p>';
        }   

        return null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * Get the object as array
     * @return array
     */
    public function getInquiryData() {
        return array(
            'amount' => $this->amount,
            'exchange_rate' => $this->exchangeRate,
            'difference' => $this->difference,
            'value_code' => $this->valueCode
        );
    }
    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * @param string $exchangeRate
     * @return $this
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    /**
     * @return int
     */
    public function getDifference()
    {
        return $this->difference;
    }

    /**
     * @param int $difference
     * @return $this
     */
    public function setDifference($difference)
    {
        $this->difference = $difference;
        return $this;
    }

    /**
     * @return string
     */
    public function getValueCode()
    {
        return $this->valueCode;
    }

    /**
     * @param string $valueCode
     * @return $this
     */
    public function setValueCode($valueCode)
    {
        $this->valueCode = $valueCode;
        return $this;
    }

}
