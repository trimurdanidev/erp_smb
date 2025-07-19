<html>
<title>Form Transaction Transfer Out</title>

<head>

</head>

<body>
    <?php
    $mdl_pay_transfer = new master_pay_transfer();
    $ctrl_pay_transfer = new master_pay_transferController($mdl_pay_transfer, $this->dbh);
    $showPay_transfer = $ctrl_pay_transfer->showData_groupByTransfer();

    $mdl_trans_log = new transaction_log();
    $ctrl_trans_log = new transaction_logController($mdl_trans_log, $this->dbh);

    $mdl_trans_buyer = new transaction_buyer();
    $ctrl_trans_buyer = new transaction_buyerController($mdl_trans_buyer, $this->dbh);
    ?>
    <div class="table-responsive">
        <table width="90%">
            <tr>
                <td align="right">
                    <button class="btn btn-green" type="button" onclick="back_off()" title="Kembali"> <span
                            class="glyphicon glyphicon-circle-arrow-left"></span> Kembali</button>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="table-responsive">

        <form name="trans_j_off" id="trans_j_off" method="post"
            action="index.php?model=transaction&action=saveTransferOut">
            <table class="table" border="1">
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-calendar"></span> Tanggal Transaksi </td>
                    <td>
                        <input type="date" class="form form-control" name="tanggal" id="tanggal" required="required"
                            title="Tanggal Transaksi">
                    </td>
                </tr>
                <tr>
                    <td class="textBold"><span class="fa fa-search"></span> Cari Part *</td>
                    <td><input type="text" nama="part" id='part' class="form form-control"
                            placeholder="Cari Kode Part, Nama Part" required /></td>
                    <td>
                        <button type="button" class="btn btn-red" id="btnTambah" onclick="tesalet()"><span
                                class="glyphicon glyphicon-ok"></span> Tampilkan</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="detail_part_submit">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-pushpin"></span> Keterangan Barang Keluar</td>
                    <td><textarea name="trans_descript" id="trans_descript" cols="30" rows="10" class="form form-control"
                            placeholder="Keterangan"></textarea>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="submit" class="btn btn-facebook"><span
                                class="glyphicon glyphicon-save"></span> Submit</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>
<script language="javascript" type="text/javascript">

    function tesalet() {
        var Part = new Array([$('#part').val()]);
        var areaPart = document.getElementById('detail_part_submit');
        const keys = {};
        keys['id'] = Part.toString();

        if (Part == null || Part == "") {
            Swal.fire("Upps\nPart Belum Ada Yang Dipilih !!");
        } else {
            $.post('index.php?model=master_product&action=getPartDet', keys, function (data) {
                $('#detail_part_submit').html(data);
            });
        }

    }

    function jshitung(id) {
        var x = $(id).attr('id').split('_');
        var price = $('#' + x[0] + '_price').val();
        var qtyBeli = $('#' + x[0] + '_qtyBeli').val();
        $('#' + x[0] + '_ttl').val('');

        if ($.trim($('#' + x[0] + '_qtyBeli').val()) == '' || $.trim($('#' + x[0] + '_price').val()) == '') {
            return false;
        } else {
            $('#' + x[0] + '_total').val(formatMoney(parseInt(price) * parseInt(qtyBeli)));
            $('#' + x[0] + '_ttl').val(parseInt(price) * parseInt(qtyBeli));
        }


        var jum = 0; i = 0; jml = 0;
        $('.tbody tr').each(function () {
            jml = '#t' + (i + 1) + '_ttl';
            if (price != '' || qtyBeli != '') {
                jum = parseInt(jum) + parseInt($(jml).val());
                // jum += parseInt($(jml).val());
                console.log(jml + parseInt($(jml).val()));
            }
            i++;
        });
        $('#gTotal').val(parseInt(jum));
        $('#totalnya').val(formatMoney(parseInt(jum)));
        $('#' + x[0] + '_edprice').val(formatMoney(parseInt(price)))
    }

    function hapusElemen(no, id) {
        var x = $(id).attr('id').split('_');
        var gtotal = $('#gTotal').val()
        var btnHps = $('#' + no[0] + '_hpsDtl').val();
        var subTtl = $('#' + x[0] + '_ttl').val();

        // console.log(subTtl);
        // console.log(gtotal);

        balik = gtotal - subTtl;
        // console.log(balik)
        $('#gTotal').val(parseInt(balik))
        $('#totalnya').val(formatMoney(parseInt(balik)));
        $(no).remove();

    }


    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }

    function formatMoney(amount, decimalCount = 0, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    }

    function priceReg(id, evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }

        var x = $(id).attr('id').split('_');
        var edValue = $('#' + x[0] + '_edprice').val();
        var setValue = $('#' + x[0] + '_price');

        setValue.val(edValue);


    }

    function back_off() {
        showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out');
    }

    $(document).ready(function () {
        $("#part").tokenInput("index.php?model=master_product&action=searchProdmulti");

    });

    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {
                if (confirm('Anda yakin save data ? ') == false) {
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
                showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out');
            }
        });
    })();
</script>
<style>
    .ac_results {
        background-color: #E8EAEB;
    }

    .ac_results ul li:hover {
        background-color: #D0D5D6;
    }
</style>
<br>
<br>