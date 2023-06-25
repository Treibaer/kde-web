<?php

namespace KDE\Controller;

use \TBCore\Api\Manager as CoreManager;
use KDE\Database\DbManager;
use KDE\Manager as AppManager;
use KDE\Model\User;
use KDE\Worker\Manager as WorkerManager;

/**
 * Class DefaultController
 * @package TB\Controller
 */
abstract class DefaultController extends \TBCore\Controller\DefaultController
{
    /**
     * @var DbManager
     */
    protected $dbManager;

    /**
     * @var CoreManager
     */
    protected $coreManager;

    /**
     * @var WorkerManager
     */
    protected $worker;

    /**
     * @var AppManager
     */
    protected $appManager;

    /**
     * @var bool
     */
    private $error404 = false;

    /**
     * DefaultController constructor.
     * @param $view
     * @param $request
     * @param AppManager $appManager
     */
    public function __construct(&$view, $request, $appManager)
    {
        parent::__construct($view, $request, $appManager->core());
        $this->dbManager = $appManager->database();
        $this->worker = $appManager->worker();
        $this->appManager = $appManager;
        $this->coreManager = $appManager->core();
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->dbManager->getUser();
    }

    public function error404()
    {
        $this->error404 = true;
    }

    public function render()
    {
        if ($this->error404) {
            return ["pageNotFound.html.twig"];
        }
        return null;
    }
}
