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
            // $sql = "SELECT a .*.b .* FROM transaction_log a inner join transaction_detail b on a.trans_id = b.trans_id WHERE a .id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."' AND b.`kd_product`= '".$this->toolsController->replacecharFind($kd_prod,$this->dbh)."'";
            $sql = "SELECT * FROM transaction_detail b INNER JOIN `transaction_log` a ON a.`trans_id` = b.`trans_id` AND a.`kd_product`=b.`kd_product` WHERE b.`trans_id`='".$this->toolsController->replacecharFind($transid,$this->dbh)."' AND b.`kd_product`='".$this->toolsController->replacecharFind($kd_prod,$this->dbh)."'";

            // echo $sql."<br>";
            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_log, $row);
            
            return $this->transaction_log;
        }
        
        function sDataByTransId($transid){
            $sql = "SELECT a .* FROM transaction_log a inner join transaction_detail b on a.trans_id = b.trans_id WHERE a .id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_log, $row);
            
            return $this->transaction_log;
        }

        function showDataByTransIdSingle($transid,$kd_prod){
            $sql = "SELECT * FROM transaction_log WHERE trans_id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."'  AND `kd_product`= '".$this->toolsController->replacecharFind($kd_prod,$this->dbh)."'";
            
            // echo $sql.'<br>';
            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_log, $row);
            
            return $this->transaction_log;
        }

        function showDataDtlArray($transid)
    {
        $sql = "SELECT * FROM transaction_log WHERE trans_id = '" . $this->toolsController->replacecharFind($transid, $this->dbh) . "'";

        // $row = $this->dbh->query($sql)->fetch();
        // $this->loadData($this->transaction_detail, $row);
        return $this->createList($sql);
    }
    }
?>
