<?php

namespace Blazing;

class Response{
    
    protected $json_response;
    protected $array_response;
    
    public function __construct($json_response){
        $this->json_response = $json_response;
        $this->array_response = json_decode($json_response, true);
    }
    
    public function __call($method, $args) {
        if (strtolower(substr((string)$method, 0, 3)) == 'get'){
            $strip_field = substr($method, 3);
            $strip_field = strtolower(str_ireplace(array('_', '-', '.'), '', $strip_field));
            $ref = new \ReflectionClass($this);
            $found = false;
            foreach ($ref->getproperties() as $prop){
                $strip_prop = strtolower(str_ireplace(array('_', '-', '.'), '', $prop->getName()));
                if ($strip_field == $strip_prop){
                    $found = true;
                    $temp = $prop->getName();
                    return $this->$temp;
                }
            }
            if (!$found){
                throw new \Exception("Unknown method " . $method);
            }
        }elseif (strtolower(substr((string)$method, 0, 3)) == 'set'){
            $strip_field = substr($method, 3);
            $strip_field = strtolower(str_ireplace(array('_', '-', '.'), '', $strip_field));
            $ref = new \ReflectionClass($this);
            $found = false;
            foreach ($ref->getproperties() as $prop){
                $strip_prop = strtolower(str_ireplace(array('_', '-', '.'), '', $prop->getName()));
                if ($strip_field == $strip_prop){
                    $found = true;
                    $temp = $prop->getName();
                    $this->$temp = $args[0];
                }
            }
            if (!$found){
                throw new \Exception("Unknown method " . $method);
            }
        }else{
            throw new \Exception("Unknown method " . $method);
        }
    }
    
    public function getJson(){
        return $this->json_response;
    }
    
    public function isOK(){
        if ($this->array_response['ok'] == true){
            return true;
        }else{
            return false;
        }
    }
    
    public function getResult(){
        if ($this->isOK()){
            return $this->array_response['result'];
        }else{
            return $this->array_response;
        }
    }
    
    public function getMsgId(){
        return $this->array_response['result']['message_id'];
    }
    
    public function getArray(){
        return $this->array_response;
    }
    
    public function getErrorCode(){
        if ($this->isOK()){return 0;}
        return $this->array_response['error_code'];
    }
    
    public function getErrorDesc(){
        if ($this->isOK()){return "Not an error!";}
        return $this->array_response['description'];
    }
    
}