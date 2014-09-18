<?php
class ShoppingCart {
	var $ckey;
    var $items;
	var $cdate;
	var $locations;
    function add_items($attributes, $qty){
		if(isset($this->items[$attributes])){
			$this->items[$attributes] += $qty;	
		}else{
			$this->items[$attributes] = $qty;	
		}
    }
    function update_items($attributes, $qty){
		if(isset($this->items[$attributes])){
			if($qty <= 0){
				$this->remove_item($attributes);
		  	}else{
				$this->items[$attributes] = $qty;	
			}	
		}
    }   
    function remove_item($attributes){
       	if(isset($this->items[$attributes])){
			unset($this->items[$attributes]);
	   	}
    }
    function show_cart(){
    	
       return $this->items;
    	}
    }
}