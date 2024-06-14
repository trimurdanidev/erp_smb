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
                WHERE a.`nm_product` LIKE '%" . $my_data . "%' AND a.`sts_aktif` IN ('1') order by b.qty_stock desc");

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

    function getPartDet(){
        $mdl_stock      = new master_stock();
        $ctrl_stock     = new master_stockController($mdl_stock,$this->dbh);

        $sql = "SELECT * FROM master_product WHERE id IN (" . $_REQUEST['id'] . ")";
        // echo $sql;
        $list_part = $this->createList($sql);
        //             echo "<option value='~'>ALL OUTLET</option>";
        $n=1;
        foreach ($list_part as $val_part) {
            $getStockPart = $ctrl_stock->showData($val_part->getKd_product());
            $no = $n++;
            echo "".$no.". <input type='hidden' name='part[]' id='part' value='".$val_part->getKd_product()."'>".$val_part->getNm_product()."<input type='text' name='qtyBeli[]' id='qtyBeli' class='form form-control' onkeypress='validate(event);' value='' placeholder='Quantity Beli (Pcs)'/><br>";
        }
    }
}
?>