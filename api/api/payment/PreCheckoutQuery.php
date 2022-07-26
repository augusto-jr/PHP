<?php

namespace Blazing\api\payment;

class PreCheckoutQuery{
    
    protected $preCheckoutQuery;
    
    public function __construct($preCheckoutQuery){
        $this->preCheckoutQuery = $preCheckoutQuery;
    }
    
}