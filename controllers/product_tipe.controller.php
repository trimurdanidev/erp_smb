<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/product_tipe.class.php';
require_once './controllers/product_tipe.controller.generate.php';
require_once './controllers/tools.controller.php';

if (!isset($_SESSION)) {
    session_start();
}

class product_tipeController extends product_tipeControllerGenerate
{
    function saveFormPost()
    {
        $this->setIsadmin(true);

        $toolsCtrl = new toolsController();

        $user = $this->user;
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $tipe_name = isset($_POST['tipe_name']) ? $_POST['tipe_name'] : "";
        $tipe_image = isset($_POST['tipe_image']) ? $_POST['tipe_image'] : "";
        $created_by = $user;
        $updated_by = $user;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $filesource = basename($_FILES['tipe_image']['name']);
        $imgTipe = $tipe_image . "_" . date('Ymd') . "_" . date("Hi") . ".jpg";
        $target_dir = "uploads/";
        $target_file = $target_dir . $imgTipe;
        $uploadOk = 1;

        $check = getimagesize($_FILES['tipe_image']['tmp_name']);
        if ($check != false) {
            $uploadok = move_uploaded_file($_FILES['tipe_image']['tmp_name'], $target_file);

            if ($uploadok) {
                $toolsCtrl->uploadImage($imgTipe,'');
            }
        }

        $this->product_tipe->setId($id);
        $this->product_tipe->setTipe_name($tipe_name);
        $this->product_tipe->setTipe_image($tipe_image);
        $this->product_tipe->setCreated_by($created_by);
        $this->product_tipe->setUpdated_by($updated_by);
        $this->product_tipe->setCreated_at($created_at);
        $this->product_tipe->setUpdated_at($updated_at);
        $this->saveData();
    }
}
?>