<?php

namespace KDE\Worker;

use KDE\Database\DbManager;
use KDE\Model\Board;
use KDE\Model\Character;
use KDE\Model\Game;
use KDE\Model\User;
use TBCore\Database\DbManager as DatabaseManager;

class KdeApi
{
    /**
     * @var DbManager
     */
    private $dbManager;

    /**
     * @var Kde
     */
    private $kde;

    /**
     * @var string|null
     */
    private $userId;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var int
     */
    private $gameId;

    /**
     * @var bool
     */
    private $isLeader;


    /**
     * MKM constructor.
     * @param DbManager $dbManager
     * @param Manager $workerManager
     * @param DatabaseManager $coreDbManager
     */
    public function __construct(DbManager $dbManager, Manager $workerManager, DatabaseManager $coreDbManager)
    {
        $this->dbManager = $dbManager;
        $this->kde = $workerManager->kde();
    }

    /**
     * @param $user User
     */
    public function login(?User $user): void
    {
        $this->user = $user;
        $this->userId = $user?->getUserId();
        $this->game = $this->loadGame();
        $this->gameId = $this->game->getGameId();
        $this->isLeader = $this->game->getLeader() == $this->userId;

    }

    public function createGame()
    {
        // example:
        // players
        // [{"id":1, "name": ""}, "1":{"id":3, "name": ""}, "2": {"id":"GUEST", "name": "Markus"}]
        $players = isset($_REQUEST["players"]) ? $_REQUEST["players"] : [];
        $playerId = 0;
        $characters = $this->dbManager->dbCharacter()->all();
        foreach ($players as $key => &$player) {
            if ($player["id"] === "guest") {
                $player["id"] = "G" . md5(uniqid($playerId));
            }
            $character = $characters[$player['characterId']];
            $player['life'] = $character->getLife();
            $player['mana'] = $character->getMana();
            $player['stamina'] = $character->getStamina();
            $player['position'] = floor($key / 2) . "_" . $key;
            $player['money'] = 0;
            $player['state'] = "";
            $player['hand'] = [];
            $player['field'] = [];
            $player['advancedSkillOne'] = false;
            $player['advancedSkillTwo'] = false;
            $player['advancedSkillThree'] = false;
            $playerId++;
        }
        $boardId = 1;
        if (isset($_POST['boardId'])) {
            $boardId = $_POST['boardId'];
        }

        $leader = (int)$_REQUEST["leader"];
        $creationDate = time();
        $state = -1;

        $game = new Game();
        $game->setLeader($leader);
        $game->setBoardId($boardId);
        $game->setPlayers(json_encode($players));
        $game->setCreationDate($creationDate);
        $game->setState($state);
        $game->setDice(6);


        $cards = $this->dbManager->dbCard()->allByType('library');
        $cardIds = [];
        foreach ($cards as $card) {
            $cardIds[] = $card->getCardId();
        }
        $game->setLibraryCards(json_encode($cardIds));
        $game->setTrashCards("[]");
        $game->setBag("[]");


        $this->dbManager->dbGame()->persist($game);

        $this->kdeJson(["leader" => $leader, "boardId" => $boardId, "players" => $players, "creationDate" => $creationDate, "state" => $state]);
    }

    public function createBoard($rows, $columns)
    {
        $title = $_POST["title"] ?? "";
        if ($title === "") {
            $title = "Leer";
        }
        $board = new Board();
        $board->setTitle($title);
        $board->setRows($rows);
        $board->setColumns($columns);
        $this->dbManager->dbGame()->persist($board);
        $this->kdeJson();
    }

    public function getBoard($boardId): void
    {
        $board = $this->dbManager->dbBoard()->getById($boardId);
        if ($board == null) {
            $this->kdeFailJson();
        }
        // load
        if (isset($_GET["load"])) {
            $this->kdeJson(['board' => json_decode($board->getContent())]);
        }
        // save
        if (isset($_GET["save"])) {
            $board->setContent(json_encode($_POST['content']));
            $board->setTitle($_POST["title"]);
            $this->dbManager->dbBoard()->persist($board);
            $this->kdeJson(["message" => "saved"]);
        }
    }

