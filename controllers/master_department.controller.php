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
    function checkDataByDeptCode($id, $dname)
    {
        $sql = "SELECT count(*) FROM db_erp_smb.master_department where description = '" . $dname . "'";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function getMaxDeptCode()
    {
        $sql = "SELECT departmentid,departmentcode 
        FROM db_erp_smb.master_department 
        ORDER BY departmentcode DESC 
        LIMIT 1";

        $row = $this->dbh->query($sql)->fetch();

        return $row;
    }

    function saveFormPost()
    {
        $this->setIsadmin(true);

        $user = $this->user;
        $departmentid = isset($_POST['departmentid']) ? $_POST['departmentid'] : "";
        $departmentcode = isset($_POST['departmentcode']) ? $_POST['departmentcode'] : "";
        $description = isset($_POST['description']) ? $_POST['description'] : "";
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : "";
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : "";
        $datetime = date("Y-m-d H:i:s");

        $getMaxsCodeDepart = $this->getMaxDeptCode();
        $checkDeptCoce = $this->checkDataByDeptCode($departmentid, $description);

        $prefix = substr($getMaxsCodeDepart['departmentcode'], 0, 3);
        $number = substr($getMaxsCodeDepart['departmentcode'], 3);
        $setlastCd = $number + 1;
        $setMaxNewCd = $prefix . str_pad($setlastCd, 3, '0', STR_PAD_LEFT);

        if ($departmentid == "" || $departmentid == null):
            if ($checkDeptCoce > 0) {
                echo "Gagal Menambahkan Data !\n Data Kode Department / Bagian Sudah Ada";
                return false;
            } else {

                //DB : sparepart_motor_bekasi
                $this->master_department->setDepartmentid($departmentid);
                // $this->master_department->setdepartmentcode($departmentcode); // cuma di DB ERP SMB
                $this->master_department->setDescription($description);
                $this->saveData();

                $getLastIdDep = $this->getLastId();
                //DB : ERP SMB
                $queryExe = "INSERT INTO db_erp_smb.master_department VALUES ('$getLastIdDep', '$setMaxNewCd', '$description', '$latitude','$longitude','$user', '$datetime',NULL,NULL);";
                $this->dbh->query($queryExe);

                echo "Berhasil Menambahkan Data.";
            }
            // echo "new" . "<br>";
        else:
            //DB : sparepart_motor_bekasi
            $this->master_department->setDepartmentid($departmentid);
            $this->master_department->setDescription($description);
            $this->saveData();

            //DB : ERP SMB
            $queryExeUp = "UPDATE db_erp_smb.master_department SET description='$description',latitude='$latitude',longitude='$longitude',updated_at='$datetime' WHERE departmentid='$departmentid';";
            $this->dbh->query($queryExeUp);
            // echo "edit" . "<br>";

            echo "Berhasil Merubah Data.";
        endif;
    }
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

    function showAllDeptERP()
    {
        $this->setIsadmin(true);
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        $sql = "SELECT DISTINCT b .* FROM master_department a 	
	LEFT JOIN db_erp_smb.`master_department` b ON a.`departmentid` = a.`departmentid`
	ORDER BY a .departmentid ASC;";

        // echo $sql;

        $last = $this->countDataAll();

        $sisa = intval($last % $limit);

        if ($sisa > 0) {
            $last = $last - $sisa;
        } else if ($last - $limit < 0) {
            $last = 0;
        } else {
            $last = $last - $limit;
        }

        $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

        if ($skip + $limit > $last) {
            $next = $last;
        } else if ($skip == 0) {
            $next = $skip + $limit + 1;
        } else {
            $next = $skip + $limit;
        }
        $first = 0;

        $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
        $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

        $master_department_list = $this->dbh->query($sql)->fetchAll();
        $isadmin = $this->isadmin;
        $ispublic = $this->ispublic;
        $isread = $this->isread;
        $isconfirm = $this->isconfirm;
        $isentry = $this->isentry;
        $isupdate = $this->isupdate;
        $isdelete = $this->isdelete;
        $isprint = $this->isprint;
        $isexport = $this->isexport;
        $isimport = $this->isimport;
        require_once './views/master_department/master_department_jquery_list.php';
    }

}
?>