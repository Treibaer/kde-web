<?php

namespace KDE\Controller;

use KDE\Model\Card;

/**
 * Class CardsController
 * @package KDE\Controller\Kde
 */
class CardsController extends DefaultController
{
    public function start()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : 'library';
        $this->view->tabControl = $this->worker->tabControl("cards");
        $this->view->subTabControl = $this->worker->kde()->subTabControl('cards', $type);
        $this->view->darkMode = true;
        $cards = $this->dbManager->dbCard()->allByType($type);
        $this->view->type = $type;
        usort($cards, function (Card $a, Card $b) {
            return $a->getTitle() > $b->getTitle();
        });
        $this->view->allCards = $cards;
        $this->view->backgroundCardUrl = $this->worker->kde()->backgroundCardUrl();
        $this->view->backgroundSmallCardUrl = $this->worker->kde()->backgroundSmallCardUrl();
    }

    public function twigFunctions(\Twig_Environment &$twig)
    {
    }
}
