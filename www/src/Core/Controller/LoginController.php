<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 10.12.17
 * Time: 17:58
 */

namespace TBCore\Controller;

/**
 * Class IndexController
 * @package Controller
 */
class LoginController extends DefaultController
{
    public function start()
    {
        $mainSite = '/';
        if (isset($_POST["login"]) && isset($_POST["user"]) && isset($_POST["modular_password"]) && strlen($_POST["modular_password"]) > 0) {
            $sessionId = $this->coreDbManager->dbDummy()->tryToLogin($_POST["user"], md5($_POST["modular_password"]));
            if ($sessionId != null) {

                // determine main module url
                $moduleFound = $this->coreDbManager->dbDummy()->getModuleUrlForSessionId($sessionId);

                $mainSite = $moduleFound == null ? $mainSite : $moduleFound;

                echo "Login successful.";
                setcookie("sessionid", $sessionId, time() + 60 * 60 * 24 * 30 * 3);
                if (isset($_GET["ref"])) {
                    $currentUrl = $_GET["ref"];
                    $url = explode("/", $currentUrl)[count(explode("/", $currentUrl)) - 1];
                    header("location:" . $url);
                } else {
                    header("location: " . $mainSite);
                }
                die;
            }
        }
    }
}
