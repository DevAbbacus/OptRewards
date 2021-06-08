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
 * @subpackage opt-rewards/includes/api/response
 * @author     Optculture Team
 */
class Opt_Rewards_PromoInquiry_ResponseBuilder {
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
     * @var string
     */
    private $couponName = '';
    /**
     * @var string
     */
    private $couponCode = '';
    /**
     * @var string
     */
    private $couponType = '';
    /**
     * @var string
     */
    private $discountCriteria = '';
    /**
     * @var string
     */
    private $excItems = '';
    /**
     * @var string
     */
    private $accumulateDisc = '';
    /**
     * @var string
     */
    private $eligibility = '';
    /**
     * @var string
     */
    private $loyaltyPoints = '';
    /**
     * @var string
     */
    private $description = '';
    /**
     * @var string
     */
    private $nudgepromocode = '';
    /**
     * @var string
     */
    private $nudgedescription = '';
    /**
     * @var string
     */
    private $appliedCoupons = '';
    /**
     * @var int
     */
    private $discValue = 0;
    /**
     * @var string
     */
    private $discValueCode = '';
    /**
     * @var string
     */
    private $minPurchaseVal = '';
    /**
     * @var string
     */
    private $receiptDisc = '';
    /**
     * @var int
     */
    private $maxreceDisc = '';
    /**
     * @var string
     */
    private $itemSku = '';
    /**
     * @var string
     */
    private $itemQty = '';
    /**
     * @var string
     */
    private $itemDisc = '';
    /**
     * @var string
     */
    private $itemMaxDisc = '';
    /**
     * @var string
     */
    private $rewardRatio = '';

