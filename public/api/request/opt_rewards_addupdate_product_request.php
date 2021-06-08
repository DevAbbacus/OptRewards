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

class Opt_Rewards_Product_AddUpdate_RequestBuilder {

     /**
     * @var int
     */
    private $requestId;
    /**
     * @var int
     */
    private $productId;
     /**
     * @var array
     */
    private $category;
    /**
     * @var string
     */
    private $productName;
    /**
     * @var string
     */
    private $sku;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $shortDescription;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $price;
    /**
     * @var string
     */
    private $taxStatus;
    /**
     * @var string
     */
    private $taxClass;
    /**
     * @var array
     */
    private $colorValue;
    /**
     * @var array
     */
    private $sizeValue;
    /**
     * @var array
     */
    private $brandValue;

    /**
     * @var string
     */
    private $parentSku;



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
        
        $requestmarge["HEADERINFO"] = [
            "REQUESTID" => $this->requestId
        ];
        $requestmarge["USERDETAILS"] = [
            "USERNAME" => $opt_data->getUsername(),
            "ORGID" => $opt_data->getOrganizationId(),
            "TOKEN" => $opt_data->getToken()
        ];
        $requestmarge["SKUINFO"] = [
            "ITEMSID" => $this->productId,
            "ITEMCATEGORY" => $this->category,
            "STORENUMBER" => $opt_data->getStoreNumber(),
            "SKU" => $this->sku,
            "DESCRIPTION" => $this->shortDescription,
            "LISTPRICE" => $this->price,
            "DCS" => "",
            "CLASSCODE" => "",
            "SUBCLASSCODE" => "",
            "DEPARTMENTCODE" => "",
            "VENDORCODE" => "",
            "UDF1" => $this->productName,
            "UDF2" => $this->type,
            "UDF3" => $this->taxStatus,
            "UDF4" => $this->taxClass,
            "UDF5" => $this->parentSku,
            "UDF6" => $this->colorValue,
            "UDF7" => $this->sizeValue,
            "UDF8" => $this->brandValue,
            "UDF9" => "",
            "UDF10" => "",
            "UDF11" => "",
            "UDF12" => "",
            "UDF13" => "",
            "UDF14" => "",
            "UDF15" => "",
        ];
        
        $request["UPDATESKUREQUEST"] = $requestmarge;
        
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
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }


    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return String
     */
    public function getProductName()
    {
        return $this->productName;
    }

     /**
     * @param String $productName
     * @return $this
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return String
     */
    public function getSku()
    {
        return $this->sku;
    }

     /**
     * @param String $sku
     * @return $this
     */
    public function setsku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return String
     */
    public function getProductType()
    {
        return $this->type;
    }

     /**
     * @param String $type
     * @return $this
     */
    public function setProductType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return String
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

     /**
     * @param String $shortDescription
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }


    /**
     * @return String
     */
    public function getPrice()
    {
        return $this->price;
    }

     /**
     * @param String $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return String
     */
    public function getTaxStatus()
    {
        return $this->taxStatus;
    }

     /**
     * @param String $taxStatus
     * @return $this
     */
    public function setTaxStatus($taxStatus)
    {
        $this->taxStatus = $taxStatus;
        return $this;
    }

    /**
     * @return String
     */
    public function getTaxClass()
    {
        return $this->taxClass;
    }

     /**
     * @param String $taxClass
     * @return $this
     */
    public function setTaxClass($taxClass)
    {
        $this->taxClass = $taxClass;
        return $this;
    }

    /**
     * @return String
     */
    public function getColorValue()
    {
        return $this->colorValue;
    }

     /**
     * @param String $colorValue
     * @return $this
     */
    public function setColorValue($colorValue)
    {
        $this->colorValue = $colorValue;
        return $this;
    }

    /**
     * @return String
     */
    public function getSizeValue()
    {
        return $this->sizeValue;
    }

     /**
     * @param String $sizeValue
     * @return $this
     */
    public function setSizeValue($sizeValue)
    {
        $this->sizeValue = $sizeValue;
        return $this;
    }

    /**
     * @return String
     */
    public function getBrandValue()
    {
        return $this->brandValue;
    }

     /**
     * @param String $brandValue
     * @return $this
     */
    public function setBrandValue($brandValue)
    {
        $this->brandValue = $brandValue;
        return $this;
    }

    /**
     * @return String
     */
    public function getParentSku()
    {
        return $this->parentSku;
    }

    /**
     * @param String $parentSku
     * @return $this
     */
    public function setParentSku($parentSku)
    {
        $this->parentSku = $parentSku;
        return $this;
    }
    
}
