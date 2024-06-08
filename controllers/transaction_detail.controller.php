<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/transaction_detail.class.php';
    require_once './controllers/transaction_detail.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class transaction_detailController extends transaction_detailControllerGenerate
    {
        function showDataDtlArray($transid){
            $sql = "SELECT * FROM transaction_detail WHERE trans_id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."'";

            // $row = $this->dbh->query($sql)->fetch();
            // $this->loadData($this->transaction_detail, $row);
            
            return $this->createList($sql);
        }
    }
?>
