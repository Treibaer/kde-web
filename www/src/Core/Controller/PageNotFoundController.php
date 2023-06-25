<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 10.12.17
 * Time: 15:58
 */

namespace TBCore\Controller;

/**
 * Class PageNotFoundController
 * @package Controller
 */
class PageNotFoundController extends DefaultController
{
    public function start()
    {
        header("HTTP/1.0 404 Not Found");
        //die("Bad route.");
    }
}
