<?php

namespace Blazing\api\payment;

class ShippingQuery{
    
    protected $shippingQuery;
    
    public function __construct($shippingQuery){
        $this->shippingQuery = $shippingQuery;
    }
    
}