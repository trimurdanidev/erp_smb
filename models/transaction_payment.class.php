<?php
    class transaction_payment
    {
	var $id;
	var $trans_id;
	var $method;
	var $payment;
	var $payment_akun;
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
	public function getMethod() {
	   return $this->method;
	}

	public function setMethod($method) {
	   $this->method = $method;
	}
	public function getPayment() {
	   return $this->payment;
	}

	public function setPayment($payment) {
	   $this->payment = $payment;
	}
	public function getPayment_akun() {
	   return $this->payment_akun;
	}

	public function setPayment_akun($payment_akun) {
	   $this->payment_akun = $payment_akun;
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
