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
class Opt_Rewards_OtpIssue_ResponseBuilder
{
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var string
     */
    public $otpCode;
    /**
     * @var string
     */
    public $errorCode;
    /**
     * @var string
     */
    public $message = '';
    /**
     * @var string
     */
    public $status;

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function build()
    {
        $otpIssueRes = $this->data;
        if (!isset($otpIssueRes['status'])) {
            throw new Exception(__('An error occurred, please try again'));
        }
      
        if ($otpIssueRes['status']['status'] === 'Failure') {
            throw new Exception(__($otpIssueRes['status']['message']));
        }
        if ($otpIssueRes && is_array($otpIssueRes)) {
            if (isset($otpIssueRes['otpCode']) && $otpIssueRes['status']['status'] === 'Success') {
                $this->setOtpCode($otpIssueRes['otpCode']);
            }
            if (isset($otpIssueRes['status']['errorCode'])) {
                $this->setErrorCode($otpIssueRes['status']['errorCode']);
            }
            if (isset($otpIssueRes['status']['message'])) {
                $this->setMessage($otpIssueRes['status']['message']);
            }
            if (isset($otpIssueRes['status']['status'])) {
                $this->setStatus($otpIssueRes['status']['status']);
            }
        }
        return $this->getOtpIssueData();
    }
    
    /**
     * @param string $otpCode
     * @return $this
     */
    public function setOtpCode($otpCode)
    {
        $this->otpCode = $otpCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getOtpCode()
    {
        return $this->otpCode;
    }
    
    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
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
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getOtpIssueData()
    {
        return [
            'errorCode' => $this->errorCode,
            'message' => $this->message,
            'status' => $this->status,
            'otpCode' => $this->otpCode
        ];
    }
}
