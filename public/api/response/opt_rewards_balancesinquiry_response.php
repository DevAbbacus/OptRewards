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
class Opt_Rewards_BalancesInquiry_ResponseBuilder {
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
        
        $balancesInquiry = $this->data;
        if(!isset($balancesInquiry['COUPONCODERESPONSE']['STATUSINFO'])) {
            throw new Exception(__('An error occurred, please try again'));
        }
        if($balancesInquiry['COUPONCODERESPONSE']['STATUSINFO']['STATUS'] === 'Failure') {
            throw new Exception(__($balancesInquiry['COUPONCODERESPONSE']['STATUSINFO']['MESSAGE']));
        }
        if(isset($balancesInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES']) && is_array($balancesInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES'])) {
            $balances = [];
            foreach ($balancesInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES'] as $balance) {
                $amount = isset($balance['AMOUNT']) ? $balance['AMOUNT'] : 0;
                $exchangeRate = isset($balance['EXCHANGERATE']) ? $balance['EXCHANGERATE'] : '';
                $difference = isset($balance['DIFFERENCE']) ? (int)$balance['DIFFERENCE'] : 0;
                $valueCode = isset($balance['VALUECODE']) ? $balance['VALUECODE'] : '';
                $this->setAmount($amount)->setDifference($difference)->setValueCode($valueCode)->setExchangeRate($exchangeRate);
                $balances[] = $this->getInquiryData();
            }
            return $balances;
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
