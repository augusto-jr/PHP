<?php

namespace Blazing;

class FileBuilder
{
    public static function buildBotFiles($name, $token)
    {
        //bot.php
        define('APP_ROOT_FOLDER', getcwd());
        define('BOT_NAME', $name);
        
        $bot = new Bot($token);
        
        $searchF  = array('{bot_var_name}', '{bot_token}', '{bot_username}', '{bot_name}', '{bot_id}');
        $replaceW = array($name, $token, $bot->getUsername(), $bot->getName(), $bot->getId());
        
        $file = file_get_contents(__DIR__ . "/bot_file_templates/bot.txt");
        if ($file == false){
            return false;
        }
        $file = str_replace($searchF, $replaceW, $file);
        $res = file_put_contents("$name/$name.php", $file);
        if ($res == false){
            return false;
        }
        
        //updates.php
        $searchF  = array('{bot_var_name}', '{bot_token}');
        $replaceW = array($name, $token);
        
        $file = file_get_contents(__DIR__ . "/bot_file_templates/updates.txt");
        if ($file == false){
            return false;
        }
        $file = str_replace($searchF, $replaceW, $file);
        $res = file_put_contents("$name/Updates.php", $file);
        if ($res == false){
            return false;
        }
        
        //commands.php
        $searchF  = array('{bot_var_name}', '{bot_token}');
        $replaceW = array($name, $token);
        
        $file = file_get_contents(__DIR__ . "/bot_file_templates/commands.txt");
        if ($file == false){
            return false;
        }
        $file = str_replace($searchF, $replaceW, $file);
        $res = file_put_contents("$name/Commands.php", $file);
        if ($res == false){
            return false;
        }
        
        //callbackqueries.php
        $searchF  = array('{bot_var_name}', '{bot_token}');
        $replaceW = array($name, $token);
        
        $file = file_get_contents(__DIR__ . "/bot_file_templates/callbackqueries.txt");
        if ($file == false){
            return false;
        }
        $file = str_replace($searchF, $replaceW, $file);
        $res = file_put_contents("$name/CallBackQueries.php", $file);
        if ($res == false){
            return false;
        }
        
        return true;
    }
    
    public static function addCommand($command, $botname)
    {
        
    }
    
    public static function addQuery($query, $botname)
    {
        
    }
    
}
