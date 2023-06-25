<?php

namespace TBCore\Worker;

/**
 * Class Config
 * @package TBCore\Worker
 */
class Config
{
    /**
     * @var mixed
     */
    private static $json = null;

    private static function init()
    {
        $path = getenv('ROOT') . '../conf/config.json';
        $environment = getenv('PRODUCTION') == 1 ? 'production' : 'development';
        if (getenv('CUSTOM') == 1) {
            $environment = "custom";
        }
        static::$json = json_decode(file_get_contents($path))->$environment;
    }

    public static function get($key)
    {
        if (null == static::$json) {
            static::init();
        }
        return static::$json->$key;
    }
}
