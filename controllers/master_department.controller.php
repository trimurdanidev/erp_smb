<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/master_department.class.php';
require_once './controllers/master_department.controller.generate.php';
if (!isset($_SESSION)) {
    session_start();
}

class master_departmentController extends master_departmentControllerGenerate
{
    function showDataDepartment()
    {
        $sql = "select * from master_department ";
        return $this->createList($sql);
    }

    function showAllDept()
    {
        $sql = "SELECT a.`departmentid`,a.`departmentcode`,a.`description`,a.`longitude`,a.`latitude` FROM db_erp_smb .master_department a
	    INNER JOIN db_sperepart_bekasi.`master_department` b 
		ON a.departmentid = b.departmentid;";
        return $this->dbh->query($sql);

    }

    function groupAllDept()
    {
        $sql = "SELECT GROUP_CONCAT(departmentid) allDept FROM db_erp_smb.`master_department` ";
        return $this->dbh->query($sql)->fetch();

    }

}
?>