    public function saveGame($gameId, $boardId, $leader): void
    {
        $game = $this->dbManager->dbGame()->getById($gameId);
        if ($game == null) {
            $this->kdeFailJson();
        }

        // save
        $game->setBoardId($boardId);
        $game->setLeader($leader);
        $this->game = $game;
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function loadGame()
    {
        $game = null;
        // guest accounts have higher prio than logged in accounts
        if (isset($_GET["userId"]) && (strpos($_GET["userId"], "G") !== false)) {
            $this->userId = $_GET["userId"];
            $game = $this->dbManager->dbGame()->getByUserId($this->userId);
        } else {
            if ($this->user === null || !isset($_GET["gameId"])) {
                $this->kdeFailJson();
            }
            $this->userId = $this->user->getUserId();
            $this->gameId = $_GET["gameId"];
            $game = $this->dbManager->dbGame()->getById($this->gameId);
        }

        if ($game === null) {
            $this->kdeFailJson();
        }
        return $game;
    }

    public function getLibrary($gameId) {
        $cards = $this->dbManager->dbCard()->allJSON();
        $libraryCards = [];

        $rawCards = json_decode($this->dbManager->dbGame()->getById($gameId)->libraryCards);
        foreach($rawCards as $rawCard) {
            if(!isset($cards[$rawCard])) {
                continue;
            }
            $card = $cards[$rawCard];
            $card->id = $rawCard;
            $libraryCards[] = $card;
        }

        $this->kdeJson(['cards' => $libraryCards]);
    }

    public function getMaps() {
        $maps = $this->dbManager->dbBoard()->allJSON();

        $this->kdeJson(['maps' => $maps]);
    }

    public function changeBoard($boardId) {
        if (!$this->isLeader) {
            $this->kdeFailJson();
        }
        $this->game->boardId = $boardId;
        $this->persistGame();
        $this->kdeJson();
    }

    private function kdeJson($jsonArray = [])
    {
        $jsonArray["kde"] = true;
        $jsonArray["alive"] = true;
        $jsonArray["version"] = $this->kde->getVersion();
        $this->json($jsonArray);
    }

    private function kdeFailJson($jsonArray = [])
    {
        $jsonArray["kde"] = false;
        $jsonArray["alive"] = false;
        $jsonArray["version"] = $this->kde->getVersion();
        $jsonArray["message"] = "random error appeared";
        //var_dump(debug_backtrace());die;
        $this->json($jsonArray);
    }

    public function moveFigure($from, $to)
    {
        $positions = $this->getPlayerPositions();


        if (!isset($positions->$from)) {
            $this->kdeFailJson([]);
        }
        if ($this->isLeader || $positions->$from->type === "pets") {
        } else {
            $this->kdeFailJson([]);
        }
        $positions->$to = $positions->$from;
        unset($positions->$from);

        $this->game->setPlayerPositions(json_encode($positions));

        $this->persistGame();
        $this->kdeJson([]);
    }

    // I/O
    private function persistGame()
    {
        $this->game->setLastUpdatedHash(md5(round(microtime(true) * 1000)));
        $this->dbManager->dbGame()->persist($this->game);
    }

    public function setFigure($enemyId, $to)
    {
        $positions = $this->getPlayerPositions();
        $enemies = $this->dbManager->dbEnemy()->all();


        $enemy = new \stdClass();
        $enemy->life = $enemies[$enemyId]->getLife();
        $enemy->type = $enemies[$enemyId]->getType();
        $enemy->id = $enemyId;
        $positions->$to = $enemy;

        $this->game->setPlayerPositions(json_encode($positions));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function sendCard($selectedPlayerId, $cardId)
    {
        if (!$this->isLeader) {
            return;
        }
        $gameUserId = "-1";
        foreach ($this->getPlayers() as $key => &$player) {
            $playerId = $player->id;
            if ($playerId == $selectedPlayerId) {
                $gameUserId = $key;
            }
        }
        if ($gameUserId === "-1") {
            die;
        }
        $players = $this->getPlayers();
        $players[$gameUserId]->hand[] = (int)$cardId;
        $this->game->setPlayers(json_encode($players));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function movePlayer($selectedUserId, $to)
    {
        $players = $this->getPlayers();

        if ($selectedUserId === "") {
            $this->kdeFailJson([]);
        }

        if ($selectedUserId !== $this->userId && !$this->isLeader) {
            $this->kdeFailJson([]);
        }
        foreach ($players as $key => &$player) {
            if ($player->id . "" === $selectedUserId) {
                $player->position = $to;
            }
        }
        $this->game->setPlayers(json_encode($players));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function removeFigure($from)
    {
        $positions = $this->getPlayerPositions();

        unset($positions->$from);

        $this->game->setPlayerPositions(json_encode($positions));

        $this->persistGame();
        $this->kdeJson([]);
    }

    public function moveCard($cardId, $randomId, $from, $selectedPlayerIdPOST)
    {

        $cards = $this->dbManager->dbCard()->all();

        $gameUserId = "-1";
        $selectedPlayerId = $this->userId;


        if ($selectedPlayerIdPOST !== "") {
            $selectedPlayerId = $selectedPlayerIdPOST;

            // non leaders are only allowed to remove their own cards
            if ($selectedPlayerId != $this->userId && !$this->isLeader) {
                $this->kdeJson([]);
            }

        }

        $players = $this->getPlayers();

        foreach ($players as $key => &$player) {
            $playerId = $player->id;
            if ($playerId == $selectedPlayerId) {
                $gameUserId = $key;
            }
        }

        $target = isset($_POST['target']) ? $_POST["target"] : null;

        if ($gameUserId === "-1") {
            if ($this->isLeader && in_array($target, ["library", "graveyard", "openCards"])) {
                // accepted
            } else {
                $this->kdeFailJson();
            }
        }

        $myHand = $players[$gameUserId]->hand;
        $myPlayField = $players[$gameUserId]->field;

        $card = null;

        $openCards = $this->getOpenCards();
        $libraryCards = $this->getLibraryCards();
        $trashCards = $this->getTrashCards();
        $bag = $this->getBag();
        if ($from === "hand") {
            for ($i = 0; $i < count($myHand); $i++) {
                if ((int)$cardId === (int)$myHand[$i]) {
                    $card = $myHand[$i];
                    unset($myHand[$i]);
                    sort($myHand);
                    break;
                }
            }
        }
        if ($from === "field") {
            for ($i = 0; $i < count($myPlayField); $i++) {
                if ((int)$cardId === (int)$myPlayField[$i]) {
                    $card = $myPlayField[$i];
                    unset($myPlayField[$i]);
                    $myPlayField = array_merge($myPlayField, []);
                    break;
                }
            }
        }
        if ($from === "openCards") {
            foreach ($openCards as $key => $openCard) {
                if ($openCard->randomId === $randomId) {
                    $card = $openCard->cardId;
                    unset($openCards[$key]);
                    $openCards = array_merge($openCards, []);
                    break;
                }
            }
        }
        if ($from === "graveyard") {
            for ($i = 0; $i < count($trashCards); $i++) {
                if ((int)$cardId === (int)$trashCards[$i]) {
                    $card = $trashCards[$i];
                    unset($trashCards[$i]);
                    $trashCards = array_merge($trashCards, []);
                    break;
                }
            }
        }
        if ($from === "bag") {
            for ($i = 0; $i < count($bag); $i++) {
                if ((int)$cardId === (int)$bag[$i]) {
                    $card = $bag[$i];
                    unset($bag[$i]);
                    $bag = array_merge($bag, []);
                    break;
                }
            }
        }

        if ($from === "library") {
            if($target === "openCards") {
                for ($i = 0; $i < count($libraryCards); $i++) {
                    if ((int)$cardId === (int)$libraryCards[$i]) {
                        $card = $libraryCards[$i];
                        unset($libraryCards[$i]);
                        $libraryCards = array_merge($libraryCards, []);
                        break;
                    }
                }
            } else {
                $card = $this->drawRandomCardFromLibrary($libraryCards);
            }
        }
        // v2
        if ($from === null) {
            foreach ($players as &$player) {
                for ($i = 0; $i < count($player->hand); $i++) {
                    if ((int)$cardId === (int)$player->hand[$i]) {
                        $card = $player->hand[$i];
                        unset($player->hand[$i]);
                        sort($player->hand);
                        break;
                    }
                }
                for ($i = 0; $i < count($player->field); $i++) {
                    if ((int)$cardId === (int)$player->field[$i]) {
                        $card = $player->field[$i];
                        unset($player->field[$i]);
                        sort($player->field);
                        break;
                    }
                }
            }

            $myHand = $players[$gameUserId]->hand;
            $myPlayField = $players[$gameUserId]->field;

            foreach ($openCards as $key => $openCard) {
                if ($openCard->cardId === (int)$cardId) {
                    $card = $openCard->cardId;
                    unset($openCards[$key]);
                    $openCards = array_merge($openCards, []);
                    break;
                }
            }
        }

        // check null, so that card is valid in current hand
        if ($card !== null && $card !== 0) {
            $title = $cards[$card]->getTitle();
            if ($target === "field") {
                $myPlayField[] = $card;
                sort($myPlayField);
                $players[$gameUserId]->field = (array)$myPlayField;
            }
            if ($target === "hand") {
                $myHand[] = $card;
                sort($myHand);
                $players[$gameUserId]->hand = (array)$myHand;
            }
            if ($target === "library") {
                $libraryCards[] = $card;
            }
            if ($target === "graveyard") {
                $trashCards[] = $card;
            }
            if ($target === "bag") {
                $bag[] = $card;
            }
            if ($target === "openCards") {

                $newCard = new \stdClass();
                $newCard->cardId = $card;
                $newCard->visibleCardId = $card;
                $newCard->randomId = uniqid($this->userId);
                $openCards[] = $newCard;
            }
        }

        $this->game->setPlayers(json_encode($players));
        $this->game->setOpenCards(json_encode($openCards));
        $this->game->setLibraryCards(json_encode($libraryCards));
        $this->game->setTrashCards(json_encode($trashCards));
        $this->game->setBag(json_encode($bag));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function setAttribute($value, $selectedPlayerId, $attribute)
    {
        if (!$this->isLeader && $this->userId != $selectedPlayerId) {
            $this->kdeFailJson();
        }
        $players = $this->getPlayers();
        foreach ($players as $key => &$player) {
            if ($player->id == $selectedPlayerId) {
                $player->$attribute = $value;
            }
        }
        $this->game->setPlayers(json_encode($players));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function setEnemyAttribute($value, $selectedEnemyId, $attribute)
    {
        if (!$this->isLeader) {
            $this->kdeFailJson();
        }
        $playerPositions = $this->getPlayerPositions();
        if (isset($playerPositions->$selectedEnemyId)) {
            $playerPositions->$selectedEnemyId->$attribute = $value;
        }
        $this->game->setPlayerPositions(json_encode($playerPositions));
        $this->persistGame();
        $this->kdeJson([]);
    }

    private function loadFieldData()
    {
        $isLeader = $this->game->getLeader() === $this->userId;
        /** @var User[] $users */
        $users = $this->dbManager->dbUser()->getUsersAsAssocArray();
        $userNames = [];
        foreach ($users as $user) {
            $userNames[$user->getUserId()] = [
                "id" => $user->getUserId(),
                "user" => $user->getUserName()
            ];
        }
        $players = $this->getPlayers();
        $openCards = $this->getOpenCards();
        $characters = $this->dbManager->dbCharacter()->all();
        $gameUserId = -1;

        $playerColors = ['#325ea8', '#669966', '#a8324c', '#a8a032'];
        foreach ($players as $key => &$player) {
            $char = $characters[$player->characterId];
            $playerId = $player->id;
            if ($playerId == $this->userId) {
                $gameUserId = $key;
            } else {
                //$player->id = "";
            }
            if ($player->name === "") {
                $player->name = $userNames[$playerId]["user"];
            }

            $player->cardsInHand = count($player->hand);
            $player->characterType = $char->getType();
            $player->imageUrl = $char->getImageUrl();
            $player->icon = $char->getFieldImageUrl();
            $player->fullCardUrl = $char->getFullCardUrl();
            $player->hand = $this->enrichCards($player->hand);
            $player->field = $this->enrichCards($player->field);
            $player->color = $playerColors[$key];
            $player->skills = $this->getSkills($player, $char);
        }

        if ($gameUserId == -1 && $this->game->getLeader() != $this->userId) {
            $this->kdeFailJson();
        }

        // card -> [{"randomId": "ABC", "cardId": 13, "visibleCardId": 0}]

        foreach ($openCards as &$openCard) {
            $openCard->cardId = $openCard->visibleCardId;
            $openCard->imageUrl = $this->imageForCardId($openCard->cardId);
        }

        $me = null;
        if ($gameUserId != -1) {
            $me = $players[$gameUserId];
        }
        // clean hands
        if (!$isLeader) {
            foreach ($players as $key => &$player) {
                // show hand to everyone
                /*if ($gameUserId == -1 || $gameUserId != $key) {
                    $player->hand = [];
                }*/
            }
        }
        $playerPositions = $this->getPlayerPositions();
        $libraryCards = $this->getLibraryCards();

        $activity = [];
        foreach ($activity as &$item) {
            $newItem = new \stdClass();
            $newItem->text = date('H:i', $item->activityDate) . ': ' . $item->text;
            $newItem->html = $newItem->text;
            $newItem->id = (int)$item->kdeGameActivityId;

            $item = $newItem;
        }
        $trashCards = $this->getTrashCards();

        $graveyard = [];
        foreach ($trashCards as $value) {
            $graveyard[] = $this->cards[$value];
        }
        $bagCards = $this->getBag();
        $bag = [];
        foreach ($bagCards as $value) {
            $bag[] = $this->cards[$value];
        }

        $marketPlace = $this->kde->getMarketPlaceCard($this->game->getMarketPlace());

        if ($marketPlace === null) {
            $marketPlace = "";
        }

        $custom = $this->getCustom();
        if (isset($custom->mergePlayerPosition)) {
            foreach ($players as &$player) {
                $player->position = "";
            }
        }
        $enemies = $this->kde->getEnemiesAndPets();

        foreach ($playerPositions as &$playerPosition) {
            $playerPosition->enemy = $enemies[$playerPosition->id];
            if(!isset($playerPosition->stamina)) {
                $playerPosition->stamina = $playerPosition->enemy->stamina;
            }
            if(!isset($playerPosition->mana)) {
                $playerPosition->mana = $playerPosition->enemy->mana;
            }
        }

        $ping = $this->getPing();

        $data = [
            "gameId" => $this->game->getGameId(),
            "boardId" => $this->game->getBoardId(),
            "me" => $me,
            "leader" => [
                "id" => $this->game->getLeader(),
                "name" => $userNames[$this->game->getLeader()]["user"],
            ],
            "players" => $players,
            "playerPositions" => $playerPositions,
            "openCards" => $openCards,
            "numberOfLibraryCards" => count($libraryCards),
            "numberOfTrashCards" => count($trashCards),
            "numberOfBagCards" => count($bagCards),
            "activity" => $activity,
            "graveyard" => $graveyard,
            "bag" => $bag,
            "ping" => $ping,
            "marketPlace" => $marketPlace,
            "dice" => $this->game->getDice(),
            "diceTime" => $this->game->getDiceTime(),
            "diceRoller" => $this->game->getDiceRoller(),
            "custom" => $custom,
        ];
        return $data;
    }

    private $cards = [];

    public function loadField($hash)
    {
        $this->cards = $this->dbManager->dbCard()->allJSON();

        $answeringAfterSeconds = 400;
        $i = 0;
        while (true) {
            $this->game = $this->loadGame();
            $lastUpdatedHash = $this->game->getLastUpdatedHash();
            if ($hash === "" || $hash !== $this->game->getLastUpdatedHash() || $i > $answeringAfterSeconds) {
                $fieldData = $this->loadFieldData();
                $fieldData['hash'] = $lastUpdatedHash;
                $this->kdeJson($fieldData);
            }
            $i++;
            // 50 ms
            usleep(50000);
        }
    }

    public function fightArea($action, $coo, $attribute)
    {
        if (!$this->isLeader) {
            return;
        }
        $playerPositions = $this->getPlayerPositions();

        if (!isset($playerPositions->$coo)) {
            $this->kdeFailJson();
        }
        if ($action === "add") {
            $playerPositions->$coo->$attribute = $playerPositions->$coo->$attribute + 1;
        }
        if ($action === "sub") {
            $playerPositions->$coo->$attribute = max(0, $playerPositions->$coo->$attribute - 1);
            if ($playerPositions->$coo->$attribute === 0 && $attribute === "life") {
                $action = "remove";
            }
        }
        if ($action === "remove") {
            unset($playerPositions->$coo);
        }

        $this->game->setPlayerPositions(json_encode($playerPositions));
        $this->persistGame();

        $this->kdeJson();
    }

    public function removeMergePlayer()
    {

        $custom = json_decode($this->game->getCustom());
        if (!isset($custom->mergePlayerPosition)) {
            $this->kdeFailJson();
        }
        $players = $this->getPlayers();
        $pos = explode('_', $custom->mergePlayerPosition);
        $x = $pos[0];
        $y = $pos[1];
        foreach ($players as $key => &$player) {
            $newPos = $player->position;
            switch ($key) {
                case '0':
                    $newPos = join('_', [$x, $y]);
                    break;
                case '1':
                    $newPos = join('_', [$x - 1, $y]);
                    break;
                case '2':
                    $newPos = join('_', [$x - (1 - ($y % 2)), $y + 1]);
                    break;
                case '3':
                    $newPos = join('_', [$x + ($y % 2), $y + 1]);
                    break;
                case '4':
                    $newPos = join('_', [$x + 1, $y]);
                    break;
                case '5':
                    $newPos = join('_', [$x + ($y % 2), $y - 1]);
                    break;
                case '6':
                    $newPos = join('_', [$x - (1 - ($y % 2)), $y - 1]);
                    break;
            }
            $player->position = $newPos;
        }

        // re - set all players in near of merge player
        unset($custom->mergePlayerPosition);

        $this->game->setPlayers(json_encode($players));
        $this->game->setCustom(json_encode($custom));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function setMergePlayer($to)
    {
        $custom = $this->getCustom();

        $custom->mergePlayerPosition = $to;

        $this->game->setCustom(json_encode($custom));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function highlightField($position)
    {
        $players = $this->getPlayers();
        $partOfTheGame = false;
        foreach ($players as &$player) {
            if ($player->id === $this->userId) {
                $partOfTheGame = true;
                if ($position === "" || (isset($player->ping) && $player->ping === $position)) {
                    unset($player->ping);
                } else {
                    $player->ping = $position;
                }
            } else if (isset($player->ping) && $player->ping === $position) {
                unset($player->ping);
            }
        }

        $custom = $this->getCustom();

        if (isset($custom->leaderPing) && $custom->leaderPing === $position) {
            unset($custom->leaderPing);
        } else if (!$partOfTheGame && $this->isLeader) {
            if (isset($custom->leaderPing) && $custom->leaderPing === $position) {
                // handled above
            } else {
                $custom->leaderPing = $position;
            }
        }
        $this->game->setPlayers(json_encode($players));
        $this->game->setCustom(json_encode($custom));
        $this->persistGame();
        $this->kdeJson([]);
    }

    private function getSkills($player, Character $char)
    {
        return [
            "baseSkill0" => [
                "text" => $char->getBaseSkill0(),
                "enabled" => true
            ],
            "baseSkill1" => [
                "text" => $char->getBaseSkill1(),
                "enabled" => true
            ],
            "advancedSkillOne" => [
                "text" => $char->getAdvancedSkillOne(),
                "enabled" => isset($player->advancedSkillOne) && $player->advancedSkillOne,
            ],
            "advancedSkillTwo" => [
                "text" => $char->getAdvancedSkillTwo(),
                "enabled" => isset($player->advancedSkillTwo) && $player->advancedSkillTwo,
            ],
            "advancedSkillThree" => [
                "text" => $char->getAdvancedSkillThree(),
                "enabled" => isset($player->advancedSkillThree) && $player->advancedSkillThree,
            ],
        ];
    }

    public function setSkill($gameUserId, $value)
    {
        $players = $this->getPlayers();
        $player = $players[$gameUserId];
        $players[$gameUserId]->$value = isset($player->$value) ? !$player->$value : true;
        $this->game->setPlayers(json_encode($players));
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function drawLootCard($numberOfCards)
    {
        $libraryCards = $this->getLibraryCards();
        $openCards = $this->getOpenCards();

        for ($i = 0; $i < $numberOfCards; $i++) {
            $cardId = $this->drawRandomCardFromLibrary($libraryCards);
            if (null === $cardId) {
                $this->kdeFailJson([]);
            }
            $card = new \stdClass();
            $card->cardId = $cardId;
            $card->visibleCardId = $cardId;
            $card->randomId = uniqid($this->userId + $i);
            $openCards[] = $card;
        }

        $this->game->setOpenCards(json_encode($openCards));
        $this->game->setLibraryCards(json_encode($libraryCards));

        $this->persistGame();
        $this->kdeJson([]);
    }


    private function imageForCardId($cardId)
    {
        if ($cardId === 0) {
            return "/images/kde/KDE_Kartenhintergrund_Spielkarten.png";
        }
        return $this->cards[$cardId]->imageUrl;
    }

    public function setMarketPlace($value)
    {
        $this->game->setMarketPlace($value);
        $this->persistGame();
        $this->kdeJson([]);
    }

    private function drawRandomCardFromLibrary(&$libraryCards)
    {
        $randomIndex = rand(0, count($libraryCards) - 1);
        $card = $libraryCards[$randomIndex];
        unset($libraryCards[$randomIndex]);
        $libraryCards = array_merge($libraryCards, []);
        return $card;
    }


    private function enrichCards($array)
    {
        $result = [];
        foreach ($array as $cardId) {
            if ($cardId === 0) {
                $hiddenCard = new KdeCard();
                $hiddenCard->setImageUrl("/images/kde/KDE_Kartenhintergrund_Spielkarten.png");
                $result[] = $hiddenCard->toJSONableObject();
                continue;
            }
            if (isset($this->cards[$cardId])) {
                $result[] = $this->cards[$cardId];
            }
        }
        return $result;
    }

    public function rollDice()
    {
        $this->game->setDice(rand(1, 12));
        $this->game->setDiceTime(time());
        $rollerName = "";
        foreach ($this->getPlayers() as $player) {
            if ($player->id == $this->userId) {
                $rollerName = $player->name;
                if ($rollerName === "") {
                    $rollerName = $this->user->getUserName();
                }
                break;
            }
        }
        $this->game->setDiceRoller($rollerName);
        $this->persistGame();
        $this->kdeJson([]);
    }

    public function rotateCard($randomId)
    {
        $openCards = $this->getOpenCards();

        foreach ($openCards as &$openCard) {
            if ($openCard->randomId === $randomId) {
                $openCard->visibleCardId = $openCard->cardId;
            }
        }
        $this->game->setOpenCards(json_encode($openCards));

        $this->persistGame();
        $this->kdeJson([]);
    }

    private function getTrashCards()
    {
        return json_decode($this->game->getTrashCards());
    }

    private function getBag()
    {
        return json_decode($this->game->getBag());
    }

    private function getOpenCards()
    {
        return json_decode($this->game->getOpenCards());
    }

    private function getPlayerPositions()
    {
        return json_decode($this->game->getPlayerPositions());
    }

    private function getPlayers()
    {
        return json_decode($this->game->getPlayers());
    }

    private function getLibraryCards()
    {
        return json_decode($this->game->getLibraryCards());
    }

    private function getCustom()
    {
        return json_decode($this->game->getCustom());
    }

    private function getPing()
    {
        return json_decode($this->game->getPing());
    }

    private function json($jsonArray)
    {
        header('Content-Type: application/json; charset: utf8');
        echo json_encode($jsonArray);
        die;
    }
}
