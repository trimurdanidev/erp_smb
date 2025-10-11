<?php
require_once './models/master_user.class.php';
require_once './models/master_profil.class.php';
require_once './models/report_query.class.php';
require_once './models/master_user_detail.class.php';
require_once './controllers/master_user.controller.generate.php';
require_once './controllers/master_profil.controller.php';
require_once './controllers/master_user_detail.controller.php';
require_once './controllers/tools.controller.php';
require_once './controllers/master_unit.controller.php';
require_once './controllers/master_department.controller.php';
require_once './controllers/master_group.controller.php';
require_once './controllers/login.controller.php';
require_once './models/login.class.php';
require_once './models/reset_password_log.class.php';
require_once './controllers/reset_password_log.controller.generate.php';
require_once './controllers/reset_password_log.controller.php';
require_once './controllers/report_query.controller.php';


if (!isset($_SESSION)) {
    session_start();
}

class master_userController extends master_userControllerGenerate
{
    function showDetailJQuery()
    {
        $id = $_GET['id'];
        $master_user_ = $this->showData($id);

        $master_unit = new master_unit();
        $master_unit_controller = new master_unitController($master_unit, $this->dbh);
        $master_unit_list = $master_unit_controller->showData($master_user_->getUnitid());

        $master_department = new master_department();
        $master_department_controller = new master_departmentController($master_department, $this->dbh);
        $master_department_list = $master_department_controller->showData($master_user_->getDepartmentid());

        $master_user_detail = new master_user_detail();
        $master_user_detail_controller = new master_user_detailController($master_user_detail, $this->dbh);
        $master_user_detail_list = $master_user_detail_controller->showPrevileges($master_user_);

        require_once './views/master_user/master_user_jquery_detail.php';
    }

    function showFormJQuery()
    {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
        $master_user_ = $this->showData($id);

        $master_unit = new master_unit();
        $master_unit_controller = new master_unitController($master_unit, $this->dbh);
        $master_unit_list = $master_unit_controller->showDataUnit();

        $master_department = new master_department();
        $master_department_controller = new master_departmentController($master_department, $this->dbh);
        $master_department_list = $master_department_controller->showDataDepartment();

        $master_user_detail = new master_user_detail();
        $master_user_detail_controller = new master_user_detailController($master_user_detail, $this->dbh);
        $master_user_detail_list = $master_user_detail_controller->showPrevileges($master_user_);

        require_once './views/master_user/master_user_jquery_form.php';
    }


    public function saveFormJQuery()
    {
        $options = [
            'cost' => 12,
        ];
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $user = isset($_POST['user']) ? $_POST['user'] : "";
        $description = isset($_POST['description']) ? $_POST['description'] : "";
        $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT, $options) : "";
        $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $avatar = isset($_POST['avatar']) ? $_POST['avatar'] : "";
        $nik = isset($_POST['nik']) ? $_POST['nik'] : "";
        $departmentid = isset($_POST['departmentid']) ? $_POST['departmentid'] : "";
        $unitid = isset($_POST['unitid']) ? $_POST['unitid'] : "";
        // if (password_verify($password))
        //     echo "<pre>";
        //     print_r($password);
        //     echo "</pre";

        $showDataUser = $this->showData($user);

        $this->master_user->setId($id);
        $this->master_user->setUser($user);
        $this->master_user->setDescription($description);
        $this->master_user->setUsername($username);
        $this->master_user->setPassword($id == "" || $id == null ? $password : $showDataUser->getPassword());
        $this->master_user->setPhone($phone);
        $this->master_user->setAvatar($avatar);
        $this->master_user->setNik($nik);
        $this->master_user->setDepartmentid($departmentid);
        $this->master_user->setUnitid($unitid);
        $this->saveData();

