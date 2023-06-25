<?php

namespace KDEApi\Controller\Kde\Card;

use KDE\Controller\DefaultController;

/**
 * Class RemoveController
 * @package TBApi\Controller\Kde\Enemy
 */
class RemoveController extends DefaultController
{
    public function start()
    {
        if (!isset($_REQUEST['cardId'])) {
            echo "{'success': false}";
            die;
        }
        $cardId = $_REQUEST['cardId'];
        $this->dbManager->dbCard()->remove($cardId);
        echo "{'success': true}";
        die;
    }
}
