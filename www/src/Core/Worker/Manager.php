<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 09.12.17
 * Time: 18:53
 */

namespace TBCore\Worker;

use \TBCore\Database\DbManager;
use \TBCore\Api\Manager as ApiManager;
use \TBCore\Worker\Helper\Manager as HelperManager;
use \TBCore\Worker\General\Manager as GeneralManager;

class Manager
{
    /**
     * @var array
     */
    private $setter;

    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * @var DbManager
     */
    private $dbManager;

    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
        $this->dbManager = $apiManager->database();
    }

    /**
     * @return HelperManager
     */
    public function helper()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new HelperManager();
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return GeneralManager
     */
    public function general()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new GeneralManager($this->apiManager);
        }
        return $this->setter[__FUNCTION__];
    }
}
