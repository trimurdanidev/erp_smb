<html>

<head>
    <title>Modal Detail Transaction</title>
</head>

<body>
    <table aria-colspan="8" style="width: 75%;">
        <tr>
            <td align="left">
                <h5><b><u>Detail Transaksi
                            <?php echo $getTrans->getNo_trans(); ?>
                        </u></b>
                </h5>
            </td>

            <td align="right">
                <button class="btn btn-red" onclick="ClosedetailTrans()"><span
                        class="glyphicon glyphicon-eye-close"></span>
                    Close</button>
            </td>
        </tr>
    </table>
    <br>
    <table id="detailTrans" class="table" aria-colspan="8" style="width: 75%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Part</th>
                <th>Part</th>
                <th>Qty Stock</th>
                <th>Qty Restock</th>
                <th>Qty After Restock</th>
                <th>Status</th>
                <th>
                    <center><b>#</b></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $sts = $getTrans->getTrans_status();
                $stsPrint = "";
                $stsColor = "";
                $ttleProdukDtl = "";
                switch ($sts) {
                    case 0:
                        $stsPrint = "fa fa-clock-o";
                        $stsColor = "color:red;";
                        $$ttleProdukDtl = "Quantity On Process";
                        break;

                    case 1:
                        $stsPrint = "fa fa-check";
                        $stsColor = "color:green;";
                        $$ttleProdukDtl = "Quantity Success";
                        break;

                    case 2:
                        $stsPrint = "glyphicon glyphicon-remove-circle";
                        $stsColor = "color:red;";
                        $$ttleProdukDtl = "Quantity Cancelled";
                        break;
                }

                $mdl_product = new master_product();
                $ctrl_product = new master_productController($mdl_product, $this->dbh);

                $mdl_tr_log = new transaction_log();
                $ctrl_tr_log = new transaction_logController($mdl_tr_log, $this->dbh);

                $mdl_tr_stk = new master_stock();
                $ctrl_tr_stk = new master_stockController($mdl_tr_stk,$this->dbh);

                $number = 1;
                foreach ($showDetail as $valDetail) {
                    $getProduct = $ctrl_product->showDataByKode($valDetail->getKd_product());
                    $getTrLog = $ctrl_tr_log->showDataByTransIdSingle($getTrans->getId(), $valDetail->getKd_product());
                    $getDtlStok = $ctrl_tr_stk->showDtlTransIdSingle($getTrans->getId(), $valDetail->getKd_product());
                    ?>
                    <td>
                        <?php echo $number++; ?>
                    </td>
                    <td>
                        <?php echo $valDetail->getKd_product(); ?>
                    </td>
                    <td>
                        <?php echo $getProduct->getNm_product(); ?>
                    </td>
                    <td>
                        <?php echo $sts==0 || $sts==1?$getTrLog->getQty_before():$getDtlStok->getQty_stock(); ?>
                    </td>
                    <td>
                        <?php echo $valDetail->getQty(); ?>
                    </td>
                    <td><span style="<?php echo $stsColor; ?>">
                            <?php echo $sts==0 || $sts==1?$getTrLog->getQty_after():$getDtlStok->getQty_stock(); ?>
                        </span>

                    </td>
                    <td>
                        <span style="<?php echo $stsColor; ?>" title="<?php echo $ttleProdukDtl; ?>"
                            class="<?php echo $stsPrint; ?>"></span>
                    </td>
                    <td>
                        <?php if ($getTrans->getTrans_status() == 0) { ?>
                            <button type="button" class="btn btn-default" title="Edit Quantity" data-toggle="modal"
                                data-target="#QtyEdtRes<?php echo $valDetail->getId(); ?>"><span
                                    class="glyphicon glyphicon-edit"></span>
                                Edit</button>
                        <?php } ?>
                        <div class="modal fade" id="QtyEdtRes<?php echo $valDetail->getId(); ?>" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4>Edit Quantity</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <br>
                                        <p>
                                            <?php echo $valDetail->getKd_product() . "-" . $getProduct->getNm_product(); ?>
                                        </p>
                                    </div>
                                    <div class="modal-body">
                                        <form name="frmEdit" id="frmEdit" method="POST"
                                            action="index.php?model=transaction_detail&action=simpanEditRestock">
                                            <input type="hidden" name="idItem" id="idItem"
                                                value="<?php echo $valDetail->getId(); ?>" />
                                            <label for="exampleFormControlTextarea1">Quantity Restock</label>
                                            <input type="text" class="form-control" name="qtyEdtRes" id="qtyEdtRes"
                                                onkeypress="validate(event);" value="<?php echo $valDetail->getQty(); ?>" />
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary"><span
                                                        class="glyphicon glyphicon-ok"></span>
                                                    Simpan</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                                        class="glyphicon glyphicon-remove"></span> Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </td>
                </tr>
            <?php }

                ?>

        </tbody>
    </table>
</body>

</html>
<script language="javascript" type="text/javascript">
    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {
            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                $("#QtyEdtRes").hide();
                $('.modal-backdrop').hide();
                showMenu('content', 'index.php?model=transaction&action=showAllJQuery_restok');
            }
        });
    })();

    function ClosedetailTrans() {
        var area = document.getElementById('detTrans');
        area.style.display = "none";
    }

    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }

</script>