<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 09.12.17
 * Time: 18:51
 */

namespace TBCore\Database;

use TBCore\Model\Dummy;

/**
 * Class DbDummy
 * @package TBCore\Database
 */
class DbDummy extends Database
{
    static $model = Dummy::class;
}
