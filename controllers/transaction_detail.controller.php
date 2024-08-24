<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/transaction_detail.class.php';
require_once './controllers/transaction_detail.controller.generate.php';
require_once './models/transaction.class.php';
require_once './controllers/transaction.controller.generate.php';
require_once './controllers/transaction.controller.php';
require_once './models/transaction_log.class.php';
require_once './controllers/transaction_log.controller.generate.php';
require_once './controllers/transaction_log.controller.php';
if (!isset($_SESSION)) {
    session_start();
}

class transaction_detailController extends transaction_detailControllerGenerate
{
    function showDataDtlArray($transid)
    {
        $sql = "SELECT * FROM transaction_detail WHERE trans_id = '" . $this->toolsController->replacecharFind($transid, $this->dbh) . "'";

        // $row = $this->dbh->query($sql)->fetch();
        // $this->loadData($this->transaction_detail, $row);
        return $this->createList($sql);
    }


    function simpanEditRestock()
    {
        $this->setIsadmin(true);

        $mdl_transaction = new transaction();
        $ctrl_transaction = new transactionController($mdl_transaction, $this->dbh);

        $mdl_transLog = new transaction_log();
        $ctrl_transLog = new transaction_logController($mdl_transLog, $this->dbh);

        $idItem = isset($_POST['idItem']) ? $_POST['idItem'] : "";

        if ($idItem != null || $idItem != "") {

            $getDetailProd = $this->showData($idItem);
            $ambilTransId = $getDetailProd->getTrans_id();
            $getTransactionFromDtl = $ctrl_transaction->showData($ambilTransId);
            // $getTrLogFrDtl = $ctrl_transLog->showDataByTransId($ambilTransId,$getDetailProd->getKd_product());
            $getTrLogFrDtl = $ctrl_transLog->showDataByTransIdSingle($ambilTransId, $getDetailProd->getKd_product());


            $trans_id = $ambilTransId;
            $kd_product = $getDetailProd->getKd_product();
            $qtyEdtRes = isset($_POST['qtyEdtRes']) ? $_POST['qtyEdtRes'] : "";
            // $hasilEdit =  $getTrLogFrDtl->getQty_before() + $qtyEdtRes;
            $harga = $getDetailProd->getHarga();
            $userBy = $this->user;
            $timeup = date('Y-m-d h:i:s');

            // echo $idItem."--".$qtyEdtRes."==".$getTrLogFrDtl->getQty_before()."<br>";

            //save detail
            $this->transaction_detail->setId($idItem);
            $this->transaction_detail->setTrans_id($trans_id);
            $this->transaction_detail->setKd_product($kd_product);
            $this->transaction_detail->setQty($qtyEdtRes);
            $this->transaction_detail->setHarga($harga);
            $this->saveData();

            //lopping transaction detail
            $loopDetail = $this->showDataDtlArray($ambilTransId);
            $totalDetail = 0;
            foreach ($loopDetail as $valLoopDet) {
                $totalDetail += $valLoopDet->getQty();
            }

            //save transaction
            $mdl_transaction->setId($getTransactionFromDtl->getId());
            $mdl_transaction->setNo_trans($getTransactionFromDtl->getNo_trans());
            $mdl_transaction->setTanggal($getTransactionFromDtl->getTanggal());
            $mdl_transaction->setType_trans($getTransactionFromDtl->getType_trans());
            $mdl_transaction->setQtyTotal($totalDetail);
            $mdl_transaction->setQtyRelease($getTransactionFromDtl->getQtyRelease());
            $mdl_transaction->setTrans_total($getTransactionFromDtl->getTrans_total());
            $mdl_transaction->setTrans_status($getTransactionFromDtl->getTrans_status());
            $mdl_transaction->setCreated_by($getTransactionFromDtl->getCreated_by());
            $mdl_transaction->setUpload_trans_log_id($getTransactionFromDtl->getUpload_trans_log_id());
            $mdl_transaction->setCreated_at($getTransactionFromDtl->getCreated_at());
            $mdl_transaction->setUpdated_by($userBy);
            $mdl_transaction->setUpdated_at($timeup);
            $ctrl_transaction->saveData();

            //save transaction log 
            $mdl_transLog->setId($getTrLogFrDtl->getId());
            $mdl_transLog->setTrans_id($getTrLogFrDtl->getTrans_id());
            $mdl_transLog->setTrans_type($getTrLogFrDtl->getTrans_type());
            $mdl_transLog->setQty_before($getTrLogFrDtl->getQty_before());
            $mdl_transLog->setQty_after($getTrLogFrDtl->getQty_before() + $qtyEdtRes);
            $mdl_transLog->setCreated_by($getTrLogFrDtl->getCreated_by());
            $mdl_transLog->setCreated_at($getTrLogFrDtl->getCreated_at());
            $mdl_transLog->setUpdated_by($userBy);
            $mdl_transLog->setUpdated_at($timeup);
            $ctrl_transLog->saveData();
            echo "Berhasil Edit";
            // echo "<script language='javascript' type='text/javascript'>
            // Swal.fire({
            //     title : 'Berhasil',
            //     icon : 'success',
            //     text : 'Quantity Restok Berhasil di Edit',
            // });  
            // </script>";
            // $ctrl_transaction->showAllJQuery_restok();
        } else {
            echo "<script language='javascript' type='text/javascript'>
            Swal.fire({
                title : 'Gagal Edit !',
                icon : 'error',
                text : '...'
            });
            </script>";
        }
    }
}
?>