<?php

use Shuchkin\SimpleXLS;
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
    function saveFormPost()
    {
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockController($mdl_stock, $this->dbh);

        $user = $this->user;
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $kd_product = rand();
        $nm_product = isset($_POST['nm_product']) ? $_POST['nm_product'] : "";
        $image_product = isset($_POST['image_product']) ? $_POST['image_product'] : "";
        $hrg_modal = isset($_POST['hrg_modal']) ? $_POST['hrg_modal'] : "";
        $hrg_jual = isset($_POST['hrg_jual']) ? $_POST['hrg_jual'] : "";
        $kategori_id = isset($_POST['kategori_id']) ? $_POST['kategori_id'] : 2;
        $tipe_id = 1;
        $sts_aktif = 1;
        $created_by = $user;
        $updated_by = isset($_POST['updated_by']) ? $_POST['updated_by'] : "";
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $this->master_product->setId($id);
        $this->master_product->setKd_product($kd_product);
        $this->master_product->setNm_product($nm_product);
        $this->master_product->setImage_product($image_product);
        $this->master_product->setHrg_modal($hrg_modal);
        $this->master_product->setHrg_jual($hrg_jual);
        $this->master_product->setKategori_id($kategori_id);
        $this->master_product->setTipe_id($tipe_id);
        $this->master_product->setSts_aktif($sts_aktif);
        $this->master_product->setCreated_by($created_by);
        $this->master_product->setUpdated_by($updated_by);
        $this->master_product->setCreated_at($created_at);
        $this->master_product->setUpdated_at($updated_at);
        $this->saveData();

        //stock
        $mdl_stock->setKd_product($kd_product);
        $mdl_stock->setQty_stock(0);
        $mdl_stock->setQty_stock_promo(0);
        $mdl_stock->setCreated_by($created_by);
        $mdl_stock->setUpdated_by($updated_by);
        $mdl_stock->setCreated_at($created_at);
        $mdl_stock->setUpdated_at($updated_at);
        $ctrl_stock->saveData();

        echo "Behasil Tamba Master Produk";


    }
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
        $table .= "<div class='table-responsive'>";
        $table .= "<table class='table' border='1' style='width:100%'>";
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
            $table .= "<td><input type='hidden' name='price[]' id='t" . $no . "_price' value='" . $val_part->getHrg_jual() . "'/><input type='text' name='edprice[]' id='t" . $no . "_edprice' value='" . number_format($val_part->getHrg_jual(), 0) . "' class='form form-control' onKeyPress='priceReg(this,event)' onKeyUp='priceReg(this)' ></td>";
            $table .= "<td><input type='text' name='qtyBeli[]' id='t" . $no . "_qtyBeli' class='form form-control' onkeypress='validate(event);' value='' placeholder='Quantity Beli (Pcs)' onblur='jshitung(this)' required /></td>";
            $table .= "<td><input type='hidden' name='ttl[]' id='t" . $no . "_ttl' value=''><input type='text' name='total[]' id='t" . $no . "_total' class='form form-control' readonly></td>";
            $table .= "<td><button type='button' class='btn btn-default' name='hpsDtl[]' id='t" . $no . "_hpsDtl' onclick='hapusElemen(srow" . $no . ",this); return false;'><span class='glyphicon glyphicon-remove'></span> Hapus</button></td>";
            $table .= "</tr>";
            
        }
        $table .= "</tbody>";
        $table .= "<td colspan='4'><span class='glyphicon glyphicon-asterisk'></span><b>Total</b></td><td><input type='hidden' name='gTotal' id='gTotal'><input type='text' name='totalnya' id='totalnya' class='form form-control' value='0' readonly></td>";
        $table .= "</table>";
        $table .= "</div>";
        echo $table;
    }

    function saveFormByExcel()
    {
        $this->setIsadmin(true);
        require_once './Excel/SimpleXLS.php';
        $mdl_stock = new master_stock();
        $ctrl_stock = new master_stockControllerGenerate($mdl_stock, $this->dbh);

        $mdl_prd_kategori = new product_kategori();
        $ctrl_prd_kategori = new product_kategoriController($mdl_prd_kategori, $this->dbh);

        $user = $this->user;
        $getFile_name = $_FILES['produk_iprt']['name'];
        $getThe_file = $_FILES['produk_iprt']['tmp_name'];
        $targetFile = "uploads/excel_upload/" . $getFile_name;
        $uploadok = move_uploaded_file($getThe_file, $targetFile);
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $updated_by = isset($_POST['updated_by']) ? $_POST['updated_by'] : "";
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        if ($dataExcel = SimpleXLS::parse("$targetFile")) {
            $total = 0;
            $Hitungbaris = 1;
            foreach ($dataExcel->rows() as $k => $row) {
                if ($row[3] > 0) {
                    if ($k === 0) {
                        $header_values = $row;
                        continue;
                    }

                    // echo '<pre>';
                    // echo print_r($row);
                    // echo '</pre>';
                    $jml_data = $Hitungbaris++;
                    $colnumb = $row[0];
                    $colKdPart = rand();
                    $colNamaPart = $row[1];
                    $colKatPart = $row[2];
                    $colHargaPart = $row[3];

                    $getFirstKategori = $ctrl_prd_kategori->showDataByName($colKatPart);
                    $countKatPart = $ctrl_prd_kategori->checkDataByName($colKatPart);

                    if ($countKatPart == 0 || $countKatPart == null):
                        echo "Gagal Upload !!\nKode Product $colKdPart - $colNamaPart Kategorinya Salah";
                        return false;
                    else:

                        $this->master_product->setId($id);
                        $this->master_product->setKd_product($colKdPart);
                        $this->master_product->setNm_product($colNamaPart);
                        $this->master_product->setImage_product("");
                        $this->master_product->setHrg_modal($colHargaPart);
                        $this->master_product->setHrg_jual($colHargaPart);
                        $this->master_product->setKategori_id($getFirstKategori->getId());
                        $this->master_product->setTipe_id(1);
                        $this->master_product->setSts_aktif(1);
                        $this->master_product->setCreated_by($user);
                        $this->master_product->setUpdated_by($updated_by);
                        $this->master_product->setCreated_at($created_at);
                        $this->master_product->setUpdated_at($updated_at);
                        $this->saveData();

                        //stock
                        $mdl_stock->setKd_product($colKdPart);
                        $mdl_stock->setQty_stock(0);
                        $mdl_stock->setQty_stock_promo(0);
                        $mdl_stock->setCreated_by($user);
                        $mdl_stock->setUpdated_by($updated_by);
                        $mdl_stock->setCreated_at($created_at);
                        $mdl_stock->setUpdated_at($updated_at);
                        $ctrl_stock->saveData();

                        // echo "Berhasil Upload Master Produk";
                    endif;
                } else {
                    echo "Gagal Upload !!\n Harga Produk Tidak Disarankan\n\n\n";
                }
            }
            echo "Berhasil\n\n" . ($jml_data) . " Baris Data Terupload\n\n";
        }
    }

    function exportFormat()
    {
        $this->setIsadmin(true);
        $filePath = './uploads/Template/FOMAT_UPLOAD_PRODUK.xls';


        header("Content-Type:application/xls", false);
        header("Content-Disposition: attachment; filename=" .basename($filePath));

        readfile($filePath);
    }
}
?>