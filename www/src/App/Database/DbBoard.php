<?php

namespace KDE\Database;

use KDE\Model\Board;

/**
 * Class DbBoard
 * @package KDE\Database
 */
class DbBoard extends Database
{
    static $databaseName = 'KdeBoard';
    static $model = Board::class;

    /**
     * @return Board[]
     */
    public function all()
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
    public function allSorted()
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
    public function allJSON()
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
     * @return Board || null
     */
    public function getById($boardId)
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
