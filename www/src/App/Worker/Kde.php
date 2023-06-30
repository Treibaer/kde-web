<?php

namespace KDE\Worker;

use TBCore\Exception\Exception;
use TBCore\Exception\MissingAnnotationException;
use KDE\Database\DbManager;
use KDE\Model\Enemy;

/**
 * Class Kde
 * @package TB\Worker
 */
class Kde
{
    /**
     * @var DbManager
     */
    private $dbManager;

    /**
     * MKM constructor.
     * @param DbManager $dbManager
     */
    public function __construct(DbManager $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEnemiesAndPets(): array
    {
        return $this->dbManager->dbEnemy()->allJSON();
    }

    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEnemies(): array
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON("enemy");
    }

    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getPets(): array
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON('pets');
    }
    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEvents(): array
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON('event');
    }

    public function getStates(): array
    {
        return [
            "Bewegungsunfaehig" => ["Bewegungsunfähig", "Bewegungsunfaehig.png", "Es können in der nächsten Runde keine Aktionen zum Fortbewegen genutzt werden."],
            "Benommen" => ["Benommen", "Benommen.png", "Angriff -2 und Mag. Angriff -2 beim nächsten Angriff oder Mag. Angriff, Abwehr -1 und Mag. Abwehr -1 gegen den nächsten Angriff."],
            "Umgeworfen" => ["Umgeworfen", "Umgeworfen.png", "Es müssen 3 Aktion zum Aufstehen genutzt werden. Abwehr -2 und Mag. Abwehr -2 gegen den nächsten Angriff oder Mag. Angriff."],
            "Bluten" => ["Bluten", "Bluten.png", "Das Ziel erhält Leben -1 nach seinem nächsten Zug."],
            "Verlangsamt" => ["Verlangsamt", "Verlangsamt.png", "Das Ziel hat 1 Aktion weniger in den nächsten 2 Runden."],
            "Paralysiert" => ["Paralysiert", "Paralysiert.png", "Das Ziel kann in seiner nächsten Runde keine Aktionen ausführen."],
            "Vergiftet" => ["Vergiftet", "Vergiftet.png", "Das Ziel erhält bei 9-12 am Ende seiner nächsten 2 Runden Leben -1."],
            "Geblendet" => ["Geblendet", "Geblendet.png", "Das Ziel kann in seiner nächsten Runde nicht angreifen und den nächsten Angriff oder Mag. Angriff gegen sich nicht abwehren."],
            "Verbrennung" => ["Verbrennung", "Verbrennung.png", "Das Ziel erhält bei 5-12 sofort Leben -1."],
        ];
    }

    public function backgroundCardUrl(): string
    {
        return "/images/kde/KDE_Kartenhintergrund_Spielkarten_v2.png";
    }

    public function backgroundSmallCardUrl(): string
    {
        return "/images/kde/KDE_Kartenhintergrund_Spielkarten_v2_small.png";
    }

    public function backgroundCharacterCardUrl(): string
    {
        return "/images/kde/KDE_Kartenhintergrund_Spielkarten_v2_horizontal.png";
    }

    public function getMarketPlaceCards(): array
    {
        $emptyCard = new \stdClass();
        $emptyCard->title = "Leer";
        $emptyCard->cardId = 0;
        $emptyCard->imageUrl = "/images/kde/cards/background.png";
        return array_merge([$emptyCard], $this->dbManager->dbCard()->allByTypeJSON('marketplace'));
    }

    public function getMarketPlaceCard($id)
    {
        foreach ($this->getMarketPlaceCards() as $marketPlace) {
            if ($marketPlace->cardId === $id) {
                return $marketPlace;
            }
        }
        return null;
    }

    /**
     * @return float
     */
    public function getVersion(): float
    {
        return 33;
    }

    public function defaultRows(): int
    {
        return 13;
    }
    public function defaultColumns(): int
    {
        return 21;
    }
}
