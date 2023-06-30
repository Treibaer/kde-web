<?php

namespace KDE\Database;

use KDE\Model\Game;

/**
 * Class DbGame
 * @package KDE\Database
 */
class DbGame extends Database
{
    static string $databaseName = 'KdeGame';
    static $model = Game::class;

    /**
     * @return Game[]
     */
    public function all(): array
    {
        $output = [];
        /** @var Game[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            $output[$cards[$i]->getGameId()] = $cards[$i]->toJSONableObject();
        }
        return $output;
    }

    /**
     * @return Game[]
     */
    public function myGames(): array
    {
        $output = [];
        /** @var Game[] $games */
        $games = $this->get('*');

        for ($i = 0; $i < count($games); $i++) {
            $game = $games[$i];
            $isLeader = (int)$game->leader === $this->dbManager->getUser()->getUserId();
            $players = json_decode($game->players);
            $takesPart = false;
            foreach ($players as $player) {
                if ((int)$player->id == $this->dbManager->getUser()->getUserId()) {
                    $takesPart = true;
                    break;
                }
            }
            if ($isLeader || $takesPart) {
                $output[$game->getGameId()] = $game->toJSONableObject();
            }
        }
        return $output;
    }

    /**
     * @param $gameId
     * @return Game|null
     */
    public function getById($gameId): ?Game
    {
        /** @var Game[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            if ($cards[$i]->getGameId() == $gameId) {
                return $cards[$i];
            }
        }
        return null;
    }

    /**
     * @return Game || null
     */
    public function getByUserId($userId): ?Game
    {
        /** @var Game[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            if (str_contains($cards[$i]->getPlayers(), $userId)) {
                return $cards[$i];
            }
        }
        return null;
    }
}
