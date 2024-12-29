<html>

<head>
    <title>Faktur Penjualan-
        <?php echo $nofaktur; ?>
    </title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>
    <div id="document">
        <style>
            @media print {
                .container {
                    background: #FAFAFA;
                }

                body {
                    margin-top: 20px;
                    background: #eee;
                }

                .invoice {
                    background: #fff;
                    padding: 20px
                }

                .invoice-company {
                    font-size: 20px
                }

                .invoice-header {
                    margin: 0 -20px;
                    background: #f0f3f4;
                    padding: 20px
                }

                .invoice-date,
                .invoice-from,
                .invoice-to {
                    display: table-cell;
                    width: 1%
                }

                .invoice-from,
                .invoice-to {
                    padding-right: 20px
                }

                .invoice-date .date,
                .invoice-from strong,
                .invoice-to strong {
                    font-size: 16px;
                    font-weight: 600
                }

                .invoice-date {
                    text-align: right;
                    padding-left: 20px
                }

                .invoice-price {
                    background: #f0f3f4;
                    display: table;
                    width: 100%
                }

                .invoice-price .invoice-price-left,
                .invoice-price .invoice-price-right {
                    display: table-cell;
                    padding: 20px;
                    font-size: 20px;
                    font-weight: 600;
                    width: 75%;
                    position: relative;
                    vertical-align: middle
                }

                .invoice-price .invoice-price-left .sub-price {
                    display: table-cell;
                    vertical-align: middle;
                    padding: 0 20px
                }

                .invoice-price small {
                    font-size: 12px;
                    font-weight: 400;
                    display: block
                }

                .invoice-price .invoice-price-row {
                    display: table;
                    float: left
                }

                .invoice-price .invoice-price-right {
                    width: 25%;
                    background: #2d353c;
                    color: #fff;
                    font-size: 28px;
                    text-align: right;
                    vertical-align: bottom;
                    font-weight: 300
                }

                .invoice-price .invoice-price-right small {
                    display: block;
                    opacity: .6;
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    font-size: 12px
                }

                .invoice-footer {
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                    font-size: 10px
                }

                .invoice-note {
                    color: #999;
                    margin-top: 80px;
                    font-size: 85%
                }

                .invoice>div:not(.invoice-footer) {
                    margin-bottom: 20px
                }
            }


            .container {
                background: #FAFAFA;
            }

            body {
                margin-top: 20px;
                background: #eee;
            }

            .invoice {
                background: #fff;
                padding: 20px
            }

            .invoice-company {
                font-size: 20px
            }

            .invoice-header {
                margin: 0 -20px;
                background: #f0f3f4;
                padding: 20px
            }

            .invoice-date,
            .invoice-from,
            .invoice-to {
                display: table-cell;
                width: 1%
            }

            .invoice-from,
            .invoice-to {
                padding-right: 20px
            }

            .invoice-date .date,
            .invoice-from strong,
            .invoice-to strong {
                font-size: 16px;
                font-weight: 600
            }

            .invoice-date {
                text-align: right;
                padding-left: 20px
            }

            .invoice-price {
                background: #f0f3f4;
                display: table;
                width: 100%
            }

            .invoice-price .invoice-price-left,
            .invoice-price .invoice-price-right {
                display: table-cell;
                padding: 20px;
                font-size: 20px;
                font-weight: 600;
                width: 75%;
                position: relative;
                vertical-align: middle
            }

            .invoice-price .invoice-price-left .sub-price {
                display: table-cell;
                vertical-align: middle;
                padding: 0 20px
            }

            .invoice-price small {
                font-size: 12px;
                font-weight: 400;
                display: block
            }

            .invoice-price .invoice-price-row {
                display: table;
                float: left
            }

            .invoice-price .invoice-price-right {
                width: 25%;
                background: #2d353c;
                color: #fff;
                font-size: 28px;
                text-align: right;
                vertical-align: bottom;
                font-weight: 300
            }

            .invoice-price .invoice-price-right small {
                display: block;
                opacity: .6;
                position: absolute;
                top: 10px;
                left: 10px;
                font-size: 12px
            }

            .invoice-footer {
                border-top: 1px solid #ddd;
                padding-top: 10px;
                font-size: 10px
            }

            .invoice-note {
                color: #999;
                margin-top: 80px;
                font-size: 85%
            }

            .invoice>div:not(.invoice-footer) {
                margin-bottom: 20px
            }

            .btn.btn-white,
            .btn.btn-white.disabled,
            .btn.btn-white.disabled:focus,
            .btn.btn-white.disabled:hover,
            .btn.btn-white[disabled],
            .btn.btn-white[disabled]:focus,
            .btn.btn-white[disabled]:hover {
                color: #2d353c;
                background: #fff;
                border-color: #d9dfe3;
            }
        </style>

        <?php
        $mdl_part = new master_product();
        $ctrl_part = new master_productController($mdl_part, $this->dbh);
        ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
            integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <div class="container">
            <div class="col-md-12">
                <div class="invoice">
                    <input type="hidden" name="noGetFaktur" id="noGetFaktur" value="<?php echo $nofaktur; ?>">
                    <!-- begin invoice-company -->
                    <div class="invoice-company text-inverse f-w-600">
                        <span class="pull-right hidden-print">
                            <!-- <a href="index.php?model=transaction&action=toBase64&url=<?php echo 'http://localhost/erp_smb/index.php?model=transaction&action=preview_FakturOffline&id=93&notrans=OF191024-000028'; ?>"
                                    target="_blank" class="btn btn-sm btn-white m-b-10 p-l-5"><i
                                        class="fa fa-file t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</a> -->
                            <button id="printFak" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5"><i
                                    class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</button>
                            <button id="cetakPdf" class="btn btn-default" onclick="showPDF()" title="Cetak PDF"><i
                                    class="fa fa-file t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</button>
                        </span>
                        <table>
                            <!-- <tr>
                            <td rowspan="5"><img src="./images/SMB_logo_tengah.jpg" alt="" width="100" height="90"></td>
                        </tr> -->
                            <tr>
                                <td>
                                    <h3><b><u>FAKTUR PENJUALAN</u></b></h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <!-- Jl. H. DJain No.31, RT.007/RW.003, Jatikramat, Kec. Jatiasih, Kota Bks, Jawa Barat 17421 -->
                                </td>
                            </tr>
                        </table>

                    </div>
                    <!-- end invoice-company -->
                    <!-- begin invoice-header -->
                    <div class="invoice-header">
                        <div class="invoice-from">
                            <small>From :</small>
                            <address class="m-t-5 m-b-5">
                                <strong class="text-inverse">Sparepart Motor Bekasi</strong><br>
                                Jl. H. DJain No.31<br>
                                RT.007/RW.003, Jatikramat, Kec. Jatiasih<br>
                                Kota Bks, Jawa Barat 17421<br>
                                0898-9163-219
                            </address>
                        </div>
                        <div class="invoice-to">
                            <small>To :</small>
                            <address class="m-t-5 m-b-5">
                                Nama Pelanggan :<strong class="text-inverse">
                                    <?php echo $showBuyer->getBuyer_name() != "" ? $showBuyer->getBuyer_name() : "-"; ?>
                                </strong><br>
                                No .Telepon :
                                <?php echo $showBuyer->getBuyer_phone() != "" ? $showBuyer->getBuyer_phone() : "-"; ?><br>
                                Alamat :
                                <?php echo $showBuyer->getBuyer_address() != "" ? $showBuyer->getBuyer_address() : "-"; ?><br>
                            </address>
                        </div>
                        <div class="invoice-date">
                            <small>Detail Transaksi : </small>
                            <div class="date text-inverse m-t-5">No. Transaksi :
                                <?php echo $nofaktur; ?>
                            </div>
                            <div class="invoice-detail">
                                Tanggal :
                                <?php echo $showHeadTran->getTanggal(); ?><br>
                                User :
                                <?php echo $showHeadTran->getCreated_by(); ?><br>
                                User Akses :
                                <?php echo $showKarTrans->getGroupcode(); ?><br>
                            </div>
                        </div>
                    </div>
                    <!-- end invoice-header -->
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        <!-- begin table-responsive -->
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                    <tr>
                                        <th class="text-left">No</th>
                                        <th class="text-left">Kode Part</th>
                                        <th class="text-left">Nama Part</th>
                                        <th class="text-center" width="10%">Quantity</th>
                                        <th class="text-center" width="10%">Harga</th>
                                        <th class="text-center" width="20%">Diskon</th>
                                        <th class="text-center" width="20%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $no = 1;
                                        $subTotal = 0;
                                        foreach ($showDtlTrans as $val_faktur) {
                                            $showPart = $ctrl_part->showDataByKode($val_faktur->getKd_product());
                                            $subTotal += $val_faktur->getQty() * $val_faktur->getHarga();
                                            ?>

                                            <td class="text-left">
                                                <?php echo $no++; ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo $val_faktur->getKd_product(); ?>
                                            </td>
                                            <td class="text-inverse">
                                                <?php echo $val_faktur->getNm_product(); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $val_faktur->getQty(); ?> Pcs
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format(floatval($val_faktur->getHarga())); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format(floatval(0)); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format(floatval($val_faktur->getQty() * $val_faktur->getHarga())); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- begin invoice-price -->
                        <div class="invoice-price">
                            <div class="invoice-price-left">
                                <div class="invoice-price-row">
                                    <div class="sub-price">
                                        <small>SUBTOTAL</small>
                                        <span class="text-inverse">
                                            <?php echo number_format(floatval($subTotal)) ?>
                                        </span>
                                    </div>
                                    <!-- <div class="sub-price">
                                            <i class="fa fa-plus text-muted"></i>
                                        </div> -->
                                    <!-- <div class="sub-price">
                                            <small>PAJAK PPN (11%)</small>
                                            <span class="text-inverse">
                                                <?php echo floatval(0); ?>
                                            </span>
                                        </div> -->
                                </div>
                            </div>
                            <div class="invoice-price-right">
                                <small>TOTAL</small> <span class="f-w-600">Rp.
                                    <?php echo number_format(floatval($subTotal)) ?>
                                </span>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <!-- begin invoice-note -->
                    Pembayaran :
                    <?php echo $showTransPay->getMethod() == '1' ? 'Tunai' : "Transfer"; ?>
                    <b>
                        <?php echo $showTransPay->getPayment() != "" ? $showTransPay->getPayment() : ""; ?>
                    </b>
                    <b>
                        <?php echo $showTransPay->getPayment_akun() != "" ? "(" . $showTransPay->getPayment_akun() . ")" : ""; ?>
                    </b>
                    <div class="invoice-note">
                        <table>
                            <tr>
                                <td>
                                    Hormat Kami,<br><br><br><br>
                                    (
                                    <?php echo $showHeadTran->getCreated_by() ?> )<br>
                                </td>
                                <td></td>
                                <!-- <td></td> -->
                                <!-- <td>
                                Pelanggan,<br><br><br><br>
                                (  <?php echo $showBuyer->getBuyer_name() != "" ? $showBuyer->getBuyer_name() : "-" ?>  )
                            </td> -->
                            </tr>
                        </table>
                        <!-- * If you have any questions concerning this invoice, contact [Name, Phone Number, Email] -->
                    </div>
                    <!-- end invoice-note -->
                    <!-- begin invoice-footer -->
                    <div class="invoice-footer">
                        <p class="text-center m-b-5 f-w-600">
                            THANK YOU FOR YOUR ORDER
                        </p>
                        <p class="text-center">
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> erpsmb.cloud</span>
                            <!-- <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> :016-18192302</span> -->
                            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> smbpart@gmail.com</span>
                        </p>
                    </div>
                    <!-- end invoice-footer -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>


<script>
    async function showPDF() {
        var doc = new jsPDF();
        var noGetFaktur = document.getElementById('noGetFaktur').value;
        var printFak = document.getElementById('printFak');
        var cetakPdf = document.getElementById('cetakPdf');
        cetakPdf.style.display = "none";
        printFak.style.display = "none";
        await html2canvas(document.getElementById('document'), {
            // useCORS : true,
            // allowTaint : true,
            // width : 520
        }).then((canvas) => {
            doc.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 4, 210, 0);

        });
        doc.save(noGetFaktur + '.pdf');
    }
</script>