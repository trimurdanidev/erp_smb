<?php
    class transaction
    {
	var $id;
	var $no_trans;
	var $tanggal;
	var $type_trans;
	var $qtyTotal;
	var $qtyRelease;
	var $trans_total;
	var $trans_status;
	var $created_by;
	var $upload_trans_log_id;
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
	public function getNo_trans() {
	   return $this->no_trans;
	}

	public function setNo_trans($no_trans) {
	   $this->no_trans = $no_trans;
	}
	public function getTanggal() {
	   return $this->tanggal;
	}

	public function setTanggal($tanggal) {
	   $this->tanggal = $tanggal;
	}
	public function getType_trans() {
	   return $this->type_trans;
	}

	public function setType_trans($type_trans) {
	   $this->type_trans = $type_trans;
	}
	public function getQtyTotal() {
	   return $this->qtyTotal;
	}

	public function setQtyTotal($qtyTotal) {
	   $this->qtyTotal = $qtyTotal;
	}
	public function getQtyRelease() {
	   return $this->qtyRelease;
	}

	public function setQtyRelease($qtyRelease) {
	   $this->qtyRelease = $qtyRelease;
	}
	public function getTrans_total() {
	   return $this->trans_total;
	}

	public function setTrans_total($trans_total) {
	   $this->trans_total = $trans_total;
	}
	public function getTrans_status() {
	   return $this->trans_status;
	}

	public function setTrans_status($trans_status) {
	   $this->trans_status = $trans_status;
	}
	public function getCreated_by() {
	   return $this->created_by;
	}

	public function setCreated_by($created_by) {
	   $this->created_by = $created_by;
	}
	public function getUpload_trans_log_id() {
	   return $this->upload_trans_log_id;
	}

	public function setUpload_trans_log_id($upload_trans_log_id) {
	   $this->upload_trans_log_id = $upload_trans_log_id;
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
