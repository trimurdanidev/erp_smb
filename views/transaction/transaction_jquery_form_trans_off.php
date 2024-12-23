<html>
<title>Form Transaction Offline</title>

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
            action="index.php?model=transaction&action=saveTransJualOff">
            <table class="table" border="1">
                <tr>
                    <td>
                        <h5><b>I.Jual Part</b></h5>
                    </td>
                </tr>
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
                                class="glyphicon glyphicon-ok"></span> Tambah</button>
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
                    <td colspan="3">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><b>II. Pembeli</b></h5>
                    </td>
                </tr>
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-user"></span> Nama Pembeli *</td>
                    <td><input type="text" name="buyer_name" id="buyer_name" class="form form-control"
                            placeholder="Nama Pembeli" required></td>
                </tr>
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-earphone"></span> Np Telp/Hp Pembeli</td>
                    <td><input type="text" name="buyer_phone" id="buyer_phone" onkeypress="validate(event);"
                            class="form form-control" placeholder="Nomer HP Pembeli"></td>
                </tr>
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-road"></span> Alamat</td>
                    <td><textarea name="buyer_address" id="buyer_address" cols="30" rows="10" class="form form-control"
                            placeholder="Alamat Pembeli"></textarea>
                </tr>
                <tr>
                    <td colspan="3">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><b>III. Pembayaran</b></h5>
                    </td>
                </tr>
                <tr>
                    <td class="textBold"><span class="glyphicon glyphicon-tags"></span> Metode Pembayaran *</td>
                    <td><select name="metod_pay" id="metod_pay" class="form form-control" onchange="jsMetod()" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="1">Tunai</option>
                            <option value="2">Transfer</option>
                            <!-- <option value="3">QRIS</option> -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="detail_pay_tn_submit" style="display: none;">
                            Uang Pembayaran :
                            <input type="hidden" name="bayarSet" id="bayarSet">
                            <input type="text" name="bayar" id="bayar" class="form form-control"
                                onkeypress="validate(event)" onblur="jsBayar(this)" placeholder="Masukkan Uang Bayar">
                            <script>
                                $(document).ready(function () {
                                    $('#bayar').formatCurrency({ symbol: '', roundToDecimalPlace: 0, });
                                    $('#bayar').blur(function () {
                                        $('#bayar').formatCurrency({ symbol: '', roundToDecimalPlace: 0, });
                                        $('#bayarSet').val($('#bayar').val().replace(/,/g, ''));
                                    });
                                });
                            </script>
                            Uang Kembalian :
                            <input type="hidden" name="sisa" id="sisa">
                            <input type="text" name="kembalian" id="kembalian" class="form form-control"
                                onkeypress="validate(event)" placeholder="Uang Kembalian" readonly>
                        </div>
                        <div id="detail_pay_tr_submit" style="display: none;">
                            <?php foreach ($showPay_transfer as $value) {
                                $showPayRek = $ctrl_pay_transfer->showDataByName($value->getTransfer());
                                $countRekBybank = $ctrl_pay_transfer->CountsByName($value->getTransfer());

                                ?>
                                <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#modalTrf<?php echo $value->getId() ?>"><img
                                        src="./img/icon/<?php echo $value->getImg() ?>"
                                        style="width:60px; height:50px;"></img></button>
                                <div class="modal fade" id="modalTrf<?php echo $value->getId() ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"><img
                                                        src="./img/icon/<?php echo $value->getImg() ?>"
                                                        style="width:100px; height:95px;"></img>
                                                </h1>
                                                <!-- <input type="text" name="paymenn" id="paymenn" value="<?php echo $value->getTransfer(); ?>"> -->
                                            </div>
                                            <div class="modal-body">
                                                <h4><span class="glyphicon glyphicon-th-list"></span> Transfer
                                                    <?php echo $value->getTransfer(); ?>
                                                </h4>
                                                <?php if ($value->getName_akun() == null && $value->getRek_akun() == null) {
                                                    ?>
                                                    <table class="table table-striped">
                                                        <td align="center"><b>Akun Rekening Pembayaran Belum Tersedia</b></td>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="row table-row">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left">No</th>
                                                                    <th class="text-left">A/n (Atas Nama)</th>
                                                                    <th class="text-left">No. Rekening</th>
                                                                    <th class="text-center">#</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $index = 1;
                                                                foreach ($showPayRek as $val_rek) {
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-left">
                                                                            <?php echo $index++; ?>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <b>
                                                                                <?php echo $val_rek->getName_akun(); ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <b>
                                                                                <?php echo $val_rek->getRek_akun(); ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <input type="button" class="btn btn-facebook"
                                                                                onclick="btnChoose('<?php echo $val_rek->getTransfer(); ?>','<?php echo $val_rek->getId(); ?>','<?php echo $val_rek->getName_akun(); ?>','<?php echo $val_rek->getRek_akun(); ?>')"
                                                                                value="Pilih" data-dismiss="modal" />
                                                                            <!-- <a href="#"
                                                                        onclick="btnChoose('<?php echo $val_rek->getId(); ?>')"><button
                                                                        class="btn btn-default"><span
                                                                        class="glyphicon glyphicon-ok"></span> Pilih
                                                                    </button></a> -->

                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div id="hasil_detail_pay_tr" style="display: none;">
                            <p id="printh_pay_tr" style="color: blue;"></p>
                            <input type="hidden" name="paymenn" id="paymenn" value="">
                            <input type="hidden" name="pay_akun" id="pay_akun" value="">
                        </div>
                    </td>
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
    function jsMetod() {
        var selectMetod = $('#metod_pay option:selected').val();
        // var areaTunai = document.getElementById('detail_pay_tn_submit');
        // var areaTransfer = document.getElementById('detail_pay_tr_submit');
        // var areaQris = document.getElementById('detail_pay_qr_submit');

        if (selectMetod == '1') {
            document.getElementById('detail_pay_tn_submit').style.display = "block";
            document.getElementById('detail_pay_tr_submit').style.display = "none";
            document.getElementById('detail_pay_qr_submit').style.display = "none";
        } else if (selectMetod == '2') {
            // Swal.fire('Transfer');
            document.getElementById('detail_pay_tn_submit').style.display = "none";
            document.getElementById('detail_pay_tr_submit').style.display = "block";
            document.getElementById('detail_pay_qr_submit').style.display = "none";
        } else {
            // Swal.fire('QRIS');
            document.getElementById('detail_pay_tn_submit').style.display = "none";
            document.getElementById('detail_pay_tr_submit').style.display = "none";
            document.getElementById('detail_pay_qr_submit').style.display = "block";
        }

    }

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

    function jsBayar(id) {
        var x = $(id).attr('id');
        var bayar = $('#bayar').val();
        var gtotal = $('#gTotal').val();
        var kembalian = $('#kembalian');
        var sisa = $('#sisa');

        if (parseInt(bayar) >= parseInt(gtotal)) {
            sisa.val(parseInt(bayar - gtotal));
            kembalian.val(formatMoney(parseInt(bayar - gtotal)));
            // Swal.fire(bayar + "-" + gtotal);
        } else {
            Swal.fire("Uppss!\nUang Bayar Harus Lebih dari / Sama Dengan Total Belanja");
            $('#bayar').val('');
            kembalian.val('');
            sisa.val('');
            return false;
        }
    }

    function btnChoose(bank, id, name, rekening) {
        var check_choose = document.getElementById('hasil_detail_pay_tr');
        var listPayment = document.getElementById('detail_pay_tr_submit');
        // Swal.fire("Berhasil Dipilih" + id);

        check_choose.style.display = 'block';
        document.getElementById('printh_pay_tr').innerHTML = bank + " - " + name + " - " + rekening;
        $('#paymenn').val(bank);
        $('#pay_akun').val(name + " - " + rekening);
        listPayment.style.display = 'none';

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
        showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_off');
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
                showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_off');
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