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
class Opt_Rewards_DigitalReceipt_ResponseBuilder
{
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var string
     */
    private $status = '';
    /**
     * @var string
     */
    private $message = '';

    /**
     * @return array|null
     * @throws Exception
     */
    public function build()
    {
        $receiptData = $this->data;
        if (!isset($receiptData['RESPONSEINFO']['STATUS'])) {
            throw new Exception(__('An error occurred, please try again'));
        }
        if (isset($receiptData['RESPONSEINFO']['STATUS']) && is_array($receiptData['RESPONSEINFO']['STATUS'])) {
            $setResponse = '';
            foreach ($receiptData['RESPONSEINFO'] as $resData) {
                $status = isset($resData['STATUS']) ? $resData['STATUS'] : 'Failure';
                $message = isset($resData['MESSAGE']) ? $resData['MESSAGE'] : '';
                $this->setStatus($status)->setMessage($message);
                $setResponse = $this->getReceiptResponse();
            }
            return $setResponse;
        }
        return null;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Get the object as array
     * @return array
     */
    public function getReceiptResponse()
    {
        return array(
            'status' => $this->status,
            'message' => $this->message
        );
    }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
