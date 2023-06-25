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
    public function __construct($dbManager)
    {
        $this->dbManager = $dbManager;
    }
    
    public function subTabControl($page, $subPage)
    {
        return [
            new Tab("library" == $subPage, "?type=library", "Bibliothek"),
            //new Tab("weather" == $subPage, "?type=weather", "Wetter"),
            new Tab("marketplace" == $subPage, "?type=marketplace", "Marktplatz"),
        ];
    }

    public function allUsers()
    {
        return $this->dbManager->dbUser()->getUsersAsAssocArray();
    }

    public function allAvailableAchievements()
    {
        return $this->dbManager->dbAchievement()->all();
    }

    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEnemiesAndPets()
    {
        return $this->dbManager->dbEnemy()->allJSON();
    }
    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEnemies()
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON("enemy");
    }
    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getPets()
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON('pets');
    }
    /**
     * @return Enemy[]
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function getEvents()
    {
        return $this->dbManager->dbEnemy()->allByTypeJSON('event');
    }

    public function getStates()
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
    public function getVersion()
    {
        return 33;
    }

    /**
     * @param string $file
     * @param string $imageSize
     * @param string $extension
     */
    public function getImageResized(string $file, string $imageSize, string $extension)
    {
        $size = getimagesize($file);
        $width = $size[0];
        $height = $size[1];
        $thumbSize = 80;
        if ('thumb' === $imageSize) {
            $thumbSize = min($width, $height, 80);
        } else if ('small' === $imageSize) {
            $thumbSize = min($width, $height, 400);
        } else if ('medium' === $imageSize) {
            $thumbSize = min($width, $height) * 0.5;
        } else {
            $thumbSize = min($height, $imageSize);
        }
        if ($width > $height) {
            $newWidth = $thumbSize;
            $newHeight = $height / ($width / $thumbSize);
        } else {
            $newWidth = $width / ($height / $thumbSize);
            $newHeight = $thumbSize;
        }
        $thumb = imagecreatetruecolor($newWidth, $newHeight);

        imagesavealpha($thumb, true);

        imagefill($thumb, 0, 0, imagecolorallocatealpha($thumb, 0, 0, 0, 127));

        if ('jpg' === $extension) {
            imagecopyresized($thumb, imagecreatefromjpeg($file), 0, 0, 0, 0, $newWidth, $newHeight, $width,
                $height);
            imagejpeg($thumb);
        } elseif ('png' === $extension) {
            imagecopyresampled($thumb, imagecreatefrompng($file), 0, 0, 0, 0, $newWidth, $newHeight, $width,
                $height);
            imagepng($thumb);
        }
    }
    public function defaultRows() {
        return 13;
    }
    public function defaultColumns() {
        return 21;
    }
}
