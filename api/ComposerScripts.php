<?php

namespace Blazing;

use Composer\Script\Event;

class ComposerScripts
{
    public static function new(Event $event)
    {
        $args = $event->getArguments();
		
		if (count($args) == 0)
		{
			echo "invalid command!";
			exit();
		}
        switch (strtolower($args[0]))
        {
            case "bot" : 
                self::newBot($args);
                break;
            /*case "command" : 
                self::newCommand($args);
                break;
            case "query" :
            case "callbackquery" :
                self::newQuery($args);
                break;*/
            default :
                echo "invalid command!";
                break;
        }
    }
    
    private static function newBot(array $args)
    {
		if (count($args) != 3)
		{
			echo "invalid format!\ncomposer new bot [bot_name] [bot_token]\n2 parameters required! given " . count($args);
			exit();
		}
		
        $name = $args[1];
        $token = $args[2];
        
        $pregtoken = '/^[0-9]{9}:[a-zA-Z0-9-*_*]{35}$/';
        $pregname = '/^[a-zA-Z0-9]{1,20}$/';
        $tokenresult = preg_match($pregtoken, $token);
        $nameresult = preg_match($pregname, $name);
        
        if ($tokenresult != 1)
        {
            echo "invalid token provided!";
			exit();
        }
        if ($nameresult != 1)
        {
            echo "invalid bot name!";
			exit();
        }
        
		if (file_exists($name))
		{
			echo "a bot named " . $name . " already exists!";
			exit();
		}
        
        if(mkdir($name) == false){
            echo "An error occured!";
            exit();
        }
		
		$logger = new Logger($name);
        $logger->init();
		
        if(FileBuilder::buildBotFiles($name, $token) == false){
            echo "An error occured! Bot deleted! please try to create a new bot again.";
            self::rrmdir($name);
            exit();
        }
        
		echo "$name created successfully!\n\nHappy Botting!";
		exit();
    }
    
    private static function newCommand(array $args)
    {
        if (count($args) != 3)
		{
			echo "invalid format!\ncomposer new command [bot_name] [command]\n2 parameters required! given " . count($args);
			exit();
		}
		
        $name = $args[1];
        $command = strtolower($args[2]);
        
        $commandresult = preg_match('/^[a-zA-Z0-9_]{1,100}$/', $command);
        
        if ($commandresult != 1)
        {
            echo 'invalid command provided! Command should only contain alphabetical characters, numbers and undersccores. Do not include "/" prefix.';
			exit();
        }
		if (!file_exists($name))
		{
			echo "a bot named " . $name . " does not seem to exist!";
			exit();
		}
        
        $class = $name.'\Commands';
        if ($class::has($command))
        {
            echo 'command seems to be already registered!';
            exit();
        }
        
        if(FileBuilder::addCommand($command, $name) == false){
            echo "An error occured!";
            exit();
        }
        
		echo 'command /' . $command . ' has been registered successfully for ' . $name;
		exit();
    }
    
    private static function newQuery(array $args)
    {
        if (count($args) != 3)
		{
			echo "invalid format!\ncomposer new query [bot_name] [query_data]\n2 parameters required! given " . count($args);
			exit();
		}
		
        $name = $args[1];
        $query = strtolower($args[2]);
        
        $queryresult = preg_match('/^[a-zA-Z0-9_]{1,100}$/', $command);
        
        if ($queryresult != 1)
        {
            echo 'invalid query_data provided! query should only contain alphabetical characters, numbers and undersccores.';
			exit();
        }
		if (!file_exists($name))
		{
			echo "a bot named " . $name . " does not seem to exist!";
			exit();
		}
        
        $class = $name.'\CallBackQueries';
        if ($class::has($query))
        {
            echo 'callback query data seems to be already registered!';
            exit();
        }
        
        if(FileBuilder::addQuery($command, $name) == false){
            echo "An error occured!";
            exit();
        }
        
		echo 'CallBackQuery with data ' . $query . ' has been registered successfully for ' . $name;
		exit();
    }
    
    private static function rrmdir($src) 
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    self::rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}