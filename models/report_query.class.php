<?php
    class report_query
    {
	var $id;
	var $module;
	var $reportname;
	var $header;
	var $headertable;
	var $query;
	var $crosstab;
	var $total;
	var $subtotal;
	var $headertableshow;
	var $footertableshow;
	var $totalqueryid;
	var $entrytime;
	var $entryuser;
	var $entryip;
	var $updatetime;
	var $updateuser;
	var $updateip;

        var $primarykey = "id";
        
	public function getId() {
	   return $this->id;
	}

	public function setId($id) {
	   $this->id = $id;
	}
	public function getModule() {
	   return $this->module;
	}

	public function setModule($module) {
	   $this->module = $module;
	}
	public function getReportname() {
	   return $this->reportname;
	}

	public function setReportname($reportname) {
	   $this->reportname = $reportname;
	}
	public function getHeader() {
	   return $this->header;
	}

	public function setHeader($header) {
	   $this->header = $header;
	}
	public function getHeadertable() {
	   return $this->headertable;
	}

	public function setHeadertable($headertable) {
	   $this->headertable = $headertable;
	}
	public function getQuery() {
	   return $this->query;
	}

	public function setQuery($query) {
	   $this->query = $query;
	}
	public function getCrosstab() {
	   return $this->crosstab;
	}

	public function setCrosstab($crosstab) {
	   $this->crosstab = $crosstab;
	}
	public function getTotal() {
	   return $this->total;
	}

	public function setTotal($total) {
	   $this->total = $total;
	}
	public function getSubtotal() {
	   return $this->subtotal;
	}

	public function setSubtotal($subtotal) {
	   $this->subtotal = $subtotal;
	}
	public function getHeadertableshow() {
	   return $this->headertableshow;
	}

	public function setHeadertableshow($headertableshow) {
	   $this->headertableshow = $headertableshow;
	}
	public function getFootertableshow() {
	   return $this->footertableshow;
	}

	public function setFootertableshow($footertableshow) {
	   $this->footertableshow = $footertableshow;
	}
	public function getTotalqueryid() {
	   return $this->totalqueryid;
	}

	public function setTotalqueryid($totalqueryid) {
	   $this->totalqueryid = $totalqueryid;
	}
	public function getEntrytime() {
	   return $this->entrytime;
	}

	public function setEntrytime($entrytime) {
	   $this->entrytime = $entrytime;
	}
	public function getEntryuser() {
	   return $this->entryuser;
	}

	public function setEntryuser($entryuser) {
	   $this->entryuser = $entryuser;
	}
	public function getEntryip() {
	   return $this->entryip;
	}

	public function setEntryip($entryip) {
	   $this->entryip = $entryip;
	}
	public function getUpdatetime() {
	   return $this->updatetime;
	}

	public function setUpdatetime($updatetime) {
	   $this->updatetime = $updatetime;
	}
	public function getUpdateuser() {
	   return $this->updateuser;
	}

	public function setUpdateuser($updateuser) {
	   $this->updateuser = $updateuser;
	}
	public function getUpdateip() {
	   return $this->updateip;
	}

	public function setUpdateip($updateip) {
	   $this->updateip = $updateip;
	}
         
    }
?>
