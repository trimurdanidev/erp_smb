<?php
    require_once './models/master_product.class.php';
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
 
    class master_productControllerGenerate
    {
        protected $master_product;
        var $modulename = "master_product";
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
        function __construct($master_product, $dbh) {
            $this->modulename = strtoupper($this->modulename);
            $this->master_product = $master_product;
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
            
            $sql = "INSERT INTO master_product ";
            $sql .= " ( ";
	    $sql .= "`id`,";
	    $sql .= "`kd_product`,";
	    $sql .= "`nm_product`,";
	    $sql .= "`image_product`,";
	    $sql .= "`hrg_modal`,";
	    $sql .= "`hrg_jual`,";
	    $sql .= "`kategori_id`,";
	    $sql .= "`tipe_id`,";
	    $sql .= "`sts_aktif`,";
	    $sql .= "`created_by`,";
	    $sql .= "`updated_by`,";
	    $sql .= "`created_at`,";
	    $sql .= "`updated_at` ";
            $sql .= ") ";
            $sql .= " VALUES (";
	    $sql .= " null,";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getKd_product(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getNm_product(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getImage_product(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getHrg_modal(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getHrg_jual(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getKategori_id(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getTipe_id(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getSts_aktif(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getCreated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getUpdated_by(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getCreated_at(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->master_product->getUpdated_at(), $this->dbh)."' ";

            $sql .= ")";
            $execute = $this->dbh->query($sql);
        }
        
        
        function updateData(){
            $datetime = date("Y-m-d H:i:s");
            $sql = "UPDATE master_product SET ";
	    $sql .= "`id` = '".$this->toolsController->replacecharSave($this->master_product->getId(),$this->dbh)."',";
	    $sql .= "`kd_product` = '".$this->toolsController->replacecharSave($this->master_product->getKd_product(),$this->dbh)."',";
	    $sql .= "`nm_product` = '".$this->toolsController->replacecharSave($this->master_product->getNm_product(),$this->dbh)."',";
	    $sql .= "`image_product` = '".$this->toolsController->replacecharSave($this->master_product->getImage_product(),$this->dbh)."',";
	    $sql .= "`hrg_modal` = '".$this->toolsController->replacecharSave($this->master_product->getHrg_modal(),$this->dbh)."',";
	    $sql .= "`hrg_jual` = '".$this->toolsController->replacecharSave($this->master_product->getHrg_jual(),$this->dbh)."',";
	    $sql .= "`kategori_id` = '".$this->toolsController->replacecharSave($this->master_product->getKategori_id(),$this->dbh)."',";
	    $sql .= "`tipe_id` = '".$this->toolsController->replacecharSave($this->master_product->getTipe_id(),$this->dbh)."',";
	    $sql .= "`sts_aktif` = '".$this->toolsController->replacecharSave($this->master_product->getSts_aktif(),$this->dbh)."',";
	    $sql .= "`created_by` = '".$this->toolsController->replacecharSave($this->master_product->getCreated_by(),$this->dbh)."',";
	    $sql .= "`updated_by` = '".$this->toolsController->replacecharSave($this->master_product->getUpdated_by(),$this->dbh)."',";
	    $sql .= "`created_at` = '".$this->toolsController->replacecharSave($this->master_product->getCreated_at(),$this->dbh)."',";
	    $sql .= "`updated_at` = '".$this->toolsController->replacecharSave($this->master_product->getUpdated_at(),$this->dbh)."' ";
            $sql .= "WHERE id = '".$this->master_product->getId()."'";                
            $execute = $this->dbh->query($sql);
        }
        
        function deleteData($id){
            $sql = "DELETE FROM master_product WHERE id = '".$id."'";
            $execute = $this->dbh->query($sql);
        }
        
        function showData($id){
            $sql = "SELECT * FROM master_product WHERE id = '".$this->toolsController->replacecharFind($id,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->master_product, $row);
            
            return $this->master_product;
        }
        
        function checkData($id){
            $sql = "SELECT count(*) FROM master_product where id = '".$id."'";
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
               $where .= "       or  kd_product like '%".$search."%'";
               $where .= "       or  nm_product like '%".$search."%'";
               $where .= "       or  image_product like '%".$search."%'";

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
            $sql = "SELECT * FROM master_product  ".$where;
            $sql .= " ORDER BY id";
            return $sql;
        }
        function findCountDataWhere($where){
            $sql = "SELECT count(id)  FROM master_product  ".$where;
            $sql .= " ORDER BY id";
            return $sql;
        }
        function findDataSql($sql){
            return $this->createList($sql);            
        }

        function createList($sql){
            $master_product_List = array();
            foreach ($this->dbh->query($sql) as $row) {
                    $master_product_ = new master_product();
                    $this->loadData($master_product_, $row);
                    $master_product_List[] = $master_product_;
            }
            return $master_product_List;            
        }

                
        function loadData($master_product,$row){
	    $master_product->setId(isset($row['id'])?$row['id']:"");
	    $master_product->setKd_product(isset($row['kd_product'])?$row['kd_product']:"");
	    $master_product->setNm_product(isset($row['nm_product'])?$row['nm_product']:"");
	    $master_product->setImage_product(isset($row['image_product'])?$row['image_product']:"");
	    $master_product->setHrg_modal(isset($row['hrg_modal'])?$row['hrg_modal']:"");
	    $master_product->setHrg_jual(isset($row['hrg_jual'])?$row['hrg_jual']:"");
	    $master_product->setKategori_id(isset($row['kategori_id'])?$row['kategori_id']:"");
	    $master_product->setTipe_id(isset($row['tipe_id'])?$row['tipe_id']:"");
	    $master_product->setSts_aktif(isset($row['sts_aktif'])?$row['sts_aktif']:"");
	    $master_product->setCreated_by(isset($row['created_by'])?$row['created_by']:"");
	    $master_product->setUpdated_by(isset($row['updated_by'])?$row['updated_by']:"");
	    $master_product->setCreated_at(isset($row['created_at'])?$row['created_at']:"");
	    $master_product->setUpdated_at(isset($row['updated_at'])?$row['updated_at']:"");

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

                $master_product_list = $this->showDataAllLimit();

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

                require_once './views/master_product/master_product_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showAllJQuery(){
            $this->setIsadmin(true);
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

                $master_product_list = $this->showDataAllLimit();
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
                require_once './views/master_product/master_product_jquery_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        
        function showDetail(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $master_product_ = $this->showData($id);
                require_once './views/master_product/master_product_detail.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showDetailJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $master_product_ = $this->showData($id);
                require_once './views/master_product/master_product_jquery_detail.php';
            }else{
                echo  "You cannot access this module";
            }
        }
        
        function showForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $master_product_ = $this->showData($id);
                require_once './views/master_product/master_product_form.php';
            }else{
                echo "You cannot access this module";
            }
        }

        function showFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
                $master_product_ = $this->showData($id);
                require_once './views/master_product/master_product_jquery_form.php';
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
	    $kd_product = isset($_POST['kd_product'])?$_POST['kd_product'] : "";
	    $nm_product = isset($_POST['nm_product'])?$_POST['nm_product'] : "";
	    $image_product = isset($_POST['image_product'])?$_POST['image_product'] : "";
	    $hrg_modal = isset($_POST['hrg_modal'])?$_POST['hrg_modal'] : "";
	    $hrg_jual = isset($_POST['hrg_jual'])?$_POST['hrg_jual'] : "";
	    $kategori_id = isset($_POST['kategori_id'])?$_POST['kategori_id'] : "";
	    $tipe_id = isset($_POST['tipe_id'])?$_POST['tipe_id'] : "";
	    $sts_aktif = isset($_POST['sts_aktif'])?$_POST['sts_aktif'] : "";
	    $created_by = isset($_POST['created_by'])?$_POST['created_by'] : "";
	    $updated_by = isset($_POST['updated_by'])?$_POST['updated_by'] : "";
	    $created_at = isset($_POST['created_at'])?$_POST['created_at'] : "";
	    $updated_at = isset($_POST['updated_at'])?$_POST['updated_at'] : "";
	    $this->duct->setId($id);
	    $this->master_product->setKd_product($kd_product);
	    $this->master_product->setNm_product($nm_product);
	    $this->master_product->setImage_product($image_product);
	    $this->master_product->setHrg_modal($hrg_modal);
	    $this->master_product->setHrg_jual($hrg_jual);
	    $this->master_product->setKategori_id($kategori_id);
	    $this->master_product->setTipe_id($tipe_id);
	    $this->master_product->setSts_aktif($sts_aktif);
	    $this->master_product->setCreated_by($created_by);
	    $this->master_product->setUpdated_by($updated_by);
	    $this->master_product->setCreated_at($created_at);
	    $this->master_product->setUpdated_at($updated_at);            
            $this->saveData();
        }
        public function saveData(){
            $check = $this->checkData($this->master_product->getId());
            if($check == 0){
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isentry)){
                    $this->insertData();
                    $last_id = $this->dbh->lastInsertId();
                    $this->setLastId($last_id);
                    // echo "Data is Inserted";
                }else{
                    //echo "You cannot insert data this module";
                }
            } else {
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                    $this->updateData();
                    // echo "Data is updated";
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
        public function getMaster_product() {
            return $this->master_product;
        }
        public function setMaster_product($master_product) {
            $this->master_product = $master_product;
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
