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
    protected DbManager $dbManager;

    protected CoreManager $coreManager;

    protected WorkerManager $worker;

    protected AppManager $appManager;

    private bool $error404 = false;

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
    public function getUser(): User
    {
        return $this->dbManager->getUser();
    }

    public function error404(): void
    {
        $this->error404 = true;
    }

    public function render(): ?array
    {
        if ($this->error404) {
            return ["pageNotFound.html.twig"];
        }
        return null;
    }
}
