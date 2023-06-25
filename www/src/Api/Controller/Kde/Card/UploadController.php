<?php

namespace KDEApi\Controller\Kde\Card;

use KDE\Controller\DefaultController;
use KDE\Model\Card;

/**
 * Class UploadController
 * @package TBApi\Controller\Kde\Card
 */
class UploadController extends DefaultController
{
    public function start()
    {
        $ff = "images/kde/cards/";
        $folder = getenv("ROOT") . $ff;

        if (!isset($_FILES['file0']) ||!isset($_GET['type'])) {
            die;
        }
        $numberOfFiles = $_REQUEST["numberOfFiles"];
        for ($i = 0; $i < $numberOfFiles; $i++) {
            $realName = $_FILES['file' . $i]['name'];
            $parts = explode("/", $realName);
            $fileName = $parts[count($parts) - 1];
            $parts = explode(".", $realName);
            $extension = $parts[count($parts) - 1];
            $title = utf8_encode($fileName);
            if (strpos($realName, '.') !== false) {
                $title = join('.', array_slice($parts, 0, count($parts) - 1));
            }
            $fileNameUnique = $this->uniqueNameInFolder(time(), $folder, "." . $extension);

            $newName = $folder . $fileNameUnique;
            $path = $_FILES['file' . $i]['tmp_name'];
            move_uploaded_file($path, $newName);


            $changeDate = $_REQUEST['lastModified' . $i];

            touch($newName, $changeDate);


            $kdeCard = $this->dbManager->dbCard()->getByTitle($title);

            if($kdeCard === null) {
                $kdeCard = new Card();
                $kdeCard->setTitle($title);
            }
            $kdeCard->setType($_GET['type']);

            $kdeCard->setImageUrl("/" . $ff . $fileNameUnique);
            $this->dbManager->dbCard()->persist($kdeCard);
        }
        header('Content-Type: application/json; charset: utf8');
        echo "{\"success\": true}";
        die;
    }
    private function uniqueNameInFolder($prefix, $folder, $extension)
    {
        $suffix = rand(0, 1000);
        $fileName = join("_", [$prefix, $suffix]) . $extension;
        if (file_exists($folder . $fileName)) {
            return $this->uniqueNameInFolder($prefix, $folder, $extension);
        }
        return $fileName;
    }
}
