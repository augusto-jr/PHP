<?php

namespace Blazing\api;

use Blazing\api\media\ChatPhoto;

class Chat{
    
    protected $chat;
    protected $id;
    protected $type;
    protected $title;
    protected $username;
    protected $FirstName;
    protected $LastName;
    protected $AllMembersAreAdministrators;
    protected $photo;
    protected $description;
    protected $InviteLink;
    
    public function __construct(array $chat){
        $this->chat = $chat;
        $this->id = $this->chat['id'];
        $this->type = $this->chat['type'];
        if (isset($this->chat['title'])){
            $this->title = $this->chat['title'];
        }
        if (isset($this->chat['username'])){
            $this->username = $this->chat['username'];
        }
        if (isset($this->chat['first_name'])){
            $this->FirstName = $this->chat['first_name'];
        }
        if (isset($this->chat['last_name'])){
            $this->LastName = $this->chat['last_name'];
        }
        if (isset($this->chat['all_members_are_administrators'])){
            $this->AllMembersAreAdministrators = $this->chat['all_members_are_administrators'];
        }
        if (isset($this->chat['photo'])){
            $this->photo = new ChatPhoto($this->chat['photo']);
        }
        if (isset($this->chat['description'])){
            $this->description = $this->chat['description'];
        }
        if (isset($this->chat['invite_link'])){
            $this->InviteLink = $this->chat['invite_link'];
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