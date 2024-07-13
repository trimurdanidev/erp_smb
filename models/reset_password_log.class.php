<?php
    class reset_password_log
    {
	var $id;
	var $user_id;
	var $date;
	var $time;

        var $primarykey = "id";
        
	public function getId() {
	   return $this->id;
	}

	public function setId($id) {
	   $this->id = $id;
	}
	public function getUser_id() {
	   return $this->user_id;
	}

	public function setUser_id($user_id) {
	   $this->user_id = $user_id;
	}
	public function getDate() {
	   return $this->date;
	}

	public function setDate($date) {
	   $this->date = $date;
	}
	public function getTime() {
	   return $this->time;
	}

	public function setTime($time) {
	   $this->time = $time;
	}
         
    }
?>