    /**
     * @return array|null
     * @throws Exception
     */
    /**
     * @return array|null
     * @throws Exception
     */
    public function build() {
        
        $promoInquiry = $this->data;
        $responseData = [];
        if(!isset($promoInquiry['COUPONCODERESPONSE']['STATUSINFO'])) {
            throw new Exception(__('An error occurred, please try again'));
        }
        if($promoInquiry['COUPONCODERESPONSE']['STATUSINFO']['STATUS'] === 'Failure') {
            throw new Exception(__($promoInquiry['COUPONCODERESPONSE']['STATUSINFO']['MESSAGE']));
        }
        if(isset($promoInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['CARDNUMBER'])) {
            $responseData['loyaltyinfo']['membershipnumber'] = $promoInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['CARDNUMBER'];
        }
        if(isset($promoInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES']) && is_array($promoInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES'])) {
            foreach ($promoInquiry['COUPONCODERESPONSE']['LOYALTYINFO']['BALANCES'] as $balance) {
                $amount = isset($balance['AMOUNT']) ? $balance['AMOUNT'] : 0;
                $exchangeRate = isset($balance['EXCHANGERATE']) ? $balance['EXCHANGERATE'] : '';
                $difference = isset($balance['DIFFERENCE']) ? (int)$balance['DIFFERENCE'] : 0;
                $valueCode = isset($balance['VALUECODE']) ? $balance['VALUECODE'] : '';
                $this->setAmount($amount)->setDifference($difference)->setValueCode($valueCode)->setExchangeRate($exchangeRate);
                $responseData['loyaltyinfo']['balances'][] = $this->getInquiryData();
            }
        }
        if(isset($promoInquiry['COUPONCODERESPONSE']['COUPONDISCOUNTINFO']) && is_array($promoInquiry['COUPONCODERESPONSE']['COUPONDISCOUNTINFO'])) {
            foreach ($promoInquiry['COUPONCODERESPONSE']['COUPONDISCOUNTINFO'] as $coupon) {
                $validFrom = isset($coupon['VALIDFROM']) ? $coupon['VALIDFROM'] : '';
                $validTo = isset($coupon['VALIDTO']) ? $coupon['VALIDTO'] : '';
                $checkCouponIsvalid = $this->checkCouponValidity($validFrom, $validTo);
                if($checkCouponIsvalid == 1){
                    $couponName = isset($coupon['COUPONNAME']) ? $coupon['COUPONNAME'] : '';
                    $couponCode = isset($coupon['COUPONCODE']) ? $coupon['COUPONCODE'] : '';
                    $couponType = isset($coupon['COUPONTYPE']) ? $coupon['COUPONTYPE'] : '';
                    $dicountCri = isset($coupon['DISCOUNTCRITERIA']) ? $coupon['DISCOUNTCRITERIA'] : '';
                    $excItems = isset($coupon['EXCLUDEDISCOUNTEDITEMS']) ? $coupon['EXCLUDEDISCOUNTEDITEMS'] : '';
                    $accumDisc = isset($coupon['ACCUMULATEDISCOUNT']) ? $coupon['ACCUMULATEDISCOUNT'] : '';
                    $eligibility = isset($coupon['ELIGIBILITY']) ? $coupon['ELIGIBILITY'] : '';
                    $loyalPoints = isset($coupon['LOYALTYPOINTS']) ? $coupon['LOYALTYPOINTS'] : '';
                    $description = isset($coupon['DESCRIPTION']) ? $coupon['DESCRIPTION'] : '';
                    $nudgepromocode = isset($coupon['NUDGEPROMOCODE']) ? $coupon['NUDGEPROMOCODE'] : '';
                    $nudgedescription = isset($coupon['NUDGEDESCRIPTION']) ? $coupon['NUDGEDESCRIPTION'] : '';
                    $appliedCoupons = isset($coupon['APPLIEDCOUPONS']) ? $coupon['APPLIEDCOUPONS'] : '';
                    $this->setCouponname($couponName)->setCouponcode($couponCode)->setCoupontype($couponType)->setDiscCriteria($dicountCri)->setExcItems($excItems)->setAccumulateDisc($accumDisc)->setEligibility($eligibility)->setLoyalPoints($loyalPoints)->setDescription($description)->setNudgepromocode($nudgepromocode)->setNudgedescription($nudgedescription)->setAppliedCoupons($appliedCoupons);
                    $d = 0;
                    $discountData = array();
                    $itemData = array();
                    if(!empty($coupon['DISCOUNTINFO'])){
                        foreach ($coupon['DISCOUNTINFO'] as $key => $discInfo) {
                            $value = isset($discInfo['VALUE']) ? (int)$discInfo['VALUE'] : 0;
                            $valueCode = isset($discInfo['VALUECODE']) ? $discInfo['VALUECODE'] : '';
                            $minPurchaseVal = isset($discInfo['MINPURCHASEVALUE']) ? $discInfo['MINPURCHASEVALUE'] : '';
                            $recieDisc = isset($discInfo['RECEIPTDISCOUNT']) ? $discInfo['RECEIPTDISCOUNT'] : '';
                            $maxReceiDisc = isset($discInfo['MAXRECEIPTDISCOUNT']) ? $discInfo['MAXRECEIPTDISCOUNT'] : '';
                            $this->setDiscValue($value)->setDiscValueCode($valueCode)->setMinPurchaseVal($minPurchaseVal)->setReceiptDisc($recieDisc)->setMaxReceiptDisc($maxReceiDisc);
                            if($d == $key){
                                $discountData = $this->getDiscountData();
                            }
                            $i = 0;
                            if(!empty($discInfo['ITEMCODEINFO'])){
                                foreach ($discInfo['ITEMCODEINFO'] as $ikey => $itemInfo) {
                                    $itemSku = isset($itemInfo['ITEMCODE']) ? $itemInfo['ITEMCODE'] : '';
                                    $itemQty = isset($itemInfo['QUANTITY']) ? $itemInfo['QUANTITY'] : '';
                                    $itemDisc = isset($itemInfo['ITEMDISCOUNT']) ? $itemInfo['ITEMDISCOUNT'] : '';
                                    $itemMaxDisc = isset($itemInfo['MAXITEMDISCOUNT']) ? $itemInfo['MAXITEMDISCOUNT'] : '';
                                    $rewardRatio = isset($itemInfo['REWARDRATIO']) ? $itemInfo['REWARDRATIO'] : '';
                                    $this->setItemSku($itemSku)->setItemQty($itemQty)->setItemDisc($itemDisc)->setItemMaxDisc($itemMaxDisc)->setRewardRatio($rewardRatio);
                                    if($i == $ikey){
                                        $itemData = $this->getItemData();
                                    }
                                }
                            }
                            $i++;
                        }
                    }
                    $maregData = array_merge($this->getCouponData(), $discountData, $itemData);
                    $responseData['couponinfo'][] = $maregData;
                    $d++;
                }   
            }
        }
       