        $master_user_detail = new master_user_detail();
        $master_user_detail_controller = new master_user_detailController($master_user_detail, $this->dbh);
        $master_user_detail_controller->savePrivileges();
        // else:
        //     echo "Your Username or password is incorrect!";
        // endif;

    }


    function checkAdmin($user)
    {
        $sql = "SELECT count(*) FROM master_user_detail mud WHERE `user` = '" . $user . "' AND groupcode = 'admin';";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function changePasswordForm()
    {
        require_once './views/master_user/master_user_change_password.php';
    }

    function changePassword()
    {
        $mdl_login = new login();
        $ctrl_login = new loginController($mdl_login, $this->dbh);

        $oldpassword = isset($_POST['oldpassword']) ? $_POST['oldpassword'] : "";
        $newpassword = isset($_POST['newpassword']) ? $_POST['newpassword'] : "";
        $retypepassword = isset($_POST['retypepassword']) ? $_POST['retypepassword'] : "";
        $options = [
            'cost' => 12,
        ];
        $masteruser = isset($_SESSION[config::$LOGIN_USER]) ? unserialize($_SESSION[config::$LOGIN_USER]) : new master_user();

        if (password_verify($oldpassword, $masteruser->getPassword())) {
            // if (md5($oldpassword) == $masteruser->getPassword()) {
            if ($newpassword == $retypepassword) {
                if (trim($newpassword) != "") {
                    // if (strlen(trim($newpassword)) < 8 || strlen(trim($newpassword)) == 8) {
                    $masteruser->setPassword(password_hash($newpassword, PASSWORD_DEFAULT, $options));
                    $masterusercontroller = new master_userController($masteruser, $this->dbh);
                    $masterusercontroller->saveData();

                    echo "Berhasil, Password Berhasil Diupdate";
                    // echo "<script language='javascript' type='text/javascript'>
                    // Swal.fire({
                    //     title : 'Berhasil',
                    //     icon : 'success',
                    //     text : 'Password Berhasil Diupdate'
                    //     });
                    //     </script>";

                    $_SESSION[config::$LOGIN_USER] = serialize($masteruser);
                    // } else {
                    //     echo "Password Must Be More Than 8 ";
                    // }
                } else {
                    echo "Password cannot be blank";
                }
            } else {
                echo "New Password is not the same with retypepassword";
            }
        } else {
            echo "Old password is Not The Same";
        }

    }

    function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function resetPassword()
    {
        $this->setIsadmin(true);

        $mdl_rst_password_l = new reset_password_log();
        $ctrl_rst_password_l = new reset_password_logControllerGenerate($mdl_rst_password_l, $this->dbh);

        $user = isset($_POST['user']) ? $_POST['user'] : "";
        $getuser = $this->showData($user);
        $options = [
            'cost' => 12,
        ];

        if ($getuser != "" || $getuser != null) {

            $newResetPass = $this->randomPassword();
            $hasPass = password_hash($newResetPass, PASSWORD_DEFAULT, $options);

            //updatePass;
            $updatePass = "UPDATE `master_user` SET `password`='" . $hasPass . "' where id='" . $getuser->getId() . "'";
            $rs = $this->dbh->query($updatePass);

            //updatePass ERP;
            $updatePassERP = "UPDATE db_erp_smb.`master_user` SET `password`='" . $hasPass . "' where user=' $user'";
            $rsERP = $this->dbh->query($updatePassERP);

            //log Password
            $id = $ctrl_rst_password_l->getLastId();
            // $user_id = isset($_POST['user_id'])?$_POST['user_id'] : "";
            // $date = isset($_POST['date'])?$_POST['date'] : "";
            // $time = isset($_POST['time'])?$_POST['time'] : "";
            $mdl_rst_password_l->setId($id);
            $mdl_rst_password_l->setUser_id($getuser->getId());
            $mdl_rst_password_l->setDate(date("Y-m-d"));
            $mdl_rst_password_l->setTime(date("h:i:s"));
            $ctrl_rst_password_l->saveData();

            $curl = curl_init();
            $token = "cL6UCwkj9grpaYwWpsHn";

            $phone = $getuser->getPhone();
            $pesen = "*ERP SMB*

Berhasil Reset Password. Berikut adalah Password Anda : *$newResetPass* ";
            // echo $phone;

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $phone,
                        'message' => $pesen,
                    ),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: $token"
                    ),
                )
            );

            $response = curl_exec($curl);
            curl_close($curl);
            echo $response;

        } else {
            echo "<script>alert('User Tidak Ditemukan !');</script>";
            // echo $response['reason'];
        }
    }

    function showDataAllERP()
    {

        $sql = "SELECT * FROM db_erp_smb.master_user WHERE departmentid !=0";

        return $this->createList($sql);

    }

    function monitoringAbsen()
    {
        $this->setIsadmin(true);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);

        if ($this->ispublic || $this->isadmin || $this->isread) {
            $tanggalMulai = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
            $tanggalAkir = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
            $karyawan = isset($_REQUEST['kry']) ? $_REQUEST['kry'] : "";

            $last = $this->countDataAll();
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
            $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
            $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

            $querySql = "CALL `sp_monitoring_erp_absen`('" . $tanggalMulai . "','" . $tanggalAkir . "','" . $karyawan . "');";

            // echo $querySql; 

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

            // $master_user_list = $this->createList($querySql);
            $master_user_list = $ctrl_report_query->generatetableview($querySql);

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
            require_once './views/master_user/monitoring_absen_jquery_list.php';
        } else {
            echo "You cannot access this module";
        }

    }

    function parseDetailAbsen($tanggalMulai, $tanggalAkir, $karyawan)
    {
        $sql = "CALL `sp_monitoring_erp_absen`('" . $tanggalMulai . "','" . $tanggalAkir . "','" . $karyawan . "');";

        $row = $this->dbh->query($sql);

        return $row;
    }

    function showExportTable()
    {
        $this->setIsadmin(true);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);

        $tanggalMulai = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
        $tanggalAkir = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
        $karyawan = isset($_REQUEST['kry']) ? $_REQUEST['kry'] : "";

        $report_query = $this->parseDetailAbsen($tanggalMulai, $tanggalAkir, $karyawan);

        //echo $query;
        header("Content-Type:application/csv");
        header('Content-Disposition: attachment; filename="sample_export_data.csv"');

        echo $ctrl_report_query->exportcsv($report_query, 1, 0);

    }

    function groupAllKary()
    {
        $sql = "SELECT GROUP_CONCAT(CONCAT(\"'\", `user`, \"'\")) AS allKary FROM db_erp_smb.`master_user` WHERE deleted_at IS NULL";
        $row = $this->dbh->query($sql)->fetch();

        return $row;
    }

    function dataAllKaryawan()
    {
        $this->setIsadmin(true);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);

        $master_department_mdl = new master_department();
        $master_department_ctrl = new master_departmentController($master_department_mdl, $this->dbh);

        $getGroupDept = $master_department_ctrl->groupAllDept();
        $getGroupKary = $this->groupAllKary();


        $setGroupDep = $getGroupDept['allDept'];
        $setGroupKar = $getGroupKary['allKary'];

        if ($this->ispublic || $this->isadmin || $this->isread) {
            $dept = isset($_REQUEST["dept"]) ? $_REQUEST["dept"] : "";
            $karyawan = isset($_REQUEST['kry']) ? $_REQUEST['kry'] : "";

            $last = $this->countDataAll();
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
            $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
            $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

            if ($dept != "" || $dept != null && $karyawan != null || $karyawan != ""):
                $query = "SELECT  a .`id`,
  a .`user`,
  a .`description`,
  a .`password`,
  a .`username`,
  b .`phone`,
  a .`nik`,
  a .`departmentid`,
  a .`unitid`,
  b .`is_mobile`,
  a .`entrytime`,
  a .`entryuser`,
  a .`entryip`,
  a .`updatetime`,
  a .`updateuser`,
  a .`updateip`,
  b .`avatar`,
  b .`created_by`,
  b .`created_at`,
  b .`updated_at`,
  b .`deleted_at` ,
  c.`departmentcode`,
  c.`description`,c.`latitude`,c.`longitude`,'' `address`,'' `no_ktp` FROM db_sperepart_bekasi .`master_user` a
		INNER JOIN db_erp_smb.`master_user` b ON a.`user` = b.`user`
		INNER JOIN db_erp_smb.`master_department` c ON c.`departmentid` = a.`departmentid` 
		WHERE a.departmentid IN ($dept) and a.user in ($setGroupKar) AND b.deleted_at IS NULL;";
                //             elseif ($karyawan == null || $karyawan == ""):
//                 echo "kondisi 2";
//                 $query = "SELECT  a .`id`,
//   a .`user`,
//   a .`description`,
//   a .`password`,
//   a .`username`,
//   a .`phone`,
//   a .`nik`,
//   a .`departmentid`,
//   a .`unitid`,
//   b .`is_mobile`,
//   a .`entrytime`,
//   a .`entryuser`,
//   a .`entryip`,
//   a .`updatetime`,
//   a .`updateuser`,
//   a .`updateip`,
//   a .`avatar`,
//   b .`created_by`,
//   b .`created_at`,
//   b .`updated_at`,
//   b .`deleted_at` ,
//   c.`departmentcode`,
//   c.`description` FROM db_sperepart_bekasi .`master_user` a
// 		INNER JOIN db_erp_smb.`master_user` b ON a.`user` = b.`user`
// 		INNER JOIN db_erp_smb.`master_department` c ON c.`departmentid` = a.`departmentid` 
// 		WHERE a.departmentid IN ($dept) and a.user in ('$karyawan') AND  b.deleted_at IS NULL;";
            else:
                $query = "SELECT  a .`id`,
  a .`user`,
  a .`description`,
  a .`password`,
  a .`username`,
  b .`phone`,
  a .`nik`,
  a .`departmentid`,
  a .`unitid`,
  b .`is_mobile`,
  a .`entrytime`,
  a .`entryuser`,
  a .`entryip`,
  a .`updatetime`,
  a .`updateuser`,
  a .`updateip`,
  b .`avatar`,
  b .`created_by`,
  b .`created_at`,
  b .`updated_at`,
  b .`deleted_at` ,
  c.`departmentcode`,
  c.`description`,c.`latitude`,c.`longitude`,'' `address`,'' `no_ktp` FROM db_sperepart_bekasi .`master_user` a
		INNER JOIN db_erp_smb.`master_user` b ON a.`user` = b.`user`
		INNER JOIN db_erp_smb.`master_department` c ON c.`departmentid` = a.`departmentid` 
		WHERE  b.deleted_at IS NULL;";
            endif;

            // echo $query;

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

            $master_user_list = $this->dbh->query($query);

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
            require_once './views/master_user/site_karyawan.php';
        } else {
            echo "You cannot access this module";
        }
    }

    function changeMobile()
    {
        $this->setIsadmin(true);
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $skip = isset($_REQUEST['skip']) ? $_REQUEST['skip'] : "";
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : "";
        $stat = isset($_REQUEST['stats']) ? $_REQUEST['stats'] : "";

        if ($stat == 1):
            $sql = "UPDATE db_erp_smb.master_user a
            INNER JOIN db_sperepart_bekasi.master_user b 
            ON a.`user` = b.`user` SET is_mobile=0 WHERE b.id = '" . $id . "'";
        else:
            $sql = "UPDATE db_erp_smb.master_user a
            INNER JOIN db_sperepart_bekasi.master_user b 
            ON a.`user` = b.`user` SET is_mobile=1 WHERE b.id = '" . $id . "'";
        endif;

        $execute = $this->dbh->query($sql);
        $this->dataAllKaryawan();
    }
}
?>