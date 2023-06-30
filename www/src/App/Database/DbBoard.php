<?php

namespace KDE\Database;

use KDE\Model\Board;

/**
 * Class DbBoard
 * @package KDE\Database
 */
class DbBoard extends Database
{
    static string $databaseName = 'KdeBoard';
    static $model = Board::class;

    /**
     * @return Board[]
     */
    public function all(): array
    {
        $output = [];
        /** @var Board[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            $output[$cards[$i]->getBoardId()] = $cards[$i];
        }
        return $output;
    }

    /**
     * @return Board[]
     */
    public function allSorted(): array
    {
        $boards = $this->all();
        usort($boards, function ($a, $b) {
            /** @var Board $a */
            /** @var Board $b */
            return $a->getTitle() > $b->getTitle();
        });
        return $boards;
    }

    /**
     * @return Board[]
     */
    public function allJSON(): array
    {
        $output = [];
        /** @var Board[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            $output[$cards[$i]->getBoardId()] = $cards[$i]->toJSONableObject();
        }
        return $output;
    }

    /**
     * @param $boardId
     * @return Board | null
     */
    public function getById($boardId): ?Board
    {
        /** @var Board[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            if ($cards[$i]->getBoardId() == $boardId) {
                return $cards[$i];
            }
        }
        return null;
    }
}