        return $responseData;
    }
    
    /**
     * @param $validFrom, $validTo
     * @return flag
     */
    public function checkCouponValidity($validFrom, $validTo) {
        $flag = 0;
        $opt_data = new Opt_Rewards_Data();
        $currentTime = $opt_data->getCurrentDate();
        $fromatedCurrent = strtotime($currentTime);
        if ($fromatedCurrent >= strtotime($validFrom) && $fromatedCurrent <= strtotime($validTo)) {
            $flag = 1;
        }
        return $flag;
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

    /**
     * Get the object as array
     * @return array
     */
    public function getCouponData() {
        return array(
            'couponName' => $this->couponName,
            'couponCode' => $this->couponCode,
            'couponType' => $this->couponType,
            'discountCriteria' => $this->discountCriteria,
            'excItems' => $this->excItems,
            'accumulateDisc' => $this->accumulateDisc,
            'eligibility' => $this->eligibility,
            'loyaltyPoints' => $this->loyaltyPoints,
            'description' => $this->description,
            'nudgepromocode' => $this->nudgepromocode,
            'nudgedescription' => $this->nudgedescription,
            'appliedCoupons' => $this->appliedCoupons
        );
    }

    /**
     * @return string
     */
    public function getCouponname()
    {
        return $this->couponName;
    }

    /**
     * @param string $couponName
     * @return $this
     */
    public function setCouponname($couponName)
    {
        $this->couponName = $couponName;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCouponcode()
    {
        return $this->couponCode;
    }

    /**
     * @param string $couponCode
     * @return $this
     */
    public function setCouponcode($couponCode)
    {
        $this->couponCode = $couponCode;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getCoupontype()
    {
        return $this->couponType;
    }

    /**
     * @param string $couponType
     * @return $this
     */
    public function setCoupontype($couponType)
    {
        $this->couponType = $couponType;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDiscCriteria()
    {
        return $this->discountCriteria;
    }

    /**
     * @param string $couponType
     * @return $this
     */
    public function setDiscCriteria($couponType)
    {
        $this->discountCriteria = $couponType;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getExcItems()
    {
        return $this->excItems;
    }

    /**
     * @param string $excItems
     * @return $this
     */
    public function setExcItems($excItems)
    {
        $this->excItems = $excItems;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAccumulateDisc()
    {
        return $this->accumulateDisc;
    }

    /**
     * @param string $accumDisc
     * @return $this
     */
    public function setAccumulateDisc($accumDisc)
    {
        $this->accumulateDisc = $accumDisc;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEligibility()
    {
        return $this->eligibility;
    }

    /**
     * @param string $eligibility
     * @return $this
     */
    public function setEligibility($eligibility)
    {
        $this->eligibility = $eligibility;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLoyalPoints()
    {
        return $this->loyaltyPoints;
    }

    /**
     * @param string $loyalPoints
     * @return $this
     */
    public function setLoyalPoints($loyalPoints)
    {
        $this->loyaltyPoints = $loyalPoints;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNudgepromocode()
    {
        return $this->nudgepromocode;
    }

    /**
     * @param string $nudgepromocode
     * @return $this
     */
    public function setNudgepromocode($nudgepromocode)
    {
        $this->nudgepromocode = $nudgepromocode;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getNudgedescription()
    {
        return $this->nudgedescription;
    }

    /**
     * @param string $nudgedescription
     * @return $this
     */
    public function setNudgedescription($nudgedescription)
    {
        $this->nudgedescription = $nudgedescription;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAppliedCoupons()
    {
        return $this->appliedCoupons;
    }

    /**
     * @param string $appliedCoupons
     * @return $this
     */
    public function setAppliedCoupons($appliedCoupons)
    {
        $this->appliedCoupons = $appliedCoupons;
        return $this;
    }
    
    /**
     * Get the object as array
     * @return array
     */
    public function getDiscountData() {
        return array(
            'discValue' => $this->discValue,
            'discValueCode' => $this->discValueCode,
            'minPurchaseVal' => $this->minPurchaseVal,
            'receiptDisc' => $this->receiptDisc,
            'maxreceDisc' => $this->maxreceDisc
        );
    }
    
    /**
     * @return string
     */
    public function getDiscValue()
    {
        return $this->discValue;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDiscValue($value)
    {
        $this->discValue = $value;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDiscValueCode()
    {
        return $this->discValueCode;
    }

    /**
     * @param string $valueCode
     * @return $this
     */
    public function setDiscValueCode($valueCode)
    {
        $this->discValueCode = $valueCode;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getMinPurchaseVal()
    {
        return $this->minPurchaseVal;
    }

    /**
     * @param string $minPurchaseVal
     * @return $this
     */
    public function setMinPurchaseVal($minPurchaseVal)
    {
        $this->minPurchaseVal = $minPurchaseVal;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getReceiptDisc()
    {
        return $this->receiptDisc;
    }

    /**
     * @param string $recieDisc
     * @return $this
     */
    public function setReceiptDisc($recieDisc)
    {
        $this->receiptDisc = $recieDisc;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getMaxReceiptDisc()
    {
        return $this->maxreceDisc;
    }

    /**
     * @param string $maxReceiDisc
     * @return $this
     */
    public function setMaxReceiptDisc($maxReceiDisc)
    {
        $this->maxreceDisc = $maxReceiDisc;
        return $this;
    }
    
    /**
     * Get the object as array
     * @return array
     */
    public function getItemData() {
        return array(
            'itemSku' => $this->itemSku,
            'itemQty' => $this->itemQty,
            'itemDisc' => $this->itemDisc,
            'itemMaxDisc' => $this->itemMaxDisc,
            'rewardRatio' => $this->rewardRatio
        );
    }
    
    /**
     * @return string
     */
    public function getItemSku()
    {
        return $this->itemSku;
    }

    /**
     * @param string $itemSku
     * @return $this
     */
    public function setItemSku($itemSku)
    {
        $this->itemSku = $itemSku;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getItemQty()
    {
        return $this->itemQty;
    }

    /**
     * @param string $itemQty
     * @return $this
     */
    public function setItemQty($itemQty)
    {
        $this->itemQty = $itemQty;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getItemDisc()
    {
        return $this->itemDisc;
    }

    /**
     * @param string $itemDisc
     * @return $this
     */
    public function setItemDisc($itemDisc)
    {
        $this->itemDisc = $itemDisc;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getItemMaxDisc()
    {
        return $this->itemMaxDisc;
    }

    /**
     * @param string $itemMaxDisc
     * @return $this
     */
    public function setItemMaxDisc($itemMaxDisc)
    {
        $this->itemMaxDisc = $itemMaxDisc;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getRewardRatio()
    {
        return $this->rewardRatio;
    }

    /**
     * @param string $rewardRatio
     * @return $this
     */
    public function setRewardRatio($rewardRatio)
    {
        $this->rewardRatio = $rewardRatio;
        return $this;
    }

}
