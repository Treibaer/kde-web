<?php

namespace KDE\Database;

use KDE\Model\Card;

/**
 * Class DbCard
 * @package KDE\Database
 */
class DbCard extends Database
{
    static $databaseName = 'KdeCard';
    static $model = Card::class;

    /**
     * @return Card[]
     */
    public function all()
    {
        $output = [];
        /** @var Card[] $cards */
        $cards = $this->get('*');

        for ($i = 0; $i < count($cards); $i++) {
            $output[$cards[$i]->getCardId()] = $cards[$i];
        }
        return $output;
    }

    /**
     * @param $title
     * @return null|Card
     */
    public function getByTitle($title)
    {
        $d = $this->get('*', [['title', '=', $title]]);
        if (count($d) > 0) {
            return $d[0];
        }
        return null;
    }

    /**
     * @param $type
     * @return Card[]
     */
    public function allByType($type)
    {
        return $this->get('*', [['type', '=', $type]]);
    }

    /**
     * @param $type
     * @return Card[]
     */
    public function allByTypeJSON($type)
    {
        $characters = $this->allByType($type);
        foreach ($characters as &$value) {
            $value = $value->toJSONableObject();
        }
        return $characters;
    }

    public function remove($cardId)
    {
        $this->q('DELETE FROM ' . static::$databaseName . ' WHERE cardId=' . $cardId);
    }

    /**
     * @return []
     */
    public function allJson()
    {
        $characters = $this->all();
        foreach ($characters as &$value) {
            $value = $value->toJSONableObject();
        }
        return $characters;
    }
}
