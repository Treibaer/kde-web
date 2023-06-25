<?php

namespace TBCore\Worker;

/**
 * Class Environment
 * @package classes
 */
class Environment
{
    static $initialized = false;
    protected $mode;
    public static $port = 443;
    public static $url = null;

    public function __construct()
    {
        if (!static::$initialized) {
            $this->init();
            Environment::$url = Config::get('publicURL');
        }
    }

    public function init()
    {
        if (getenv('PRODUCTION') == 1) {
            $this->mode = EnvironmentType::LIVE;
        } else {
            $this->mode = EnvironmentType::TEST;
        }
        static::$initialized = true;
    }

    public function getMode()
    {
        return $this->mode;
    }
}
