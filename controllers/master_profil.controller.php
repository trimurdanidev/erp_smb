<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/master_profil.class.php';
require_once './controllers/master_profil.controller.generate.php';
require_once './controllers/master_unit.controller.php';
require_once './controllers/master_department.controller.php';
require_once './controllers/tools.controller.php';

if (!isset($_SESSION)) {
    session_start();
}

class master_profilController extends master_profilControllerGenerate
{
    function showDataByUser($user)
    {
        $sql = "SELECT * FROM master_profil WHERE user = '" . $this->toolsController->replacecharFind($user, $this->dbh) . "'";

        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->master_profil, $row);

        return $this->master_profil;
    }
    function showAvatar()
    {
        $this->setIsadmin(true);
        $user = $this->user;
        $master_user_session = unserialize($_SESSION[config::$LOGIN_USER]);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $bowner = $id == 0;
        if ($bowner) {
            $id = $master_user_session->getId();
        }
        $master_profil = $this->showDataByUser($user);

        if ($master_profil->getId() == null || $master_profil->getId() == "") {

            echo "<img src=\"images_small/user_icon.png\" onClick=\"openForm('index.php?model=master_profil&action=showImageMedium&id=" . $master_profil->getId() . "')\" class=\"img-circle\" width=\"75\" height=\"75\" class=\"tooltip\" alt=\"User Image\"> ";
        } else {
            echo "<img src=\"uploads/image_profil/" . $master_profil->getAvatar() . "\" onClick=\"openForm('index.php?model=master_profil&action=showImageMedium&id=" . $master_profil->getId() . "')\" class=\"img-circle\" width=\"75\" height=\"75\" class=\"tooltip\" alt=\"User Image\"> ";
            // echo "<img src=\"images_small/".$master_profil->getAvatar(). "\" onClick=\"openForm('index.php?model=master_profil&action=showImageMedium&id=".$master_profil->getId()."')\" class=\"img-circle\" width=\"75\" height=\"75\" class=\"tooltip\" alt=\"User Image\"> ";
        }
    }
    function showImageMedium()
    {
        $master_user_session = unserialize($_SESSION[config::$LOGIN_USER]);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $bowner = $id == 0;

        if ($bowner) {
            $id = $master_user_session->getId();
        }

        $master_profil = $this->showData($id);
        require_once './views/master_profil/master_profil_image_medium.php';
    }

    function showImageProfil()
    {
        $master_user_session = unserialize($_SESSION[config::$LOGIN_USER]);
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $idProfil = isset($_REQUEST['idProf']) ? $_REQUEST['idProf'] : 0;
        $bowner = $id == 0;

        $master_profil = $this->showData($idProfil);
        require_once './views/master_profil/master_profil_image_medium.php';
    }

    function formUpload()
    {
        $master_user = unserialize($_SESSION[config::$LOGIN_USER]);

        require_once './views/master_profil/master_profil_upload_photo.php';
    }

    function fileUpload()
    {

        $mdl_master_user = new master_user();
        $ctrl_master_user = new master_userController($mdl_master_user, $this->dbh);

        // $formatnow = date('YmdHis');
        $formatnow = date('Y-m-d H:i:s');
        $typeid = "fileupload";
        $target_dir = "uploads/image_profil/";
        $filesource = basename($_FILES[$typeid]["name"]);
        $target_file = $target_dir . $filesource;
        $uploadOk = 1;


        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES[$typeid]["tmp_name"]);
            if ($check != false) {
                echo "File is an image - " . $check["mime"] . ".";
                $master_profil = $this->showDataByUser($this->user);
                $showUser = $ctrl_master_user->showData($this->user);

                // if ($master_profil->getId() == null || $master_profil->getId() == ""):
                //validasi profil jika belum ada
                $this->master_profil->setId($master_profil->getId());
                $this->master_profil->setNik($showUser->getNik());
                $this->master_profil->setUser($this->user);
                $this->master_profil->setAvatar($filesource);
                $this->master_profil->setDepartmentid($showUser->getDepartmentid());
                $this->master_profil->setUnitid($showUser->getUnitid());
                $this->saveData();
                // echo "Data Inserted";
                // else:
                //     $this->master_profil->setId($master_profil->getId());
                //     $this->master_profil->setNik($showUser->getNik());
                //     $this->master_profil->setUser($this->user);
                //     $this->master_profil->setAvatar($formatnow . $filesource);
                //     $this->master_profil->setDepartmentid($showUser->getDepartmentid());
                //     $this->master_profil->setUnitid($showUser->getUnitid());
                //     $this->updateData();
                //     echo "Data Updated";
                // endif;
                echo "Already Uploaded";
                $uploadok = move_uploaded_file($_FILES[$typeid]["tmp_name"], $target_file);
                $this->uploadImagePicture($filesource, $formatnow);
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
    }
    function uploadImage($filesource, $formatnow)
    {
        $toolscontroller = new toolsController();

        $target_dir = "uploads/";
        $target_file = $target_dir . $filesource;
        $target_large = "images_large/" . $formatnow;
        $target_medium = "images_medium/" . $formatnow;
        $target_small = "images_small/" . $formatnow;

        $resizedFile = $target_large . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 1024, 800, true, $resizedFile, false, false, 100);

        $resizedFile = $target_medium . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 800, 600, true, $resizedFile, false, false, 100);

        $resizedFile = $target_small . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 250, 250, true, $resizedFile, false, false, 100);

        unlink($target_file);
    }

    function uploadImagePicture($filesource, $formatnow)
    {
        $toolscontroller = new toolsController();

        $target_dir = "uploads/image_profil/";
        $target_file = $target_dir . $filesource;
        $target_large = "images_large/" . $formatnow;
        $target_medium = "images_medium/" . $formatnow;
        $target_small = "images_small/" . $formatnow;

        $resizedFile = $target_large . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 1024, 800, true, $resizedFile, false, false, 100);

        $resizedFile = $target_medium . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 800, 600, true, $resizedFile, false, false, 100);

        $resizedFile = $target_small . $filesource;
        $toolscontroller->smart_resize_image($target_file, null, 250, 250, true, $resizedFile, false, false, 100);

        unlink($target_file);
    }


    function showProfileUser()
    {
        $master_user_session = unserialize($_SESSION[config::$LOGIN_USER]);

        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $bowner = $id == 0;


        if ($bowner) {
            $master_user = $master_user_session;
        } else {
            $master_user_controller = new master_userController($master_user_session, $this->dbh);
            $master_user = $master_user_controller->showData($id);
        }

        $master_department = new master_department();
        $master_department_controller = new master_departmentController($master_department, $this->dbh);

        $master_unit = new master_unit();
        $master_unit_controller = new master_unitController($master_unit, $this->dbh);

        $master_profil = $this->showData($master_user->getId());


        require_once './views/master_profil/master_profil_view.php';

    }


    function showFormJQuery()
    {
        if ($this->ispublic || $this->isadmin || ($this->isread && $this->isupdate)) {
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
            $master_profil_ = $this->showData($id);

            $master_unit = new master_unit();
            $master_unit_controller = new master_unitController($master_unit, $this->dbh);
            $master_unit_list = $master_unit_controller->showDataUnit();

            $master_department = new master_department();
            $master_department_controller = new master_departmentController($master_department, $this->dbh);
            $master_department_list = $master_department_controller->showDataDepartment();

            require_once './views/master_profil/master_profil_jquery_form.php';
        }

    }
}
?>