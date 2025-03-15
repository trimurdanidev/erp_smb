<?php
use Shuchkin\SimpleXLS;
use Vtiful\Kernel\Format;

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
require_once './fpdf/fpdf.php';
require_once './models/report_query.class.php';
require_once './controllers/report_query.controller.generate.php';
require_once './controllers/report_query.controller.php';

require_once './models/master_user_detail.class.php';
require_once './controllers/master_user_detail.controller.php';

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


        $last = $ctrl_upload_tr_log->countDataAll();
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

        $transaction_list = $ctrl_upload_tr_log->showDataAll_SOLimit();
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
        INNER JOIN `transaction` d ON d.`upload_trans_log_id` = a.`id`
        INNER JOIN transaction_detail b ON d.id = b.`trans_id`
        INNER JOIN transaction_log c ON c.`trans_id` = d.`id`
        WHERE d.type_trans='3' and d.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.`trans_status` IN ($stsId)
        GROUP BY a.`id` 
        ORDER BY a.`created_at` DESC";

        $last = $ctrl_upload_tr_log->countDataAll();
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
        $dateTime = date('Y-m-d H:i:s');
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
            $this->transaction->setUpload_trans_log_id(0);
            $this->transaction->setCreated_at($dateTime);
            $this->transaction->setUpdated_by('');
            $this->transaction->setUpdated_at($dateTime);
            $this->saveData();

            //upload_trans_log
            // $mdl_upload_tr_log->setId($id);
            $mdl_upload_tr_log->setTrans_type(3);
            $mdl_upload_tr_log->setTrans_descrip('IN STOCK OPNAME TGL.' . date('d-m-Y') . " OLEH " . $user);
            $mdl_upload_tr_log->setJumlah_data(0);
            $mdl_upload_tr_log->setCreated_by($user);
            $mdl_upload_tr_log->setCreated_at($dateTime);
            $mdl_upload_tr_log->setUpdated_by('');
            $mdl_upload_tr_log->setUpdated_at($dateTime);
            $ctrl_upload_tr_log->setIsadmin(true);
            $ctrl_upload_tr_log->saveData();

            foreach ($dataSheet->rows() as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;
                    continue;
                }
                $jml_data = $k++;
                $kd_prod = $r[0];
                $nm_prod = $r[1];
                $stok_qty = $r[2];
                $qty = $r[3];
                $ket = $r[4];
                // echo print_r($kd_prod."-".$nm_prod."-".$qty."-".$ket."<br>");

                $count_from_product = $ctrl_product->checkDataByKode($kd_prod);
                $count_from_stock = $ctrl_stock->checkData($kd_prod);

                if ($count_from_product == 0 && $count_from_stock == 0):
                    // echo "konsalah1";
                    echo "Gagal Upload!!\nKode Product $kd_prod - $nm_prod Belum Ada di Master Produk & Master Stok";
                    //delete trans & upload log
                    $this->deleteData($this->getLastId());
                    $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                    // echo "konsalah0";
                    return false;
                elseif ($count_from_product == 0):
                    // echo "konsalah2";
                    echo "Gagal Upload !!\nKode Product $kd_prod - $nm_prod Belum Ada di Master Produk";
                    //delete trans & upload log
                    $this->deleteData($this->getLastId());
                    $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                    // echo "konsalah1";
                    return false;
                elseif ($count_from_stock == 0):
                    // echo "konsalah3";
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
                    $mdl_transaction_dtl->setNm_product("");
                    $mdl_transaction_dtl->setTrans_descript("");
                    $mdl_transaction_dtl->setQty($qty);
                    $mdl_transaction_dtl->setHarga($getHarga->getHrg_jual());
                    $ctrl_transaction_dtl->setIsadmin(true);
                    $ctrl_transaction_dtl->saveData();

                    //transaction_log
                    // $mdl_transaction_log->setId($id);
                    $mdl_transaction_log->setTrans_id($this->getLastId());
                    $mdl_transaction_log->setKd_product($kd_prod);
                    $mdl_transaction_log->setTrans_type(3);
                    $mdl_transaction_log->setQty_before($getStock->getQty_stock());
                    $mdl_transaction_log->setQty_after($qty);
                    $mdl_transaction_log->setCreated_by($user);
                    $mdl_transaction_log->setCreated_at($dateTime);
                    $mdl_transaction_log->setUpdated_by('');
                    $mdl_transaction_log->setUpdated_at($dateTime);
                    $ctrl_transaction_log->setIsadmin(true);
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
            $ctrl_upload_tr_log->setIsadmin(true);
            $ctrl_upload_tr_log->saveData();

            $this->transaction->setId($showUpdate_trs->getId());
            $this->transaction->setNo_trans($showUpdate_trs->getNo_trans());
            $this->transaction->setTanggal($showUpdate_trs->getTanggal());
            $this->transaction->setType_trans($showUpdate_trs->getType_trans());
            $this->transaction->setQtyTotal($total);
            $this->transaction->setTrans_total(0);
            $this->transaction->setQtyRelease($showUpdate_trs->getQtyRelease());
            $this->transaction->setCreated_by($showUpdate_trs->getCreated_by());
            $this->transaction->setUpload_trans_log_id($ctrl_upload_tr_log->getLastId());
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

    function showDetailJQuery()
    {
        $this->setIsadmin(true);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_trans_dettail = new transaction_detailController($mdl_transaction_dtl, $this->dbh);


        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $tipe = isset($_REQUEST['tipe']) ? $_REQUEST['tipe'] : "";

        $getTrans = $this->showData($id);
        $showDetail = $ctrl_trans_dettail->showDataDtlArray($id);

        switch ($tipe) {
            case '3':
                # code...
                require_once './views/transaction/transaction_jquery_so_detail.php';
                break;
            case '4':
                # code...
                require_once './views/transaction/transaction_jquery_restok_detail.php';
                break;
            default:
                # code...
                break;
        }
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
                $mdl_stock->setQty_stock( /*$getStok->getQty_stock() + */ $valDetail->getQty());
                $mdl_stock->setQty_stock_promo($getStok->getQty_stock_promo());
                $mdl_stock->setCreated_by($getStok->getCreated_by());
                $mdl_stock->setUpdated_by($user);
                $mdl_stock->setCreated_at($getStok->getCreated_at());
                $mdl_stock->setUpdated_at(date('Y-m-d H:i:s'));
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
            $this->transaction->setUpload_trans_log_id($getTransaction->getUpload_trans_log_id());
            $this->transaction->setCreated_at($getTransaction->getCreated_at());
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at(date('Y-m-d H:i:s'));
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
        $typeTrans = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "";
        $cekSO = $this->cekSOnotConfirm();
        if ($cekSO > 0) {
            echo "<script language='javascript' type='text/javascript'>
            Swal.fire({
                title : 'Gagal ! Ada Transaksi Stock Opname Belum Terilis / Masih On Process',
                icon : 'error',
                text : 'Mohon Selesaikan Stock Opname Terlebih Dahhulu '
            });
            </script>";
            return false;
        } else {
            switch ($typeTrans) {
                case '1':
                    require_once './views/transaction/transaction_jquery_form_trans_oln.php';
                    break;
                case '2':
                    require_once './views/transaction/transaction_jquery_form_trans_off.php';
                    break;
            }
        }

    }

    function countDataOffline(){            
        $sql = "SELECT count(id)  FROM transaction WHERE type_trans=2";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function countDataOnline(){            
        $sql = "SELECT count(id)  FROM transaction WHERE type_trans=1";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function showAllJQuery_trans_off()
    {
        $this->setIsadmin(true);
        $userLogin = $this->user;
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

        $mdl_user_detail = new master_user_detail();
        $ctlr_user_detail = new master_user_detailController($mdl_user_detail, $this->dbh);
        $showDetailUser = $ctlr_user_detail->showData_byUsernya($userLogin);

        $last = $this->countDataOffline();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        // print_r($showDetailUser->getGroupcode());

        if ($showDetailUser->getGroupcode() == 'Owner'):
            $sql = "SELECT * FROM `transaction` WHERE type_trans=2 order by id desc";
            $sql .= " limit " . $skip . ", " . $limit;
        else:
            $sql = "SELECT * FROM `transaction` WHERE type_trans=2 and created_by = '$userLogin' order by id desc";
            $sql .= " limit " . $skip . ", " . $limit;
        endif;

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
        $this->setIsadmin(true);
        $userLogin = $this->user;
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

        $mdl_user_detail = new master_user_detail();
        $ctlr_user_detail = new master_user_detailController($mdl_user_detail, $this->dbh);
        $showDetailUser = $ctlr_user_detail->showData_byUsernya($userLogin);

        $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
        $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
        $pyment = isset($_REQUEST["payment"]) ? $_REQUEST["payment"] : "";
        
        $last = $this->countDataOffline();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";


        if ($showDetailUser->getGroupcode() == 'Owner'):
            if ($pyment == null || $pyment == "") {
                $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
                INNER JOIN transaction_payment d ON d.`trans_id` = a.id
                INNER JOIN transaction_buyer f ON f.trans_id = a.id 
                WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' 
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            } else {
                $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
                INNER JOIN transaction_payment d ON d.`trans_id` = a.id
                INNER JOIN transaction_buyer f ON f.trans_id = a.id
                WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.payment='$pyment' 
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            }
        else:
            if ($pyment == null || $pyment == "") {
                $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
                INNER JOIN transaction_payment d ON d.`trans_id` = a.id
                INNER JOIN transaction_buyer f ON f.trans_id = a.id 
                WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' and a.created_by = '$userLogin'
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            } else {
                $sql = "SELECT a.*,d.method,d.payment,d.payment_akun,f.buyer_name,f.buyer_phone,f.buyer_address FROM `transaction` a 
                INNER JOIN transaction_payment d ON d.`trans_id` = a.id
                INNER JOIN transaction_buyer f ON f.trans_id = a.id
                WHERE a.`type_trans` =2 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.payment='$pyment' and a.created_by = '$userLogin'
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            }
        endif;


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
        $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : "";
        $generateNotrans = 'OF' . date('dmy', strtotime($tanggal)) . '-' . sprintf('%06s', $nomorakhir);
        $no_trans = isset($generateNotrans) ? $generateNotrans : "";
        $dateTime = date('Y-m-d H:i:s', strtotime($tanggal));
        // $qtyTotal = isset($_POST['qtyTotal']) ? $_POST['qtyTotal'] : "";
        // $qtyRelease = isset($_POST['qtyRelease']) ? $_POST['qtyRelease'] : "";
        // $qtyTotal = 0;
        // $qtyRelease = 0;
        $total = 0;
        $part = isset($_POST['part']) ? $_POST['part'] : "";
        $qtyBeli = isset($_POST['qtyBeli']) ? $_POST['qtyBeli'] : "";
        $namepr = isset($_POST['namepr']) ? $_POST['namepr'] : "";
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
            $this->transaction->setUpload_trans_log_id(0);
            $this->transaction->setCreated_at($dateTime);
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at($dateTime);
            $this->saveData();


            foreach ($part as $arr_part => $y) {
                // transaction detail
                $part = $y;
                $qty = $qtyBeli[$arr_part];
                $priceSet = $price[$arr_part];
                $nameSet = $namepr[$arr_part];

                // $mdl_trans_dtl->setId($id);
                $mdl_trans_dtl->setTrans_id($this->getLastId());
                $mdl_trans_dtl->setKd_product($part);
                $mdl_trans_dtl->setNm_product($nameSet);
                $mdl_trans_dtl->setQty($qty);
                $mdl_trans_dtl->setHarga($priceSet);
                $ctrl_trans_dtl->setIsadmin(true);
                $ctrl_trans_dtl->saveData();

                $total += $qty;
                $getStock = $ctrl_mst_stok->showData($part);

                //transaction log
                $mdl_trans_log->setTrans_id($this->getLastId());
                $mdl_trans_log->setKd_product($part);
                $mdl_trans_log->setTrans_type(2);
                $mdl_trans_log->setQty_before($getStock->getQty_stock());
                $mdl_trans_log->setQty_after($getStock->getQty_stock() - $qty);
                $mdl_trans_log->setCreated_by($user);
                $mdl_trans_log->setCreated_at($dateTime);
                $mdl_trans_log->setUpdated_by('');
                $mdl_trans_log->setUpdated_at($dateTime);
                $ctrl_trans_log->setIsadmin(true);
                $ctrl_trans_log->saveData();

                //master_stock
                $mdl_mst_stok->setKd_product($part);
                $mdl_mst_stok->setQty_stock($getStock->getQty_stock() - $qty);
                $mdl_mst_stok->setQty_stock_promo($getStock->getQty_stock_promo());
                $mdl_mst_stok->setCreated_by($getStock->getCreated_by());
                $mdl_mst_stok->setUpdated_by($user);
                $mdl_mst_stok->setCreated_at($getStock->getCreated_at());
                $mdl_mst_stok->setUpdated_at(date('Y-m-d H:i:s'));
                $ctrl_mst_stok->setIsadmin(true);
                $ctrl_mst_stok->saveData();
            }

            //paymen transaction
            // $mdl_trans_payment->setId($id);
            $mdl_trans_payment->setTrans_id($this->getLastId());
            $mdl_trans_payment->setMethod($method_pay);
            $mdl_trans_payment->setPayment($method_pay == '1' ? 'Tunai' : $paymenn);
            $mdl_trans_payment->setPayment_akun($pay_akun);
            $mdl_trans_payment->setCreated_by($user);
            $mdl_trans_payment->setCreated_at(date('Y-m-d H:i:s'));
            $mdl_trans_payment->setUpdated_by($user);
            $mdl_trans_payment->setUpdated_at(date('Y-m-d H:i:s'));
            $ctrl_trans_payment->setIsadmin(true);
            $ctrl_trans_payment->saveData();

            //buyer transaction
            // $mdl_trans_buyer->setId($id);
            $mdl_trans_buyer->setTrans_id($this->getLastId());
            $mdl_trans_buyer->setBuyer_name($buyer_name);
            $mdl_trans_buyer->setBuyer_phone($buyer_phone);
            $mdl_trans_buyer->setBuyer_address($buyer_address);
            $ctrl_trans_buyer->setIsadmin(true);
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

    function preview_FakturOffline()
    {
        $idfaktur = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $nofaktur = isset($_REQUEST['notrans']) ? $_REQUEST['notrans'] : "";

        $mdl_transaction_buyer = new transaction_buyer();
        $ctrl_transaction_buyer = new transaction_buyerController($mdl_transaction_buyer, $this->dbh);

        $mdl_master_user_detail = new master_user_detail();
        $ctrl_master_user_detail = new master_user_detailController($mdl_master_user_detail, $this->dbh);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $mdl_transaction_payment = new transaction_payment();
        $ctrl_transaction_payment = new transaction_paymentController($mdl_transaction_payment, $this->dbh);

        $showBuyer = $ctrl_transaction_buyer->showDataBytransId($idfaktur);
        $showHeadTran = $this->showData($idfaktur);
        $showKarTrans = $ctrl_master_user_detail->showData_byUsernya($showHeadTran->getCreated_by());
        $showDtlTrans = $ctrl_transaction_dtl->showDataDtlArray($idfaktur);
        $showTransPay = $ctrl_transaction_payment->showDataBytransId($idfaktur);

        require_once './views/document/faktur_penjualan_offline.php';

    }

    function showDataLogUpload($idLog)
    {
        $this->setIsadmin(true);
        $sql = "SELECT * FROM transaction WHERE upload_trans_log_id = '" . $this->toolsController->replacecharFind($idLog, $this->dbh) . "'";

        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->transaction, $row);

        return $this->transaction;
    }

    function cekSOnotConfirm()
    {
        $this->setIsadmin(true);
        $sql = "SELECT count(*) FROM `transaction` a 
        INNER JOIN upload_trans_log b ON b.`id` = a.`upload_trans_log_id` 
        WHERE type_trans='3' and trans_status ='0'";

        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function createPDFFaktur($idfaktur = "")
    {
        $idPrintPDF = $idfaktur != "" ? $idfaktur : $_REQUEST['id'];

        $mdl_transaction_buyer = new transaction_buyer();
        $ctrl_transaction_buyer = new transaction_buyerController($mdl_transaction_buyer, $this->dbh);

        $mdl_master_user_detail = new master_user_detail();
        $ctrl_master_user_detail = new master_user_detailController($mdl_master_user_detail, $this->dbh);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $mdl_transaction_payment = new transaction_payment();
        $ctrl_transaction_payment = new transaction_paymentController($mdl_transaction_payment, $this->dbh);

        $mdl_mast_produk = new master_product();
        $ctrl_mast_produk = new master_productController($mdl_mast_produk, $this->dbh);

        $showBuyer = $ctrl_transaction_buyer->showDataBytransId($idfaktur);
        $showHeadTran = $this->showData($idfaktur);
        $showKarTrans = $ctrl_master_user_detail->showData_byUsernya($showHeadTran->getCreated_by());
        $showDtlTrans = $ctrl_transaction_dtl->showDataDtlArray($idfaktur);
        $showTransPay = $ctrl_transaction_payment->showDataBytransId($idfaktur);



        $title1 = "FAKTUR PENJUALAN";

        $pdfin = new FPDF('P', 'mm', 'Legal');

        // membuat halaman baru
        $pdfin->AddPage();
        $pdfin->SetTopMargin(6);
        $pdfin->SetLeftMargin(6);
        $pdfin->SetAutoPageBreak(20, 4);
        // setting jenis font yang akan digunakan
        $pdfin->SetFont('Arial', 'BU', 16);
        $pdfin->Cell(192, 5, $title1, 0, 1, 'L');
        $pdfin->Cell(200, 6, '', 0, 1);

        $pdfin->SetFont('Arial', '', 9);
        $pdfin->Cell(192, 7, "From :", 0, 1, 'L');
        $pdfin->SetFont('Arial', 'B', 9);
        $pdfin->Cell(0, 0, "Sparepart Motor Bekasi", 0, 0, 'L');
        $pdfin->SetFont('Arial', '', 9);
        $pdfin->Cell(0, 0, "Jl. H. DJain No.31", 0, 0, 'L');

        $pdfin->Cell(180, 7, "Sparepart Motor Bekasi", 0, 1, 'L');

        $pdfin->SetFont('Times', 'B', 9);
        $pdfin->Cell(50, 5, 'No. Transaksi : ' . $showHeadTran->getNo_trans(), 0, 2, 'L');
        // $pdfin->Cell(130,5,$getHeader->getCmpnyaddress(),0,0,'L');
        // $pdfin->Cell(50,5,'Delivery Date: '.date("d/m/Y",strtotime($doDate)),0,1,'L');
        // $pdfin->Cell(130,5,strtoupper($getHeader->getCmpnycity()),0,0,'L');            
        // $pdfin->Cell(50,5,'Customer Reff: '.$poRef,0,1,'L');
        $pdfin->Cell(10, 5, '', 0, 1);
        // $pdfin->Cell(130,5,'Delivered To: '.$deliveryTo,0,0,'L');
        // $pdfin->Cell(50,5,'No. Picking List: '.$noPicking,0,1,'L');
        // $pdfin->Cell(130,5,$deliveryName,0,0,'L');
        // $pdfin->Cell(50,5,'No. PO: '.$poCust,0,1,'L');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdfin->Cell(10, 8, '', 0, 1);

        $pdfin->SetFont('Courier', 'B', 9);
        $pdfin->Cell(10, 8, 'No', 1, 0, 'C');
        $pdfin->Cell(30, 8, 'Kode Part', 1, 0, 'C');
        $pdfin->Cell(112, 8, 'Nama Part', 1, 0, 'C');
        $pdfin->Cell(15, 8, 'Quantity', 1, 0, 'C');
        $pdfin->Cell(15, 8, 'Harga', 1, 0, 'C');
        $pdfin->Cell(20, 8, 'Diskon', 1, 1, 'C');
        // $pdfin->SetFont('Total', '', 9);

        $no = 1;
        $totInnerQty = 0;
        $totWeight = 0;
        $totPcsQty = 0;
        $totVolume = 0;
        $gtotalPcs = 0;

        foreach ($showDtlTrans as $row) {
            $getPart = $ctrl_mast_produk->showDataByKode($row->getKd_product());

            $pdfin->Cell(10, 6, $no, 1, 0, 'C');
            $pdfin->Cell(30, 6, $row->getKd_product(), 1, 0, 'C');
            $pdfin->Cell(112, 6, $getPart->getNm_product(), 1, 0, 'L');
            $pdfin->Cell(15, 6, number_format($row->getQty()), 1, 0, 'R');
            $pdfin->Cell(15, 6, number_format($row->getHarga()), 1, 0, 'R');
            $pdfin->Cell(20, 6, number_format(0), 1, 1, 'R');

            $no++;
        }

        $pdfin->Cell(10, 2, '', 0, 1);
        $pdfin->SetFont('Courier', '', 9);
        $pdfin->Cell(50, 4, 'Total Inner Qty: ', 0, 0, 'L');
        $pdfin->Cell(30, 4, number_format($totInnerQty), 0, 0, 'R');
        $pdfin->Cell(44, 4, '', 0, 0, 'C');
        $pdfin->Cell(50, 4, 'Total Wt. (Kgs):', 0, 0, 'L');
        $pdfin->Cell(29, 4, number_format($totWeight), 0, 1, 'R');

        $pdfin->Cell(50, 4, 'Total Pcs Qty: ', 0, 0, 'L');
        $pdfin->Cell(30, 4, number_format($totPcsQty), 0, 0, 'R');
        $pdfin->Cell(44, 4, '', 0, 0, 'C');
        $pdfin->Cell(50, 4, 'Total Volume (m3):', 0, 0, 'L');
        $pdfin->Cell(29, 4, number_format($totVolume), 0, 1, 'R');

        $pdfin->Cell(50, 4, 'Grand Total Pcs: ', 0, 0, 'L');
        $pdfin->Cell(30, 4, number_format($gtotalPcs), 0, 1, 'R');

        $pdfin->Cell(200, 4, '----------------------------------------------------------------------------------------------------------', 0, 0, 'L');

        $fileName = $idfaktur;

        // auto save file for windows path using wamp server
        //$pdf->Output('F', 'E:\\wamp\\www\\scm_mb\\views\\surat_jalan_tracking_log\\pdf_files\\'.$fileName.'.pdf');            
        // auto save file for linux path
        // $pdfin->Output('F', '/var/www/html/scm_mb/views/surat_jalan_tracking_log/pdf_files/'.$fileName.'.pdf');
        $pdfin->Output('F', './views/document/pdf_files/' . $fileName . '.pdf');

        // for browser view
        //$pdfin->Output();    
    }

    function openPDFFile()
    {
        $this->setIsadmin(true);
        $idFaktur = $_REQUEST['id'];

        // for linux path
        // $pathFile = '/var/www/html/scm_mb/views/surat_jalan_tracking_log/pdf_files/'.$idFaktur.'.pdf';

        // for widows path in wampserver
        $pathFile = 'D:\\xampp8\\htdocs\\erp_smb\\views\\document\\pdf_files\\' . $idFaktur . '.pdf';

        if (file_exists($pathFile)) {
            //echo "The file $pathFile exists";
            // header("location:./views/surat_jalan_tracking_log/pdf_files/" . $idFaktur . ".pdf");
            header("location:./views/document/pdf_files/" . $idFaktur . ".pdf");
        } else {
            echo "The file $pathFile does not exists";
            $this->createPDFFaktur($idFaktur);
            header("location:./views/document/pdf_files/" . $idFaktur . ".pdf");
        }
    }


    function showAllJQuery_restok()
    {
        $this->setIsadmin(true);
        $cekSO = $this->cekSOnotConfirm();
        if ($cekSO > 0) {
            echo "<script language='javascript' type='text/javascript'>
            Swal.fire({
                title : 'Gagal ! Ada Transaksi Stock Opname Belum Terilis / Masih On Process',
                icon : 'error',
                text : 'Mohon Selesaikan Stock Opname Terlebih Dahhulu '
            });
            </script>";
            return false;
            // require_once './views/transaction/transaction_jquery_so.php';
        } else {

            $mdl_transcation_dtl = new transaction_detail();
            $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

            $mdl_transaction_log = new transaction_log();
            $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

            $mdl_trans_type = new transaction_type();
            $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

            $mdl_upload_tr_log = new upload_trans_log();
            $ctrl_upload_tr_log = new upload_trans_logController($mdl_upload_tr_log, $this->dbh);


            // $last = $this->countDataAll();
            $last = $ctrl_upload_tr_log->countDataAll();

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

            $transaction_list = $ctrl_upload_tr_log->showDataAll_restokLimit();
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

            require_once './views/transaction/transaction_jquery_restok.php';
        }
    }

    function saveUploadRestok()
    {
        $this->setIsadmin(true);
        $cekDokRestok = $this->cekRestoknotRilis();
        if ($cekDokRestok > 0):
            echo "Gagal ! Mohon Selesaikan Restok Sebelumnya Terlebih Dahulu";
            return false;
        else:
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
            $numbering = $this->showDataNomorTerakhir(4);

            if (count($numbering) > 0) {
                $nomorakhir = $numbering['id'] + 1;
            }

            $id = isset($_POST['id']) ? $_POST['id'] : "";
            $generateNotrans = 'RS' . date('dmy') . '-' . sprintf('%06s', $nomorakhir);
            $no_trans = isset($generateNotrans) ? $generateNotrans : "";
            $dateTime = date('Y-m-d H:i:s');
            $getFile_name = $_FILES['file_restok']['name'];
            $getThe_file = $_FILES['file_restok']['tmp_name'];
            $targetFile = "uploads/excel_upload/" . $getFile_name;
            $uploadok = move_uploaded_file($getThe_file, $targetFile);

            if ($dataSheet = SimpleXLS::parse("$targetFile")) {
                // print_r( $xlsx->rows() );
                $total = 0;

                //transaction
                $this->transaction->setId($id);
                $this->transaction->setNo_trans($no_trans);
                $this->transaction->setTanggal($dateTime);
                $this->transaction->setType_trans(4);
                $this->transaction->setQtyTotal($total);
                $this->transaction->setQtyRelease(0);
                $this->transaction->setTrans_total(0);
                $this->transaction->setTrans_status(0);
                $this->transaction->setCreated_by($user);
                $this->transaction->setUpload_trans_log_id(0);
                $this->transaction->setCreated_at($dateTime);
                $this->transaction->setUpdated_by('');
                $this->transaction->setUpdated_at($dateTime);
                $this->saveData();

                //upload_trans_log
                // $mdl_upload_tr_log->setId($id);
                $ctrl_upload_tr_log->setIsadmin(true);
                $mdl_upload_tr_log->setTrans_type(4);
                $mdl_upload_tr_log->setTrans_descrip('IN RE-STOCK TGL.' . date('d-m-Y') . " OLEH " . $user);
                $mdl_upload_tr_log->setJumlah_data(0);
                $mdl_upload_tr_log->setCreated_by($user);
                $mdl_upload_tr_log->setCreated_at($dateTime);
                $mdl_upload_tr_log->setUpdated_by('');
                $mdl_upload_tr_log->setUpdated_at($dateTime);
                $ctrl_upload_tr_log->saveData();

                $Hitungbaris = 1;
                foreach ($dataSheet->rows() as $k => $r) {
                    if ($r[4] > 0) {
                        if ($k === 0) {
                            $header_values = $r;
                            continue;
                        }

                        $jml_data = $Hitungbaris++;
                        $cul_no = $r[0];
                        $cul_kd_prod = str_replace("#", "", str_replace(" ", "", $r[1]));
                        $cul_nm_prod = $r[2];
                        $cul_qty_stok = $r[3];
                        $cul_qty = $r[4];
                        $ket = $r[5];
                        // echo print_r($cul_kd_prod."-".$cul_nm_prod."-".$cul_qty."-".$ket."<br>");

                        $count_from_product = $ctrl_product->checkDataByKode($cul_kd_prod);
                        $count_from_stock = $ctrl_stock->checkData($cul_kd_prod);

                        if ($count_from_product == 0 && $count_from_stock == 0):
                            echo "Gagal Upload!!\nKode Product $cul_kd_prod - $cul_nm_prod Belum Ada di Master Produk & Master Stok";
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            return false;
                        elseif ($count_from_product == 0):
                            echo "Gagal Upload !!\nKode Product $cul_kd_prod - $cul_nm_prod Belum Ada di Master Produk";
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            return false;
                        elseif ($count_from_stock == 0):
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            // echo "Kon salah 2";
                            return false;
                        else:
                            //get_stock_produk
                            $getStock = $ctrl_stock->showData($cul_kd_prod);
                            $getHarga = $ctrl_product->showDataByKode($cul_kd_prod);
                            $total += $cul_qty;

                            //transaction_detail
                            // $mdl_transaction_dtl->setId($id);
                            $mdl_transaction_dtl->setTrans_id($this->getLastId());
                            $mdl_transaction_dtl->setKd_product($cul_kd_prod);
                            $mdl_transaction_dtl->setNm_product("");
                            $mdl_transaction_dtl->setTrans_descript("");
                            $mdl_transaction_dtl->setQty($cul_qty);
                            $mdl_transaction_dtl->setHarga($getHarga->getHrg_jual());
                            $ctrl_transaction_dtl->setIsadmin(true);
                            $ctrl_transaction_dtl->saveData();

                            //transaction_log
                            // $mdl_transaction_log->setId($id);
                            $mdl_transaction_log->setTrans_id($this->getLastId());
                            $mdl_transaction_log->setKd_product($cul_kd_prod);
                            $mdl_transaction_log->setTrans_type(4);
                            $mdl_transaction_log->setQty_before($getStock->getQty_stock());
                            $mdl_transaction_log->setQty_after($getStock->getQty_stock() + $cul_qty);
                            $mdl_transaction_log->setCreated_by($user);
                            $mdl_transaction_log->setCreated_at($dateTime);
                            $mdl_transaction_log->setUpdated_by('');
                            $mdl_transaction_log->setUpdated_at($dateTime);
                            $ctrl_transaction_log->setIsadmin(true);
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
                }
                echo ($Hitungbaris - 1) . " Baris Data Dengan Quantity\n\n";
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
                $ctrl_upload_tr_log->setIsadmin(true);
                $ctrl_upload_tr_log->saveData();

                $this->transaction->setId($showUpdate_trs->getId());
                $this->transaction->setNo_trans($showUpdate_trs->getNo_trans());
                $this->transaction->setTanggal($showUpdate_trs->getTanggal());
                $this->transaction->setType_trans($showUpdate_trs->getType_trans());
                $this->transaction->setQtyTotal($total);
                $this->transaction->setTrans_total(0);
                $this->transaction->setQtyRelease($showUpdate_trs->getQtyRelease());
                $this->transaction->setCreated_by($showUpdate_trs->getCreated_by());
                $this->transaction->setUpload_trans_log_id($ctrl_upload_tr_log->getLastId());
                $this->transaction->setCreated_at($showUpdate_trs->getCreated_at());
                $this->transaction->setUpdated_by($showUpdate_trs->getUpdated_by());
                $this->transaction->setUpdated_at($showUpdate_trs->getUpdated_at());
                $this->saveData();
                echo "Success TerUpload";
            } else {
                echo SimpleXLS::parseError();
            }
        endif;
    }

    function confirmRestock()
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
                $mdl_stock->setQty_stock($getStok->getQty_stock() + $valDetail->getQty());
                $mdl_stock->setQty_stock_promo($getStok->getQty_stock_promo());
                $mdl_stock->setCreated_by($getStok->getCreated_by());
                $mdl_stock->setUpdated_by($user);
                $mdl_stock->setCreated_at($getStok->getCreated_at());
                $mdl_stock->setUpdated_at(date('Y-m-d H:i:s'));
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
            $this->transaction->setUpload_trans_log_id($getTransaction->getUpload_trans_log_id());
            $this->transaction->setCreated_at($getTransaction->getCreated_at());
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at(date('Y-m-d H:i:s'));
            $this->updateData();
            // echo "<script>alert('Stock Opname Berhasil Terilis');</script>";
            $this->showAllJQuery_restok();
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

    public function export_stock()
    {

        $this->setIsadmin(true);
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);
        $Now = date('ymd_his');

        $sql = "SELECT @noq:=@noq+1 `No`, a.* FROM (
                SELECT concat('#',a.`kd_product`) `Kode Part`, b.`nm_product` `Part`, a.`qty_stock` `Stock`, 0 `Qty Restok (Isi Disini)`,'RESTOK' `Ket` FROM master_stock a
                INNER JOIN master_product b ON a.`kd_product` = b.`kd_product`
                ORDER BY Part ASC 
                ) AS a,(SELECT @noq:=0) AS noq
                ORDER BY Stock ASC
                ;";

        header("Content-Type:application/xls", false);
        header("Content-Disposition: attachment; filename=" . $ctrl_stock->getModulename() . "_" . $Now . ".xls");
        if ($ctrl_stock->getModulename() != "report_query") {
            $report_query = new report_query();
            $report_query_controller = new report_queryController($report_query, $this->dbh);
            echo $report_query_controller->generatetableviewExcel($sql);
        } else {
            echo $ctrl_report_query->generatetableviewExcel($sql);
        }
    }


    function showAllJQuery_reStock_by_data()
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
        INNER JOIN `transaction` d ON d.`upload_trans_log_id` = a.`id`
        INNER JOIN transaction_detail b ON d.id = b.`trans_id`
        INNER JOIN transaction_log c ON c.`trans_id` = d.`id`
        WHERE d.type_trans='4' and d.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND d.`trans_status` IN ($stsId)
        GROUP BY a.`id` 
        ORDER BY a.`created_at` DESC";

        $last = $ctrl_upload_tr_log->countDataAll();
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

        require_once './views/transaction/transaction_jquery_restok.php';
    }

    function CancelSO()
    {
        $this->setIsadmin(true);
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $user = $this->user;
        $total = 0;
        $dateTime = date('Y-m-d H:i:s');

        $getTransaction = $this->showData($id);

        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockControllerGenerate($mdl_stock, $this->dbh);

        $mdl_tran_log = new transaction_log();
        $ctrl_tran_log = new transaction_logController($mdl_tran_log, $this->dbh);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $showDtlTrans = $ctrl_transaction_dtl->showDataDtlArray($id);


        if ($id != "") {
            foreach ($showDtlTrans as $valDetail) {
                $getPrdLog = $ctrl_tran_log->showDataByTransId($id, $valDetail->getKd_product());
                $total += $valDetail->getQty();
                // transaction log
                $mdl_tran_log->setId($getPrdLog->getId());
                $mdl_tran_log->setTrans_id($getPrdLog->getTrans_id());
                $mdl_tran_log->setKd_product($getPrdLog->getKd_product());
                $mdl_tran_log->setTrans_type($getPrdLog->getTrans_type());
                $mdl_tran_log->setQty_before($getPrdLog->getQty_before());
                $mdl_tran_log->setQty_after(0);
                $mdl_tran_log->setCreated_by($getPrdLog->getCreated_by());
                $mdl_tran_log->setCreated_at($getPrdLog->getCreated_at());
                $mdl_tran_log->setUpdated_by($user);
                $mdl_tran_log->setUpdated_at($dateTime);
                $ctrl_tran_log->saveData();
            }
            //transaction
            $this->transaction->setId($id);
            $this->transaction->setNo_trans($getTransaction->getNo_trans());
            $this->transaction->setTanggal($getTransaction->getTanggal());
            $this->transaction->setType_trans($getTransaction->getType_trans());
            $this->transaction->setQtyTotal($getTransaction->getQtyTotal());
            $this->transaction->setQtyRelease(0);
            $this->transaction->setTrans_total(0);
            $this->transaction->setTrans_status(2);
            $this->transaction->setCreated_by($getTransaction->getCreated_by());
            $this->transaction->setUpload_trans_log_id($getTransaction->getUpload_trans_log_id());
            $this->transaction->setCreated_at($getTransaction->getCreated_at());
            $this->transaction->setUpdated_by($user);
            $this->transaction->setUpdated_at(date('Y-m-d H:i:s'));
            $this->updateData();
            // echo "<script>alert('Stock Opname Berhasil Cancel');</script>";

            if ($getTransaction->getType_trans() == '3'):
                $this->showAllJQuery_so();
            elseif ($getTransaction->getType_trans() == '4'):
                $this->showAllJQuery_restok();
            elseif ($getTransaction->getType_trans() == '1'):
                $this->showAllJQuery_trans_onln();
            endif;

        } else {
            echo "<script language='javascript' type='text/javascript'>
            Swal.fire({
                title : 'Gagal Cancel !',
                icon : 'error',
                text : 'Silahkan Cek Koneksi Internet Anda'
            });
            </script>";
        }
    }

    function showAllJQuery_trans_onln()
    {
        $this->setIsadmin(true);
        $userLogin = $this->user;

        $mdl_user_detail = new master_user_detail();
        $ctlr_user_detail = new master_user_detailController($mdl_user_detail, $this->dbh);
        $showDetailUser = $ctlr_user_detail->showData_byUsernya($userLogin);

        $last = $this->countDataOnline();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        // print_r($showDetailUser->getGroupcode());

        if ($showDetailUser->getGroupcode() == 'Owner'):
            $sql = "SELECT * FROM `transaction` WHERE type_trans=1 order by id desc";
            $sql .= " limit " . $skip . ", " . $limit;
        else:
            $sql = "SELECT * FROM `transaction` WHERE type_trans=1 and created_by='$userLogin' order by id desc";
            $sql .= " limit " . $skip . ", " . $limit;
        endif;

        // echo $sql;


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

        require_once './views/transaction/transaction_jquery_trans_oln.php';
    }

    public function export_stock_opname()
    {

        $this->setIsadmin(true);
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);
        $Now = date('ymd_his');
        $fileNameExport = "STOCK_OPNAME_" . $Now;

        $sql = "SELECT concat('',a.`kd_product`,'') `kode product`, b.`nm_product` `nama product`, a.`qty_stock` `Stock`, 0 `qty`,'STOCK_OPNAME' `ket` FROM master_stock a
                INNER JOIN master_product b ON a.`kd_product` = b.`kd_product`
                ORDER BY b.`nm_product` ASC";

        header("Content-Type:application/xls", false);
        header("Content-Disposition: attachment; filename=" . $fileNameExport . ".xls");
        if ($ctrl_stock->getModulename() != "report_query") {
            $report_query = new report_query();
            $report_query_controller = new report_queryController($report_query, $this->dbh);
            echo $report_query_controller->generatetableviewExcel($sql);
        } else {
            echo $ctrl_report_query->generatetableviewExcel($sql);
        }
    }

    function cekRestoknotRilis()
    {
        $this->setIsadmin(true);
        $sql = "SELECT count(*) FROM `transaction` a 
        INNER JOIN upload_trans_log b ON b.`id` = a.`upload_trans_log_id` 
        WHERE type_trans='4' and trans_status ='0'";

        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }
    function cekOnlineUsernotRilis()
    {
        $userLogin = $this->user;

        if ($userLogin != null || $userLogin != ""):
            $this->setIsadmin(true);
            $sql = "SELECT count(*) FROM `transaction` a 
            INNER JOIN upload_trans_log b ON b.`id` = a.`upload_trans_log_id` 
            WHERE type_trans='1' and trans_status ='0' and a.created_by='$userLogin'";

            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        else:
            echo '<script>window.location="index.php";</script>';
        endif;
    }

    public function export_closing()
    {

        $this->setIsadmin(true);
        $user = $this->user;
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $mdl_report_query = new report_query();
        $ctrl_report_query = new report_queryController($mdl_report_query, $this->dbh);
        $Now = date('ymd_his');
        $tp_mktplce = isset($_REQUEST['tp_mkt']) ? $_REQUEST['tp_mkt'] : "";
        $mktplc = "";

        // die();

        switch ($tp_mktplce) {
            case 'SHP':
                $mktplc = 'Shopee';
                break;
            case 'LZD':
                $mktplc = 'Lazada';
                break;
            case 'TKP':
                $mktplc = 'Tokopedia';
                break;
            case 'TTS':
                $mktplc = 'TikTokShop';
                break;
            default:
                $mktplc = '';
                break;
        }

        $fileNameExport = "CLOSING_OLN_" . $mktplc . '_' . $user . '_' . $Now;
        $sql = "SELECT ROW_NUMBER() OVER (
            ORDER BY Part) `No`, a.* FROM (
            SELECT concat('#',a.`kd_product`) `Kode Part`, b.`nm_product` `Part`, a.`qty_stock` `Stock`, 0 `Qty Online (Isi Disini)`,'$mktplc' `Marketplace`,'ONLINE' `Ket` FROM master_stock a
            INNER JOIN master_product b ON a.`kd_product` = b.`kd_product`
            ) AS a,(SELECT @noq:=0) AS noq
            ORDER BY Part ASC;";

        header("Content-Type:application/xls", false);
        header("Content-Disposition: attachment; filename=" . $fileNameExport . ".xls");
        if ($ctrl_stock->getModulename() != "report_query") {
            $report_query = new report_query();
            $report_query_controller = new report_queryController($report_query, $this->dbh);
            echo $report_query_controller->generatetableviewExcel($sql);
        } else {
            echo $ctrl_report_query->generatetableviewExcel($sql);
        }
    }

    function saveUploadOnline()
    {
        $this->setIsadmin(true);
        $cekDokOnline = $this->cekOnlineUsernotRilis();
        if ($cekDokOnline > 0):
            echo "Gagal ! Mohon Selesaikan Transaksi Closing Online Anda Sebelumnya Terlebih Dahulu";
            return false;
        else:
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
            $numbering = $this->showDataNomorTerakhir(1);

            if (count($numbering) > 0) {
                $nomorakhir = $numbering['id'] + 1;
            }

            $id = isset($_POST['id']) ? $_POST['id'] : "";
            $generateNotrans = 'ON' . date('dmy') . '-' . sprintf('%06s', $nomorakhir);
            $no_trans = isset($generateNotrans) ? $generateNotrans : "";
            $dateTime = date('Y-m-d H:i:s');
            $getFile_name = $_FILES['file_upload']['name'];
            $getThe_file = $_FILES['file_upload']['tmp_name'];
            $targetFile = "uploads/excel_upload/" . $getFile_name;
            $uploadok = move_uploaded_file($getThe_file, $targetFile);

            if ($dataSheet = SimpleXLS::parse("$targetFile")) {
                // print_r( $xlsx->rows() );
                $total = 0;

                //transaction
                $this->transaction->setId($id);
                $this->transaction->setNo_trans($no_trans);
                $this->transaction->setTanggal($dateTime);
                $this->transaction->setType_trans(1);
                $this->transaction->setQtyTotal($total);
                $this->transaction->setQtyRelease(0);
                $this->transaction->setTrans_total(0);
                $this->transaction->setTrans_status(0);
                $this->transaction->setCreated_by($user);
                $this->transaction->setUpload_trans_log_id(0);
                $this->transaction->setCreated_at($dateTime);
                $this->transaction->setUpdated_by('');
                $this->transaction->setUpdated_at($dateTime);
                $this->saveData();

                //upload_trans_log
                // $mdl_upload_tr_log->setId($id);
                $mdl_upload_tr_log->setTrans_type(1);
                $mdl_upload_tr_log->setTrans_descrip('OUT CLOSING ONLINE TGL.' . date('d-m-Y') . " OLEH " . $user);
                $mdl_upload_tr_log->setJumlah_data(0);
                $mdl_upload_tr_log->setCreated_by($user);
                $mdl_upload_tr_log->setCreated_at($dateTime);
                $mdl_upload_tr_log->setUpdated_by('');
                $mdl_upload_tr_log->setUpdated_at($dateTime);
                $ctrl_upload_tr_log->setIsadmin(true);
                $ctrl_upload_tr_log->saveData();

                $Hitungbaris = 1;
                foreach ($dataSheet->rows() as $k => $r) {
                    if ($r[4] > 0) {
                        if ($k === 0) {
                            $header_values = $r;
                            continue;
                        }
                        $jml_data = $Hitungbaris++;
                        $cul_no = $r[0];
                        $cul_kd_prod = str_replace("#", "", str_replace(" ", "", $r[1]));
                        $cul_nm_prod = $r[2];
                        $cul_qty_stok = $r[3];
                        $cul_qty = $r[4];
                        $cul_marketplace = $r[5];
                        $ket = $r[6];
                        // echo print_r($kd_prod."-".$nm_prod."-".$qty."-".$ket."<br>");

                        $count_from_product = $ctrl_product->checkDataByKode($cul_kd_prod);
                        $count_from_stock = $ctrl_stock->checkData($cul_kd_prod);

                        if ($count_from_product == 0 && $count_from_stock == 0):
                            echo "Gagal Upload!!\nKode Product $cul_kd_prod - $cul_nm_prod Belum Ada di Master Produk & Master Stok";
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            return false;
                        elseif ($count_from_product == 0):
                            echo "Gagal Upload !!\nKode Product $cul_kd_prod - $cul_nm_prod Belum Ada di Master Produk";
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            return false;
                        elseif ($count_from_stock == 0):
                            //delete trans & upload log
                            $this->deleteData($this->getLastId());
                            $ctrl_upload_tr_log->deleteData($ctrl_upload_tr_log->getLastId());
                            // echo "Kon salah 2";
                            return false;
                        else:
                            $this->setIsadmin(true);
                            //get_stock_produk
                            $getStock = $ctrl_stock->showData($cul_kd_prod);
                            $getHarga = $ctrl_product->showDataByKode($cul_kd_prod);
                            $total += $cul_qty;

                            //transaction_detail
                            // $mdl_transaction_dtl->setId($id);
                            $mdl_transaction_dtl->setTrans_id($this->getLastId());
                            $mdl_transaction_dtl->setKd_product($cul_kd_prod);
                            $mdl_transaction_dtl->setNm_product("");
                            $mdl_transaction_dtl->setTrans_descript($cul_marketplace);
                            $mdl_transaction_dtl->setQty($cul_qty);
                            $mdl_transaction_dtl->setHarga($getHarga->getHrg_jual());
                            $ctrl_transaction_dtl->setIsadmin(true);
                            $ctrl_transaction_dtl->saveData();

                            //transaction_log
                            // $mdl_transaction_log->setId($id);
                            $mdl_transaction_log->setTrans_id($this->getLastId());
                            $mdl_transaction_log->setKd_product($cul_kd_prod);
                            $mdl_transaction_log->setTrans_type(4);
                            $mdl_transaction_log->setQty_before($getStock->getQty_stock());
                            $mdl_transaction_log->setQty_after($getStock->getQty_stock() - $cul_qty);
                            $mdl_transaction_log->setCreated_by($user);
                            $mdl_transaction_log->setCreated_at($dateTime);
                            $mdl_transaction_log->setUpdated_by('');
                            $mdl_transaction_log->setUpdated_at($dateTime);
                            $ctrl_transaction_log->setIsadmin(true);
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
                }

                echo ($Hitungbaris - 1) . " Baris Data Dengan Quantity\n\n";
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
                $ctrl_upload_tr_log->setIsadmin(true);
                $ctrl_upload_tr_log->saveData();

                $this->transaction->setId($showUpdate_trs->getId());
                $this->transaction->setNo_trans($showUpdate_trs->getNo_trans());
                $this->transaction->setTanggal($showUpdate_trs->getTanggal());
                $this->transaction->setType_trans($showUpdate_trs->getType_trans());
                $this->transaction->setQtyTotal($total);
                $this->transaction->setTrans_total(0);
                $this->transaction->setQtyRelease($showUpdate_trs->getQtyRelease());
                $this->transaction->setCreated_by($showUpdate_trs->getCreated_by());
                $this->transaction->setUpload_trans_log_id($ctrl_upload_tr_log->getLastId());
                $this->transaction->setCreated_at($showUpdate_trs->getCreated_at());
                $this->transaction->setUpdated_by($showUpdate_trs->getUpdated_by());
                $this->transaction->setUpdated_at($showUpdate_trs->getUpdated_at());
                $this->saveData();
                echo "Success TerUpload";
            } else {
                echo SimpleXLS::parseError();
            }
        endif;
    }

    public function CancelOnn()
    {
        # code...
    }

    function cekOnlineUserBelumConf()
    {
        $userLogin = $this->user;

        if ($userLogin != null || $userLogin != ""):
            $this->setIsadmin(true);
            $sql = "SELECT count(*) FROM `transaction` a 
            INNER JOIN upload_trans_log b ON b.`id` = a.`upload_trans_log_id` 
            WHERE type_trans='1' and trans_status ='0' and a.created_by!='$userLogin'";

            $row = $this->dbh->query($sql)->fetch();
            return $row[0];
        else:
            echo '<script>window.location="index.php";</script>';
        endif;
    }

    public function confirmOnline()
    {
        $this->setIsadmin(true);
        $cekDokBelumConf = $this->cekOnlineUserBelumConf();

        // if ($cekDokBelumConf > 0):
        //     echo "<script language='javascript' type='text/javascript'>
        //     Swal.fire({
        //         title : 'Gagal ! Ada Transaksi Closing Online Dari Admin Lain Belum Confirm / Masih On Process',
        //         icon : 'error',
        //         text : 'Mohon Selesaikan Closing Online Tersebut Terlebih Dahhulu '
        //     });
        //     </script>";
        // else:

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $user = $this->user;
        $total = 0;

        $getTransaction = $this->showData($id);

        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockControllerGenerate($mdl_stock, $this->dbh);

        $mdl_transaction_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transaction_dtl, $this->dbh);

        $mdl_part = new master_product();
        $ctrl_part = new master_productController($mdl_part, $this->dbh);

        $showDtlTrans = $ctrl_transaction_dtl->showDataDtlArray($id);


        if ($id != "") {
            $totStatus = 0;
            $jmlDataNo = 0;
            $total = 0;
            foreach ($showDtlTrans as $valDetail) {
                $getStok = $ctrl_stock->showData($valDetail->getKd_product());
                $getPart = $ctrl_part->showDataByKode($valDetail->getKd_product());

                // echo "<pre>";
                // echo print_r($valDetail->getKd_product() . "-" . $getStok->getQty_stock() . "-" . $valDetail->getQty(), true);
                // echo "</pre>";

                $setStatusCount = "";

                if ($getStok->getQty_stock() > $valDetail->getQty() || $getStok->getQty_stock() == $valDetail->getQty()):
                    $setStatusCount = "YES";
                    $jmlDataYes = $totStatus++;
                    // $totStatusCountYES = count($setStatusCountYES);
                elseif ($getStok->getQty_stock() < $valDetail->getQty()):
                    $setStatusCount = "NO";
                    $jmlDataNo = $totStatus++;
                    // $totStatusCountNO = count($setStatusCounNO);
                else:
                endif;


                // echo "<pre>";
                // echo print_r($setStatusCount, true);
                // echo "</pre>";

            }

            // echo "Yes count: " . $jmlDataYes . "\n";
            // echo "No count: " . $jmlDataNo . "\n";

            if ($jmlDataNo > 0) {
                for ($i = 0; $i < $jmlDataNo; $i++) {
                    // foreach ($showDtlTrans as $valDetail2) {
                    // $getStok2 = $ctrl_stock->showData($i$valDetail2->getKd_product());
                    // $getPart2 = $ctrl_part->showDataByKode($valDetail2->getKd_product());
                    // $setStatus = 'N';
                    // $ketKosong = "Stok " . $valDetail2->getKd_product() . "-" . $getPart2->getNm_product() . " Tidak Mencukupi. Sisa Stoknya " . $getStok2->getQty_stock() . " Pcs";
                    // echo $setStatusCount."<br>";
                    $nom = $jmlDataNo - 1;
                    // if ($setStatusCount == 'NO') {

                    echo "<script language='javascript' type='text/javascript'>
                        Swal.fire({
                            title : 'Gagal Confirm!',
                            icon : 'error',
                            text : 'Ada " . $jmlDataNo . " Produk Stok Lebih Kecil dari Qty Closing'
                            });
                            
                            </script>";
                    // }

                }
                $this->showAllJQuery_trans_onln();

            } else {
                // echo "123 ";
                foreach ($showDtlTrans as $valDetail3) {
                    $getStok3 = $ctrl_stock->showData($valDetail3->getKd_product());
                    $total += $valDetail3->getQty();
                    //master_stock
                    $mdl_stock->setKd_product($valDetail3->getKd_product());
                    $mdl_stock->setQty_stock($getStok3->getQty_stock() - $valDetail3->getQty());
                    $mdl_stock->setQty_stock_promo($getStok3->getQty_stock_promo());
                    $mdl_stock->setCreated_by($getStok3->getCreated_by());
                    $mdl_stock->setUpdated_by($user);
                    $mdl_stock->setCreated_at($getStok3->getCreated_at());
                    $mdl_stock->setUpdated_at(date('Y-m-d H:i:s'));
                    $ctrl_stock->saveData();
                }
                $setStatus = 'Y';

                //transaction
                if ($setStatus == 'Y') {
                    $this->transaction->setId($id);
                    $this->transaction->setNo_trans($getTransaction->getNo_trans());
                    $this->transaction->setTanggal($getTransaction->getTanggal());
                    $this->transaction->setType_trans($getTransaction->getType_trans());
                    $this->transaction->setQtyTotal($getTransaction->getQtyTotal());
                    $this->transaction->setQtyRelease($total);
                    $this->transaction->setTrans_total(0);
                    $this->transaction->setTrans_status(1);
                    $this->transaction->setCreated_by($getTransaction->getCreated_by());
                    $this->transaction->setUpload_trans_log_id($getTransaction->getUpload_trans_log_id());
                    $this->transaction->setCreated_at($getTransaction->getCreated_at());
                    $this->transaction->setUpdated_by($user);
                    $this->transaction->setUpdated_at(date('Y-m-d H:i:s'));
                    $this->updateData();
                    echo "<script language='javascript' type='text/javascript'>
                    Swal.fire({
                    title : 'Berhasil',
                    icon : 'success',
                    text : 'Closing Online Berhasil Terconfirm'
                    });
                    </script>";
                    //     echo "<script language='javascript' type='text/javascript'>
                    //     Swal.fire({
                    //     title : 'Gagal Confirm !--1',
                    //     icon : 'error',
                    //     text : 'Silahkan Cek Koneksi Internet Anda'
                    // });
                    // </script>";
                    $this->showAllJQuery_trans_onln();
                }
                $this->showAllJQuery_trans_onln();
            }
        } else {
            echo "<script language='javascript' type='text/javascript'>
                    Swal.fire({
                        title : 'Gagal Confirm !',
                        icon : 'error',
                        text : 'Silahkan Cek Koneksi Internet Anda'
                        });
                        </script>";
        }
        // endif;
    }

    function showAllJQuery_trans_onn_by_search()
    {
        $userLogin = $this->user;
        $mdl_transcation_dtl = new transaction_detail();
        $ctrl_transaction_dtl = new transaction_detailController($mdl_transcation_dtl, $this->dbh);

        $mdl_transaction_log = new transaction_log();
        $ctrl_transaction_log = new transaction_logController($mdl_transaction_log, $this->dbh);

        $mdl_trans_type = new transaction_type();
        $ctrl_trans_type = new transaction_typeController($mdl_trans_type, $this->dbh);

        $mdl_user_detail = new master_user_detail();
        $ctlr_user_detail = new master_user_detailController($mdl_user_detail, $this->dbh);
        $showDetailUser = $ctlr_user_detail->showData_byUsernya($userLogin);

        // print_r($showDetailUser->getGroupcode());

        $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
        $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
        $mktplc = isset($_REQUEST["mktplc"]) ? $_REQUEST["mktplc"] : "";

        $last = $this->countDataOnline();
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

        if ($showDetailUser->getGroupcode() == 'Owner'):
            if ($mktplc == null || $mktplc == "") {
                $sql = "SELECT DISTINCT b .`trans_descript`,a.* FROM `transaction` a 
                INNER JOIN `transaction_detail` b ON a.`id` = b.`trans_id`
                WHERE a.`type_trans` =1 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate'
                GROUP BY a.`id`
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            } else {
                $sql = "SELECT DISTINCT b.`trans_descript`, a.* FROM `transaction` a 
                INNER JOIN `transaction_detail` b ON a.`id` = b.`trans_id`
                WHERE a.`type_trans` =1 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND b.`trans_descript`='$mktplc'
                GROUP BY a.`id`
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            }
        else:
            if ($mktplc == null || $mktplc == "") {
                $sql = "SELECT DISTINCT b .`trans_descript`,a.* FROM `transaction` a 
                INNER JOIN `transaction_detail` b ON a.`id` = b.`trans_id`
                WHERE a.`type_trans` =1 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' and a.created_by = '$userLogin'
                GROUP BY a.`id`
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            } else {
                $sql = "SELECT DISTINCT b.`trans_descript`, a.* FROM `transaction` a 
                INNER JOIN `transaction_detail` b ON a.`id` = b.`trans_id`
                WHERE a.`type_trans` =1 AND a.`tanggal` BETWEEN '$fromDate' AND '$toDate' AND b.`trans_descript`='$mktplc' and a.created_by = '$userLogin'
                GROUP BY a.`id`
                ORDER BY a.`created_at` DESC";
                $sql .= " limit " . $skip . ", " . $limit;
            }
        endif;

        // echo $sql;


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

        require_once './views/transaction/transaction_jquery_trans_oln.php';
    }
}
?>