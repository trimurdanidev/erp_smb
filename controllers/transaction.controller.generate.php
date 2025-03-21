<?php
    require_once './models/transaction.class.php';
    require_once './models/master_module.class.php';
    require_once './controllers/master_module.controller.php';
    require_once './models/master_group_detail.class.php';
    require_once './controllers/master_group_detail.controller.php';
    require_once './models/report_query.class.php';
    require_once './controllers/report_query.controller.php';
    require_once './controllers/tools.controller.php';
    require_once './database/config.php';

    if (!isset($_SESSION)) {
        session_start();
    }
 
    class transactionControllerGenerate
    {
        protected $transaction;
        var $modulename = "transaction";
        var $dbh;
        var $limit = 20;
        var $user = "None";
        var $ip = "";
        var $isadmin = false;
        var $ispublic = false;
        var $isread = false;
        var $isconfirm = false;
        var $isentry = false;
        var $isupdate = false;
        var $isdelete = false;
        var $isprint = false;
        var $isexport = false;
        var $isimport = false;
        var $lastID = "";
        var $toolsController;
        function __construct($transaction, $dbh) {
            $this->modulename = strtoupper($this->modulename);
            $this->transaction = $transaction;
            $this->dbh = $dbh;            
                                     
            $user = isset($_SESSION[config::$LOGIN_USER])? unserialize($_SESSION[config::$LOGIN_USER]): new master_user() ;
            $this->user = $user->getUser();
            $this->ip =  $_SERVER['REMOTE_ADDR'];
            if ($this->modulename != "MASTER_MODULE") {
                $master_module = new master_module();
                $master_moduleController = new master_moduleController($master_module, $this->dbh);
                $master_module_list = $master_moduleController->showFindData("module", "=", $this->modulename);            
                if(count($master_module_list) == 0) {
                    $master_module_list[] = new master_module();
                }
            }else{
                $master_module_list = $this->showFindData("module", "=", $this->modulename);
            }
            foreach ($master_module_list as $master_module){
                $this->ispublic = $master_module->getPublic() > 0 ? true : false;                
            }            
            if(isset($_SESSION[config::$ISADMIN])) {
                $this->isadmin = unserialize($_SESSION[config::$ISADMIN]);
            }else{
                $this->isadmin = false;
            }

            $this->isadmin = isset($_SESSION[config::$ISADMIN]) ? unserialize($_SESSION[config::$ISADMIN]) : false;
            if(isset($_SESSION[config::$MASTER_GROUP_DETAIL_LIST]) ){
                $master_group_detail_list = unserialize($_SESSION[config::$MASTER_GROUP_DETAIL_LIST]);
            }else{
                $master_group_detail_list[] = new master_group_detail();
            }
            foreach($master_group_detail_list as $master_group_detail) {
                if($master_group_detail->getModule() == $this->modulename) {
                    $this->isread = $master_group_detail->getRead();
                    $this->isconfirm = $master_group_detail->getConfirm();
                    $this->isentry = $master_group_detail->getEntry();
                    $this->isupdate = $master_group_detail->getUpdate();
                    $this->isdelete = $master_group_detail->getDelete();
                    $this->isprint = $master_group_detail->getPrint();
                    $this->isexport = $master_group_detail->getExport();
                    $this->isimport = $master_group_detail->getImport();
                    break;
                }                
            }
            $this->toolsController = new toolsController();
        }
        
        function insertData(){
            $datetime = date("Y-m-d H:i:s");
            
            $sql = "INSERT INTO transaction ";
            $sql .= " ( ";
	    $sql .= "`id`,";
	    $sql .= "`no_trans`,";
	    $sql .= "`tanggal`,";
	    $sql .= "`type_trans`,";
	    $sql .= "`qtyTotal`,";
	    $sql .= "`qtyRelease`,";
	    $sql .= "`trans_total`,";
	    $sql .= "`trans_status`,";
	    $sql .= "`created_by`,";
	    $sql .= "`upload_trans_log_id`,";
	    $sql .= "`created_at`,";
	    $sql .= "`updated_by`,";
	    $sql .= "`updated_at` ";
            $sql .= ") ";
            $sql .= " VALUES (";
	    $sql .= " null,";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getNo_trans(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getTanggal(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getType_trans(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getQtyTotal(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getQtyRelease(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getTrans_total(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getTrans_status(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getCreated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getUpload_trans_log_id(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getCreated_at(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getUpdated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->transaction->getUpdated_at(), $this->dbh)."' ";

            $sql .= ")";
            $execute = $this->dbh->query($sql);
        }
        
        
        function updateData(){
            $datetime = date("Y-m-d H:i:s");
            $sql = "UPDATE transaction SET ";
	    $sql .= "`id` = '".$this->toolsController->replacecharSave($this->transaction->getId(),$this->dbh)."',";
	    $sql .= "`no_trans` = '".$this->toolsController->replacecharSave($this->transaction->getNo_trans(),$this->dbh)."',";
	    $sql .= "`tanggal` = '".$this->toolsController->replacecharSave($this->transaction->getTanggal(),$this->dbh)."',";
	    $sql .= "`type_trans` = '".$this->toolsController->replacecharSave($this->transaction->getType_trans(),$this->dbh)."',";
	    $sql .= "`qtyTotal` = '".$this->toolsController->replacecharSave($this->transaction->getQtyTotal(),$this->dbh)."',";
	    $sql .= "`qtyRelease` = '".$this->toolsController->replacecharSave($this->transaction->getQtyRelease(),$this->dbh)."',";
	    $sql .= "`trans_total` = '".$this->toolsController->replacecharSave($this->transaction->getTrans_total(),$this->dbh)."',";
	    $sql .= "`trans_status` = '".$this->toolsController->replacecharSave($this->transaction->getTrans_status(),$this->dbh)."',";
	    $sql .= "`created_by` = '".$this->toolsController->replacecharSave($this->transaction->getCreated_by(),$this->dbh)."',";
	    $sql .= "`upload_trans_log_id` = '".$this->toolsController->replacecharSave($this->transaction->getUpload_trans_log_id(),$this->dbh)."',";
	    $sql .= "`created_at` = '".$this->toolsController->replacecharSave($this->transaction->getCreated_at(),$this->dbh)."',";
	    $sql .= "`updated_by` = '".$this->toolsController->replacecharSave($this->transaction->getUpdated_by(),$this->dbh)."',";
	    $sql .= "`updated_at` = '".$this->toolsController->replacecharSave($this->transaction->getUpdated_at(),$this->dbh)."' ";
            $sql .= "WHERE id = '".$this->transaction->getId()."'";                
            $execute = $this->dbh->query($sql);
        }
        
        function deleteData($id){
            $sql = "DELETE FROM transaction WHERE id = '".$id."'";
            $execute = $this->dbh->query($sql);
        }
        
        function showData($id){
            $sql = "SELECT * FROM transaction WHERE id = '".$this->toolsController->replacecharFind($id,$this->dbh)."'";
            
            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction, $row);
            
            return $this->transaction;
        }
        
        function checkData($id){
            $sql = "SELECT count(*) FROM transaction where id = '".$id."'";
            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        }
        
        function showDataAll(){
            $sql = $this->findDataWhere("");
            return $this->createList($sql);            
        }
        function showDataAllQuery(){
            return $this->findDataWhere($this->showDataWhereQuery());
        }
        function countDataAll(){            
            $sql = $this->findCountDataWhere($this->showDataWhereQuery());
            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        }

        function showDataWhereQuery(){
            $bsearch = isset($_REQUEST["search"]) ;
            $where = "";
            if ($bsearch) {
                $search = $_REQUEST["search"] ;
               $where .= " where id like '%".$search."%'";
               $where .= "       or  no_trans like '%".$search."%'";
               $where .= "       or  tanggal like '%".$search."%'";
               $where .= "       or  type_trans like '%".$search."%'";

            }            
            return $where;
        }        
        function showDataAllLimit(){
            $sql = $this->showDataAllLimitQuery();
            return $this->createList($sql);            
        }

        function showDataAllLimitQuery(){            
            $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : 20;
            $sql = $this->showDataAllQuery();
            $sql .= " limit ". $skip . ", ". $limit;
            return $sql;
        }
        function showFindData($field, $operator ,$keyword){
            $sql = $this->findData($field, $operator ,$keyword);
            return $this->createList($sql);
        }
        
        function findData($field, $operator ,$keyword){
            $where = "WHERE (".$field." ". $operator ."  '$keyword')";
            return $this->findDataWhere($where);
        }
        function findDataWhere($where){
            $sql = "SELECT * FROM transaction  ".$where;
            $sql .= " ORDER BY id";
            return $sql;
        }
        function findCountDataWhere($where){
            $sql = "SELECT count(id)  FROM transaction  ".$where;
            $sql .= " ORDER BY id";
            return $sql;
        }
        function findDataSql($sql){
            return $this->createList($sql);            
        }

        function createList($sql){
            $transaction_List = array();
            foreach ($this->dbh->query($sql) as $row) {
                    $transaction_ = new transaction();
                    $this->loadData($transaction_, $row);
                    $transaction_List[] = $transaction_;
            }
            return $transaction_List;            
        }

                
        function loadData($transaction,$row){
	    $transaction->setId(isset($row['id'])?$row['id'] : "");
	    $transaction->setNo_trans(isset($row['no_trans'])?$row['no_trans'] : "");
	    $transaction->setTanggal(isset($row['tanggal'])?$row['tanggal'] : "");
	    $transaction->setType_trans(isset($row['type_trans'])?$row['type_trans'] : "");
	    $transaction->setQtyTotal(isset($row['qtyTotal'])?$row['qtyTotal'] : "");
	    $transaction->setQtyRelease(isset($row['qtyRelease'])?$row['qtyRelease'] : "");
	    $transaction->setTrans_total(isset($row['trans_total'])?$row['trans_total'] : "");
	    $transaction->setTrans_status(isset($row['trans_status'])?$row['trans_status'] : "");
	    $transaction->setCreated_by(isset($row['created_by'])?$row['created_by'] : "");
	    $transaction->setUpload_trans_log_id(isset($row['upload_trans_log_id'])?$row['upload_trans_log_id'] : "");
	    $transaction->setCreated_at(isset($row['created_at'])?$row['created_at'] : "");
	    $transaction->setUpdated_by(isset($row['updated_by'])?$row['updated_by'] : "");
	    $transaction->setUpdated_at(isset($row['updated_at'])?$row['updated_at'] : "");

        }

        function show(){
            $this->showAll();
        }
        
        function showAll(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $last = $this->countDataAll();
                $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

                $sisa = $last % $limit;

                if ($sisa > 0) {
                    $last = $last - $sisa;
                }else if ($last - $limit < 0){
                    $last = 0;
                }else{
                    $last = $last -$limit;
                }

                $previous = $skip - $limit < 0 ? 0 : $skip - $limit ;

                if ($skip + $limit > $last) {
                    $next = $last;
                }else if($skip == 0 ) {
                    $next = $skip + $limit + 1;
                }else{
                    $next = $skip + $limit;
                }
                $first = 0;

                $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
                $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

                $transaction_list = $this->showDataAllLimit();

                $isadmin = $this->isadmin;
                $ispublic = $this->ispublic;
                $isread = $this->isread;
                $isconfirm = $this->isconfirm;
                $isentry = $this->isentry;
                $isupdate = $this->isupdate;
                $isdelete = $this->isdelete;
                $isprint = $this->isprint;
                $isexport = $this->isexport ;
                $isimport = $this->isimport;

                require_once './views/transaction/transaction_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showAllJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $last = $this->countDataAll();
                $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

                $sisa = intval($last % $limit);

                if ($sisa > 0) {
                    $last = $last - $sisa;
                }else if ($last - $limit < 0){
                    $last = 0;
                }else{
                    $last = $last -$limit;
                }

                $previous = $skip - $limit < 0 ? 0 : $skip - $limit ;

                if ($skip + $limit > $last) {
                    $next = $last;
                }else if($skip == 0 ) {
                    $next = $skip + $limit + 1;
                }else{
                    $next = $skip + $limit;
                }
                $first = 0;

                $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
                $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

                $transaction_list = $this->showDataAllLimit();
                $isadmin = $this->isadmin;
                $ispublic = $this->ispublic;
                $isread = $this->isread;
                $isconfirm = $this->isconfirm;
                $isentry = $this->isentry;
                $isupdate = $this->isupdate;
                $isdelete = $this->isdelete;
                $isprint = $this->isprint;
                $isexport = $this->isexport ;
                $isimport = $this->isimport;
                require_once './views/transaction/transaction_jquery_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        
        function showDetail(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $transaction_ = $this->showData($id);
                require_once './views/transaction/transaction_detail.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showDetailJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $transaction_ = $this->showData($id);
                require_once './views/transaction/transaction_jquery_detail.php';
            }else{
                echo  "You cannot access this module";
            }
        }
        
        function showForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $transaction_ = $this->showData($id);
                require_once './views/transaction/transaction_form.php';
            }else{
                echo "You cannot access this module";
            }
        }

        function showFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
                $transaction_ = $this->showData($id);
                require_once './views/transaction/transaction_jquery_form.php';
            }else{
                echo "You cannot access this module";
            }
        }        
        function deleteForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isdelete)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $this->deleteData($id);
                $this->showAll();
            }else{
                echo "You cannot access this module";
            }
        }
        function deleteFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isdelete)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $this->deleteData($id);
                $this->showAllJQuery();
            }else{
                echo "You cannot access this module";
            }
        }
        function saveFormJQuery(){
            $this->saveFormPost();
        }
        function saveForm(){
            $this->saveFormPost();
            $this->showAll();
        }                
        function saveFormPost(){
	    $id = isset($_POST['id'])?$_POST['id'] : "";
	    $no_trans = isset($_POST['no_trans'])?$_POST['no_trans'] : "";
	    $tanggal = isset($_POST['tanggal'])?$_POST['tanggal'] : "";
	    $type_trans = isset($_POST['type_trans'])?$_POST['type_trans'] : "";
	    $qtyTotal = isset($_POST['qtyTotal'])?$_POST['qtyTotal'] : "";
	    $qtyRelease = isset($_POST['qtyRelease'])?$_POST['qtyRelease'] : "";
	    $trans_total = isset($_POST['trans_total'])?$_POST['trans_total'] : "";
	    $trans_status = isset($_POST['trans_status'])?$_POST['trans_status'] : "";
	    $created_by = isset($_POST['created_by'])?$_POST['created_by'] : "";
	    $upload_trans_log_id = isset($_POST['upload_trans_log_id'])?$_POST['upload_trans_log_id'] : "";
	    $created_at = isset($_POST['created_at'])?$_POST['created_at'] : "";
	    $updated_by = isset($_POST['updated_by'])?$_POST['updated_by'] : "";
	    $updated_at = isset($_POST['updated_at'])?$_POST['updated_at'] : "";
	    $this->transaction->setId($id);
	    $this->transaction->setNo_trans($no_trans);
	    $this->transaction->setTanggal($tanggal);
	    $this->transaction->setType_trans($type_trans);
	    $this->transaction->setQtyTotal($qtyTotal);
	    $this->transaction->setQtyRelease($qtyRelease);
	    $this->transaction->setTrans_total($trans_total);
	    $this->transaction->setTrans_status($trans_status);
	    $this->transaction->setCreated_by($created_by);
	    $this->transaction->setUpload_trans_log_id($upload_trans_log_id);
	    $this->transaction->setCreated_at($created_at);
	    $this->transaction->setUpdated_by($updated_by);
	    $this->transaction->setUpdated_at($updated_at);            
            $this->saveData();
        }
        public function saveData(){
            $check = $this->checkData($this->transaction->getId());
            if($check == 0){
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isentry)){
                    $this->insertData();
                    $last_id = $this->dbh->lastInsertId();
                    $this->setLastId($last_id);
                    // echo "Data is Inserted-TRANS";
                }else{
                    //echo "You cannot insert data this module";
                }
            } else {
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                    $this->updateData();
                    //echo "Data is updated";
                }else{
                    //echo "You cannot update this module";
                }
            }
        }
        public function export() {
            $sql = $this->findDataWhere($this->showDataWhereQuery());
            header("Content-Type:application/csv",false);
            header("Content-Disposition: attachment; filename=". $this->getModulename() .".csv");           
            if($this->getModulename() != "report_query"){
                $report_query = new report_query();
                $report_query_controller = new report_queryController($report_query, $this->dbh);
                echo $report_query_controller->exportcsv($sql);
            }else{
                echo $this->exportcsv($sql);                
            }
        }
        public function printdata() {
            $sql = $this->findDataWhere($this->showDataWhereQuery());
            echo "<html>";
            echo "<head>";
            echo "</head>";
            echo "<body onLoad=\"window.print();window.close()\">";
            echo "<H1>".$this->getModulename()."</H1>";
            if($this->getModulename() != "report_query"){
                $report_query = new report_query();
                $report_query_controller = new report_queryController($report_query, $this->dbh);
                echo $report_query_controller->generatetableview($sql);
            }else{
                echo $this->generatetableview($sql);                
            }
            echo "</body>";
            echo "</html>";
        }
        public function getTransaction() {
            return $this->transaction;
        }
        public function setTransaction($transaction) {
            $this->transaction = $transaction;
        }
        public function getDbh() {
            return $this->dbh;
        }
        public function setDbh($dbh) {
            $this->dbh = $dbh;
        }
        public function getModulename() {
            return $this->modulename;
        }

        public function setModulename($modulename) {
            $this->modulename = $modulename;
        }

        public function getLimit() {
            return $this->limit;
        }

        public function setLimit($limit) {
            $this->limit = $limit;
        }

        public function getUser() {
            return $this->user;
        }

        public function setUser($user) {
            $this->user = $user;
        }

        public function getIp() {
            return $this->ip;
        }

        public function setIp($ip) {
            $this->ip = $ip;
        }

        public function getIsadmin() {
            return $this->isadmin;
        }

        public function setIsadmin($isadmin) {
            $this->isadmin = $isadmin;
        }

        public function getIspublic() {
            return $this->ispublic;
        }

        public function setIspublic($ispublic) {
            $this->ispublic = $ispublic;
        }

        public function getIsread() {
            return $this->isread;
        }

        public function setIsread($isread) {
            $this->isread = $isread;
        }

        public function getIsconfirm() {
            return $this->isconfirm;
        }

        public function setIsconfirm($isconfirm) {
            $this->isconfirm = $isconfirm;
        }

        public function getIsentry() {
            return $this->isentry;
        }

        public function setIsentry($isentry) {
            $this->isentry = $isentry;
        }

        public function getIsupdate() {
            return $this->isupdate;
        }

        public function setIsupdate($isupdate) {
            $this->isupdate = $isupdate;
        }

        public function getIsdelete() {
            return $this->isdelete;
        }

        public function setIsdelete($isdelete) {
            $this->isdelete = $isdelete;
        }

        public function getIsprint() {
            return $this->isprint;
        }

        public function setIsprint($isprint) {
            $this->isprint = $isprint;
        }

        public function getIsexport() {
            return $this->isexport;
        }

        public function setIsexport($isexport) {
            $this->isexport = $isexport;
        }

        public function getIsimport() {
            return $this->isimport;
        }

        public function setIsimport($isimport) {
            $this->isimport = $isimport;
        }

        public function setLastId($id){
            $this->lastID = $id;
        }
        
        public function getLastId(){
            return $this->lastID;
        }
    }
?>
