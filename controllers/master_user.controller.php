<?php
require_once './models/master_user.class.php';
require_once './models/master_profil.class.php';
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
        $password = isset($_POST['password']) ? password_hash($_POST['password'], null, $options) : "";
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $avatar = isset($_POST['avatar']) ? $_POST['avatar'] : "";
        $nik = isset($_POST['nik']) ? $_POST['nik'] : "";
        $departmentid = isset($_POST['departmentid']) ? $_POST['departmentid'] : "";
        $unitid = isset($_POST['unitid']) ? $_POST['unitid'] : "";
        $this->master_user->setId($id);
        $this->master_user->setUser($user);
        $this->master_user->setDescription($description);
        $this->master_user->setPassword($password);
        $this->master_user->setUsername($username);
        $this->master_user->setAvatar($avatar);
        $this->master_user->setNik($nik);
        $this->master_user->setDepartmentid($departmentid);
        $this->master_user->setUnitid($unitid);
        $this->saveData();

        $master_user_detail = new master_user_detail();
        $master_user_detail_controller = new master_user_detailController($master_user_detail, $this->dbh);
        $master_user_detail_controller->savePrivileges();
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
                    if (strlen(trim($newpassword)) < 8 || strlen(trim($newpassword)) == 8) {
                        $masteruser->setPassword(password_hash($newpassword, null, $options));
                        $masterusercontroller = new master_userController($masteruser, $this->dbh);
                        $masterusercontroller->saveData();
                        $_SESSION[config::$LOGIN_USER] = serialize($masteruser);

                    } else {
                        echo "Password Must Be More Than 8 ";
                    }
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

        $mdl_rst_password_l               = new reset_password_log();
        $ctrl_rst_password_l              = new reset_password_logControllerGenerate($mdl_rst_password_l,$this->dbh);

        $user = isset($_POST['user']) ? $_POST['user'] : "";
        $getuser = $this->showData($user);
        $options = [
            'cost' => 12,
        ];
        
        if ($getuser != "" || $getuser != null) {

            $newResetPass = $this->randomPassword();
            $hasPass = password_hash($newResetPass,null,$options);

            //updatePass;
            $updatePass = "UPDATE `master_user` SET `password`='".$hasPass."' where id='".$getuser->getId()."'";
            $rs = $this->dbh->query($updatePass);

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
            $token = "Up#YVpLNfcEkqw3PCpBH";
            
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
}
?>