<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 17.09.17-16:38
 */

namespace TBCore\Worker\General;

/**
 * Class Common
 * @package classes
 */
class Common
{
    /**
     * @param int $year
     * @return int
     */
    public function getFromTimestamp($year)
    {
        return mktime(0, 0, 0, 1, 1, $year);
    }

    public function getToTimestamp($year)
    {
        if (0 == $year) {
            $year = 3000;
        }
        return mktime(23, 59, 59, 12, 31, $year);
    }
}
