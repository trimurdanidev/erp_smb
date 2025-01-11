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

    function showDashStock($qetQueryGraph)
    {
        $this->setIsadmin(true);
        if ($this->ispublic || $this->isadmin || $this->isread) {
            // $last = 0;
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
            $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
            $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
            $getQuery = isset($_REQUEST["queryGet"]) ? $_REQUEST["queryGet"]:"";

            if ($search == null || $search == "") {
                // echo "1";
                $sql = $qetQueryGraph;
            } else {
                // $this->showDashStockBydata();
                 // echo "2";
                 $sql = "SELECT a .*,,b.qty_stock,b.qty_stock_promo FROM master_stock b 
                 INNER JOIN master_product a ON a.`kd_product` = b.`kd_product` 
                 INNER JOIN product_kategori c ON c.`id` = a.`kategori_id` 
                 INNER JOIN product_tipe d ON d.`id` = a.`tipe_id`
                 WHERE b.kd_product LIKE '%".$search."%' OR a.nm_product LIKE '%".$search."%' 
                     
                 ORDER BY a.nm_product";
            }

            // echo $sql;
            // $sisa = intval($last % $limit);

            // if ($sisa > 0) {
            //     $last = $last - $sisa;
            // } else if ($last - $limit < 0) {
            //     $last = 0;
            // } else {
            //     $last = $last - $limit;
            // }

            // $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

            // if ($skip + $limit > $last) {
            //     $next = $last;
            // } else if ($skip == 0) {
            //     $next = $skip + $limit + 1;
            // } else {
            //     $next = $skip + $limit;
            // }
            // $first = 0;

            // $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
            // $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

            // $master_stock_list = $this->showDataAllLimit();
            $master_stock_list = $this->createList($sql);

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
            require_once './views/master_stock/master_stock_jquery_list.php';
        } else {
            echo "You cannot access this module";
        }
    }

    function showDashStockBydata()
    {
        $this->setIsadmin(true);
        if ($this->ispublic || $this->isadmin || $this->isread) {
            // $last = 0;
            $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
            $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
            $search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";
            $getQuery = isset($_REQUEST["queryGet"]) ? $_REQUEST["queryGet"]:"";

                // echo "2";
                $sql = "SELECT a .*,b.qty_stock,b.qty_stock_promo FROM master_stock b 
                INNER JOIN master_product a ON a.`kd_product` = b.`kd_product` 
                INNER JOIN product_kategori c ON c.`id` = a.`kategori_id` 
                INNER JOIN product_tipe d ON d.`id` = a.`tipe_id`
                WHERE b.kd_product LIKE '%".$search."%' OR a.nm_product LIKE '%".$search."%' 
                    
                ORDER BY a.nm_product";

            // echo $sql;
            // $sisa = intval($last % $limit);

            // if ($sisa > 0) {
            //     $last = $last - $sisa;
            // } else if ($last - $limit < 0) {
            //     $last = 0;
            // } else {
            //     $last = $last - $limit;
            // }

            // $previous = $skip - $limit < 0 ? 0 : $skip - $limit;

            // if ($skip + $limit > $last) {
            //     $next = $last;
            // } else if ($skip == 0) {
            //     $next = $skip + $limit + 1;
            // } else {
            //     $next = $skip + $limit;
            // }
            // $first = 0;

            // $pageactive = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($skip / $limit)) + 1;
            // $pagecount = $last == 0 ? $sisa == 0 ? 0 : 1 : intval(($last / $limit)) + 1;

            // $master_stock_list = $this->showDataAllLimit();
            $master_stock_list = $this->createList($sql);

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
            require_once './views/master_stock/master_stock_jquery_list.php';
        } else {
            echo "You cannot access this module";
        }
    }
}
?>