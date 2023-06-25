<?php

namespace KDEApi\Controller\User;

use KDE\Controller\DefaultController;

/**
 * Class CreateController
 * @package KDEApi\Controller\User
 */
class CreateController extends DefaultController
{
    public function start()
    {
        if (!$this->appManager->dbManager->getUser()->getCanSeeUsers()) {
            $this->error("access denied");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['user'])) {
                $this->error("login name is missing");
            }
            if (empty($_POST['pass'])) {
                $this->error("password is missing");
            }
            $fullName = empty($_POST['fullName']) ? "" : $_POST['fullName'];
            $email = empty($_POST['email']) ? "" : $_POST['email'];
            if (isset($_POST['user']) && isset($_POST['pass'])) {
                $this->dbManager->dbUser()->createUser($_POST['user'], $_POST['pass'], $fullName, $email);
                $this->json(["success" => true, "message" => "user successfully created"]);
            }
        }
        $this->error404();
    }

    function json($jsonArray)
    {
        header('Content-Type: application/json; charset: utf8');
        echo json_encode($jsonArray);
        die;
    }

    function error404()
    {
        header('Content-Type: application/json; charset: utf8');
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['success' => false]);
        die;
    }

    function error($message)
    {
        header('Content-Type: application/json; charset: utf8');
        //header("HTTP/1.0 401 Error");
        echo json_encode(['success' => false, "message" => $message]);
        die;
    }
}
