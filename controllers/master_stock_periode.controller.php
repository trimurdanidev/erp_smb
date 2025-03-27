<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/master_stock_periode.class.php';
require_once './controllers/master_stock_periode.controller.generate.php';
require_once './models/master_stock.class.php';
require_once './controllers/master_stock.controller.generate.php';
require_once './controllers/master_stock.controller.php';
if (!isset($_SESSION)) {
    session_start();
}

class master_stock_periodeController extends master_stock_periodeControllerGenerate
{
    function getStockAwal()
    {
        $this->setIsadmin(true);

        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $getStok = $ctrl_stock->showDataAll();

        foreach ($getStok as $key) {
            $id = isset($_POST['id']) ? $_POST['id'] : "";
            $mop = date('m');
            $yop = date('Y');
            $kd_product = $key->getKd_product();
            $qty_stock = $key->getQty_stock();
            $qty_stock_promo = $key->getQty_stock_promo();
            $created_by = 'auto';
            $updated_by = '';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');


            // $this->master_stock_periode->setId($id);
            $this->master_stock_periode->setMop($mop);
            $this->master_stock_periode->setYop($yop);
            $this->master_stock_periode->setKd_product($kd_product);
            $this->master_stock_periode->setQty_stock($qty_stock);
            $this->master_stock_periode->setQty_stock_promo($qty_stock_promo);
            $this->master_stock_periode->setCreated_by($created_by);
            $this->master_stock_periode->setUpdated_by($updated_by);
            $this->master_stock_periode->setCreated_at($created_at);
            $this->master_stock_periode->setUpdated_at($updated_at);
            $this->saveData();
        }
    }
}
?>