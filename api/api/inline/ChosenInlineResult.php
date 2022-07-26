<?php

namespace Blazing\api\inline;

use Blazing\api\User;
use Blazing\api\media\Location;

class ChosenInlineResult{
    
    protected $ChosenInlineResult;
    protected id;
    protected from;
    protected sender;
    protected location;
    protected inline_message_id;
    protected query;
    
    public function __construct(array $result){
        $this->ChosenInlineResult = $result;
        $this->id = $result['result_id'];
        $this->from = new User($result['from']);
        $this->sender = $this->from;
        if (isset($result['location']){
            $this-> = new Location($result['location']);
        }
        if (isset($result['inline_message_id']){
            $this-> = $result['inline_message_id'];
        }
        $this-> = $result['query'];
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