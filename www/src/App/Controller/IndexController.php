<?php

namespace KDE\Controller;

/**
 * Class IndexController
 * @package EX\Controller
 */
class IndexController extends DefaultController
{
    public function start()
    {
        $this->view->title = "Kristalle der Ewigkeit";
        $this->view->darkMode = true;
        $this->view->tabControl = $this->worker->tabControl("index");
        $this->view->characterOfTheDay = $this->dbManager->dbCharacter()->characterOfTheDay();
        $cards = $this->dbManager->dbCard()->allByType('library');
        $cardOfTheDay = $cards[date('z') % count($cards)];
        $cardOfTheWeek = $cards[date('w') % count($cards)];
        $this->view->cardOfTheDay= $cardOfTheDay;
        $this->view->cardOfTheWeek= $cardOfTheWeek;
    }
}
