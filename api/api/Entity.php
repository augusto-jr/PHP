<?php

namespace Blazing\api;

class Entity{
    
    protected $entity;
    protected $type;
    protected $offset;
    protected $length;
    protected $url;
    protected $user;
    protected $message;
    
    public function __construct($entity, $message){
        $this->entity = $entity;
        $this->message = $message;
        $this->offset = $this->entity['offset'];
        $this->length = $this->entity['length'];
        $this->type = $this->entity['type'];
        if (isset($this->entity['url'])){
            $this->url = $this->entity['url'];
        }
        if (isset($this->entity['user'])){
            $this->user = $this->entity['user'];
        }
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
    
    public function getText(){
        return substr($this->getMessage()->getText(), $this->getOffset(), $this->getLength());
    }
    
}