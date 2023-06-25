<?php

/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 29.12.16
 * Time: 20:04
 */

namespace TBCore\Model;

use TBCore\Annotation;

/**
 * Class Session
 * @Annotation\Table("Session")
 * @package TBCore\Model
 */
class Session extends Model
{
    /**
     * @var int
     */
    protected $sessionId;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var int
     */
    protected $dateCreated;

    /**
     * @var int
     */
    protected $dateValidation;

    /**
     * @var bool
     */
    protected $isValid;

    /**
     * @var string
     */
    protected $browser;

    /**
     * @var string
     */
    protected $device;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $value;

    /**
     * @return int
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param int $sessionId
     * @return Session
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Session
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param int $dateCreated
     * @return Session
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * @param int $dateValidation
     * @return Session
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param string $browser
     * @return Session
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param string $device
     * @return Session
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return Session
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Session
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return Session
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }
}
