<?php

namespace KDEApi\Controller\Kde\Enemy;

use KDE\Controller\DefaultController;
use KDE\Model\Character;
use KDE\Model\Enemy;

/**
 * Class EditController
 * @package KDEApi\Controller\Kde\Enemy
 */
class EditController extends DefaultController
{
    public function start()
    {
        if (!isset($_REQUEST['kdeEnemyId'])) {
            echo "{'success': false}";
            die;
        }
        $enemyId = $_REQUEST['kdeEnemyId'];

        $enemy = new Enemy();
        if ($enemyId != -1) {
            $existingCharacter = $this->dbManager->dbEnemy()->get('*', [['kdeEnemyId', '=', $enemyId]], []);
            if (count($existingCharacter) > 0) {
                $enemy = $existingCharacter[0];
            }
        }
        $enemy->enrichFromArray($_REQUEST);
        if ($enemyId == -1) {
            $enemy->setKdeEnemyId(0);
        }
        $this->dbManager->dbEnemy()->persist($enemy);
        echo "{'success': true}";
        die;
    }
}
