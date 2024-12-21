<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Master Produk</title>
</head>

<body>
    <?php
    $mdl_prd_ktgor = new product_kategori();
    $ctrl_prd_ktgor = new product_kategoriController($mdl_prd_ktgor, $this->dbh);
    $show_ktgr_prdk = $ctrl_prd_ktgor->showDataAll();
    ?>
    <div class="nav" style="width: 95%;">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#sat">Satuan (Single Produk)</a></li>
            <li><a data-toggle="tab" href="#gab">Gabungan (Upload Excel)</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div id="sat" class="tab-pane fade in active">
            <br>
            <h4 style="color:blue;">FORM INPUT PRODUK SATUAN</h4>
            <br>
            <form name="frmmaster_product" id="frmmaster_product" method="post"
                action="index.php?model=master_product&action=saveFormJQuery">
                <table>
                    <tr>
                        <td class="textBold">ID</td>
                        <td><input type="text" class="form form-control" style="text-align: right;"
                                onkeypress="validate(event);" name="id" id="id"
                                value="<?php echo $master_product_->getId(); ?>" size="40" ReadOnly
                                placeholder="Autonumber"></td>
                    </tr>

                    <tr>
                        <td class="textBold">Kode Part</td>
                        <td><input type="text" class="form form-control" name="kd_product" id="kd_product"
                                value="<?php echo $master_product_->getKd_product(); ?>" size="40" readonly
                                placeholder="Autogenerate"></td>
                    </tr>

                    <tr>
                        <td class="textBold">Nama Part</td>
                        <td><input type="text" class="form form-control" name="nm_product" id="nm_product"
                                value="<?php echo $master_product_->getNm_product(); ?>" size="40" required></td>
                    </tr>

                    <!-- <tr> 
                    <td class="textBold">Image_product</td> 
                    <td><input type="text"  name="image_product" id="image_product" value="<?php echo $master_product_->getImage_product(); ?>" size="40"   ></td>
                </tr> -->
                    <td class="textBold">Kategori Part</td>
                    <td>
                        <select name="kategori_id" id="kategori_id" class="form form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($show_ktgr_prdk as $valuKat) { ?>
                                <option value="<?php echo $valuKat->getId() ?>"><?php echo $valuKat->getKategori_name(); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <tr>
                        <td class="textBold">Harga Produk</td>
                        <td><input type="text" class="form form-control" name="hrg_modal" id="hrg_modal"
                                value="<?php echo $master_product_->getHrg_modal(); ?>" size="40"
                                onkeypress="setHarga(),validate(event);" onkeyup="setHarga()" required></td>
                    </tr>

                    <tr>
                        <td class="textBold">Harga Produk</td>
                        <td><input type="text" class="form form-control" name="hrg_jual" id="hrg_jual"
                                value="<?php echo $master_product_->getHrg_jual(); ?>" size="40" readonly required></td>
                    </tr>

                    <!-- <tr> 
                </tr>
        
                <tr> 
                    <td class="textBold">Tipe_id</td> 
                    <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="tipe_id" id="tipe_id" value="<?php echo $master_product_->getTipe_id(); ?>" size="40"   ></td>
                </tr>
        
                <tr> 
                    <td class="textBold">Sts_aktif</td> 
                    <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="sts_aktif" id="sts_aktif" value="<?php echo $master_product_->getSts_aktif(); ?>" size="11"   ></td>
                </tr>
        
                <tr> 
                    <td class="textBold">Created_by</td> 
                    <td><input type="text"  name="created_by" id="created_by" value="<?php echo $master_product_->getCreated_by(); ?>" size="10"   ></td>
                </tr>
        
                <tr> 
                    <td class="textBold">Updated_by</td> 
                    <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $master_product_->getUpdated_by(); ?>" size="10"   ></td>
                </tr>
        
                <tr> 
                    <td class="textBold">Created_at</td> 
                    <td><input type="text"  name="created_at" id="created_at" value="<?php echo $master_product_->getCreated_at(); ?>" size="10"   ></td>
                </tr>
        
                <tr> 
                    <td class="textBold">Updated_at</td> 
                    <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $master_product_->getUpdated_at(); ?>" size="10"   ></td>
                </tr> -->


                    <tr>
                        <td></td>
                        <td><button type="submit" name="submit" value="" class="btn btn-facebook"><span
                                    class="glyphicon glyphicon-save"></span> Submit</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="gab" class="tab-pane fade">
            <br>
            <h4 style="color:green;">FORM INPUT PRODUK BY UPLOAD EXCEL</h4>
            <br>
            <form name="frmUpload_j_oln_gab" id="frmUpload_j_oln_gab"
                action="index.php?model=master_product&action=saveFormByExcel" method="POST"
                enctype="multipart/form-data">
                <table border="1" cellpadding="2" style="" width="50%">
                    <tr>
                        <td>
                            <button type="button" class="btn btn-red" title="Download Template Excel" onclick="exportFormat()"><span class="glyphicon glyphicon-export"></span>
                                Download Template
                                Excel</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Upload Excel</td>
                        <td>
                            <input type="file" style="text-align: left;" class="form form-control" name="produk_iprt"
                                id="produk_iprt" size="10"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                title="Pilih Document Excel" required />
                        </td>
                    <tr>
                        <td></td>
                        <td>
                            <button class="btn btn-green" type="submit" onclick="" title="Proses Upload Excel"> <span
                                    class="glyphicon glyphicon-upload"></span>
                                Submit</button>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>
</body>

</html>
<script language="javascript" type="text/javascript">
    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {
                if (confirm('Anda yakin Upload data ? ') == false) {
                    return false;
                }

                Swal.fire({
                    title: 'Saving...',
                    html: 'Please wait...',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                });
                swal.showLoading();

                $('#submit').prop('disabled', true);
            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                // console.log(xhr);
                showMenu('content', 'index.php?model=master_product&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
            }
        });
    })();
    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }

    function setHarga() {
        var edValue = $('#hrg_modal').val();
        var setValue = $('#hrg_jual');

        setValue.val(edValue);
    }

    function exportFormat() {
        window.location = "index.php?model=master_product&action=exportFormat";
    }
</script>

<br>




<br>
<br>