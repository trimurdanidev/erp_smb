<?php
    require_once './models/upload_trans_log.class.php';
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
 
    class upload_trans_logControllerGenerate
    {
        protected $upload_trans_log;
        var $modulename = "upload_trans_log";
        var $dbh;
        var $limit = 10;
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
        function __construct($upload_trans_log, $dbh) {
            $this->modulename = strtoupper($this->modulename);
            $this->upload_trans_log = $upload_trans_log;
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
            
            $sql = "INSERT INTO upload_trans_log ";
            $sql .= " ( ";
	    $sql .= "`id`,";
	    $sql .= "`trans_type`,";
	    $sql .= "`trans_descrip`,";
	    $sql .= "`jumlah_data`,";
	    $sql .= "`created_by`,";
	    $sql .= "`created_at`,";
	    $sql .= "`updated_by`,";
	    $sql .= "`updated_at` ";
            $sql .= ") ";
            $sql .= " VALUES (";
	    $sql .= " null,";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getTrans_type(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getTrans_descrip(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getJumlah_data(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getCreated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getCreated_at(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getUpdated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->upload_trans_log->getUpdated_at(), $this->dbh)."' ";

            $sql .= ")";
            $execute = $this->dbh->query($sql);
        }
        
        
        function updateData(){
            $datetime = date("Y-m-d H:i:s");
            $sql = "UPDATE upload_trans_log SET ";
	    $sql .= "`id` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getId(),$this->dbh)."',";
	    $sql .= "`trans_type` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getTrans_type(),$this->dbh)."',";
	    $sql .= "`trans_descrip` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getTrans_descrip(),$this->dbh)."',";
	    $sql .= "`jumlah_data` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getJumlah_data(),$this->dbh)."',";
	    $sql .= "`created_by` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getCreated_by(),$this->dbh)."',";
	    $sql .= "`created_at` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getCreated_at(),$this->dbh)."',";
	    $sql .= "`updated_by` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getUpdated_by(),$this->dbh)."',";
	    $sql .= "`updated_at` = '".$this->toolsController->replacecharSave($this->upload_trans_log->getUpdated_at(),$this->dbh)."' ";
            $sql .= "WHERE id = '".$this->upload_trans_log->getId()."'";                
            $execute = $this->dbh->query($sql);
        }
        
        function deleteData($id){
            $sql = "DELETE FROM upload_trans_log WHERE id = '".$id."'";
            $execute = $this->dbh->query($sql);
        }
        
        function showData($id){
            $sql = "SELECT * FROM upload_trans_log WHERE id = '".$this->toolsController->replacecharFind($id,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->upload_trans_log, $row);
            
            return $this->upload_trans_log;
        }
        
        function checkData($id){
            $sql = "SELECT count(*) FROM upload_trans_log where id = '".$id."'";
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
               $where .= "       or  trans_type like '%".$search."%'";
               $where .= "       or  trans_descrip like '%".$search."%'";
               $where .= "       or  jumlah_data like '%".$search."%'";

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
            $sql = "SELECT * FROM upload_trans_log  ".$where;
            $sql .= " ORDER BY created_at desc";
            return $sql;
        }
        function findCountDataWhere($where){
            $sql = "SELECT count(id)  FROM upload_trans_log  ".$where;
            $sql .= " ORDER BY id";
            return $sql;
        }
        function findDataSql($sql){
            return $this->createList($sql);            
        }

        function createList($sql){
            $upload_trans_log_List = array();
            foreach ($this->dbh->query($sql) as $row) {
                    $upload_trans_log_ = new upload_trans_log();
                    $this->loadData($upload_trans_log_, $row);
                    $upload_trans_log_List[] = $upload_trans_log_;
            }
            return $upload_trans_log_List;            
        }

                
        function loadData($upload_trans_log,$row){
	    $upload_trans_log->setId(isset($row['id'])?$row['id']:"");
	    $upload_trans_log->setTrans_type(isset($row['trans_type'])?$row['trans_type']:"");
	    $upload_trans_log->setTrans_descrip(isset($row['trans_descrip'])?$row['trans_descrip']:"");
	    $upload_trans_log->setJumlah_data(isset($row['jumlah_data'])?$row['jumlah_data']:"");
	    $upload_trans_log->setCreated_by(isset($row['created_by'])?$row['created_by']:"");
	    $upload_trans_log->setCreated_at(isset($row['created_at'])?$row['created_at']:"");
	    $upload_trans_log->setUpdated_by(isset($row['updated_by'])?$row['updated_by']:"");
	    $upload_trans_log->setUpdated_at(isset($row['updated_at'])?$row['updated_at']:"");

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

                $upload_trans_log_list = $this->showDataAllLimit();

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

                require_once './views/upload_trans_log/upload_trans_log_list.php';
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

                $upload_trans_log_list = $this->showDataAllLimit();
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
                require_once './views/upload_trans_log/upload_trans_log_jquery_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        
        function showDetail(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $upload_trans_log_ = $this->showData($id);
                require_once './views/upload_trans_log/upload_trans_log_detail.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showDetailJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $upload_trans_log_ = $this->showData($id);
                require_once './views/upload_trans_log/upload_trans_log_jquery_detail.php';
            }else{
                echo  "You cannot access this module";
            }
        }
        
        function showForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $upload_trans_log_ = $this->showData($id);
                require_once './views/upload_trans_log/upload_trans_log_form.php';
            }else{
                echo "You cannot access this module";
            }
        }

        function showFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
                $upload_trans_log_ = $this->showData($id);
                require_once './views/upload_trans_log/upload_trans_log_jquery_form.php';
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
	    $trans_type = isset($_POST['trans_type'])?$_POST['trans_type'] : "";
	    $trans_descrip = isset($_POST['trans_descrip'])?$_POST['trans_descrip'] : "";
	    $jumlah_data = isset($_POST['jumlah_data'])?$_POST['jumlah_data'] : "";
	    $created_by = isset($_POST['created_by'])?$_POST['created_by'] : "";
	    $created_at = isset($_POST['created_at'])?$_POST['created_at'] : "";
	    $updated_by = isset($_POST['updated_by'])?$_POST['updated_by'] : "";
	    $updated_at = isset($_POST['updated_at'])?$_POST['updated_at'] : "";
	    $this->upload_trans_log->setId($id);
	    $this->upload_trans_log->setTrans_type($trans_type);
	    $this->upload_trans_log->setTrans_descrip($trans_descrip);
	    $this->upload_trans_log->setJumlah_data($jumlah_data);
	    $this->upload_trans_log->setCreated_by($created_by);
	    $this->upload_trans_log->setCreated_at($created_at);
	    $this->upload_trans_log->setUpdated_by($updated_by);
	    $this->upload_trans_log->setUpdated_at($updated_at);            
            $this->saveData();
        }
        public function saveData(){
            $this->setIsadmin(true);
            $check = $this->checkData($this->upload_trans_log->getId());
            if($check == 0){
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isentry)){
                    $this->insertData();
                    $last_id = $this->dbh->lastInsertId();
                    $this->setLastId($last_id);
                    // echo "Berhasil Tersimpan-UPLTRLOG \n--";
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
        public function getUpload_trans_log() {
            return $this->upload_trans_log;
        }
        public function setUpload_trans_log($upload_trans_log) {
            $this->upload_trans_log = $upload_trans_log;
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
