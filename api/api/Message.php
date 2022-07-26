<?php

namespace Blazing\api;

use Blazing\api\media\Audio;
use Blazing\api\media\Document;
use Blazing\api\media\Game;
use Blazing\api\media\PhotoSize;
use Blazing\api\media\Sticker;
use Blazing\api\media\Video;
use Blazing\api\media\Voice;
use Blazing\api\media\VideoNote;
use Blazing\api\media\Contact;
use Blazing\api\media\Location;
use Blazing\api\media\Venue;
use Blazing\api\payment\Invoice;
use Blazing\api\payment\SuccessfulPayment;

class Message{
    
    //fields/properties/vars region
    protected $message;
    protected $id;
    protected $text;
    protected $chat;
    protected $sender;
    protected $SendDate;
    protected $ForwardOriginSender;
    protected $ForwardOriginChat;
    protected $ForwardOriginMessageId;
    protected $ForwardOriginDate;
    protected $ReplyMessage;
    protected $EditDate;
    protected $entities = array();
    protected $audio;
    protected $document;
    protected $game;
    protected $photo;
    protected $sticker;
    protected $video;
    protected $voice;
    protected $VideoNote;
    protected $NewChatMembers;
    protected $caption;
    protected $contact;
    protected $location;
    protected $venue;
    protected $NewChatMember;
    protected $LeftChatMember;
    protected $NewChatTitle;
    protected $NewChatPhoto;
    protected $DeleteChatPhoto;
    protected $GroupChatCreated;
    protected $SuperGroupChatCreated;
    protected $ChannelChatCreated;
    protected $MigrateToChatId;
    protected $MigrateFromChatId;
    protected $PinnedMessage;
    protected $invoice;
    protected $SuccessfulPayment;
    //endregion
    
    public function __construct(array $message){
        $this->message = $message;
        $this->id = $this->message['message_id'];
        if (isset($this->message['text'])) {
            $this->text = $this->message['text'];
        }
        $this->chat = new Chat($this->message['chat']);
        if (isset($this->message['from'])) {
            $this->sender = new User($this->message['from']);
        }
        $this->Date = $this->message['date'];
        if (isset($this->message['forward_from'])) {
            $this->ForwardOriginSender = new User($this->message['forward_from']);
        }
        if (isset($this->message['forward_from_chat'])) {
            $this->ForwardOriginChat = new chat($this->message['forward_from_chat']);
        }
        if (isset($this->message['forward_from_message_id'])) {
            $this->ForwardOriginMessageId = $this->message['forward_from_message_id'];
        }
        if (isset($this->message['forward_date'])) {
            $this->ForwardOriginDate = $this->message['forward_date'];
        }
        if (isset($this->message['reply_to_message'])) {
            $this->ReplyMessage = new Message($this->message['reply_to_message']);
        }
        if (isset($this->message['edit_date'])) {
            $this->EditDate = $this->message['edit_date'];
        }
        if (isset($this->message['entities'])) {
            foreach ($this->message['entities'] as $entity) {
                $this->entities[] = new Entity($entity, $this);
            }
        }
        if (isset($this->message['audio'])) {
            $this->audio = new Audio($this->message['audio']);
        }
        if (isset($this->message['document'])) {
            $this->document = new Document($this->message['document']);
        }
        if (isset($this->message['game'])) {
            $this->game = new Game($this->message['game']);
        }
        if (isset($this->message['photo'])) {
            $this->photo = new PhotoSize($this->message['photo']);
        }
        if (isset($this->message['sticker'])) {
            $this->sticker = new Sticker($this->message['sticker']);
        }
        if (isset($this->message['video'])) {
            $this->video = new Video($this->message['video']);
        }
        if (isset($this->message['voice'])) {
            $this->voice = new Voice($this->message['voice']);
        }
        if (isset($this->message['video_note'])) {
            $this->VideoNote = new VideoNote($this->message['video_note']);
        }
        if (isset($this->message['new_chat_members'])) {
            $this->NewChatMembers = $this->message['new_chat_members'];
        }
        if (isset($this->message['caption'])) {
            $this->caption = $this->message['caption'];
        }
        if (isset($this->message['contact'])) {
            $this->contact = new Contact($this->message['contact']);
        }
        if (isset($this->message['location'])) {
            $this->location = new Location($this->message['location']);
        }
        if (isset($this->message['venue'])) {
            $this->venue = new Venue($this->message['venue']);
        }
        if (isset($this->message['new_chat_member'])) {
            $this->NewChatMember = new User($this->message['new_chat_member']);
        }
        if (isset($this->message['left_chat_member'])) {
            $this->LeftChatMember = new User($this->message['left_chat_member']);
        }
        if (isset($this->message['new_chat_title'])) {
            $this->NewChatTitle = $this->message['new_chat_title'];
        }
        if (isset($this->message['new_chat_photo'])) {
            $this->NewChatPhoto = $this->message['new_chat_photo'];
        }
        if (isset($this->message['delete_chat_photo'])) {
            $this->DeleteChatPhoto = $this->message['delete_chat_photo'];
        }
        if (isset($this->message['group_chat_created'])) {
            $this->GroupChatCreated = $this->message['group_chat_created'];
        }
        if (isset($this->message['supergroup_chat_created'])) {
            $this->SuperGroupChatCreated = $this->message['supergroup_chat_created'];
        }
        if (isset($this->message['channel_chat_created'])) {
            $this->ChannelChatCreated = $this->message['channel_chat_created'];
        }
        if (isset($this->message['migrate_to_chat_id'])) {
            $this->MigrateToChatId = $this->message['migrate_to_chat_id'];
        }
        if (isset($this->message['migrate_from_chat_id'])) {
            $this->MigrateFromChatId = $this->message['migrate_from_chat_id'];
        }
        if (isset($this->message['pinned_message'])) {
            $this->PinnedMessage = new Message($this->message['pinned_message']);
        }
        if (isset($this->message['invoice'])) {
            $this->invoice = new Invoice($this->message['invoice']);
        }
        if (isset($this->message['successful_payment'])) {
            $this->SuccessfulPayment = new SuccessfulPayment($this->message['successful_payment']);
        }
    }
    
    public function has($strip_field) {
        if ($this->$strip_field == null) {
            return false;
        }
        return true;
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
    
    public function hasCommand(){
        foreach ($this->entities as $entity){
            if ($entity->getType() == 'bot_command'){
                return true;
            }
        }
        return false;
    }
    
    public function getCommand(){
        foreach ($this->entities as $entity){
            if ($entity->getType() == 'bot_command'){
                return $entity->getText();
            }
        }
        throw new \Exception("Message does not contain a command!");
    }
    
    
    
}