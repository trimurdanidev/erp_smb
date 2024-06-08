<?php
    class transaction_log
    {
	var $id;
	var $trans_id;
	var $trans_type;
	var $qty_before;
	var $qty_after;
	var $created_by;
	var $created_at;
	var $updated_by;
	var $updated_at;

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
	public function getTrans_type() {
	   return $this->trans_type;
	}

	public function setTrans_type($trans_type) {
	   $this->trans_type = $trans_type;
	}
	public function getQty_before() {
	   return $this->qty_before;
	}

	public function setQty_before($qty_before) {
	   $this->qty_before = $qty_before;
	}
	public function getQty_after() {
	   return $this->qty_after;
	}

	public function setQty_after($qty_after) {
	   $this->qty_after = $qty_after;
	}
	public function getCreated_by() {
	   return $this->created_by;
	}

	public function setCreated_by($created_by) {
	   $this->created_by = $created_by;
	}
	public function getCreated_at() {
	   return $this->created_at;
	}

	public function setCreated_at($created_at) {
	   $this->created_at = $created_at;
	}
	public function getUpdated_by() {
	   return $this->updated_by;
	}

	public function setUpdated_by($updated_by) {
	   $this->updated_by = $updated_by;
	}
	public function getUpdated_at() {
	   return $this->updated_at;
	}

	public function setUpdated_at($updated_at) {
	   $this->updated_at = $updated_at;
	}
         
    }
?>
