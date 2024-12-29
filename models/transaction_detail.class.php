<?php
    class transaction_detail
    {
	var $id;
	var $trans_id;
	var $kd_product;
	var $nm_product;
	var $trans_descript;
	var $qty;
	var $harga;

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
	public function getTrans_descript() {
	   return $this->trans_descript;
	}

	public function setTrans_descript($trans_descript) {
	   $this->trans_descript = $trans_descript;
	}
	public function getQty() {
	   return $this->qty;
	}

	public function setQty($qty) {
	   $this->qty = $qty;
	}
	public function getHarga() {
	   return $this->harga;
	}

	public function setHarga($harga) {
	   $this->harga = $harga;
	}
         
    }
?>
