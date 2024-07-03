<?php
    class master_pay_transfer
    {
	var $id;
	var $transfer;
	var $img;
	var $name_akun;
	var $rek_akun;
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
	public function getTransfer() {
	   return $this->transfer;
	}

	public function setTransfer($transfer) {
	   $this->transfer = $transfer;
	}
	public function getImg() {
	   return $this->img;
	}

	public function setImg($img) {
	   $this->img = $img;
	}
	public function getName_akun() {
	   return $this->name_akun;
	}

	public function setName_akun($name_akun) {
	   $this->name_akun = $name_akun;
	}
	public function getRek_akun() {
	   return $this->rek_akun;
	}

	public function setRek_akun($rek_akun) {
	   $this->rek_akun = $rek_akun;
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
