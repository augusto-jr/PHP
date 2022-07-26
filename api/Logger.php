<?php

namespace Blazing;

class Logger
{
    protected $name;
    protected $path;
    
    public function __construct($name=null)
    {
		if ($name == null){
			$this->name = BOT_NAME;
		}else{
			$this->name = $name;
		}
        $this->path = $this->name.'/logs';
    }
    
    public function init()
    {
        if (!file_exists($this->name)){
            echo 'A bot with name '.$this->name.' does not seem to exist! Bot deleted! please try to create a new bot again.';
            $this->rrmdir($this->name);
            exit();
        }
        if (mkdir($this->name.'/logs') == false){
            echo 'failed to create logs directory! Bot deleted! please try to create a new bot again.';
            $this->rrmdir($this->name);
            exit();
        }
        $this->path = $this->name.'/logs';
        
        $errors = file_put_contents($this->path.'/errors.log', "[begin]\n\n");
        $responses = file_put_contents($this->path.'/responses.log', "[begin]\n\n");
        $updates = file_put_contents($this->path.'/updates.log', "[begin]\n\n");
        
        if ($errors == false || $responses == false || $updates == false){
            echo 'error while creating log files! Bot deleted! please try to create a new bot again.';
            $this->rrmdir($this->name);
            exit();
        }
        
        //$this->logError("log files created successfully");
        //$this->logError("log files created successfully");
    }
    
    private function rrmdir($src) 
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    
    public function logError(string $data)
    {
        $data = "\n\n" . date("d/m/Y H:i:s") . '        ' . $this->name . "\n" . $data;
        file_put_contents(APP_ROOT_FOLDER . '/' . $this->path.'/errors.log', $data , FILE_APPEND | LOCK_EX);
    }
    
    public function logResponse(string $data)
    {
        $data = "\n\n" . date("d/m/Y H:i:s") . '        ' . $this->name . "\n" . $data;
        file_put_contents(APP_ROOT_FOLDER . '/' . $this->path.'/responses.log', $data , FILE_APPEND | LOCK_EX);
    }
    
    public function logUpdate(string $data)
    {
        $data = "\n\n" . date("d/m/Y H:i:s") . '        ' . $this->name . "\n" . $data;
        file_put_contents(APP_ROOT_FOLDER . '/' . $this->path.'/updates.log', $data , FILE_APPEND | LOCK_EX);
    }
}