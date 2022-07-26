<?php

namespace Blazing;

use Blazing\Logger;
use \Exception;

class BotException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        
        $logger = new Logger(BOT_NAME);
        
        $logger->logError($message . "\n" . parent::__tostring() . "\nerror code: " . $code);
		exit;
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}