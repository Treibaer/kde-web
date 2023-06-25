<?php

namespace KDE\Controller;

use KDE\Model\Character;

/**
 * Class CharacterController
 * @package TB\Controller\Kde
 */
class CharacterController extends DefaultController
{
    public function start()
    {
        $this->view->darkMode = true;
        $this->view->tabControl = $this->worker->tabControl('character');
        $this->view->characters = $this->dbManager->dbCharacter()->allJSON();
        $this->view->dummyCharacter = (new Character())->toJSONableObject();
        $this->view->enemies = [];
        $this->view->backgroundCharacterCardUrl = $this->worker->kde()->backgroundCharacterCardUrl();
        $this->view->backgroundCardUrl = $this->worker->kde()->backgroundCardUrl();
    }

    public function twigFunctions(\Twig_Environment &$twig)
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
