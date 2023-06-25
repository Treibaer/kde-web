<?php

namespace KDE\Database;

use KDE\Model\Kde\KdeAchievement;

/**
 * Class DbAchievement
 * @package TB\Database
 */
class DbAchievement extends Database
{
    static $databaseName = 'KdeAchievement';
    static $model = Achievement::class;

    /**
     * @return Achievement[]
     */
    public function all()
    {
        $achievements = [];
        /** @var Achievement[] $chars */
        $chars = $this->get('*');

        for ($i = 0; $i < count($chars); $i++) {
            $achievements[$chars[$i]->getAchievementId()] = $chars[$i]->toJSONableObject();
        }
        return $achievements;
    }
}
