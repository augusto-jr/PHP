<?php

namespace Blazing\api\payment;

class Invoice{
    
    protected $invoice;
    
    public function __construct($invoice){
        $this->invoice = $invoice;
    }
}