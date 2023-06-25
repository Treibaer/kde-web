<?php

namespace KDE\Controller;

class ImageController extends DefaultController
{
    public function start()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        $path = explode('/', $requestURI);
        $path = explode(".png", $path[count($path) - 1])[0];
        $parts = explode("_", $path);
        $cardId = $parts[0];
        $style = "default";
        if (count($parts) > 1) {
            $style = $parts[1];
        }

        $root = getenv('ROOT');
        $card = $this->dbManager->dbCard()->all()[$cardId];
        header("Content-type: image/png");
        $pngName = $cardId . ".png";
        header("Content-Disposition:filename=" . $pngName);
        $filePath = $root . $card->originalImageUrl;
        if ($style === 'original') {
            readfile($filePath);
            die;
        }
        readfile($root.$card->originalImageUrl);
        die;
    }
}
