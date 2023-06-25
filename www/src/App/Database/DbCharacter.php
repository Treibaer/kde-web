<?php

namespace KDE\Database;

use KDE\Model\Character;

/**
 * Class DbCharacter
 * @package KDE\Database
 */
class DbCharacter extends Database
{
    static $databaseName = 'KdeCharacter';
    static $model = Character::class;

    /**
     * @return Character[]
     */
    public function all()
    {
        $characters = [];
        /** @var Character[] $chars */
        $chars = $this->get('*');

        for ($i = 0; $i < count($chars); $i++) {
            $characters[$chars[$i]->getCharacterId()] = $chars[$i];
        }
        return $characters;
    }

    /**
     * @return Character
     */
    public function characterOfTheDay()
    {
        /** @var Character[] $chars */
        $chars = $this->get('*');
        return $chars[date('z') % count($chars)];
    }

    /**
     * @return []
     */
    public function allJSON()
    {
        $characters = $this->all();
        foreach ($characters as &$value) {
            $value = $value->toJSONableObject();
        }
        return $characters;
    }
}
