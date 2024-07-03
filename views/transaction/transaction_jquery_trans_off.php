<html>

<head>
    <title>Transaction Offline</title>
</head>

<body>
    <?php
    $mdl_pay_transfer = new master_pay_transfer();
    $ctrl_pay_transfer = new master_pay_transferController($mdl_pay_transfer, $this->dbh);
    $showPay_transfer = $ctrl_pay_transfer->showDataAll();
    ?>
    <h2>Transaction Jual Offline</h2>
    <!-- <div id="trans_off"> -->
    <form name="trans_j_off" id="trans_j_off" method="post"
        action="index.php?model=transaction&action=saveTransJualOff">
        <table border="1">
            <tr>
                <td>
                    <h5><b>I.Jual Part</b></h5>
                </td>
            </tr>
            <tr>
                <td class="textBold"><span class="fa fa-search"></span> Cari Part</td>
                <td><input type="text" nama="part" id='part' class="form form-control"
                        placeholder="Cari Kode Part, Nama Part"></td>
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
                <td class="textBold"><span class="glyphicon glyphicon-user"></span> Nama Pembeli</td>
                <td><input type="text" name="buyer_name" id="buyer_name" class="form form-control"
                        placeholder="Nama Pembeli"></td>
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
                <td class="textBold"><span class="glyphicon glyphicon-tags"></span> Metode Pembayaran</td>
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
                        <?php foreach ($showPay_transfer as $value) { ?>
                            <button type="button" class="btn btn-default" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"><img src="./img/icon/<?php echo $value->getImg() ?>"
                                    style="width:60px; height:50px;"></img></button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <button class="btn btn-default"><img src="./img/icon/btn-bri.png"
                                style="width:60px; height:50px;"></img></button>
                        <button class="btn btn-default"><img src="./img/icon/btn-mandiri.png"
                                style="width:60px; height:50px;"></img></button>
                        <button class="btn btn-default"><img src="./img/icon/btn-bni.png"
                                style="width:60px; height:50px;"></img><br></button><br><br>
                        <button class="btn btn-default"><img src="./img/icon/btn-gopay.png"
                                style="width:60px; height:50px;"></img></button>
                        <button class="btn btn-default"><img src="./img/icon/btn-ovo.png"
                                style="width:60px; height:50px;"></img></button> -->
                        <?php } ?>
                    </div>
                    <div id="detail_pay_qr_submit" style="display: none;">
                        <p>Disini Button Logo Bank Qris</p>
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
    <!-- </div> -->
    <br>
    <div id="table_trans_off">
        <table class="table">

        </table>
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
            // alert('Transfer');
            document.getElementById('detail_pay_tn_submit').style.display = "none";
            document.getElementById('detail_pay_tr_submit').style.display = "block";
            document.getElementById('detail_pay_qr_submit').style.display = "none";
        } else {
            // alert('QRIS');
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
            alert("Upps\nPart Belum Ada Yang Dipilih !!");
        } else {
            $.post('index.php?model=master_product&action=getPartDet', keys, function (data) {
                $('#detail_part_submit').html(data);
            });
        }

    }

    function hapusElemen(no) {
        $(no).remove();
        var gtotal = $('#gTotal').val()
        var btnHps = $('#' + no[0] + '_hpsDtl').val();

        // alert(gtotal+btnHps);
        // var i = 0 ;
        // $('.tbody tr').each(function () {
        //     jml = '#t' + ( i + 1 ) + '_ttl';
        // });

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


        var jum = 0; i = 0;
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
            // alert(bayar + "-" + gtotal);
        } else {
            alert("Uppss!\nUang Bayar Harus Lebih dari / Sama Dengan Total Belanja");
            $('#bayar').val('');
            kembalian.val('');
            sisa.val('');
            return false;
        }
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

    $(document).ready(function () {
        $("#part").tokenInput("index.php?model=master_product&action=searchProdmulti");
    });

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