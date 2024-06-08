<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/master_product.class.php';
    require_once './controllers/master_product.controller.generate.php';
    require_once './models/product_kategori.class.php';
    require_once './controllers/product_kategori.controller.generate.php';
    require_once './controllers/product_kategori.controller.php';
    require_once './models/product_tipe.class.php';
    require_once './controllers/product_tipe.controller.generate.php';
    require_once './controllers/product_tipe.controller.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class master_productController extends master_productControllerGenerate
    {
        function showDataByKode($kode){
            $sql = "SELECT * FROM master_product WHERE kd_product= '".$this->toolsController->replacecharFind($kode,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->master_product, $row);
            
            return $this->master_product;
        }

        function checkDataByKode($kode){
            $sql = "SELECT count(*) FROM master_product where kd_product = '".$kode."'";
            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        }
    }
?>
