<?php


namespace TBCore\Exception;

use Throwable;

class Exception extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $trace = $this->getTrace();
        if (strlen($message) == 0 && count($trace) > 0 && isset($trace[0]['file']) && isset($trace[0]['function'])) {
            $currentTrace = $trace[0];
            $message = $currentTrace['file'] . ':' . $currentTrace['function'];
        }
        parent::__construct($message, $code, $previous);
    }
}
