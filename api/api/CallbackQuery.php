<?php

namespace Blazing\api;

class CallBackQuery{
    
    protected $CallBackQuery;
    protected $id;
    protected $from;
    protected $sender;
    protected $message;
    protected $InlineMessageId;
    protected $ChatInstance;
    protected $data;
    protected $GameShortName;
    
    public function __construct(array $query){
        $this->CallBackQuery = $query;
        $this->id = $this->CallBackQuery['id'];
        $this->from = new User($this->CallBackQuery['from']);
        $this->sender = $this->from;
        if (isset($this->CallBackQuery['message'])){
            $this->message = new Message($this->CallBackQuery['message']);
        }
        if (isset($this->CallBackQuery['inline_message_id'])){
            $this->InlineMessageId = $this->CallBackQuery['inline_message_id'];
        }
        if (isset($this->CallBackQuery['chat_instance'])){
            $this->ChatInstance = $this->CallBackQuery['chat_instance'];
        }
        if (isset($this->CallBackQuery['data'])){
            $this->data = $this->CallBackQuery['data'];
        }
        if (isset($this->CallBackQuery['game_short_name'])){
            $this->GameShortName = $this->CallBackQuery['game_short_name'];
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
    
}