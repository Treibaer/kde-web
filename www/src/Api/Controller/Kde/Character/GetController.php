<?php

namespace KDEApi\Controller\Kde\Character;

use KDE\Controller\DefaultController;

/**
 * Class GetController
 * @package KDEApi\Controller\Kde\Character
 */
class GetController extends DefaultController
{
    public function start()
    {
        header('Content-Type: application/json; charset: utf8');
        $characters = $this->dbManager->dbCharacter()->allJSON();
        echo json_encode(['data' => $characters]);
        die;
    }
}
