<?php

namespace KDE\Controller;

use KDE\Worker\Kde;

/**
 * Class BoardsController
 * @package TB\Controller\Kde
 */
class BoardsController extends DefaultController
{
    /**
     * @var Kde
     */
    private ?Kde $kde = null;

    public function start(): void
    {
        $this->kde = $this->worker->kde();
        $this->view->tabControl = $this->worker->tabControl("boards");
        $this->view->darkMode = true;
        $boardId = 0;
        if (isset($_GET['boardId'])) {
            $boardId = $_GET["boardId"];
            $this->view->page = "edit";
            $this->view->boardTitle = $this->dbManager->dbBoard()->getById($boardId)->getTitle();
            $this->view->board = $this->dbManager->dbBoard()->getById($boardId);
        } else {
            $this->view->page = "overview";
            $this->view->boards = $this->dbManager->dbBoard()->allSorted();
        }

        $this->view->version = $this->kde->getVersion();
        $this->view->boardId = $boardId;
        $this->view->enemies = [];
        $this->view->defaultRows = $this->kde->defaultRows();
        $this->view->defaultColumns = $this->kde->defaultColumns();
    }

    public function twigFunctions(\Twig_Environment &$twig)
    {
    }
}
