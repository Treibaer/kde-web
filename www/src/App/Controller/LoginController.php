<?php

namespace KDE\Controller;

/**
 * Class LoginController
 * @package KDE\Controller
 */
class LoginController extends DefaultController
{
    public function start(): void
    {
        $mainSite = '/';
        if (isset($_POST["login"]) && isset($_POST["user"]) && isset($_POST["modular_password"]) && strlen($_POST["modular_password"]) > 0) {
            $sessionId = $this->dbManager->dbUser()->tryToLogin($_POST["user"], $_POST["modular_password"]);
            if ($sessionId != null) {

                // determine main module url
                $moduleFound = $this->coreDbManager->dbDummy()->getModuleUrlForSessionId($sessionId);

                $mainSite = $moduleFound == null ? $mainSite : $moduleFound;

                echo "Login successful.";
                setcookie("sessionid", $sessionId, time() + 60 * 60 * 24 * 30 * 3);
                if (isset($_GET["ref"]) && $_GET["ref"] !== "/") {
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
