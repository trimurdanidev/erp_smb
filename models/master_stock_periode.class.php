<?php
    class master_stock_periode
    {
	var $id;
	var $mop;
	var $yop;
	var $kd_product;
	var $qty_stock;
	var $qty_stock_promo;
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
	public function getMop() {
	   return $this->mop;
	}

	public function setMop($mop) {
	   $this->mop = $mop;
	}
	public function getYop() {
	   return $this->yop;
	}

	public function setYop($yop) {
	   $this->yop = $yop;
	}
	public function getKd_product() {
	   return $this->kd_product;
	}

	public function setKd_product($kd_product) {
	   $this->kd_product = $kd_product;
	}
	public function getQty_stock() {
	   return $this->qty_stock;
	}

	public function setQty_stock($qty_stock) {
	   $this->qty_stock = $qty_stock;
	}
	public function getQty_stock_promo() {
	   return $this->qty_stock_promo;
	}

	public function setQty_stock_promo($qty_stock_promo) {
	   $this->qty_stock_promo = $qty_stock_promo;
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
