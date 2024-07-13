<?php
    require_once './models/master_user.class.php';
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
 
    class master_userControllerGenerate
    {
        protected $master_user;
        var $modulename = "master_user";
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
        function __construct($master_user, $dbh) {
            $this->modulename = strtoupper($this->modulename);
            $this->master_user = $master_user;
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
            
            $sql = "INSERT INTO master_user ";
            $sql .= " ( ";
	    $sql .= "`id`,";
	    $sql .= "`user`,";
	    $sql .= "`description`,";
	    $sql .= "`password`,";
	    $sql .= "`username`,";
	    $sql .= "`phone`,";
	    $sql .= "`nik`,";
	    $sql .= "`departmentid`,";
	    $sql .= "`unitid`,";
	    $sql .= "`entrytime`,";
	    $sql .= "`entryuser`,";
	    $sql .= "`entryip`,";
	    $sql .= "`updatetime`,";
	    $sql .= "`updateuser`,";
	    $sql .= "`updateip`,";
	    $sql .= "`avatar` ";
            $sql .= ") ";
            $sql .= " VALUES (";
	    $sql .= " null,";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getUser(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getDescription(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getPassword(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getUsername(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getPhone(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getNik(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getDepartmentid(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getUnitid(), $this->dbh)."',";
	    $sql .= "'".$datetime."',";
	    $sql .= "'".$this->user."',";
	    $sql .= "'".$this->ip."',";
	    $sql .= "'".$datetime."',";
	    $sql .= "'".$this->user."',";
	    $sql .= "'".$this->ip."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_user->getAvatar(), $this->dbh)."' ";

            $sql .= ")";
            $execute = $this->dbh->query($sql);
        }
        
        
        function updateData(){
            $datetime = date("Y-m-d H:i:s");
            $sql = "UPDATE master_user SET ";
	    $sql .= "`id` = '".$this->toolsController->replacecharSave($this->master_user->getId(),$this->dbh)."',";
	    $sql .= "`user` = '".$this->toolsController->replacecharSave($this->master_user->getUser(),$this->dbh)."',";
	    $sql .= "`description` = '".$this->toolsController->replacecharSave($this->master_user->getDescription(),$this->dbh)."',";
	    $sql .= "`password` = '".$this->toolsController->replacecharSave($this->master_user->getPassword(),$this->dbh)."',";
	    $sql .= "`username` = '".$this->toolsController->replacecharSave($this->master_user->getUsername(),$this->dbh)."',";
	    $sql .= "`phone` = '".$this->toolsController->replacecharSave($this->master_user->getPhone(),$this->dbh)."',";
	    $sql .= "`nik` = '".$this->toolsController->replacecharSave($this->master_user->getNik(),$this->dbh)."',";
	    $sql .= "`departmentid` = '".$this->toolsController->replacecharSave($this->master_user->getDepartmentid(),$this->dbh)."',";
	    $sql .= "`unitid` = '".$this->toolsController->replacecharSave($this->master_user->getUnitid(),$this->dbh)."',";
	    $sql .= "`updatetime` = '".$datetime."',";
	    $sql .= "`updateuser` = '".$this->user."',";
	    $sql .= "`updateip` = '".$this->ip."',";
	    $sql .= "`avatar` = '".$this->toolsController->replacecharSave($this->master_user->getAvatar(),$this->dbh)."' ";
            $sql .= "WHERE user = '".$this->master_user->getUser()."'";                
            $execute = $this->dbh->query($sql);
        }
        
        function deleteData($id){
            $sql = "DELETE FROM master_user WHERE user = '".$id."'";
            $execute = $this->dbh->query($sql);
        }
        
        function showData($id){
            $sql = "SELECT * FROM master_user WHERE user = '".$this->toolsController->replacecharFind($id,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->master_user, $row);
            
            return $this->master_user;
        }
        
        function checkData($id){
            $sql = "SELECT count(*) FROM master_user where user = '".$id."'";
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
               $where .= "       or  user like '%".$search."%'";
               $where .= "       or  description like '%".$search."%'";
               $where .= "       or  password like '%".$search."%'";

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
            $sql = "SELECT * FROM master_user  ".$where;
            $sql .= " ORDER BY user";
            return $sql;
        }
        function findCountDataWhere($where){
            $sql = "SELECT count(user)  FROM master_user  ".$where;
            $sql .= " ORDER BY user";
            return $sql;
        }
        function findDataSql($sql){
            return $this->createList($sql);            
        }

        function createList($sql){
            $master_user_List = array();
            foreach ($this->dbh->query($sql) as $row) {
                    $master_user_ = new master_user();
                    $this->loadData($master_user_, $row);
                    $master_user_List[] = $master_user_;
            }
            return $master_user_List;            
        }

                
        function loadData($master_user,$row){
	    $master_user->setId(isset($row['id'])?$row['id']:"");
	    $master_user->setUser(isset($row['user'])?$row['user']:"");
	    $master_user->setDescription(isset($row['description'])?$row['description']:"");
	    $master_user->setPassword(isset($row['password'])?$row['password']:"");
	    $master_user->setUsername(isset($row['username'])?$row['username']:"");
	    $master_user->setPhone(isset($row['phone'])?$row['phone']:"");
	    $master_user->setNik(isset($row['nik'])?$row['nik']:"");
	    $master_user->setDepartmentid(isset($row['departmentid'])?$row['departmentid']:"");
	    $master_user->setUnitid(isset($row['unitid'])?$row['unitid']:"");
	    $master_user->setEntrytime(isset($row['entrytime'])?$row['entrytime']:"");
	    $master_user->setEntryuser(isset($row['entryuser'])?$row['entryuser']:"");
	    $master_user->setEntryip(isset($row['entryip'])?$row['entryip']:"");
	    $master_user->setUpdatetime(isset($row['updatetime'])?$row['updatetime']:"");
	    $master_user->setUpdateuser(isset($row['updateuser'])?$row['updateuser']:"");
	    $master_user->setUpdateip(isset($row['updateip'])?$row['updateip']:"");
	    $master_user->setAvatar(isset($row['avatar'])?$row['avatar']:"");

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

                $master_user_list = $this->showDataAllLimit();

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

                require_once './views/master_user/master_user_list.php';
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

                $master_user_list = $this->showDataAllLimit();
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
                require_once './views/master_user/master_user_jquery_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        
        function showDetail(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $master_user_ = $this->showData($id);
                require_once './views/master_user/master_user_detail.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showDetailJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $master_user_ = $this->showData($id);
                require_once './views/master_user/master_user_jquery_detail.php';
            }else{
                echo  "You cannot access this module";
            }
        }
        
        function showForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $master_user_ = $this->showData($id);
                require_once './views/master_user/master_user_form.php';
            }else{
                echo "You cannot access this module";
            }
        }

        function showFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
                $master_user_ = $this->showData($id);
                require_once './views/master_user/master_user_jquery_form.php';
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
	    $user = isset($_POST['user'])?$_POST['user'] : "";
	    $description = isset($_POST['description'])?$_POST['description'] : "";
	    $password = isset($_POST['password'])?$_POST['password'] : "";
	    $username = isset($_POST['username'])?$_POST['username'] : "";
	    $phone = isset($_POST['phone'])?$_POST['phone'] : "";
	    $nik = isset($_POST['nik'])?$_POST['nik'] : "";
	    $departmentid = isset($_POST['departmentid'])?$_POST['departmentid'] : "";
	    $unitid = isset($_POST['unitid'])?$_POST['unitid'] : "";
	    $avatar = isset($_POST['avatar'])?$_POST['avatar'] : "";
	    $this->master_user->setId($id);
	    $this->master_user->setUser($user);
	    $this->master_user->setDescription($description);
	    $this->master_user->setPassword($password);
	    $this->master_user->setUsername($username);
	    $this->master_user->setPhone($phone);
	    $this->master_user->setNik($nik);
	    $this->master_user->setDepartmentid($departmentid);
	    $this->master_user->setUnitid($unitid);
	    $this->master_user->setAvatar($avatar);            
            $this->saveData();
        }
        public function saveData(){
            $check = $this->checkData($this->master_user->getUser());
            if($check == 0){
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isentry)){
                    $this->insertData();
                    $last_id = $this->dbh->lastInsertId();
                    $this->setLastId($last_id);
                    //echo "Data is Inserted";
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
        public function getMaster_user() {
            return $this->master_user;
        }
        public function setMaster_user($master_user) {
            $this->master_user = $master_user;
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
