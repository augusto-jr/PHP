<?php

namespace Blazing\api\inline;

class InlineKeyboard{
	protected $kb;
	protected $rec;
    
    public function __construct(){
		$this->rec = array();
		$this->kb = array();
	}
	public function addButton(array $data, $row, $column){
		if ($row < 1 || $column < 1){
			throw new \Exception("row and column args should be positive integers");
		}
		if ($column > 5){
			throw new \Exception("Maximum 5 buttons is allowed per row");
		}
		
		if (array_key_exists($row, $this->rec)){
			$cols = $this->rec[$row];
			$cols = array_merge($cols, array($column-1 => $column));
			$this->rec[$row] = $cols;
		}else{
			$this->rec[$row] = array($column-1 => $column);
		}
		
		$this->kb[$row-1][$column-1] = $data;
	}
	public function getKeyboard(){
		if (empty($this->kb)){
			throw new \Exception("No buttons on added to the keyboard");
		}
		$i =0;
		foreach ($this->rec as $key => $rec){
			$i++;
			if ($key !== $i){
				throw new \Exception("row $i missing!");
			}
			$j = 0;
			foreach ($rec as $k => $r){
				$j++;
				if ($r !== $j){
					throw new \Exception("column $j of row $i missing!");
				}
			}
		}
		return json_encode(array("inline_keyboard" => $this->kb), true);
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