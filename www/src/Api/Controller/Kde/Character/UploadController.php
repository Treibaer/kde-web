<?php

namespace KDEApi\Controller\Kde\Character;

use KDE\Controller\DefaultController;

/**
 * Class UploadController
 * @package KDEApi\Controller\Kde\Character
 */
class UploadController extends DefaultController
{
    public function handleFile()
    {
        $relativePath = "/images/kde/";
        $folder = getenv("ROOT") . "../www" . $relativePath;

        if (!isset($_FILES['file'])) {
            return null;
        }
        $realName = $_FILES['file']['name'];
        $parts = explode("/", $realName);
        $fileName = $parts[count($parts) - 1];
        $parts = explode(".", $realName);
        $extension = $parts[count($parts) - 1];
        $title = $fileName;
        if (strpos($realName, '.') !== false) {
            $title = join('.', array_slice($parts, 0, count($parts) - 1));
        }

        $suffix = rand(0, 1000);
        $fileNameUnique = join("_", [time(), $suffix, $this->dbManager->getUser()->getUserId()]) . "." . $extension;

        $newName = $folder . $fileNameUnique;
        $path = $_FILES['file']['tmp_name'];
        move_uploaded_file($path, $newName);

        $changeDate = time();

        touch($newName, $changeDate);

        return $relativePath . $fileNameUnique;
    }

    public function start()
    {
        try {
            $fileName = $this->handleFile();

            echo json_encode(["data" => $fileName]);
            die;

        } catch (\Exception $exception) {
        }
        echo json_encode(["success" => true]);
        die;
    }
}
