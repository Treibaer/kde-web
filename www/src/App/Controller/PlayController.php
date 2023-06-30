<?php

namespace KDE\Controller;

use KDE\Worker\Kde;

/**
 * Class PlayController
 * @package TB\Controller\Kde
 */
class PlayController extends DefaultController
{
    private ?Kde $kde = null;

    public function start(): void
    {
        // character overview
        if (isset($_GET["characters"])) {
            $chars = $this->dbManager->dbCharacter()->all();
            foreach ($chars as $char) {
                echo "<img style='width:400px' src='" . $char->getFullCardUrl() . "'>'";
            }
            die;
        }
        $userId = null;
        $darkMode = true;

        // play controller is public, so we have to check if we are logged in
        if ($this->getUser() !== null) {
            $userId = $this->getUser()->getUserId();
        }
        $gameId = isset($_GET["gameId"]) ? $_GET["gameId"] : 0;

        $game = $this->dbManager->dbGame()->getById($gameId);

        if (isset($_GET["userId"]) && (strpos($_GET["userId"], "G") !== false)) {
            $userId = $_GET["userId"];
            $game = $this->dbManager->dbGame()->getByUserId($userId);
        }


        if ($game == null) {
            header("HTTP/1.0 404 Not Found");
            echo "404";
            die;
        }
        //check if takes part
        $takesPart = false;
        $takesPartAsAPlayer = false;

        if ($game->getLeader() === (int)$userId) {
            $takesPart = true;
        }
        $players = json_decode($game->getPlayers());
        foreach ($players as $gameUserId => $player) {
            if ($player->id == $userId) {
                $this->view->gameUserId = $gameUserId;
                $takesPart = true;
                $takesPartAsAPlayer = true;
                break;
            }
        }

        if (!$takesPart) {
            header("HTTP/1.0 404 Not Found");
            echo "404";
            die;
        }

        // fetch boardId, etc.
        $boardId = $game->getBoardId();
        $this->view->tabControl = $this->worker->tabControl("play");
        $this->view->darkMode = true;
        $this->kde = $this->worker->kde();
        $this->view->boardId = $boardId;
        $this->view->board = $this->dbManager->dbBoard()->getById($boardId);
        $this->view->gameId = $game->getGameId();
        $this->view->version = $this->kde->getVersion();
        $this->view->kdeUserId = $userId;

        $isLeader = $userId == $game->getLeader();
        $this->view->isLeader = $isLeader;
        $this->view->myPlayerId = $userId;
        $this->view->states = $this->kde->getStates();
        $this->view->page = "play";
        $this->view->backgroundCardUrl = $this->worker->kde()->backgroundCardUrl();
        $this->view->backgroundSmallCardUrl = $this->worker->kde()->backgroundSmallCardUrl();
        $this->view->backgroundCharacterCardUrl = $this->worker->kde()->backgroundCharacterCardUrl();


        $users = $this->dbManager->dbUser()->getUsersAsAssocArray();
        $userNames = [];
        foreach ($users as $user) {
            $userNames[$user->getUserId()] = [
                "id" => $user->getUserId(),
                "user" => $user->getUserName()
            ];
        }

        $players = json_decode($game->getPlayers());
        foreach ($players as $key => &$player) {
            $playerId = $player->id;
            if ($player->name === "") {
                $player->name = $userNames[$playerId]["user"];
            }
        }


        if ($isLeader) {


            $this->view->game = $game;
            $cards = $this->dbManager->dbCard()->allJSON();

            usort($cards, function ($a, $b) {
                return $b->title < $a->title;
            });

            $this->view->cards = $cards;


        }
        $this->view->customPlayers = (array)$players;
        $this->view->enemies = $this->kde->getEnemies();

        usort($this->view->enemies, function ($a, $b) {
            return $a->name > $b->name;
        });
        $this->view->pets = $this->kde->getPets();

        usort($this->view->pets, function ($a, $b) {
            return $a->name > $b->name;
        });
        $this->view->events = $this->kde->getEvents();

        usort($this->view->events, function ($a, $b) {
            return $a->name > $b->name;
        });

        $this->view->takesPart = $takesPartAsAPlayer;
    }

    public function twigFunctions(\Twig_Environment &$twig)
    {
    }
}
