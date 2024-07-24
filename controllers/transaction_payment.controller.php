<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/transaction_payment.class.php';
    require_once './controllers/transaction_payment.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class transaction_paymentController extends transaction_paymentControllerGenerate
    {
        function showDataBytransId($transid){
            $sql = "SELECT * FROM transaction_payment WHERE trans_id = '".$this->toolsController->replacecharFind($transid,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->transaction_payment, $row);
            
            return $this->transaction_payment;
        }
    }
?>
