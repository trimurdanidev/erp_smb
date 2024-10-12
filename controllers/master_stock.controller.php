<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/master_stock.class.php';
require_once './controllers/master_stock.controller.generate.php';
require_once './models/transaction_detail.class.php';
require_once './controllers/transaction_detail.controller.php';
require_once './controllers/transaction_detail.controller.generate.php';
if (!isset($_SESSION)) {
    session_start();
}

class master_stockController extends master_stockControllerGenerate
{
    function showDtlTransIdSingle($id, $kd_prod)
    {
        $sql = "SELECT a .*,b .* FROM transaction_detail a INNER JOIN master_stock b ON a.`kd_product` = b.`kd_product` WHERE a .trans_id = '" . $this->toolsController->replacecharFind($id, $this->dbh) . "' AND a .`kd_product`= '" . $this->toolsController->replacecharFind($kd_prod, $this->dbh) . "'";

        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->master_stock, $row);

        return $this->master_stock;
    }
}
?>