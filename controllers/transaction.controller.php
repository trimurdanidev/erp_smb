<?php
use Shuchkin\SimpleXLS;

require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/transaction.class.php';
require_once './controllers/transaction.controller.generate.php';
require_once './models/transaction_detail.class.php';
require_once './controllers/transaction_detail.controller.php';
require_once './controllers/transaction_detail.controller.generate.php';
require_once './models/transaction_log.class.php';
require_once './controllers/transaction_log.controller.php';
require_once './controllers/transaction_log.controller.generate.php';
require_once './models/transaction_type.class.php';
require_once './controllers/transaction_type.controller.php';
require_once './controllers/transaction_type.controller.generate.php';
require_once './controllers/transaction_log.controller.generate.php';
require_once './models/upload_trans_log.class.php';
require_once './controllers/upload_trans_log.controller.php';
require_once './controllers/upload_trans_log.controller.generate.php';
require_once './models/master_stock.class.php';
require_once './controllers/master_stock.controller.php';
require_once './controllers/master_stock.controller.generate.php';
require_once './models/master_product.class.php';
require_once './controllers/master_product.controller.php';
require_once './controllers/excel_reader2.php';
require_once './models/master_pay_transfer.class.php';
require_once './controllers/master_pay_transfer.controller.php';
require_once './controllers/master_pay_transfer.controller.generate.php';
require_once './models/transaction_payment.class.php';
require_once './controllers/transaction_payment.controller.php';
require_once './controllers/transaction_payment.controller.generate.php';
require_once './controllers/master_pay_transfer.controller.generate.php';
require_once './models/transaction_buyer.class.php';
require_once './controllers/transaction_buyer.controller.php';
require_once './controllers/transaction_buyer.controller.generate.php';
require_once './models/initial_company.class.php';
require_once './controllers/initial_company.controller.generate.php';
require_once './controllers/initial_company.controller.php';

if (!isset($_SESSION)) {
    session_start();
}

class transactionController extends transactionControllerGenerate
{
    function showDataNomorTerakhir($type)
    {
        $this->setIsadmin(true);
        $sql = "select COUNT(*) id from `transaction` WHERE type_trans='" . $type . "' order by id desc";
        $row = $this->dbh->query($sql)->fetch();
        // $this->loadData($this->transaction, $row);

        return $row;


    }

