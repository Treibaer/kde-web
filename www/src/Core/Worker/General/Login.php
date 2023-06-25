<?php

namespace TBCore\Worker\General;

use TBCore\Api\Manager as ApiManager;
use TBCore\Model\User;

/**
 * Class Login
 * @package Worker\General
 */
class Login
{
    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * Login constructor.
     * @param ApiManager $apiManager
     */
    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * @return User
     */
    public function init()
    {
        $user = null;
        $sessionId = null;
        if (!empty($_COOKIE['sessionid'])) {
            $sessionId = $_COOKIE['sessionid'];
        }
        if (!empty($_REQUEST['sessionId'])) {
            $sessionId = $_REQUEST['sessionId'];
        }
        if (null !== $sessionId) {
            $user = $this->apiManager->database()->dbUser()->getUserBySessionId($sessionId);
            if (null !== $user) {
                $this->apiManager->database()->setUser($user);
            }
        }
        return $user;
    }

    /**
     *
     */
    public function logout()
    {
        setcookie("sessionid", "", -1);
    }
}
