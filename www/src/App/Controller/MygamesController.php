<?php

namespace KDE\Controller;

/**
 * Class MygamesController
 * @package KDE\Controller
 */
class MygamesController extends DefaultController
{
    public function start(): void
    {
        $this->view->tabControl = $this->worker->tabControl("mygames");
        $this->view->darkMode = true;

        $users = $this->dbManager->dbUser()->getUsersAsAssocArray();
        $userNames = [];
        $guestNames = [];
        foreach ($users as $user) {
            $userNames[$user->getUserId()] = [
                "id" => $user->getUserId(),
                "user" => $user->getUserName(),
            ];
        }
        $dbGames = $this->dbManager->dbGame()->myGames();
        $games = [];
        $leader = 1;
        $gameUrl = "/play?gameId=";
        $guestGameUrl = "/play?userId=";
        foreach ($dbGames as $game) {
            $leader = $game->leader;
            if (isset($userNames[$leader])) {
                $leader = $userNames[$leader]["user"];
            }
            $players = (array)json_decode($game->players);
            foreach ($players as $key => &$player) {
                if ($player->name === "") {
                    $player->name = $userNames[$player->id]["user"];
                    if ($player->id == $this->getUser()->getUserId()) {
                        $player->url = $gameUrl . $game->gameId;
                    }
                } else { // guest
                    $player->url = $guestGameUrl . $player->id;
                    $guestNames[] = $player->name;
                }
                if ($player->id . "" === "" . $leader) {
                    $leader = $player->name;
                }
            }
            $games[] = [
                "gameId" => $game->gameId,
                "boardId" => $game->boardId,
                "leader" => $leader,
                "players" => $players,
            ];
        }

        $this->view->gameUrl = $gameUrl;
        $this->view->games = $games;
        $this->view->users = $userNames;
        $this->view->boards = $this->dbManager->dbBoard()->allSorted();
    }

    public function twigFunctions(\Twig_Environment &$twig)
    {
    }
}
