<?php
    class upload_trans_log
    {
	var $id;
	var $trans_type;
	var $trans_descrip;
	var $jumlah_data;
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
	public function getTrans_type() {
	   return $this->trans_type;
	}

	public function setTrans_type($trans_type) {
	   $this->trans_type = $trans_type;
	}
	public function getTrans_descrip() {
	   return $this->trans_descrip;
	}

	public function setTrans_descrip($trans_descrip) {
	   $this->trans_descrip = $trans_descrip;
	}
	public function getJumlah_data() {
	   return $this->jumlah_data;
	}

	public function setJumlah_data($jumlah_data) {
	   $this->jumlah_data = $jumlah_data;
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
