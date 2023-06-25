<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 17:55
 */

namespace TBCore\Worker;

/**
 * Class LoggingType
 * @package classes
 */
abstract class LoggingType {
    const QUERY = -1;
    const INFO = 0;
    const DEBUG = 1;
    const WARNING = 2;
    const FATAL = 3;
    const DISASTER = 4;
}
