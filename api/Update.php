<?php

namespace Blazing;
use Blazing\api\Message;
use Blazing\api\inline\InlineQuery;
use Blazing\api\inline\ChosenInlineResult;
use Blazing\api\CallbackQuery;
use Blazing\api\payment\ShippingQuery;
use Blazing\api\payment\PreCheckoutQuery;

//use mybots\kristie\Commands;

class Update{
    protected $update;
    protected $update_id;
    protected $updateobject;
    protected $host;
    
    const UpdateTypes = array('Message', 'InlineQuery', 'ChosenInlineResult', 'CallBackQuery', 'ShippingQuery', 'PreCheckoutQuery');
    
    public function __construct(array $resquest, $bot){
        $this->host = $bot;
        $this->update = $resquest;
        if (isset($this->update['update_id'])){
            $this->update_id = $this->update['update_id'];
        }
        if (isset($this->update['message'])){
            $this->updateobject = new Message($this->update['message']);
        }
        if (isset($this->update['edited_message'])){
            $this->updateobject = new Message($this->update['edited_message']);
        }
        if (isset($this->update['channel_post'])){
            $this->updateobject = new Message($this->update['channel_post']);
        }
        if (isset($this->update['edited_channel_post'])){
            $this->updateobject = new Message($this->update['edited_channel_post']);
        }
        if (isset($this->update['inline_query'])){
            $this->updateobject = new InlineQuery($this->update['inline_query']);
        }
        if (isset($this->update['chosen_inline_result'])){
            $this->updateobject = new ChosenInlineResult($this->update['chosen_inline_result']);
        }
        if (isset($this->update['callback_query'])){
            $this->updateobject = new CallbackQuery($this->update['callback_query']);
        }
        if (isset($this->update['shipping_query'])){
            $this->updateobject = new ShippingQuery($this->update['shipping_query']);
        }
        if (isset($this->update['pre_checkout_query'])){
            $this->updateobject = new PreCheckoutQuery($this->update['pre_checkout_query']);
        }
        
        $class = BOT_NAME . '\Updates';
        $class::newUpdate($bot, $this);
        
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
    
    public function getUpdateType(){
        $ok = false;
        $tempref = new \ReflectionClass($this->getUpdateObject());
        $type = $tempref->getShortName();
        foreach (self::UpdateTypes as &$value) {
            if ($type == $value){
                $ok = true;
            }
        }
        if (!$ok){
            throw new \Exception("Unknown update type! " . $type . " hello");
        }
        return $type;
    }
    
    public function hasCommand(){
        if ($this->getUpdateType() == 'Message' && $this->getUpdateObject()->has('entities')){
            foreach ($this->getUpdateObject()->getEntities() as $entity){
                if ($entity->getType() == 'bot_command'){
                    return true;
                }
            }
        }
        return false;
    }
    
    public function getCommand(){
        if ($this->hasCommand()){
            foreach ($this->getUpdateObject()->getEntities() as $entity){
                if ($entity->getType() == 'bot_command'){
                    return $entity->gettext();
                }
            }
        }else{
            throw new \Exception("Update does not contain a bot command!");
        }
    }
    
    public function isCallBackQuery(){
        if ($this->getUpdateType() == 'CallBackQuery'){
            return true;
        }else{
            return false;
        }
    }
    
    public function getCallBackQueryData(){
        if ($this->isCallBackQuery()){
            return $this->getUpdateObject()->getData();
        }else{
            throw new \Exception("Update does not contain a CBQ data!");
        }
    }
}