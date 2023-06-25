<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 09.12.17
 * Time: 19:12
 */

namespace TBCore\Worker\Helper;

/**
 * Class Manager
 * @package Worker\Helper
 */
class Manager
{
    /**
     * @var array
     */
    private $setter;

    /**
     * @return Worker
     */
    public function worker()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new Worker();
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return Url
     */
    public function url()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new Url();
        }
        return $this->setter[__FUNCTION__];
    }
}
