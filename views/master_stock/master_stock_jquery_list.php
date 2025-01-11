<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Actual</title>
</head>

<body>
    <?php

    $mdl_part = new master_product();
    $ctrl_part = new master_productController($mdl_part, $this->dbh);

    $mdl_part_kategori = new product_kategori();
    $ctrl_part_kategori = new product_kategoriController($mdl_part_kategori, $this->dbh);

    $mdl_part_tipe = new product_tipe();
    $ctrl_part_tipe = new product_tipeController($mdl_part_tipe, $this->dbh);

    ?>
    <div id="header_list">
    </div>
    <input type="hidden" name="setQuery" id="setQuery" value="<?php echo $sql; ?>">
    <input type="hidden" name="graphId" id="graphId" value="<?php echo isset($_REQUEST['id']); ?>">
    <table width="95%">
        <tr>
            <!-- <td align="left">
                    <img alt="Move First" src="./img/icon/icon_move_first.gif"
                        onclick="showMenu('content', 'index.php?model=master_stock&action=showAllJQuery&search=<?php echo $search ?>');">
                    <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                        onclick="showMenu('content', 'index.php?model=master_stock&action=showAllJQuery&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                    Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                    <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                        onclick="showMenu('content', 'index.php?model=master_stock&action=showAllJQuery&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                    <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                        onclick="showMenu('content', 'index.php?model=master_stock&action=showAllJQuery&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    <a href="index.php?model=master_stock&action=export&search=<?php echo $search ?>">Export</a>
                    <a href="index.php?model=master_stock&action=printdata&search=<?php echo $search ?>" target="_"><img
                            src="./images/icon_print.png" /></a>
                </td> -->
            <td>
                <?php echo $search!=null||$search!=""?'':'<span class="glyphicon glyphicon-search"></span>';?>
            </td>
            <td>
            
                <input type="<?php echo $search!=null||$search!=""?'hidden':'text';?>" name="search" id="search" class="form form-control"
                    placeholder="Cari Kode Part, Nama Part" value="<?php echo $search ?>" onkeyup="searchData()">

            </td>
        </tr>
    </table>
    <br>
    <div >
        <table id="table-stock-act" class="table table-striped" width="95%">
            <tr>
                <th class="textBold">No</th>
                <th class="textBold">Kode Part Aplikasi</th>
                <th class="textBold">Nama Part</th>
                <th class="textBold">Stock Aktual</th>
                <th class="textBold">Kategori</th>
                <th class="textBold">Tipe Part</th>
                <th class="textBold">&nbsp;</th>
            </tr>
            <?php

            $no = 1;


            if ($master_stock_list != "") {
                foreach ($master_stock_list as $master_stock) {

                    $showPartnye = $ctrl_part->showDataByKode($master_stock->getKd_product());
                    $showKatnye = $ctrl_part_kategori->showData($showPartnye->getKategori_id());
                    $showTipenye = $ctrl_part_tipe->showData($showPartnye->getTipe_id());
                    // echo "<pre>";
                    // echo print_r($master_stock);
                    // echo "</pre>";
                    $pi = $no + 1;
                    $bg = ($pi % 2 != 0) ? "#E1EDF4" : "#F0F0F0";
                    ?>
                    <tr bgcolor="<?php echo $bg; ?>">
                        <td><?php echo $no; ?></td>
                        <td><?php echo $master_stock->getKd_product(); ?></td>
                        <td><?php echo $showPartnye->getNm_product(); ?></td>
                        <td><?php echo $master_stock->getQty_stock(); ?></td>
                        <td><?php echo $showKatnye->getKategori_name(); ?></td>
                        <td><?php echo $showTipenye->getTipe_name(); ?></td>
                        <!-- <td><?php //echo $master_stock->getCreated_by(); ?></td> -->
                    </tr>
                    <?php
                    $no++;
                }
            }
            ?>
        </table>
    </div>
    <br>
</body>

</html>

<script type="text/javascript">
    function deletedata(id, skip, search) {
        var ask = confirm("Do you want to delete ID " + id + " ?");
        if (ask == true) {
            site = "index.php?model=master_stock&action=deleteFormJQuery&skip=" + skip + "&search=" + search + "&id=" + id;
            target = "content";
            showMenu(target, site);
        }
    }
    function searchData() {
        var searchdata = document.getElementById("search").value;
        var setquery = document.getElementById("setQuery").value;
        var graphId = document.getElementById('graphId').value;
        site = 'index.php?model=master_stock&action=showDashStockBydata&search=' + searchdata ;
        // site = 'index.php?model=master_stock&action=showDashStockBydata&search=' + searchdata + '&queryGet=' + setquery;
        // site = 'index.php?model=graph_query&action=showGraph&id=' +graphId ;
        target = "table-stock-act";
        // (site, target);
        showMenu(target, site);
    }
    $(document).ready(function () {
        $('#search').keypress(function (e) {
            if (e.which == 13) {
                searchData();
            }
        });
    });
</script>