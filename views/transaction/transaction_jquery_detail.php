<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quantity Closing Online</title>
</head>

<body>
    <?php
    $mdl_trns_dtl = new transaction_detail();
    $ctrl_trans_detail = new transaction_detailController($mdl_trns_dtl, $this->dbh);

    $mdl_master_product = new master_product();
    $ctrl_master_product = new master_productController($mdl_master_product, $this->dbh);

    $mdl_master_stok = new master_stock();
    $ctrl_mst_stok = new master_stockController($mdl_master_stok, $this->dbh);

    ?>
    <form name="frmEdit" id="frmEdit" method="POST"
        action="index.php?model=transaction_detail&action=simpanEditRestock">
        <table class="table" border="1">
            <tr>
                <thead>

                    <input type="hidden" name="elementdiv" id="elementdiv" value="<?php echo $idElement; ?>">
                    <input type="hidden" name="idItem" id="idItem" value="<?php echo $id; ?>" />
                    <th colspan="2" class="text-left"><b>Edit Quantity</b></th>
                    <th class="text-right"><button type="button" class="close" aria-label="Close"
                            onclick="tutupFormEdit('<?php echo $idElement; ?>')"><span aria-hidden="true">&times;</span>
                            Close</button></th>
                </thead>
            </tr>
            <tr>
                <?php foreach ($data_detail as $value) {
                    $namePart = $ctrl_master_product->showDataByKode($value->getKd_product());
                    $ggetStok = $ctrl_mst_stok->showData($value->getKd_product());
                    ?>
                    <td colspan="2"><b>( <?php echo $value->getKd_product(); ?> )
                            <?php echo $namePart->getNm_product(); ?></b>
                    </td>
                    <input type="hidden" name="stoke" id="stoke" value="<?php echo $ggetStok->getQty_stock(); ?>">
                    <input type="hidden" name="qtyEdtRes" id="qtyEdtRes" value="<?php echo $value->getQty(); ?>">
                    <td><input type="text" class="form-control" name="qtyEdtResin" id="qtyEdtResin"
                            onkeypress="validate(event);" onblur="cekStok();" value="<?php echo $value->getQty(); ?>">
                    </td>
                <?php } ?>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-left"><button type="submit" class="btn btn-primary"><span
                            class="glyphicon glyphicon-ok"></span>
                        Simpan</button></td>

            </tr>
        </table>
    </form>
</body>

</html>
<script language="javascript" type="text/javascript">
    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }

    function cekStok() {
        var stoke = $('#stoke').val();
        var edite = $('#qtyEdtRes').val();
        var editeIn = document.getElementById('qtyEdtResin').value;
        // alert(stoke + '-' + editeIn);
        if (parseInt(editeIn) > parseInt(stoke)) {
            Swal.fire("Uppss!\nQuantity Edit Tidak Boleh Melebihi Stok Maksimal!");
            $('#qtyEdtResin').val(parseInt(stoke));
            return false;
        }
    }

    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {

            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                $("#modalTrfOff").hide();
                $('.modal-backdrop').hide();
                showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln');
            }
        });
    })();
</script>