    function countNomorTerakhir($type)
    {
        $this->setIsadmin(true);
        $sql = "select count(*) from `transaction` WHERE type_trans='" . $type . "' order by id desc limit 1";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];

    }

    function showDataSummarySO($type)
    {
        $this->setIsadmin(true);
        $sql = "select sum() from `transaction` WHERE id='" . $this->getLastId() . "' order by id desc limit 1";
        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->transaction, $row);

        return $this->transaction;

    }

    function showAllJQuery_so()
    {
        $mdl_transcation_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_upload_tr_log = new upload_trans_log();
        $ctrl_upload_tr_log = new upload_trans_logController($mdl_upload_tr_log, $this->dbh);


        $last = $this->countDataAll();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        $sisa = intval($last % $limit);

        if ($sisa > 0) {
            $last = $last - $sisa;
        } else if ($last - $limit < 0) {
            $last = 0;
        } else {
            $last = $last - $limit;
        }

        $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

        if ($skip + $limit > $last) {
            $next = $last;
        } else if ($skip == 0) {
            $next = $skip + $limit + 1;
        } else {
            $next = $skip + $limit;
        }
        $first = 0;

        $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
        $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

        $transaction_list = $ctrl_upload_tr_log->showDataAllLimit();
        $isadmin = $this->isadmin;
        $ispublic = $this->ispublic;
        $isread = $this->isread;
        $isconfirm = $this->isconfirm;
        $isentry = $this->isentry;
        $isupdate = $this->isupdate;
        $isdelete = $this->isdelete;
        $isprint = $this->isprint;
        $isexport = $this->isexport;
        $isimport = $this->isimport;

        require_once './views/transaction/transaction_jquery_so.php';
    }

    function showAllJQuery_so_by_data()
    {
        $mdl_transcation_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_upload_tr_log = new upload_trans_log();
        $ctrl_upload_tr_log = new upload_trans_logController($mdl_upload_tr_log, $this->dbh);

        $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
        $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
        $stsId = isset($_REQUEST["sts"]) ? $_REQUEST["sts"] : "";

        $sql = "SELECT a.* FROM `upload_trans_log` a
        LEFT JOIN transaction_detail b ON a.id = b.`trans_id`
        INNER JOIN transaction_log c ON c.`trans_id` = a.`id`
        INNER JOIN `transaction` d ON d.`id` = a.`id`
        WHERE d.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.`trans_status` IN ($stsId)
        GROUP BY a.`id` 
        ORDER BY a.`created_at` DESC";

        $last = $this->countDataAll();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        $sisa = intval($last % $limit);

        if ($sisa > 0) {
            $last = $last - $sisa;
        } else if ($last - $limit < 0) {
            $last = 0;
        } else {
            $last = $last - $limit;
        }

        $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

        if ($skip + $limit > $last) {
            $next = $last;
        } else if ($skip == 0) {
            $next = $skip + $limit + 1;
        } else {
            $next = $skip + $limit;
        }
        $first = 0;

        $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
        $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

        $transaction_list = $ctrl_upload_tr_log->createList($sql);
        $isadmin = $this->isadmin;
        $ispublic = $this->ispublic;
        $isread = $this->isread;
        $isconfirm = $this->isconfirm;
        $isentry = $this->isentry;
        $isupdate = $this->isupdate;
        $isdelete = $this->isdelete;
        $isprint = $this->isprint;
        $isexport = $this->isexport;
        $isimport = $this->isimport;

        require_once './views/transaction/transaction_jquery_so.php';
    }

    function saveUploadSo()
    {
        $this->setIsadmin(true);
        require_once './Excel/SimpleXLS.php';
        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_upload_tr_log = new upload_trans_log();
        $ctrl_upload_tr_log = new upload_trans_logController($mdl_upload_tr_log, $this->dbh);

        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockControllerGenerate($mdl_stock, $this->dbh);

        $mdl_product = new master_product();
        $ctrl_product = new master_productController($mdl_product, $this->dbh);

        $user = $this->user;
        $numbering = $this->showDataNomorTerakhir(3);

        if (count($numbering) > 0) {
            $nomorakhir = $numbering['id'] + 1;
        }

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $generateNotrans = 'SO' . date('dmy') . '-' . sprintf('%06s', $nomorakhir);
        $no_trans = isset($generateNotrans) ? $generateNotrans : "";
        $dateTime = date('Y-m-d h:i:s');
        $getFile_name = $_FILES['stock_so']['name'];
        $getThe_file = $_FILES['stock_so']['tmp_name'];
        $targetFile = "uploads/excel_upload/" . $getFile_name;
        $uploadok = move_uploaded_file($getThe_file, $targetFile);

        if ($dataSheet = SimpleXLS::parse("$targetFile")) {
            // print_r( $xlsx->rows() );
            $total = 0;

            //transaction
            $this->transaction->setId($id);
            $this->transaction->setNo_trans($no_trans);
            $this->transaction->setTanggal($dateTime);
            $this->transaction->setType_trans(3);
            $this->transaction->setQtyTotal($total);
            $this->transaction->setQtyRelease(0);
            $this->transaction->setTrans_total(0);
            $this->transaction->setTrans_status(0);
            $this->transaction->setCreated_by($user);
            $this->transaction->setCreated_at($dateTime);
            $this->transaction->setUpdated_by('');
            $this->transaction->setUpdated_at('');
            $this->saveData();

            //upload_trans_log
            // $mdl_upload_tr_log->setId($id);
            $mdl_upload_tr_log->setTrans_type(3);
            $mdl_upload_tr_log->setTrans_descrip('IN STOCK OPNAME TGL.' . date('d-m-Y') . " OLEH " . $user);
            $mdl_upload_tr_log->setJumlah_data(0);
            $mdl_upload_tr_log->setCreated_by($user);
            $mdl_upload_tr_log->setCreated_at($dateTime);
            $mdl_upload_tr_log->setUpdated_by('');
            $mdl_upload_tr_log->setUpdated_at('');
            $ctrl_upload_tr_log->saveData();

            foreach ($dataSheet->rows() as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;
                    continue;
                }
                $jml_data = $k++;
                $kd_prod = $r[0];
                $nm_prod = $r[1];
                $qty = $r[2];
                $ket = $r[3];
                // echo print_r($kd_prod."-".$nm_prod."-".$qty."-".$ket."<br>");

                $count_from_product = $ctrl_product->checkDataByKode($kd_prod);
                $count_from_stock = $ctrl_stock->checkData($kd_prod);

                if ($count_from_product == 0 && $count_from_stock == 0):
                    echo "Gagal Upload!!\nKode Product $kd_prod - $nm_prod Belum Ada di Master Produk & Master Stok";
                    //delete trans & upload log
                    $this->deleteData($this->getLastId());
                    $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                    // echo "konsalah0";
                    return false;
                elseif ($count_from_product == 0):
                    echo "Gagal Upload !!\nKode Product $kd_prod - $nm_prod Belum Ada di Master Produk";
                    //delete trans & upload log
                    $this->deleteData($this->getLastId());
                    $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                    // echo "konsalah1";
                    return false;
                elseif ($count_from_stock == 0):
                    // echo "Gagal Upload !\nKode Product $kd_prod - $nm_prod Belum Ada di Master Stock";
                    //delete trans & upload log
                    $this->deleteData($this->getLastId());
                    $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                    // echo "Kon salah 2";
                    return false;
                else:
                    // echo "kon bener";
                    //get_stock_produk
                    $getStock = $ctrl_stock->showData($kd_prod);
                    $getHarga = $ctrl_product->showDataByKode($kd_prod);
                    $total += $qty;

                    //transaction_detail
                    // $mdl_transaction_dtl->setId($id);
                    $mdl_transaction_dtl->setTrans_id($this->getLastId());
                    $mdl_transaction_dtl->setKd_product($kd_prod);
                    $mdl_transaction_dtl->setQty($qty);
                    $mdl_transaction_dtl->setHarga($getHarga->getHrg_jual());
                    $ctrl_transaction_dtl->saveData();

                    //transaction_log
                    // $mdl_transaction_log->setId($id);
                    $mdl_transaction_log->setTrans_id($this->getLastId());
                    $mdl_transaction_log->setTrans_type(3);
                    $mdl_transaction_log->setQty_before($getStock->getQty_stock());
                    $mdl_transaction_log->setQty_after($qty);
                    $mdl_transaction_log->setCreated_by($user);
                    $mdl_transaction_log->setCreated_at($dateTime);
                    $mdl_transaction_log->setUpdated_by('');
                    $mdl_transaction_log->setUpdated_at('');
                    $ctrl_transaction_log->saveData();

                    //master_stock
                    // $mdl_stock->setKd_product($kd_prod);
                    // $mdl_stock->setQty_stock($getStock->getQty_stock() + $qty);
                    // $mdl_stock->setQty_stock_promo($getStock->getQty_stock_promo());
                    // $mdl_stock->setCreated_by($getStock->getCreated_by());
                    // $mdl_stock->setUpdated_by($user);
                    // $mdl_stock->setCreated_at($getStock->getCreated_at());
                    // $mdl_stock->setUpdated_at($dateTime);
                    // $ctrl_stock->saveData();

                endif;
            }

            $showUpdadte_upl = $ctrl_upload_tr_log->showData($ctrl_upload_tr_log->getLastId());
            $showUpdate_trs = $this->showData($this->getLastId());

            $mdl_upload_tr_log->setId($showUpdadte_upl->getId());
            $mdl_upload_tr_log->setTrans_type($showUpdadte_upl->getTrans_type());
            $mdl_upload_tr_log->setTrans_descrip($showUpdadte_upl->getTrans_descrip());
            $mdl_upload_tr_log->setJumlah_data($jml_data);
            $mdl_upload_tr_log->setCreated_by($showUpdadte_upl->getCreated_by());
            $mdl_upload_tr_log->setCreated_at($showUpdadte_upl->getCreated_at());
            $mdl_upload_tr_log->setUpdated_by($showUpdadte_upl->getUpdated_by());
            $mdl_upload_tr_log->setUpdated_at($showUpdadte_upl->getUpdated_at());
            $ctrl_upload_tr_log->saveData();

            $this->transaction->setId($showUpdate_trs->getId());
            $this->transaction->setNo_trans($showUpdate_trs->getNo_trans());
            $this->transaction->setTanggal($showUpdate_trs->getTanggal());
            $this->transaction->setType_trans($showUpdate_trs->getType_trans());
            $this->transaction->setQtyTotal($total);
            $this->transaction->setTrans_total(0);
            $this->transaction->setQtyRelease($showUpdate_trs->getQtyRelease());
            $this->transaction->setCreated_by($showUpdate_trs->getCreated_by());
            $this->transaction->setCreated_at($showUpdate_trs->getCreated_at());
            $this->transaction->setUpdated_by($showUpdate_trs->getUpdated_by());
            $this->transaction->setUpdated_at($showUpdate_trs->getUpdated_at());
            $this->saveData();
            echo "Uploas Success";
        } else {
            echo SimpleXLS::parseError();
        }

    }

    function showDataArray($id)
    {
        $sql = "SELECT * FROM `transaction` WHERE id = '" . $this->toolsController->replacecharFind($id, $this->dbh) . "'";
        return $this->createList($sql);
    }

    function showDetailJQuery_so()
    {
        $this->setIsadmin(true);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_trans_dettail = new transaction_detailController($mdl_transaction_dtl, $this->dbh);


        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $getTrans = $this->showData($id);
        $showDetail = $ctrl_trans_dettail->showDataDtlArray($id);

        require_once './views/transaction/transaction_jquery_so_detail.php';
    }

    function confirmSO()
    {
        $this->setIsadmin(true);
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $user = $this->user;
        $total = 0;

        $getTransaction = $this->showData($id);

        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockControllerGenerate($mdl_stock, $this->dbh);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $showDtlTrans = $ctrl_transaction_dtl->showDataDtlArray($id);


        if ($id != "") {
            foreach ($showDtlTrans as $valDetail) {
                $getStok = $ctrl_stock->showData($valDetail->getKd_product());
                $total += $valDetail->getQty();
                //master_stock
                $mdl_stock->setKd_product($valDetail->getKd_product());
                $mdl_stock->setQty_stock( /*$getStok->getQty_stock() + */$valDetail->getQty());
                $mdl_stock->setQty_stock_promo($getStok->getQty_stock_promo());
                $mdl_stock->setCreated_by($getStok->getCreated_by());
                $mdl_stock->setUpdated_by($user);
                $mdl_stock->setCreated_at($getStok->getCreated_at());
                $mdl_stock->setUpdated_at(date('Y-m-d h:i:s'));
                $ctrl_stock->saveData();
            }
            //transaction
            $this->transaction->setId($id);
            $this->transaction->setNo_trans($getTransaction->getNo_trans());
            $this->transaction->setTanggal($getTransaction->getTanggal());
            $this->transaction->setType_trans($getTransaction->getType_trans());
            $this->transaction->setQtyTotal($getTransaction->getQtyTotal());
            $this->transaction->setQtyRelease($total);
            $this->transaction->setTrans_total(0);
            $this->transaction->setTrans_status(1);
            $this->transaction->setCreated_by($getTransaction->getCreated_by());
            $this->transaction->setCreated_at($getTransaction->getCreated_at());
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at(date('Y-m-d h:i:s'));
            $this->updateData();
            // echo "<script>alert('Stock Opname Berhasil Terilis');</script>";
            $this->showAllJQuery_so();
        } else {
            echo "<script language='javascript' type='text/javascript'>
            Swal.fire({
                title : 'Gagal Rilis !',
                icon : 'error',
                text : 'Silahkan Cek Koneksi Internet Anda'
            });
            </script>";
        }

    }
    function showFormJQuery_transOff()
    {
        $this->setIsadmin(true);
        require_once './views/transaction/transaction_jquery_form_trans_off.php';
    }
    function showAllJQuery_trans_off()
    {
        $mdl_transcation_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_trans_payment = new transaction_payment();
        $ctrl_trans_payment = new transaction_paymentController($mdl_trans_payment, $this->dbh);

        $mdl_trans_buyer = new transaction_buyer();
        $ctrl_trans_buyer = new transaction_buyerController($mdl_trans_buyer, $this->dbh);


        $sql = "SELECT * FROM `transaction` WHERE type_trans=2 order by id desc";

        $last = $this->countDataAll();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        $sisa = intval($last % $limit);

        if ($sisa > 0) {
            $last = $last - $sisa;
        } else if ($last - $limit < 0) {
            $last = 0;
        } else {
            $last = $last - $limit;
        }

        $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

        if ($skip + $limit > $last) {
            $next = $last;
        } else if ($skip == 0) {
            $next = $skip + $limit + 1;
        } else {
            $next = $skip + $limit;
        }
        $first = 0;

        $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
        $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

        $transaction_list = $this->createList($sql);
        $isadmin = $this->isadmin;
        $ispublic = $this->ispublic;
        $isread = $this->isread;
        $isconfirm = $this->isconfirm;
        $isentry = $this->isentry;
        $isupdate = $this->isupdate;
        $isdelete = $this->isdelete;
        $isprint = $this->isprint;
        $isexport = $this->isexport;
        $isimport = $this->isimport;

        require_once './views/transaction/transaction_jquery_trans_off.php';
    }

    function showAllJQuery_trans_off_by_search()
    {
        $mdl_transcation_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_trans_payment = new transaction_payment();
        $ctrl_trans_payment = new transaction_paymentController($mdl_trans_payment, $this->dbh);

        $mdl_trans_buyer = new transaction_buyer();
        $ctrl_trans_buyer = new transaction_buyerController($mdl_trans_buyer, $this->dbh);

        $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
        $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
        $pyment = isset($_REQUEST["payment"]) ? $_REQUEST["payment"] : "";

        if ($pyment == null || $pyment == "") {
            $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
            INNER JOIN transaction_payment d ON d.`trans_id` = a.id
            INNER JOIN transaction_buyer f ON f.trans_id = a.id 
            WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' 
            ORDER BY a.`created_at` DESC";
        } else {
            $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
            INNER JOIN transaction_payment d ON d.`trans_id` = a.id
            INNER JOIN transaction_buyer f ON f.trans_id = a.id
            WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.payment='$pyment' 
            ORDER BY a.`created_at` DESC";
        }

        $last = $this->countDataAll();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        $sisa = intval($last % $limit);

        if ($sisa > 0) {
            $last = $last - $sisa;
        } else if ($last - $limit < 0) {
            $last = 0;
        } else {
            $last = $last - $limit;
        }

        $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

        if ($skip + $limit > $last) {
            $next = $last;
        } else if ($skip == 0) {
            $next = $skip + $limit + 1;
        } else {
            $next = $skip + $limit;
        }
        $first = 0;

        $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
        $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

        $transaction_list = $this->createList($sql);
        $isadmin = $this->isadmin;
        $ispublic = $this->ispublic;
        $isread = $this->isread;
        $isconfirm = $this->isconfirm;
        $isentry = $this->isentry;
        $isupdate = $this->isupdate;
        $isdelete = $this->isdelete;
        $isprint = $this->isprint;
        $isexport = $this->isexport;
        $isimport = $this->isimport;

        require_once './views/transaction/transaction_jquery_trans_off.php';
    }

    function saveTransJualOff()
    {
        $this->setIsadmin(true);

        $mdl_trans_dtl = new transaction_detail();
        $ctrl_trans_dtl = new transaction_detailController($mdl_trans_dtl, $this->dbh);

        $mdl_trans_buyer = new transaction_buyer();
        $ctrl_trans_buyer = new transaction_buyerController($mdl_trans_buyer, $this->dbh);

        $mdl_trans_payment = new transaction_payment();
        $ctrl_trans_payment = new transaction_paymentController($mdl_trans_payment, $this->dbh);

        $mdl_trans_log = new transaction_log();
        $ctrl_trans_log = new transaction_logController($mdl_trans_log, $this->dbh);

        $mdl_mst_stok = new master_stock();
        $ctrl_mst_stok = new master_stockController($mdl_mst_stok, $this->dbh);

        $user = $this->user;
        $numbering = $this->countNomorTerakhir(2);
        $setNumbering = $this->showDataNomorTerakhir(2);

        if ($numbering > 0) {
            $nomorakhir = $setNumbering['id'] + 1;
        } else {
            $nomorakhir = 1;
        }

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $generateNotrans = 'OF' . date('dmy') . '-' . sprintf('%06s', $nomorakhir);
        $no_trans = isset($generateNotrans) ? $generateNotrans : "";
        $dateTime = date('Y-m-d h:i:s');
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : "";
        // $qtyTotal = isset($_POST['qtyTotal']) ? $_POST['qtyTotal'] : "";
        // $qtyRelease = isset($_POST['qtyRelease']) ? $_POST['qtyRelease'] : "";
        // $qtyTotal = 0;
        // $qtyRelease = 0;
        $total = 0;
        $part = isset($_POST['part']) ? $_POST['part'] : "";
        $qtyBeli = isset($_POST['qtyBeli']) ? $_POST['qtyBeli'] : "";
        $price = isset($_POST['price']) ? $_POST['price'] : "";
        $method_pay = isset($_POST['metod_pay']) ? $_POST['metod_pay'] : "";
        $paymenn = isset($_POST['paymenn']) ? $_POST['paymenn'] : "";
        $pay_akun = isset($_POST['pay_akun']) ? $_POST['pay_akun'] : "";
        $gTotal = isset($_POST['gTotal']) ? $_POST['gTotal'] : "";
        $buyer_name = isset($_POST['buyer_name']) ? $_POST['buyer_name'] : "";
        $buyer_phone = isset($_POST['buyer_phone']) ? $_POST['buyer_phone'] : "";
        $buyer_address = isset($_POST['buyer_address']) ? $_POST['buyer_address'] : "";

        if ($part != null || $id != "") {

            // transaction
            $this->transaction->setId($id);
            $this->transaction->setNo_trans($no_trans);
            $this->transaction->setTanggal($dateTime);
            $this->transaction->setType_trans(2);
            $this->transaction->setQtyTotal($total);
            $this->transaction->setQtyRelease($total);
            $this->transaction->setTrans_total($gTotal);
            $this->transaction->setTrans_status(1);
            $this->transaction->setCreated_by($user);
            $this->transaction->setCreated_at($dateTime);
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at($dateTime);
            $this->saveData();


            foreach ($part as $arr_part => $y) {
                // transaction detail
                $part = $y;
                $qty = $qtyBeli[$arr_part];
                $priceSet = $price[$arr_part];

                // $mdl_trans_dtl->setId($id);
                $mdl_trans_dtl->setTrans_id($this->getLastId());
                $mdl_trans_dtl->setKd_product($part);
                $mdl_trans_dtl->setQty($qty);
                $mdl_trans_dtl->setHarga($priceSet);
                $ctrl_trans_dtl->saveData();

                $total += $qty;
                $getStock = $ctrl_mst_stok->showData($part);

                //transaction log
                $mdl_trans_log->setTrans_id($this->getLastId());
                $mdl_trans_log->setTrans_type(2);
                $mdl_trans_log->setQty_before($getStock->getQty_stock());
                $mdl_trans_log->setQty_after($getStock->getQty_stock() - $qty);
                $mdl_trans_log->setCreated_by($user);
                $mdl_trans_log->setCreated_at($dateTime);
                $mdl_trans_log->setUpdated_by('');
                $mdl_trans_log->setUpdated_at('');
                $ctrl_trans_log->saveData();

                //master_stock
                $mdl_mst_stok->setKd_product($part);
                $mdl_mst_stok->setQty_stock($getStock->getQty_stock() - $qty);
                $mdl_mst_stok->setQty_stock_promo($getStock->getQty_stock_promo());
                $mdl_mst_stok->setCreated_by($getStock->getCreated_by());
                $mdl_mst_stok->setUpdated_by($user);
                $mdl_mst_stok->setCreated_at($getStock->getCreated_at());
                $mdl_mst_stok->setUpdated_at(date('Y-m-d h:i:s'));
                $ctrl_mst_stok->saveData();
            }

            //paymen transaction
            // $mdl_trans_payment->setId($id);
            $mdl_trans_payment->setTrans_id($this->getLastId());
            $mdl_trans_payment->setMethod($method_pay);
            $mdl_trans_payment->setPayment($method_pay == '1' ? 'Tunai' : $paymenn);
            $mdl_trans_payment->setPayment_akun($pay_akun);
            $mdl_trans_payment->setCreated_by($user);
            $mdl_trans_payment->setCreated_at(date('Y-m-d h:i:s'));
            $mdl_trans_payment->setUpdated_by($user);
            $mdl_trans_payment->setUpdated_at(date('Y-m-d h:i:s'));
            $ctrl_trans_payment->saveData();

            //buyer transaction
            // $mdl_trans_buyer->setId($id);
            $mdl_trans_buyer->setTrans_id($this->getLastId());
            $mdl_trans_buyer->setBuyer_name($buyer_name);
            $mdl_trans_buyer->setBuyer_phone($buyer_phone);
            $mdl_trans_buyer->setBuyer_address($buyer_address);
            $ctrl_trans_buyer->saveData();

            //Update Total
            $queryExe = "UPDATE `transaction` set qtyTotal='$total',qtyRelease='$total' where id='" . $this->getLastId() . "'";
            $this->dbh->query($queryExe);

            echo "Success\nData Tersimpan";
        } else {
            echo "Swal.fire({
                title:'Gagal!',
                text:'Transaksi Offline not saved',
                icon:'error'

            })";
        }


    }

    function preview_FakturOffline(){
        $idfaktur = isset($_REQUEST['id'])?$_REQUEST['id']:"";
        $nofaktur = isset($_REQUEST['notrans'])?$_REQUEST['notrans']:"";

        $mdl_transaction_buyer      = new transaction_buyer();
        $ctrl_transaction_buyer     = new transaction_buyerController($mdl_transaction_buyer,$this->dbh);

        $mdl_master_user_detail     = new master_user_detail();
        $ctrl_master_user_detail    = new master_user_detailController($mdl_master_user_detail,$this->dbh);

        $mdl_transaction_dtl        = new transaction_detail();
        $ctrl_transaction_dtl       = new transaction_detailController($mdl_transaction_dtl,$this->dbh);

        $mdl_transaction_payment    = new transaction_payment();
        $ctrl_transaction_payment   = new transaction_paymentController($mdl_transaction_payment,$this->dbh);

        $showBuyer                  = $ctrl_transaction_buyer->showDataBytransId($idfaktur);
        $showHeadTran               = $this->showData($idfaktur);  
        $showKarTrans               = $ctrl_master_user_detail->showData_byUsernya($showHeadTran->getCreated_by());
        $showDtlTrans               = $ctrl_transaction_dtl->showDataDtlArray($idfaktur);
        $showTransPay               =$ctrl_transaction_payment->showDataBytransId($idfaktur);

        require_once './views/document/faktur_penjualan_offline.php';

    }
}
?>