<?php

/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 17:54
 */
namespace TBCore\Worker;

use \TBCore\Database\DbManager;
use \TBCore\Model\Log;

/**
 * Class Logging
 * @package classes
 */
class Logging {

    public function __construct() {
    }

    /**
     * @param int|LoggingType $type
     * @param $errorMessage
     */
    public static function log($type = LoggingType::INFO, $errorMessage) {

        $time = date('d.m.Y h:m:s');
        $outputText = '';
        $doNot = [LoggingType::QUERY, LoggingType::QUERY];
        if (!in_array($type, $doNot)) {
            $outputText = static::formatBackTrace(debug_backtrace());
            echo $outputText;
            echo("Fehlernachricht: <b>$errorMessage</b>");
            echo "<br>";
        } else {
        }
        $log = new Log();
        $log->setErrorMessage($errorMessage);
        $log->setLogId(0);
        $log->setLoggingType($type);
        $log->setStackTrace($outputText);
        $log->setTime(time());
        $log->setErrorFile(explode(":", $outputText)[0]);
        $log->setGetData(json_encode($_GET));
        $log->setPostData(json_encode($_POST));
        (new DbManager())->dbLog()->insert($log);
        if (false && $type == LoggingType::DISASTER) {
            $mail = new Mail();
            $mail->setSender('logging@treibaer.de=>Treibaer Logging');
            $mail->addRecipient('hannes@treibaer.dev=>Hannes');
            $mail->setTitle("[Disaster] Treibaer: $errorMessage");
            $mail->setContent("Disaster-Meldung am $time Uhr:<br><br>Fehlernachricht:<br><b>" . $errorMessage . "</b><br>StackTrace:<br>" . $outputText);
            $mail->send(true);
        }
    }

    /**
     * @param $backTrace
     * @return string
     */
    public static function formatBackTrace($backTrace) {
        $outputText = "";
        for ($i = 0; $i < count($backTrace); $i++) {
            $d = (array)$backTrace[$i];
            $outputText .= $d['file'] . ':';
            $outputText .= $d['line'] . '(';
            if (isset($d['class']))
                $outputText .= $d['class'] . '::';
            $outputText .= $d['function'] . ')';
            $outputText .= "<br>";

        }
        return $outputText;
    }
}
