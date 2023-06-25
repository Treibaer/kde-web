<?php

namespace KDEApi\Controller\User;

use KDE\Controller\DefaultController;

/**
 * Class EditController
 * @package KDEApi\Controller\User
 */
class EditController extends DefaultController
{
    public function start()
    {
        if (!$this->appManager->dbManager->getUser()->getIsAdmin()) {
            $this->error("access denied");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = $this->dbManager->dbUser()->byUserId($_POST['userId']);

            if ($user === null) {
                $this->error("user not found");
            }
            $user->setUserName($_POST['userName']);
            if ($_POST['password'] !== "") {
                $user->setPassword($this->dbManager->dbUser()->encrypt($_POST['password']));
            }
            $user->setFullName($_POST['fullName']);
            $user->setEmail($_POST['email']);
            if ($this->appManager->dbManager->getUser()->getUserId() === $user->getUserId()) {
                if($_POST['isAdmin'] !== "true") {
                    $this->error("not allowed to remove admin");
                }
            } else {
                $user->setIsAdmin($_POST['isAdmin'] === "true");
            }
            $user->setCanEditCards($_POST['canEditCards'] === "true");
            $user->setCanEditGames($_POST['canEditGames'] === "true");
            $user->setCanEditBoards($_POST['canEditBoards'] === "true");
            $user->setCanSeeUsers($_POST['canEditUsers'] === "true");
            $this->dbManager->dbUser()->persist($user);
            $this->json(["success" => true, "message" => "user successfully edited"]);
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
