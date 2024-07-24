<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/transaction_buyer.class.php';
    require_once './controllers/transaction_buyer.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class transaction_buyerController extends transaction_buyerControllerGenerate
    {
        function showDataBytransId($transid){
            $sql = "SELECT * FROM transaction_buyer WHERE trans_id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_buyer, $row);
            
            return $this->transaction_buyer;
        }
    }
?>
