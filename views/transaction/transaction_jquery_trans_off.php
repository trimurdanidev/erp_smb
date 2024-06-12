<html>

<head>
    <title>Transaction Offline</title>
</head>

<body>
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
                    <td><button class="btn btn-facebook" onclick="tesalet()"><span
                                class="glyphicon glyphicon-ok"></span> Tambah</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="detail_part_submit"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        _______________________________________________________________________________________
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
                        _______________________________________________________________________________________
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
                            <option value="3">QRIS</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="detail_pay_tn_submit" style="display: none;">
                            Uang Pembayaran :
                            <input type="text" name="bayar" id="bayar" class="form form-control"
                                onkeypress="validate(event)" placeholder="Masukkan Uang Bayar">
                            Uang Kembalian :
                            <input type="text" name="kembalian" id="kembalian" class="form form-control"
                                onkeypress="validate(event)" placeholder="Uang Kembalian" readonly>
                        </div>
                        <div id="detail_pay_tr_submit" style="display: none;">
                            <p>Disini Button Logo Bank</p>
                        </div>
                        <div id="detail_pay_qr_submit" style="display: none;">
                            <p>Disini Button Logo Bank Qris</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="submit" class="btn btn-facebook"><span class="glyphicon glyphicon-save"></span> Submit</button>
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
        alert('Berhasil');
    }

</script>
<br>
<br>