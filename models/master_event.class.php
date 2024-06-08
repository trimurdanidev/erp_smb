<?php
    class master_event
    {
	var $id;
	var $tanggal_event_start;
	var $tanggal_event_end;
	var $name_event;
	var $descript_event;
	var $aktif_event;
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
	public function getTanggal_event_start() {
	   return $this->tanggal_event_start;
	}

	public function setTanggal_event_start($tanggal_event_start) {
	   $this->tanggal_event_start = $tanggal_event_start;
	}
	public function getTanggal_event_end() {
	   return $this->tanggal_event_end;
	}

	public function setTanggal_event_end($tanggal_event_end) {
	   $this->tanggal_event_end = $tanggal_event_end;
	}
	public function getName_event() {
	   return $this->name_event;
	}

	public function setName_event($name_event) {
	   $this->name_event = $name_event;
	}
	public function getDescript_event() {
	   return $this->descript_event;
	}

	public function setDescript_event($descript_event) {
	   $this->descript_event = $descript_event;
	}
	public function getAktif_event() {
	   return $this->aktif_event;
	}

	public function setAktif_event($aktif_event) {
	   $this->aktif_event = $aktif_event;
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
