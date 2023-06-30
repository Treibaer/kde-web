<?php

namespace KDE\Controller;

use KDE\Model\Enemy;

/**
 * Class EnemyController
 * @package KDE\Controller\Kde
 */
class EnemyController extends DefaultController
{
    public function start(): void
    {
        $this->view->darkMode = true;
        $type = $_GET['type'] ?? 'enemy';
        $this->view->tabControl = $this->worker->tabControl($type);
        $enemies = $this->dbManager->dbEnemy()->allByTypeJSON($type);
        usort($enemies, function ($a, $b) {
            return $a->name > $b->name;
        });
        $this->view->enemies = $enemies;

        $dummy = new Enemy();
        $dummy->setType($type);
        $this->view->dummyEnemy = $dummy->toJSONableObject();
        $this->view->backgroundCardUrl = $this->worker->kde()->backgroundCardUrl();
        $this->view->backgroundSmallCardUrl = $this->worker->kde()->backgroundSmallCardUrl();
    }

    public function twigFunctions(\Twig_Environment &$twig): void
    {
        $twig->addFunction(new \Twig_SimpleFunction('select', function ($title, $id, $minValue, $maxValue) {
            $return = "<td>$title</td><td><select id='$id'>";
            for ($i = $minValue; $i <= $maxValue; $i++) {
                $return .= "<option>$i</option>";
            }
            $return .= "</select></td>";
            return $return;
        }));
    }
}
