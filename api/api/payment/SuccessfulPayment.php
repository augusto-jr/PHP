<?php

namespace Blazing\api\payment;

class SuccessfulPayment{
    
    protected $successfulpayment;
    
    public function __construct($successfulpayment){
        $this->successfulpayment = $successfulpayment;
    }
    
}