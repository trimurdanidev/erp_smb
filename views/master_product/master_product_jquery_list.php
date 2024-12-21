<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Data Master Produk></title>
</head>

<body>
    <h1>DATA PRODUK SMB</h1>
    <div id="header_list">
        <table width="95%">
            <tr>
                <td align="left">
                    <img alt="Move First" src="./img/icon/icon_move_first.gif"
                        onclick="showMenu('content', 'index.php?model=master_product&action=showAllJQuery&search=<?php echo $search ?>');">
                    <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                        onclick="showMenu('content', 'index.php?model=master_product&action=showAllJQuery&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                    Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                    <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                        onclick="showMenu('content', 'index.php?model=master_product&action=showAllJQuery&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                    <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                        onclick="showMenu('content', 'index.php?model=master_product&action=showAllJQuery&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    <a href="index.php?model=master_product&action=export&search=<?php echo $search ?>">Export</a>
                    <a href="index.php?model=master_product&action=printdata&search=<?php echo $search ?>"
                        target="_"><img src="./images/icon_print.png" /></a>
                </td>
                <td align="right">
                    <input type="text" name="search" id="search" value="<?php echo $search ?>">&nbsp;&nbsp;<input
                        type="button" class="btn btn-info btn-sm" value="find" onclick="searchData()">
                    <?php if ($isadmin || $ispublic || $isentry) { ?>
                        <button type="button" class="btn btn-warning btn-orange" name="new"
                            onclick="showMenu('header_list', 'index.php?model=master_product&action=showFormJQuery')"><span class="glyphicon glyphicon-plus"></span> Tambah Data</button>
                    <?php } ?>

                </td>
            </tr>
        </table>
        <table border="1" cellpadding="2" style="border-collapse: collapse;" width="95%">
            <tr>
                <th class="textBold">id</th>
                <th class="textBold">Kode Produk</th>
                <th class="textBold">Nama Produk</th>
                <th class="textBold">Gambar Produk</th>
                <th class="textBold">Harga</th>
                <th class="textBold">Kategori</th>
                <th class="textBold">Tipe</th>
                <th class="textBold">Status Produk</th>
                <th class="textBold">Created By</th>
                <th class="textBold">Updated By</th>
                <th class="textBold">Created At</th>
                <th class="textBold">Updated At</th>
                <td>&nbsp;</td>
            </tr>
            <?php

            $no = 1;

            if ($master_product_list != "") {
                foreach ($master_product_list as $master_product) {

                    $ketegori_product = new product_kategori();
                    $kategori_productCtrl = new product_kategoriController($ketegori_product, $this->dbh);
                    $showKategoriProduk = $kategori_productCtrl->showData($master_product->getKategori_id());

                    $tipe_product = new product_tipe();
                    $tipe_productCtrl = new product_tipeController($tipe_product, $this->dbh);
                    $showTipeProduk = $tipe_productCtrl->showData($master_product->getTipe_id());

                    $pi = $no + 1;
                    $bg = ($pi % 2 != 0) ? "#E1EDF4" : "#F0F0F0";
                    ?>
                    <tr bgcolor="<?php echo $bg; ?>">
                        <td><a href='#'
                                onclick="showMenu('header_list', 'index.php?model=master_product&action=showDetailJQuery&id=<?php echo $master_product->getId(); ?>')"><?php echo $master_product->getId(); ?></a>
                        </td>
                        <td><?php echo $master_product->getKd_product(); ?></td>
                        <td><?php echo $master_product->getNm_product(); ?></td>
                        <td><a href="" data-toggle="modal" data-target="#modal_gambar<?php echo $master_product->getId(); ?>"
                                title="Lihat gambar">
                                <?php if ($master_product->getImage_product() != "") { ?>

                                    <img src="<?php echo $master_product->getImage_product(); ?>" style="width:150px;height:150px;">
                                <?php } else { ?>

                                    <img src="./images/no-image-available-icon.png">
                                <?php } ?></a>
                            <div class="modal fade" id="modal_gambar<?php echo $master_product->getId(); ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="width:1000px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                <?php echo $master_product->getNm_product(); ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" align="center">
                                            <?php if ($master_product->getImage_product() != "") { ?>
                                                <img src="<?php echo $master_product->getImage_product(); ?>"
                                                    style="align:center" />
                                            <?php } else { ?>
                                                <h3>Gambar Tidak Tersedia</h3>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo "Rp. ", number_format($master_product->getHrg_jual()); ?></td>
                        <td><?php echo $showKategoriProduk->getKategori_name() ?></td>
                        <td><?php echo $showTipeProduk->getTipe_name() ?></td>
                        <td><?php echo $master_product->getSts_aktif() == "1" ? "Aktif" : "Tidak Aktif"; ?></td>
                        <td><?php echo $showTipeProduk->getCreated_by() ?></td>
                        <td><?php echo $showTipeProduk->getUpdated_by() ?></td>
                        <td><?php echo $showTipeProduk->getCreated_at() ?></td>
                        <td><?php echo $showTipeProduk->getUpdated_at() ?></td>
                        <td align="center" class="combobox">
                            <?php if ($isadmin || $ispublic || $isupdate) { ?>
                                <a href='#' class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#modal_edit<?php echo $master_product->getId(); ?>">Edit</a> |
                                <div class="modal fade" id="modal_edit<?php echo $master_product->getId(); ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style="width:1000px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Produk</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <form>
                                                    <div class="form-group">
                                                        <div id="blah" class="col-lg-4 dropzone" ondrop="drag_drop(event)"
                                                            ondragover="return false">
                                                            <p>Drop Your Image Here</p>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Kode Produk</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo $master_product->getKd_product(); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Nama Produk</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo $master_product->getNm_product(); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlTextarea1">Harga Modal</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo number_format($master_product->getHrg_modal()); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Harga Jual</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo number_format($master_product->getHrg_jual()); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Kategori Produk</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo $showKategoriProduk->getKategori_name(); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleFormControlInput1">Tipe Produk</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo $showTipeProduk->getTipe_name(); ?>">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($isadmin || $ispublic || $isdelete) { ?>
                                <a href='#'
                                    onclick="deletedata('<?php echo $master_product->getId() ?>','<?php echo $skip ?>','<?php echo $search ?>')">[Delete]</a>
                            <?php } ?>

                        </td>
                    </tr>
                    <?php
                    $no++;
                }
            }
            ?>
        </table>
    </div>
</body>

</html>
<script type="text/javascript">
    function deletedata(id, skip, search) {
        var ask = confirm("Do you want to delete ID " + id + " ?");
        if (ask == true) {
            site = "index.php?model=master_product&action=deleteFormJQuery&skip=" + skip + "&search=" + search + "&id=" + id;
            target = "content";
            showMenu(target, site);
        }
    }
    function searchData() {
        var searchdata = document.getElementById("search").value;
        site = 'index.php?model=master_product&action=showAllJQuery&search=' + searchdata;
        target = "content";
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


<br>