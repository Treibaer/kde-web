<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 10.12.17
 * Time: 18:02
 */

namespace TBCore\Controller;

class LogoutController extends DefaultController
{
    public function start()
    {
        $this->coreWorker->general()->login()->logout();
        echo "Ausgeloggt.";
        die;
    }
}
