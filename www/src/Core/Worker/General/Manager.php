<?php

namespace TBCore\Worker\General;

use TBCore\Api\Manager as ApiManager;

class Manager
{
    /**
     * @var array
     */
    private $setter;

    /**
     * @var ApiManager $apiManager
     */
    private $apiManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * @return Login
     */
    public function login()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new Login($this->apiManager);
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return Check
     */
    public function check()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new Check($this->apiManager);
        }
        return $this->setter[__FUNCTION__];
    }
}
