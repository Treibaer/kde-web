<?php

namespace KDE\Database;

use KDE\Model\GameActivity;

/**
 * Class DbGameActivity
 * @package KDE\Database
 */
class DbGameActivity extends Database
{
    static $databaseName = 'KdeGameActivity';
    static $model = GameActivity::class;

    /**
     * @return DbGameActivity[]
     */
    public function all()
    {
        $output = [];
        /** @var [] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            $output[$cards[$i]->getGameId()] = $cards[$i]->toJSONableObject();
        }
        return $output;
    }

    /**
     * @return []
     */
    public function getById($gameId)
    {
        $output = [];
        /** @var Game[] $cards */
        $cards = $this->get('*', [["gameId", "=", $gameId]], [["activityDate", "DESC"]], 10);

        for ($i = 0; $i < count($cards); $i++) {
            $output[] = $cards[$i]->toJSONableObject();
        }
        return array_reverse($output);
    }
}
