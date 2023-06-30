<?php

namespace KDEApi\Controller;

use KDE\Controller\DefaultController;

/**
 * Class GeneralController
 * @package KDEApi\Controller\Kde
 */
class GeneralController extends DefaultController
{
    public function start(): void
    {
        $kde = $this->appManager->worker()->kde();
        $kdeApi = $this->appManager->worker()->kdeApi();

        if (isset($_GET["kde"])) {
            if (isset($_GET['createGame'])) {
                $kdeApi->createGame();
            }

            if (isset($_GET['createBoard'])) {
                $rows = $_POST['rows'] ?? $kde->defaultRows();
                $columns = $_POST['columns'] ?? $kde->defaultColumns();
                $kdeApi->createBoard($rows, $columns);
            }

            if (isset($_GET["board"]) && isset($_GET["boardId"])) {
                $kdeApi->getBoard($_GET["boardId"]);
            }

            if (isset($_GET["game"]) && isset($_GET["save"])) {
                $kdeApi->saveGame($_POST["gameId"], $_POST["boardId"], $_POST["leader"]);
            }

            $version = $_GET['version'] ?? 0;

            if ($version != $kde->getVersion()) {
                $this-> kdeFailJson();
            }

            $kdeApi->login($this->getUser());

            if (isset($_GET["sendCard"])) {
                $kdeApi->sendCard($_POST["selectedPlayerId"], $_POST["cardId"]);
            }

            if (isset($_GET["moveCard"])) {
                $cardId = $_POST["cardId"] ?? 0;
                $randomId = $_POST["randomId"] ?? 0;
                $selectedPlayerId = $_POST["selectedPlayerId"] ?? "";

                $from = $_POST['from'] ?? null;
                $kdeApi->moveCard($cardId, $randomId, $from, $selectedPlayerId);
            }

            if (isset($_GET["moveFigure"])) {
                $kdeApi->moveFigure($_GET["from"], $_GET["to"]);
            }

            if (isset($_GET["library"])) {
                $kdeApi->getLibrary($_GET["gameId"]);
            }

            if (isset($_GET["maps"])) {
                $kdeApi->getMaps();
            }

            if (isset($_GET["changeBoard"])) {
                $kdeApi->changeBoard($_POST['boardId']);
            }

            if (isset($_GET["movePlayer"])) {
                $kdeApi->movePlayer($_GET["selectedUserId"], $_GET["to"]);
            }

            if (isset($_GET["removeFigure"])) {
                $kdeApi->removeFigure($_GET['from']);
            }

            if (isset($_GET["adjustAttribute"])) {
                $kdeApi->setAttribute($_POST['value'], $_POST["playerId"], $_POST['attribute']);
            }

            if (isset($_GET["adjustEnemyAttribute"])) {
                $kdeApi->setEnemyAttribute($_POST['value'], $_POST["enemyId"], $_POST['attribute']);
            }

            if (isset($_GET["setSkill"])) {
                $kdeApi->setSkill($_POST['gameUserId'], $_POST["skill"]);
            }

            if (isset($_GET["setFigure"])) {
                $kdeApi->setFigure($_GET["enemyId"], $_GET['to']);
            }

            if (isset($_GET["setMergePlayer"])) {
                $kdeApi->setMergePlayer($_GET["to"]);
            }

            if (isset($_GET["highlightField"])) {
                $kdeApi->highlightField($_GET['pos']);
            }

            if (isset($_GET["removeMergePlayer"])) {
                $kdeApi->removeMergePlayer();
            }

            if (isset($_GET["drawLootCard"])) {
                $kdeApi->drawLootCard($_POST['count']);
            }

            if (isset($_GET["fightArea"])) {
                $kdeApi->fightArea($_POST['action'], $_POST['coo'], $_POST['attribute']);
            }

            if (isset($_GET["rollDice"])) {
                $kdeApi->rollDice();
            }

            if (isset($_GET["rotateCard"])) {
                $kdeApi->rotateCard($_POST["randomId"]);
            }

            if (isset($_GET["setMarketPlace"])) {
                $kdeApi->setMarketPlace($_POST['key']);
            }

            // get field information: at first: the players hand
            if (isset($_GET["field"])) {
                $kdeApi->loadField($_GET['hash'] ?? "");
            }
            $this->kdeFailJson();
        }
    }

    private function kdeFailJson()
    {
        $jsonArray = [];
        global $kde;
        $jsonArray["kde"] = false;
        $jsonArray["alive"] = false;
        $jsonArray["version"] = $kde->getVersion();
        $this->json($jsonArray);
    }

    private function json($jsonArray)
    {
        header('Content-Type: application/json; charset: utf8');
        echo json_encode($jsonArray);
        die;
    }
}
