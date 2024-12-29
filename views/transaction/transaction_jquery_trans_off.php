<html>

<head>
    <title>Transaction Offline</title>
</head>

<body>
    <?php
    $mdl_pay_transfer = new master_pay_transfer();
    $ctrl_pay_transfer = new master_pay_transferController($mdl_pay_transfer, $this->dbh);
    $arr_payment = $ctrl_pay_transfer->showDataAll();

    $mdl_trans_log = new transaction_log();
    $ctrl_trans_log = new transaction_logController($mdl_trans_log, $this->dbh);

    $mdl_trans_buyer = new transaction_buyer();
    $ctrl_trans_buyer = new transaction_buyerController($mdl_trans_buyer, $this->dbh);

    $mdl_trans_pay = new transaction_payment();
    $ctrl_trans_pay = new transaction_paymentController($mdl_trans_pay, $this->dbh);

    $mdl_trans_detail = new transaction_detail();
    $ctrl_trans_detail = new transaction_detailController($mdl_trans_detail, $this->dbh);

    $mdl_mst_part = new master_product();
    $ctrl_mst_part = new master_productController($mdl_mst_part, $this->dbh);
    ?>
    <h2>Transaction Jual Offline</h2>
    <div id="header_list">
        <br>
        <div id="table_trans_off" class="table-responsive">
            <table class="table" border="0" width="80%">
                <!-- <tr>
                <td>
                        <span class="glyphicon glyphicon-search"></span>
                        Search Data
                    </td>
                </tr> -->
                <tr>
                    <?php
                    $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
                    $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
                    $paymn = isset($_REQUEST["payment"]) ? $_REQUEST["payment"] : "";
                    ?>
                    <td><span class="glyphicon glyphicon-search"></span></td>
                    <td>
                        <input type="date" name="dari" id="dari" size="5"
                            value="<?php echo $fromDate == date('Y-m-d') ? date('Y-m-d') : $fromDate; ?>"
                            class="form form-control" placeholder="from Date">
                    </td>
                    <td>S/D</td>
                    <td><input type="date" name="sampai" id="sampai" size="5"
                            value="<?php echo $toDate == date('Y-m-d') ? date('Y-m-d') : $toDate; ?>"
                            class="form form-control" placeholder="To Date" onchange="serchData()">
                    </td>
                    <td>
                        <select name="payment" id="payment" class="form form-control" onchange="serchData()">
                            <option value="">All Pembayaran</option>
                            <?php foreach ($arr_payment as $key => $value) { ?>
                                <option value="<?php echo $value->getTransfer(); ?>" <?php echo $value->getTransfer() == $paymn ? 'selected' : ''; ?>><?php echo $value->getTransfer(); ?>
                                </option>
                            <?php } ?>
                            <option value="Tunai">TUNAI</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="getFrom" id="getFrom" value="<?php echo $fromDate; ?>">
                        <input type="hidden" name="getTo" id="getTo" value="<?php echo $toDate; ?>">
                        <input type="hidden" name="getPayment" id="getPayment" value="<?php echo $paymn; ?>">
                    </td>
                    <td>
                        <button type="button" onclick="resetFilter()" class="btn btn-default"><span
                                class="glyphicon glyphicon-repeat"></span>
                            Reset
                        </button>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button type="button" class="btn btn-green"
                            onclick="showMenu('header_list', 'index.php?model=transaction&action=showFormJQuery_transOff&type=2')"><span
                                class="glyphicon glyphicon-plus"></span> Tambah Data</button>
                    </td>
            </table>
        </div>
        <br>
        <div class="table-responsive">

            <table class="table table-striped" style="width: 95%;">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">No Transaksi</th>
                        <th class="text-left">Deskrip Transaksi</th>
                        <th class="text-left">Pelanggan</th>
                        <th class="text-left">Qty Total</th>
                        <th class="text-left">Sub Total</th>
                        <th class="text-left">Diskon</th>
                        <th class="text-left">Pajak</th>
                        <th class="text-left">Total Penjualan</th>
                        <th class="text-left">Pembayaran</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $no = 1;
                        $grandTotal = 0;
                        $qtyTotalAll = 0;
                        foreach ($transaction_list as $trans_off) {
                            $getTransLog = $ctrl_trans_log->sDataByTransId($trans_off->getId());
                            $getBuyer = $ctrl_trans_buyer->showDataBytransId($trans_off->getId());
                            $getPaymen = $ctrl_trans_pay->showDataBytransId($trans_off->getId());
                            $getIconPayment = $ctrl_pay_transfer->showDataSingle($getPaymen->getPayment());
                            $grandTotal += $trans_off->getTrans_total() != null ? $trans_off->getTrans_total() : 0;
                            $qtyTotalAll += $trans_off->getQtyRelease() != null ? $trans_off->getQtyRelease() : 0;
                            ?>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $trans_off->getTanggal(); ?>
                            </td>
                            <td>
                                <?php echo $trans_off->getNo_trans(); ?>
                            </td>
                            <td>
                                <?php echo "OUT OFFLINE TGL." . $trans_off->getTanggal() . " OLEH " . $trans_off->getCreated_by(); ?>
                            </td>
                            <td>
                                <?php echo $getBuyer->getBuyer_name() != "" ? $getBuyer->getBuyer_name() : "-"; ?>
                            </td>
                            <td>
                                <?php echo $trans_off->getQtyRelease(); ?> Pcs
                            </td>
                            <td>
                                <?php echo number_format(floatVal($trans_off->getTrans_total())); ?>
                            </td>
                            <td>
                                <?php echo "0"; ?>
                            </td>
                            <td>
                                <?php echo "0"; ?>
                            </td>
                            <td>
                                <?php echo number_format(floatVal($trans_off->getTrans_total())); ?>
                            </td>
                            <td>
                                <?php if ($getPaymen->getPayment() != null) { ?>
                                    <img src="./img/icon/<?php echo $getPaymen->getPayment() == 'Tunai' ? 'ico_cash.png' : $getIconPayment->getImg(); ?>"
                                        style="width:80px; height:70px;">
                                <?php } else {
                                    "-";
                                } ?>
                            </td>
                            <td><button type="button" class="btn btn-default" title="Detail Transaksi" data-toggle="modal"
                                    data-target="#modalTrfOff<?php echo $trans_off->getId() ?>"><span
                                        class="glyphicon glyphicon-eye-open"></span> Details</button>
                                <div class="modal fade" id="modalTrfOff<?php echo $trans_off->getId() ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title fs-5" id="exampleModalLabel">Detail Penjualan
                                                    Offline
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <h4><span class="glyphicon glyphicon-th-list"></span>
                                                    <?php echo $trans_off->getNo_trans(); ?>
                                                </h4>
                                                <?php if ($trans_off->getId() == null || $trans_off->getId() == "") {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <td align="center"><b>Detail Penjualan Tidak Tersedia</b></td>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row table-row">
                                                        <div class="table-responsive">

                                                            <table class="table table-striped" width="50%">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-left">No</th>
                                                                        <th class="text-left">Kode Part</th>
                                                                        <th class="text-left">Part</th>
                                                                        <th class="text-left"><span style="color:red;">Nama Part (Set Faktur)</span></th>
                                                                        <th class="text-left">Qty</th>
                                                                        <th class="text-left">Harga</th>
                                                                        <th class="text-left">Diskon (%)</th>
                                                                        <th class="text-left">Jumlah</th>
                                                                        <th class="text-center">#</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $showDtlTrans = $ctrl_trans_detail->showDataDtlArray($trans_off->getId());
                                                                    $index = 1;
                                                                    $totalPnjl = 0;
                                                                    foreach ($showDtlTrans as $val_part) {
                                                                        $namePart = $ctrl_mst_part->showDataByKode($val_part->getKd_product());
                                                                        $totalPnjl += $val_part->getHarga() * $val_part->getQty();
                                                                        ?>
                                                                        <tr>
                                                                            <td class="text-left">
                                                                                <?php echo $index++; ?>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo $val_part->getKd_product() ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo $namePart->getNm_product(); ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo $val_part->getNm_product(); ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo $val_part->getQty() ?> Pcs
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo number_format(floatVal($val_part->getHarga())) ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo number_format(floatVal(0)) ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-left">
                                                                                <b>
                                                                                    <?php echo number_format(floatVal($val_part->getHarga() * $val_part->getQty())) ?>
                                                                                </b>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <span style="color:green"
                                                                                    class="fa fa-check"></span>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    } ?>
                                                                    <tr>
                                                                        <td colspan="7" class="text-left"><b>Total</b></td>
                                                                        <td class="text-center"><b>
                                                                                <?php echo number_format(floatVal($totalPnjl)); ?>
                                                                            </b></td>
                                                                            <td></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                                    class="glyphicon glyphicon-eye-close"></span> Close</button>
                                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                        </div>
                                    </div>
                                </div>
            </div>
            <button class="btn btn-primary"
                onclick="previewFaktur('<?php echo $trans_off->getId(); ?>','<?php echo $trans_off->getNo_trans(); ?>')"><span
                    class="glyphicon glyphicon-book"></span> Faktur</button>
            </td>
            </tr>
            <?php
                        }
                        ?>
        <tr>
            <td colspan="5"><b>Total</b></td>
            <td colspan="4"><b>
                    <?php echo $qtyTotalAll; ?> Pcs
                </b></td>
            <td colspan="3"><b>
                    <?php echo number_format(floatVal($grandTotal)); ?>
                </b></td>
        </tr>
        </tbody>
        </table>
    </div>
    </div>
</body>

</html>
<script language="javascript" type="text/javascript">
    function serchData() {
        var payment = $('#payment option:selected').val();
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        var param = {};

        param['payment'] = payment;
        param['dari'] = dari;
        param['sampai'] = sampai;

        $.post('index.php?model=transaction&action=showAllJQuery_trans_off_by_search', param, function (data) {
            $('#content').html(data);
        });
    }

    function resetFilter() {
        showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_off');
    }

    function previewFaktur(id, notrans) {
        window.open("index.php?model=transaction&action=preview_FakturOffline&id=" + id + "&notrans=" + notrans);
    }
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