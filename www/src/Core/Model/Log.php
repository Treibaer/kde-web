<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 08.01.17
 * Time: 00:43
 */

namespace TBCore\Model;

use TBCore\Worker\LoggingType;

/**
 * Class Log
 * @Annotation\Table("Log")
 * @package TBCore\Model
 */
class Log extends Model {
    /**
     * @var int
     */
    protected $logId;
    /**
     * @var int
     */
    protected $time;
    /**
     * @var LoggingType
     */
    protected $loggingType;
    /**
     * @var string
     */
    protected $errorMessage;
    /**
     * @var string
     */
    protected $stackTrace;

    /**
     * @var string
     */
    protected $errorFile;

    /**
     * @var string
     */
    protected $postData;
    /**
     * @var string
     */
    protected $getData;

    /**
     * @return int
     */
    public function getLogId() {
        return $this->logId;
    }

    /**
     * @param int $logId
     * @return Log
     */
    public function setLogId($logId) {
        $this->logId = $logId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * @param int $time
     * @return Log
     */
    public function setTime($time) {
        $this->time = $time;
        return $this;
    }

    /**
     * @return LoggingType
     */
    public function getLoggingType() {
        return $this->loggingType;
    }

    /**
     * @param LoggingType $loggingType
     * @return Log
     */
    public function setLoggingType($loggingType) {
        $this->loggingType = $loggingType;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     * @return Log
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getStackTrace() {
        return $this->stackTrace;
    }

    /**
     * @param string $stackTrace
     * @return Log
     */
    public function setStackTrace($stackTrace) {
        $this->stackTrace = $stackTrace;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorFile() {
        return $this->errorFile;
    }

    /**
     * @param string $errorFile
     * @return Log
     */
    public function setErrorFile($errorFile) {
        $this->errorFile = $errorFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostData() {
        return $this->postData;
    }

    /**
     * @param string $postData
     * @return Log
     */
    public function setPostData($postData) {
        $this->postData = $postData;
        return $this;
    }

    /**
     * @return string
     */
    public function getGetData() {
        return $this->getData;
    }

    /**
     * @param string $getData
     * @return Log
     */
    public function setGetData($getData) {
        $this->getData = $getData;
        return $this;
    }

}
