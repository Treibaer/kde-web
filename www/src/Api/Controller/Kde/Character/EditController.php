<?php

namespace KDEApi\Controller\Kde\Character;

use KDE\Controller\DefaultController;
use KDE\Model\Character;

/**
 * Class EditController
 * @package KDEApi\Controller\Kde\Character
 */
class EditController extends DefaultController
{
    public function start()
    {
        if (!isset($_REQUEST['characterId'])) {
            echo "{'success': false}";
            die;
        }
        $characterId = $_REQUEST['characterId'];

        $character = new Character();
        if ($characterId != -1) {
            $existingCharacter = $this->dbManager->dbCharacter()->get('*', [['characterId', '=', $characterId]], []);
            if (count($existingCharacter) > 0) {
                $character = $existingCharacter[0];
            }
        }
        foreach($_REQUEST as $key => $value) {
            $_REQUEST[$key] = str_replace("230j2d3p903j", "+", $value);
        }
        $character->enrichFromArray($_REQUEST);
        if ($characterId == -1) {
            $character->setCharacterId(0);
        }
        $this->dbManager->dbCharacter()->persist($character);
        echo "{'success': true}";
        die;
    }
}
