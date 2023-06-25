<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 18:04
 */

namespace TBCore\Model;

use TBCore\Annotation\Table;

use Doctrine\Common\Annotations\AnnotationReader;
use TBCore\Exception\MissingAnnotationException;
use TBCore\Worker\Logging;
use TBCore\Worker\LoggingType;

abstract class Model
{
    public function __construct($o = null)
    {
        if ($o != null) {
            return $this->initWithObject($o);
        }
        return $this;
    }

    private function initWithObject($o)
    {
        foreach ($o as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $o->$key;
            } else {
                //(new Logging())->log(LoggingType::FATAL, 'missing value: ' . $key . ' of class: ' . get_class($this));
            }
        }
        return $this;
    }

    /**
     * @deprecated be careful using it!
     * @param $o
     * @return $this
     */
    public function enrichFromArray($o)
    {
        $primaryKeys = $this->getPrimaryKeys();
        foreach ($o as $key => $value) {
            if (property_exists($this, $key) && !in_array($key, $primaryKeys)) {
                $this->$key = $o[$key];
            }
        }
        return $this;
    }

    /**
     * it's a bad idea, but it's fast and minimizes code
     * @param $key
     * @return object
     */
    public function get($key)
    {
        return $this->$key;
    }

    public function getProperties()
    {
        return get_class_vars(get_class($this));
    }

    /**
     * object
     */
    public function toJSONableObject()
    {
        $ret = new \stdClass();
        $props = $this->getProperties();
        foreach ($props as $key => $value) {
            $value = $this->$key === null ? '' : $this->$key;
            $ret->$key = $value;
        }
        return $ret;
    }

    /**
     * @param $property
     * @param $annotation
     * @return string
     */
    public function getAnnotation($property, $annotation)
    {
        $annotationReader = new AnnotationReader();
        $reflectionProperty = new \ReflectionProperty(get_class($this), $property);
        $propertyAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, $annotation);
        return $propertyAnnotation;
    }

    /**
     * @return string
     * @throws MissingAnnotationException
     */
    public function getTableName()
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass(get_class($this));
        /** @var Table $propertyAnnotation */
        $propertyAnnotation = $annotationReader->getClassAnnotation($reflectionClass, 'TBCore\Annotation\Table');
        if (null == $propertyAnnotation) {
            throw new MissingAnnotationException('missing class annotation Annotation\Table: ' . get_class($this));
        }
        return $propertyAnnotation->value;
    }

    /**
     * @return string[]
     */
    public function getPrimaryKeys()
    {
        $primaryKeys = [];
        foreach (array_keys($this->getProperties()) as $property) {
            if (null != $this->getAnnotation($property, 'TBCore\Annotation\PrimaryKey')) {
                $primaryKeys[] = $property;
            }
        }
        return $primaryKeys;
    }

    /**
     * @return bool
     */
    public function isHidden($property)
    {
        return null !== $this->getAnnotation($property, 'TBCore\Annotation\Hidden');
    }

    function utf8_urldecode($str)
    {
        $str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));
        return html_entity_decode($str, null, 'UTF-8');
    }
}
