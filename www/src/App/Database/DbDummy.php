<?php

namespace KDE\Database;

use TBCore\Model\Dummy;

/**
 * Class DbDummy
 * @package TB\Database
 */
class DbDummy extends Database
{
    static $model = Dummy::class;
}
