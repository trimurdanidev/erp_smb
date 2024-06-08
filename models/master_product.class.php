<?php
    class master_product
    {
	var $id;
	var $kd_product;
	var $nm_product;
	var $image_product;
	var $hrg_modal;
	var $hrg_jual;
	var $kategori_id;
	var $tipe_id;
	var $sts_aktif;
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
	public function getKd_product() {
	   return $this->kd_product;
	}

	public function setKd_product($kd_product) {
	   $this->kd_product = $kd_product;
	}
	public function getNm_product() {
	   return $this->nm_product;
	}

	public function setNm_product($nm_product) {
	   $this->nm_product = $nm_product;
	}
	public function getImage_product() {
	   return $this->image_product;
	}

	public function setImage_product($image_product) {
	   $this->image_product = $image_product;
	}
	public function getHrg_modal() {
	   return $this->hrg_modal;
	}

	public function setHrg_modal($hrg_modal) {
	   $this->hrg_modal = $hrg_modal;
	}
	public function getHrg_jual() {
	   return $this->hrg_jual;
	}

	public function setHrg_jual($hrg_jual) {
	   $this->hrg_jual = $hrg_jual;
	}
	public function getKategori_id() {
	   return $this->kategori_id;
	}

	public function setKategori_id($kategori_id) {
	   $this->kategori_id = $kategori_id;
	}
	public function getTipe_id() {
	   return $this->tipe_id;
	}

	public function setTipe_id($tipe_id) {
	   $this->tipe_id = $tipe_id;
	}
	public function getSts_aktif() {
	   return $this->sts_aktif;
	}

	public function setSts_aktif($sts_aktif) {
	   $this->sts_aktif = $sts_aktif;
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
