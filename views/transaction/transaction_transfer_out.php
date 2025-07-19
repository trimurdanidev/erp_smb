<html>

<head>
    <title>Transaction Transfer Out</title>
</head>

<body>
    <?php

    $mdl_trans_log = new transaction_log();
    $ctrl_trans_log = new transaction_logController($mdl_trans_log, $this->dbh);

    $mdl_trans_detail = new transaction_detail();
    $ctrl_trans_detail = new transaction_detailController($mdl_trans_detail, $this->dbh);

    $mdl_mst_part = new master_product();
    $ctrl_mst_part = new master_productController($mdl_mst_part, $this->dbh);
    ?>
    <h2>Transfer Out</h2>
    <div id="header_list">
        <br>
        <div id="table_transfer_out" class="table-responsive">
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
                    $stsId = isset($_REQUEST["sts"]) ? $_REQUEST["sts"] : "";
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
                        <select name="sts" id="sts" class="form form-control" onchange="serchData()">
                            <option value="0,1" <?php echo $stsId == '0,1' ? "selected" : ""; ?>>All Status</option>
                            <option value="0" <?php echo $stsId == '0' ? "selected" : ""; ?>>On Process</option>
                            <option value="1" <?php echo $stsId == '1' ? "selected" : ""; ?>>Success</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="getFrom" id="getFrom" value="<?php echo $fromDate; ?>">
                        <input type="hidden" name="getTo" id="getTo" value="<?php echo $toDate; ?>">
                        <input type="hidden" name="getStatus" id="getStatus" value="<?php echo $stsId; ?>">
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
                            onclick="showMenu('header_list', 'index.php?model=transaction&action=showFormJQuery_transOff&type=9')"><span
                                class="glyphicon glyphicon-plus"></span> Tambah Data</button>
                    </td>
            </table>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table" width="95%">
                <tr>
                    <td align="left">
                        <img alt="Move First" src="./img/icon/icon_move_first.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out&search=<?php echo $search ?>');">
                        <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                        Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                        <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                        <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    </td>
                </tr>
            </table>
            <table class="table table-striped" style="width: 95%;">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">No Transaksi</th>
                        <th class="text-left">Deskrip Transaksi</th>
                        <th class="text-left">Keterangan</th>
                        <th class="text-left">Qty Total</th>
                        <th class="text-left">Sub Total</th>
                        <th class="text-left">Grand Total</th>
                        <th class="text-left">Status</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $no = 1;
                        $grandTotal = 0;
                        $qtyTotalAll = 0;
                        foreach ($transaction_list as $transfer_out) {
                            $sts = $transfer_out->getTrans_status();
                            $stsPrint = "";
                            $stsColor = "";
                            $ttleProdukDtl = "";
                            switch ($sts) {
                                case 0:
                                    $stsPrint = "fa fa-clock-o";
                                    $stsColor = "color:red;";
                                    $$ttleProdukDtl = "Quantity On Process";
                                    $statusBadge = "Pending";
                                    break;

                                case 1:
                                    $stsPrint = "fa fa-check";
                                    $stsColor = "color:green;";
                                    $$ttleProdukDtl = "Quantity Success";
                                    $statusBadge = "Success";
                                    break;

                                case 2:
                                    $stsPrint = "glyphicon glyphicon-remove-circle";
                                    $stsColor = "color:red;";
                                    $$ttleProdukDtl = "Quantity Cancelled";
                                    $statusBadge = "Cancelled";
                                    break;
                            }
                            $getTransLog = $ctrl_trans_log->sDataByTransId($transfer_out->getId());
                            $grandTotal += $transfer_out->getTrans_total() != null ? $transfer_out->getTrans_total() : 0;
                            $qtyTotalAll += $transfer_out->getQtyRelease() != null ? $transfer_out->getQtyRelease() : 0;
                            ?>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $transfer_out->getTanggal(); ?>
                            </td>
                            <td>
                                <?php echo $transfer_out->getNo_trans(); ?>
                            </td>
                            <td>
                                <?php echo "OUT TRANSFER OUT TGL." . $transfer_out->getTanggal() . " OLEH " . $transfer_out->getCreated_by(); ?>
                            </td>
                            <td>
                                <?php echo ''; ?>
                            </td>
                            <td>
                                <?php echo $transfer_out->getQtyRelease(); ?> Pcs
                            </td>
                            <td>
                                <?php echo number_format(floatVal($transfer_out->getTrans_total())); ?>
                            </td>
                            <td>
                                <?php echo number_format(floatVal($transfer_out->getTrans_total())); ?>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-primary"><?php echo $statusBadge; ?></span>
                            </td>
                            <td><button type="button" class="btn btn-default" title="Detail Transaksi" data-toggle="modal"
                                    data-target="#modalTrfOff<?php echo $transfer_out->getId() ?>"><span
                                        class="glyphicon glyphicon-eye-open"></span> Details</button>
                                <div class="modal fade" id="modalTrfOff<?php echo $transfer_out->getId() ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title fs-5" id="exampleModalLabel">Detail Transfer Out
                                                </h3>
                                            </div>
                                            <div class="modal-body">
                                                <h4><span class="glyphicon glyphicon-th-list"></span>
                                                    <?php echo $transfer_out->getNo_trans(); ?>
                                                </h4>
                                                <?php if ($transfer_out->getId() == null || $transfer_out->getId() == "") {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <td align="center"><b>Detail Transfer Out Tidak Tersedia</b></td>
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
                                                                        <th class="text-left"><span style="color:red;">Nama Part
                                                                                (Set Faktur)</span></th>
                                                                        <th class="text-left">Qty</th>
                                                                        <th class="text-left">Harga</th>
                                                                        <th class="text-left">Diskon (%)</th>
                                                                        <th class="text-left">Jumlah</th>
                                                                        <th class="text-center">#</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $showDtlTrans = $ctrl_trans_detail->showDataDtlArray($transfer_out->getId());
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
                                                                                <span style="<?php echo $stsColor; ?>"
                                                                                    title="<?php echo $ttleProdukDtl; ?>"
                                                                                    class="<?php echo $stsPrint; ?>"></span>
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
            <?php if ($transfer_out->getTrans_status() == '0') { ?>
                <button type="button" class="btn btn-green" title="Release Transaksi"
                    onclick="ReleaseTrans('<?php echo $transfer_out->getId(); ?>','<?php echo $transfer_out->getNo_trans(); ?>')"><span
                        class="glyphicon glyphicon-thumbs-up"></span> Confirm</button>
                <button type="button" class="btn btn-red" title="Cancel Transaksi"
                    onclick="CancelTrans('<?php echo $transfer_out->getId(); ?>','<?php echo $transfer_out->getNo_trans(); ?>')"><span
                        class="glyphicon glyphicon-remove"></span> Cancel</button>
            <?php } ?>
            </td>
            </tr>
            <?php
                        }
                        ?>
        <tr>
            <td colspan="5"><b>Total</b></td>
            <td colspan="2"><b>
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
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
         var sts = $('#sts option:selected').val();
        var param = {};

        param['sts'] = sts;
        param['dari'] = dari;
        param['sampai'] = sampai;

        Swal.fire({
            title: 'Searching...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });
        swal.showLoading();

        $.post('index.php?model=transaction&action=showAllJQuery_tf_out_by_data', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function resetFilter() {
        showMenu('content', 'index.php?model=transaction&action=showAllJquery_transfer_out');
    }

    function ReleaseTrans(id, notrans) {
        Swal.fire({
            title: "Are you sure Confirm Transfer Out No." + notrans + " ?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                site = "index.php?model=transaction&action=confirmRestock&id=" + id;
                target = "content";
                showMenu(target, site);
                Swal.fire({
                    title: "Success Confirm",
                    text: "Transaksi Transfer Out No." + notrans + " has been Confirmed.",
                    icon: "success"
                });
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Not Confirmed!",
                    text: "",
                    icon: "info"
                });
            }
        });
    }

    function CancelTrans(id, notrans) {
        Swal.fire({
            title: "Are you sure Cancel Transaksi Transfer Out No." + notrans + " ?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                site = "index.php?model=transaction&action=CancelTFO&id=" + id;
                target = "content";
                showMenu(target, site);
                Swal.fire({
                    title: "Cancelled!",
                    text: "Transaksi Transfer Out No." + notrans + " has been Cancelled.",
                    icon: "success"
                });
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Not Cancelled!",
                    text: "",
                    icon: "info"
                });
            }
        });
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