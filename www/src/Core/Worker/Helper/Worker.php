<?php

namespace TBCore\Worker\Helper;

/**
 * Class Worker
 * @package TBCore\Worker\Helper
 */
class Worker
{
    public function getDaysInAMonth($year = null)
    {
        if (null == $year) {
            $year = (int)date('Y');
        }
        return [31, (($year) % 4 == 0) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    }

    public function getMonths()
    {
        return ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
    }
}
