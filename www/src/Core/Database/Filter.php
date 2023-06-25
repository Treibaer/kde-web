<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 29.09.18
 * Time: 21:11
 */

namespace TBCore\Database;

/**
 * Class Filter
 * @package TB\Database
 */
class Filter
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $comparator;

    /**
     * @var string
     */
    private $value;

    /**
     * Filter constructor.
     * @param string $key
     * @param string $comparator
     * @param string $value
     */
    public function __construct($key, $comparator, $value)
    {
        $this->key = $key;
        $this->comparator = $comparator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Filter
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getComparator()
    {
        return $this->comparator;
    }

    /**
     * @param string $comparator
     * @return Filter
     */
    public function setComparator($comparator)
    {
        $this->comparator = $comparator;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Filter
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
