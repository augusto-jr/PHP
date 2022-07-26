<?php

namespace Blazing;

use Blazing\Logger;

class CurlRequest{
    
    protected $url;
    protected $data;
    
    public function __construct(string $url, array $data){
        $this->url = $url;
        $this->data = $data;
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
    
    public function execute(){
        $data = json_encode($this->data);
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new \Exception("the array provided could not be properly encoded as a json object!");
        }
        
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		$logger = new Logger();
		$logger->logResponse(print_r(json_decode($json_response,true),true));

        if ( $status != 201 && $status != 200 ) {
            $data = $this->data;
			if (isset($data['reply_to_message_id'])){
				unset($data['reply_to_message_id']);
				$this->data = $data;
				$this->execute();
			}
        }
        if ( $status != 201 && $status != 200 ) {
            //die("Error: call to URL $this->url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . var_dump($data));
            $logger = new logger(BOT_NAME);
            $logger->logError("Error: call to URL $this->url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . print_r($data));
        }
        curl_close($curl);
        $response = new Response($json_response);
        
        return $response;
    }
}