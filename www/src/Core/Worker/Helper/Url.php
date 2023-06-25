<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 15.12.17
 * Time: 21:17
 */

namespace TBCore\Worker\Helper;

/**
 * Class Url
 * @package Worker\Helper
 */
class Url
{
    public function utf8_urldecode($str)
    {
        $str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));
        return html_entity_decode($str, null, 'UTF-8');
    }

    /**
     * @param string $content
     * @return string
     */
    public function decode($content)
    {
        return $this->utf8_urldecode($content);
    }
}
