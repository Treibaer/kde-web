<?php

namespace KDE\Database;

use KDE\Model\Enemy;

/**
 * Class DbEnemy
 * @package KDE\Database
 */
class DbEnemy extends Database
{
    static $databaseName = 'KdeEnemy';
    static $model = Enemy::class;

    /**
     * @return Enemy[]
     */
    public function all()
    {
        $characters = [];
        /** @var Enemy[] $chars */
        $chars = $this->get('*');

        for ($i = 0; $i < count($chars); $i++) {
            $characters[$chars[$i]->getKdeEnemyId()] = $chars[$i];
        }
        return $characters;
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

    /**
     * @return []
     */
    public function allByTypeJSON($type)
    {
        $characters = $this->allByType($type);
        foreach ($characters as &$value) {
            $value = $value->toJSONableObject();
        }
        return $characters;
    }

    /**
     * @param string $type
     * @return Enemy[]
     */
    public function allByType($type)
    {
        $characters = [];
        /** @var Enemy[] $chars */
        $chars = $this->get('*', [['type', '=', $type]]);

        for ($i = 0; $i < count($chars); $i++) {
            $characters[$chars[$i]->getKdeEnemyId()] = $chars[$i];
        }
        return $characters;
    }
}
