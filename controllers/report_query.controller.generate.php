<?php
    require_once './models/report_query.class.php';
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
 
    class report_queryControllerGenerate
    {
        protected $report_query;
        var $modulename = "report_query";
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
        function __construct($report_query, $dbh) {
            $this->modulename = strtoupper($this->modulename);
            $this->report_query = $report_query;
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
            
            $sql = "INSERT INTO report_query ";
            $sql .= " ( ";
	    $sql .= "`id`,";
	    $sql .= "`reportname`,";
	    $sql .= "`header`,";
	    $sql .= "`query`,";
	    $sql .= "`crosstab`,";
	    $sql .= "`total`,";
	    $sql .= "`subtotal`,";
	    $sql .= "`entrytime`,";
	    $sql .= "`entryuser`,";
	    $sql .= "`entryip`,";
	    $sql .= "`updatetime`,";
	    $sql .= "`updateuser`,";
	    $sql .= "`updateip` ";
            $sql .= ") ";
            $sql .= " VALUES (";
	    $sql .= " null,";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getReportname(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getHeader(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getQuery(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getCrosstab(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getTotal(), $this->dbh)."',";
	    $sql .= "'".$this->toolsController->replacecharSave($this->report_query->getSubtotal(), $this->dbh)."',";
	    $sql .= "'".$datetime."',";
	    $sql .= "'".$this->user."',";
	    $sql .= "'".$this->ip."',";
	    $sql .= "'".$datetime."',";
	    $sql .= "'".$this->user."',";
	    $sql .= "'".$this->ip."' ";

            $sql .= ")";
            $execute = $this->dbh->query($sql);
        }
        
        
        function updateData(){
            $datetime = date("Y-m-d H:i:s");
            $sql = "UPDATE report_query SET ";
	    $sql .= "`id` = '".$this->toolsController->replacecharSave($this->report_query->getId(),$this->dbh)."',";
	    $sql .= "`reportname` = '".$this->toolsController->replacecharSave($this->report_query->getReportname(),$this->dbh)."',";
	    $sql .= "`header` = '".$this->toolsController->replacecharSave($this->report_query->getHeader(),$this->dbh)."',";
	    $sql .= "`query` = '".$this->toolsController->replacecharSave($this->report_query->getQuery(),$this->dbh)."',";
	    $sql .= "`crosstab` = '".$this->toolsController->replacecharSave($this->report_query->getCrosstab(),$this->dbh)."',";
	    $sql .= "`total` = '".$this->toolsController->replacecharSave($this->report_query->getTotal(),$this->dbh)."',";
	    $sql .= "`subtotal` = '".$this->toolsController->replacecharSave($this->report_query->getSubtotal(),$this->dbh)."',";
	    $sql .= "`updatetime` = '".$datetime."',";
	    $sql .= "`updateuser` = '".$this->user."',";
	    $sql .= "`updateip` = '".$this->ip."' ";
            $sql .= "WHERE id = '".$this->report_query->getId()."'";                
            $execute = $this->dbh->query($sql);
        }
        
        function deleteData($id){
            $sql = "DELETE FROM report_query WHERE id = '".$id."'";
            $execute = $this->dbh->query($sql);
        }
        
        function showData($id){
            $sql = "SELECT * FROM report_query WHERE id = '".$this->toolsController->replacecharFind($id,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->report_query, $row);
            
            return $this->report_query;
        }
        
        function checkData($id){
            $sql = "SELECT count(*) FROM report_query where id = '".$id."'";
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
               $where .= "       or  reportname like '%".$search."%'";
               $where .= "       or  header like '%".$search."%'";
               $where .= "       or  query like '%".$search."%'";

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
            $sql = "SELECT * FROM report_query  ".$where;
            $sql .= " ORDER BY id desc";
            return $sql;
        }
        function findCountDataWhere($where){
            $sql = "SELECT count(id)  FROM report_query  ".$where;
            $sql .= " ORDER BY id desc";
            return $sql;
        }
        function findDataSql($sql){
            return $this->createList($sql);            
        }

        function createList($sql){
            $report_query_List = array();
            foreach ($this->dbh->query($sql) as $row) {
                    $report_query_ = new report_query();
                    $this->loadData($report_query_, $row);
                    $report_query_List[] = $report_query_;
            }
            return $report_query_List;            
        }

                
        function loadData($report_query,$row){
	    $report_query->setId($row['id']);
	    $report_query->setReportname($row['reportname']);
	    $report_query->setHeader($row['header']);
	    $report_query->setQuery($row['query']);
	    $report_query->setCrosstab($row['crosstab']);
	    $report_query->setTotal($row['total']);
	    $report_query->setSubtotal($row['subtotal']);
	    $report_query->setEntrytime($row['entrytime']);
	    $report_query->setEntryuser($row['entryuser']);
	    $report_query->setEntryip($row['entryip']);
	    $report_query->setUpdatetime($row['updatetime']);
	    $report_query->setUpdateuser($row['updateuser']);
	    $report_query->setUpdateip($row['updateip']);

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

                $report_query_list = $this->showDataAllLimit();

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

                require_once './views/report_query/report_query_list.php';
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

                $report_query_list = $this->showDataAllLimit();
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
                require_once './views/report_query/report_query_jquery_list.php';
            }else{
                echo "You cannot access this module";
            }
        }
        
        function showDetail(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $report_query_ = $this->showData($id);
                require_once './views/report_query/report_query_detail.php';
            }else{
                echo "You cannot access this module";
            }
        }
        function showDetailJQuery(){
            if ($this->ispublic || $this->isadmin || $this->isread ){
                $id = $_GET['id'];
                $report_query_ = $this->showData($id);
                require_once './views/report_query/report_query_jquery_detail.php';
            }else{
                echo  "You cannot access this module";
            }
        }
        
        function showForm(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $report_query_ = $this->showData($id);
                require_once './views/report_query/report_query_form.php';
            }else{
                echo "You cannot access this module";
            }
        }

        function showFormJQuery(){
            if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
                $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
                $report_query_ = $this->showData($id);
                require_once './views/report_query/report_query_jquery_form.php';
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
	    $reportname = isset($_POST['reportname'])?$_POST['reportname'] : "";
	    $header = isset($_POST['header'])?$_POST['header'] : "";
	    $query = isset($_POST['query'])?$_POST['query'] : "";
	    $crosstab = isset($_POST['crosstab'])?$_POST['crosstab'] : "";
	    $total = isset($_POST['total'])?$_POST['total'] : "";
	    $subtotal = isset($_POST['subtotal'])?$_POST['subtotal'] : "";

	    $this->report_query->setId($id);
	    $this->report_query->setReportname($reportname);
	    $this->report_query->setHeader($header);
	    $this->report_query->setQuery($query);
	    $this->report_query->setCrosstab($crosstab);
	    $this->report_query->setTotal($total);
	    $this->report_query->setSubtotal($subtotal);
            
            $this->saveData();
        }
        public function saveData(){
            $check = $this->checkData($this->report_query->getId());
            if($check == 0){
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isentry)){
                    $this->insertData();
                    $last_id = $this->dbh->lastInsertId();
                    $this->setLastId($last_id);
                    echo "Data is Inserted";
                }else{
                    echo "You cannot insert data this module";
                }
            } else {
                if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)){
                    $this->updateData();
                    echo "Data is updated";
                }else{
                    echo "You cannot update this module";
                }
            }
        }
        public function export() {
            $sql = $this->findDataWhere($this->showDataWhereQuery());
            header("Content-Type:application/xls",false);
            header("Content-Disposition: attachment; filename=". $this->getModulename() .".xls");           
            if($this->getModulename() != "report_query"){
                $report_query = new report_query();
                $report_query_controller = new report_queryController($report_query, $this->dbh);
                echo $report_query_controller->generatetableviewExcel($sql);
            }else{
                echo $this->generatetableviewExcel($sql);                
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
        public function getReport_query() {
            return $this->report_query;
        }
        public function setReport_query($report_query) {
            $this->report_query = $report_query;
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
        function searchreport_query(){
            $search         = empty ($_REQUEST['search'])?"":$_REQUEST['search'];
            $limit          = empty ($_REQUEST['limit'])?"5":$_REQUEST['limit'];
            $skip           = empty ($_REQUEST['skip'])?"0":$_REQUEST['skip'];
            
            $pg_now=$skip;
            if($skip==""){
                $skip=0;
            }
            if($skip!=0){
                $skip=($skip-1)*$limit;
            }
            $no=($skip)+1;
            if($skip==0){
                $pg_now=1;
            }
            $list_=$this->createList($this->showallreport_query($search)." limit ".$skip.",".$limit);
            $row_count=count($this->createList($this->showallreport_query($search)));
            
            $list= '
                
            <table id="tlist"  class="ui-widget ui-widget-content" style="display: table;width:500px;" cellspacing="0" cellpadding="4">    
                <thead id="tlist-head"><tr class="ui-widget-header">
                        <th style="text-align:center;">No</th>
                        
                        <th style="text-align:left;">
                            id
                        </th>
                        <th style="text-align:left;">
                            id
                        </th>
                        
                    </tr>
                </thead>    

                <tbody id="tlist-body">
                    ';
                    
                    foreach ($list_ as $report_query){
                        if($no%2==0){
                            $bg="#b4d8b2";
                        }else{
                            $bg="#FFFFFF";
                        }
                        $list .= '
                                    <tr style="background:'.$bg.';" id="row_1_" class="list_row row_1_" id_negara="1" onclick="jvpilih('.$report_query->getId().');">
                                        <td style="text-align:center; vertical-align:top; width:20px;">'.$no.'</td>
                                        <td style="text-align:left; vertical-align:top; width:30px;" class="">
                                        <input type="hidden" id="idnya'.$report_query->getId().'" value="'.$report_query->getId().'" >
                                        <input type="hidden" id="nilainya'.$report_query->getId().'" value="'.$report_query->getId().'" >
                                        '.$report_query->getId().'</td>
                                        <td style="text-align:center; vertical-align:top; width:50px;" class="">'.$report_query->getId().'</td> 
                                    </tr>
                                ';
                        $no++;
                    }
                    $list .= '
                </tbody>
            </table>';               
            $hasil = array(
                       'list'=>$list,
                       'num'=>$row_count,
                       'jumpage'=>ceil($row_count/$limit),
                       'pagenow'=>$pg_now,
                );
            echo json_encode($hasil);
        }
        
        function showallreport_query($search=""){
            $sql="select * from report_query";
            $where ="";
            if($search!=""){
                
                                 $where .= " where id like '%".$search."%'";
               $where .= "       or  reportname like '%".$search."%'";
               $where .= "       or  header like '%".$search."%'";
               $where .= "       or  query like '%".$search."%'";

                  
                
            }
            $sql .=$where;
            $sql .="
                order by id desc ";
            return $sql;
        } 
        
        function getModalList(){
            $modul          = $_REQUEST['modul'];
            $lebar          = $_REQUEST['lebar'];
            $idnya          = $_REQUEST['idnya'];
            $nilainya       = $_REQUEST['nilainya'];
            require_once './views/modal_list.php';              
        }   
    }
?>
