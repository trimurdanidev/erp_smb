<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/master_product.class.php';
require_once './controllers/master_product.controller.generate.php';
require_once './models/product_kategori.class.php';
require_once './controllers/product_kategori.controller.generate.php';
require_once './controllers/product_kategori.controller.php';
require_once './models/product_tipe.class.php';
require_once './controllers/product_tipe.controller.generate.php';
require_once './controllers/product_tipe.controller.php';
require_once './models/master_stock.class.php';
require_once './controllers/master_stock.controller.generate.php';
require_once './controllers/master_stock.controller.php';
if (!isset($_SESSION)) {
    session_start();
}

class master_productController extends master_productControllerGenerate
{
    function showDataByKode($kode)
    {
        $sql = "SELECT * FROM master_product WHERE kd_product= '" . $this->toolsController->replacecharFind($kode, $this->dbh) . "'";

        $row = $this->dbh->query($sql)->fetch();
        $this->loadData($this->master_product, $row);

        return $this->master_product;
    }

    function checkDataByKode($kode)
    {
        $sql = "SELECT count(*) FROM master_product where kd_product = '" . $kode . "'";
        $row = $this->dbh->query($sql)->fetch();
        return $row[0];
    }

    function searchProdmulti()
    {
        $this->setIsadmin(true);

        // $dbh = new PDO("mysql:host=localhost;port=3306;dbname=sgs_distribution", "root", "");

        $q = $_GET['q'];
        $my_data = $q;
        $sql = $this->dbh->query("SELECT a.`id` `id`,a.`kd_product`, concat(a.`nm_product`,' - Stok = ',b.`qty_stock`,'Pcs') `name`,a.`hrg_jual` `price_jual`,a.`hrg_modal` `price_modal`,b.`qty_stock`,b.`qty_stock_promo` FROM master_product a 
                LEFT JOIN master_stock b ON a.`kd_product` = b.`kd_product`
                WHERE a.`nm_product` LIKE '%" . $my_data . "%' AND a.`sts_aktif` IN ('1') AND b.`qty_stock` !=0 OR b.`qty_stock_promo`!=0 order by b.qty_stock desc");

        // Generate array with Produk data 
        $ProdData = array();
        if ($sql->rowCount() > 0) {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $ProdData[] = $row;
            }
        }

        // Return results as json encoded array 
        echo json_encode($ProdData);
    }

    function getPartDet()
    {
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $sql = "SELECT * FROM master_product WHERE id IN (" . $_REQUEST['id'] . ")";
        // echo $sql;
        $list_part = $this->createList($sql);
        //             echo "<option value='~'>ALL OUTLET</option>";
        $n = 1;
        $table = "";
        $table .= "<table border='1' style='width:100%'>";
        $table .= "<thead>";
        $table .= " <tr>";
        $table .= "<th>No</th>";
        $table .= "<th>Part</th>";
        $table .= "<th>Harga</th>";
        $table .= "<th>Quantity</th>";
        $table .= "<th>Jumlah</th>";
        $table .= "<th>#</th>";
        $table .= "</tr>";
        $table .= "</thead>";
        $table .= " <tbody class='tbody'>";
        foreach ($list_part as $val_part) {
            $getStockPart = $ctrl_stock->showData($val_part->getKd_product());
            $no = $n++;
            $table .= "<tr id='srow" . $no . "'>";
            $table .= "<input type='hidden' name='part[]' id='part" . $no . "' value='" . $val_part->getKd_product() . "'>";
            $table .= "<td>" . $no . ".</td>";
            $table .= "<td><input type='text' class='form form-control' value='" . $val_part->getNm_product() . "' readonly></td>";
            $table .= "<td><input type='hidden' name='price[]' id='t" . $no . "_price' value='" . $val_part->getHrg_jual() . "'/><input type='text' value='" . number_format($val_part->getHrg_jual(), 0) . "' class='form form-control' readonly></td>";
            $table .= "<td><input type='text' name='qtyBeli[]' id='t" . $no . "_qtyBeli' class='form form-control' onkeypress='validate(event);' value='' placeholder='Quantity Beli (Pcs)' onblur='jshitung(this)' required /></td>";
            $table .= "<td><input type='hidden' name='ttl[]' id='t" . $no . "_ttl' value=''><input type='text' name='total[]' id='t" . $no . "_total' class='form form-control' readonly></td>";
            $table .= "<td><button type='button' class='btn btn-default' name='hpsDtl[]' id='t" . $no . "_hpsDtl' onclick='hapusElemen(srow" . $no . "); return false;'><span class='glyphicon glyphicon-remove'></span> Hapus</button></td>";
            $table .= "</tr>";

        }
        $table .= "</tbody>";
        $table .= "<td colspan='4'><span class='glyphicon glyphicon-asterisk'></span><b>Total</b></td><td><input type='hidden' name='gTotal' id='gTotal'><input type='text' name='totalnya' id='totalnya' class='form form-control' value='0' readonly></td>";
        $table .= "<table>";
        echo $table;
    }
}
?>