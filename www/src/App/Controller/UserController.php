<?php

namespace KDE\Controller;


/**
 * Class UserController
 * @package KDE\Controller\Kde
 */
class UserController extends DefaultController
{
    public function start(): void
    {
        $this->view->users = $this->dbManager->dbUser()->getUsersAsAssocArray();
        $this->view->tabControl = $this->worker->tabControl("user");
        $this->view->darkMode = true;
        $this->view->isAdmin = $this->dbManager->getUser()->isAdmin;
    }

    public function twigFunctions(\Twig_Environment &$twig): void
    {
        $twig->addFunction(new \Twig_SimpleFunction('formatDate', function ($timestamp) {
            return date("d.m.Y", $timestamp);
        }));
    }
}
