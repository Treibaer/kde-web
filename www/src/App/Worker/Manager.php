<?php

namespace KDE\Worker;

use KDE\Manager as AppManager;
use KDE\Model\User;

class Manager
{
    /**
     * @var array
     */
    private array $setter;

    public function __construct(private AppManager $appManager)
    {
    }

    /**
     * @return Kde
     */
    public function kde(): Kde
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new Kde($this->appManager->dbManager);
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return KdeApi
     */
    public function kdeApi(): KdeApi
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new KdeApi($this->appManager->dbManager, $this, $this->appManager->core()->database());
        }
        return $this->setter[__FUNCTION__];
    }

    public function tabControl($page)
    {
        $user = $this->appManager->dbManager->getUser();
        $tabs = [
            new Tab("index" == $page, "/", "Start"),
            new Tab("mygames" == $page, "/mygames", "Spiele"),
        ];
        if ($user->getCanEditGames()) {
            $tabs[] =  new Tab("games" == $page, "/games", "Spieleübersicht");
        }
        if ($user->getCanEditBoards()) {
            $tabs[] =  new Tab("boards" == $page, "/boards", "Bretter");
        }
        if($user->getCanEditCards()) {
            $tabs = array_merge($tabs, [
                new Tab("character" == $page, "/character", "Charaktere"),
                new Tab("enemy" == $page, "/enemy", "Gegner"),
                new Tab("pets" == $page, "/enemy?type=pets", "Pets"),
                new Tab("event" == $page, "/enemy?type=event", "Events"),
                new Tab("cards" == $page, "/cards", "Karten"),
            ]);
        }

        if ($user->getCanSeeUsers()) {
            $tabs[] = new Tab("user" == $page, "/user", "Benutzer");
        }
        return $tabs;
    }

    public function login(): ?User
    {
        $user = null;
        $sessionId = null;
        if (!empty($_COOKIE['sessionid'])) {
            $sessionId = $_COOKIE['sessionid'];
        }
        if (!empty($_REQUEST['sessionId'])) {
            $sessionId = $_REQUEST['sessionId'];
        }
        if (null !== $sessionId) {
            $user = $this->appManager->dbManager->dbUser()->bySessionId($sessionId);
            if (null !== $user) {
                $this->appManager->database()->setUser($user);
            }
        }
        return $user;
    }

    public function logout()
    {
        setcookie("sessionid", "", -1);
    }
}
