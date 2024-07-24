<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/master_pay_transfer.class.php';
    require_once './controllers/master_pay_transfer.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class master_pay_transferController extends master_pay_transferControllerGenerate
    {
        function showData_groupByTransfer(){
            $sql = "SELECT * FROM `master_pay_transfer` GROUP BY `transfer`";
            return $this->createList($sql);            
        }

        function showDataByName($name){
            $sql = "SELECT * FROM `master_pay_transfer` WHERE transfer='".$name."'";
            return $this->createList($sql);            
        }

        function CountsByName($name){
            $sql = "SELECT COUNT(*) FROM `master_pay_transfer` WHERE transfer='".$name."'";
            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        }

        function showDataSingle($trf){
            $sql = "SELECT * FROM master_pay_transfer WHERE transfer = '".$this->toolsController->replacecharFind($trf,$this->dbh)."'";

            $row = $this->dbh->query($sql)->fetch();
            $this->loadData($this->master_pay_transfer, $row);
            
            return $this->master_pay_transfer;
        }
    }
?>
