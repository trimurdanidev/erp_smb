<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/transaction_log.class.php';
    require_once './controllers/transaction_log.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class transaction_logController extends transaction_logControllerGenerate
    {
        function showDataByTransId($transid,$kd_prod){
            $sql = "SELECT a .* FROM transaction_log a inner join transaction_detail b on a.trans_id = b.trans_id WHERE a .id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."' AND b.`kd_product`= '".$this->toolsController->replacecharFind($kd_prod,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_log, $row);
            
            return $this->transaction_log;
        }
    }
?>
