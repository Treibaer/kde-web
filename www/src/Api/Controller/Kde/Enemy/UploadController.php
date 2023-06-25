<?php

namespace KDEApi\Controller\Kde\Enemy;

/**
 * Class UploadController
 * @package KDEApi\Controller\Kde\Enemy
 */
class UploadController extends \KDEApi\Controller\Kde\Character\UploadController
{
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
