<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 09.12.17
 * Time: 12:02
 */

namespace TBCore\Controller;

use TBCore\Api\Manager as CoreManager;
use TBCore\Database\DbManager;
use http\Env\Request;
use TBCore\Worker\Manager as WorkerManager;

abstract class DefaultController
{
    /**
     * @var object
     */
    protected $view;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DbManager
     */
    protected $coreDbManager;

    /**
     * @var WorkerManager
     */
    protected $coreWorker;

    /**
     * @var CoreManager
     */
    protected $apiManager;

    public function decode()
    {
        foreach ($_REQUEST as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $_REQUEST[$key] = $this->coreWorker->helper()->url()->decode($value);
        }
    }

    /**
     * DefaultController constructor.
     * @param $view
     * @param $request
     * @param CoreManager $apiManager
     */
    public function __construct(&$view, $request, CoreManager $apiManager)
    {
        $this->view = $view;
        $this->request = $request;
        $this->coreDbManager = $apiManager->database();
        $this->coreWorker = $apiManager->worker();
        $this->apiManager = $apiManager;
        $this->request = $request;
    }

    public function prepare()
    {
    }

    abstract public function start();

    public function render()
    {
        return null;
    }

    public function twigFunctions(\Twig_Environment &$twig)
    {
    }
}
