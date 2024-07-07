<?php
    class transaction_buyer
    {
	var $id;
	var $trans_id;
	var $buyer_name;
	var $buyer_phone;
	var $buyer_address;

        var $primarykey = "id";
        
	public function getId() {
	   return $this->id;
	}

	public function setId($id) {
	   $this->id = $id;
	}
	public function getTrans_id() {
	   return $this->trans_id;
	}

	public function setTrans_id($trans_id) {
	   $this->trans_id = $trans_id;
	}
	public function getBuyer_name() {
	   return $this->buyer_name;
	}

	public function setBuyer_name($buyer_name) {
	   $this->buyer_name = $buyer_name;
	}
	public function getBuyer_phone() {
	   return $this->buyer_phone;
	}

	public function setBuyer_phone($buyer_phone) {
	   $this->buyer_phone = $buyer_phone;
	}
	public function getBuyer_address() {
	   return $this->buyer_address;
	}

	public function setBuyer_address($buyer_address) {
	   $this->buyer_address = $buyer_address;
	}
         
    }
?>
