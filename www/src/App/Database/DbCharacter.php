<?php

namespace KDE\Database;

use KDE\Model\Character;

/**
 * Class DbCharacter
 * @package KDE\Database
 */
class DbCharacter extends Database
{
    static string $databaseName = 'KdeCharacter';
    static $model = Character::class;

    /**
     * @return Character[]
     */
    public function all(): array
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
    public function characterOfTheDay(): Character
    {
        /** @var Character[] $chars */
        $chars = $this->get('*');
        return $chars[date('z') % count($chars)];
    }

    /**
     * @return array
     */
    public function allJSON(): array
    {
        $characters = $this->all();
        foreach ($characters as &$value) {
            $value = $value->toJSONableObject();
        }
        return $characters;
    }
}
