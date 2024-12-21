<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/product_kategori.class.php';
require_once './controllers/product_kategori.controller.generate.php';
require_once './controllers/tools.controller.php';

if (!isset($_SESSION)) {
    session_start();
}

class product_kategoriController extends product_kategoriControllerGenerate
{
    function saveFormPost()
    {
        $this->setIsadmin(true);

        $toolsCtrl = new toolsController();

        $user = $this->user;
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $kategori_name = isset($_POST['kategori_name']) ? $_POST['kategori_name'] : "";
        // $kategori_image = $_REQUEST['kategori_image'];
        $created_by = $user;
        $updated_by = $user;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $filesource = basename($_FILES['kategori_image']['name']);
        $imgKategori = $kategori_name . "_" . date('Ymd') . "_" . date("Hi") . ".jpg";
        $target_dir = "uploads/";
        $target_file = $target_dir . $imgKategori;
        $uploadOk = 1;

        $check = getimagesize($_FILES['kategori_image']['tmp_name']);
        if ($check != false) {
            $uploadok = move_uploaded_file($_FILES['kategori_image']['tmp_name'], $target_file);

            if ($uploadok) {
                $toolsCtrl->uploadImage($imgKategori,'');
            }
        }


        $this->product_kategori->setId($id);
        $this->product_kategori->setKategori_name($kategori_name);
        $this->product_kategori->setKategori_image($imgKategori);
        $this->product_kategori->setCreated_by($created_by);
        $this->product_kategori->setUpdated_by($updated_by);
        $this->product_kategori->setCreated_at($created_at);
        $this->product_kategori->setUpdated_at($updated_at);
        $this->saveData();
    }

    function showDataByName($name){
        $sql = "SELECT * FROM product_kategori WHERE kategori_name = '".$this->toolsController->replacecharFind($name,$this->dbh)."'";

        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->product_kategori, $row);
        
        return $this->product_kategori;
    }

    function checkDataByName($name){
        $sql = "SELECT count(*) FROM product_kategori where kategori_name = '".$name."'";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }
}
?>