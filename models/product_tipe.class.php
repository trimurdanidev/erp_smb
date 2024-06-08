<?php
    class product_tipe
    {
	var $id;
	var $tipe_name;
	var $tipe_image;
	var $created_by;
	var $updated_by;
	var $created_at;
	var $updated_at;

        var $primarykey = "id";
        
	public function getId() {
	   return $this->id;
	}

	public function setId($id) {
	   $this->id = $id;
	}
	public function getTipe_name() {
	   return $this->tipe_name;
	}

	public function setTipe_name($tipe_name) {
	   $this->tipe_name = $tipe_name;
	}
	public function getTipe_image() {
	   return $this->tipe_image;
	}

	public function setTipe_image($tipe_image) {
	   $this->tipe_image = $tipe_image;
	}
	public function getCreated_by() {
	   return $this->created_by;
	}

	public function setCreated_by($created_by) {
	   $this->created_by = $created_by;
	}
	public function getUpdated_by() {
	   return $this->updated_by;
	}

	public function setUpdated_by($updated_by) {
	   $this->updated_by = $updated_by;
	}
	public function getCreated_at() {
	   return $this->created_at;
	}

	public function setCreated_at($created_at) {
	   $this->created_at = $created_at;
	}
	public function getUpdated_at() {
	   return $this->updated_at;
	}

	public function setUpdated_at($updated_at) {
	   $this->updated_at = $updated_at;
	}
         
    }
?>
