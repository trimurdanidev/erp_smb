<?php
    class product_kategori
    {
	var $id;
	var $kategori_name;
	var $kategori_image;
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
	public function getKategori_name() {
	   return $this->kategori_name;
	}

	public function setKategori_name($kategori_name) {
	   $this->kategori_name = $kategori_name;
	}
	public function getKategori_image() {
	   return $this->kategori_image;
	}

	public function setKategori_image($kategori_image) {
	   $this->kategori_image = $kategori_image;
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
