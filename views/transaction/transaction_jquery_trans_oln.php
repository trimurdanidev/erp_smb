<html>

<head>
    <title>Transaction Online</title>
</head>

<body>
    <?php
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

    $mdl_mst_stok = new master_stock();
    $ctrl_mst_stok = new master_stockController($mdl_mst_stok, $this->dbh);

    $mdl_uplod_tr = new upload_trans_log();
    $ctrl_uplod_tr = new upload_trans_logController($mdl_uplod_tr, $this->dbh);
    ?>

    <h2>Transaction Jual Online</h2>
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
                    $mktpc = isset($_REQUEST["mktplc"]) ? $_REQUEST["mktplc"] : "";
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
                        <select name="mktplc" id="mktplc" class="form form-control" onchange="serchData()">
                            <option value="">All Marketplace</option>
                            <option value="Shopee" <?php echo $mktpc == "Shopee" ? 'selected' : ''; ?>> Shopee
                            </option>
                            <option value="Lazada" <?php echo $mktpc == "Lazada" ? 'selected' : ''; ?>> Lazada
                            </option>
                            <option value="Tokopedia" <?php echo $mktpc == "Tokopedia" ? 'selected' : ''; ?>>
                                Tokopedia</option>
                            <option value="TikTok Shop" <?php echo $mktpc == "TikTok Shop" ? 'selected' : ''; ?>>
                                TikTok Shop</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="getFrom" id="getFrom" value="<?php echo $fromDate; ?>">
                        <input type="hidden" name="getTo" id="getTo" value="<?php echo $toDate; ?>">
                        <input type="hidden" name="getPayment" id="getPayment" value="<?php echo $mktpc; ?>">
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
                            onclick="showMenu('header_list', 'index.php?model=transaction&action=showFormJQuery_transOff&type=1')"><span
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
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln&search=<?php echo $search ?>');">
                        <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                        Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                        <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                        <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
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
                        <th class="text-left">Sub Total</th>
                        <th class="text-left">Sub Total Release</th>
                        <th class="text-left">Diskon</th>
                        <th class="text-left">Pajak</th>
                        <th class="text-left">Total Penjualan</th>
                        <th class="text-left">Marketplace</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $no = 1;
                        $grandTotal = 0;
                        $qtyTotalAll = 0;
                        $qtyTotalAllRelease = 0;
                        foreach ($transaction_list as $trans_onn) {
                            $getTransLog = $ctrl_trans_log->sDataByTransId($trans_onn->getId());
                            $qtyTotalAll += $trans_onn->getQtyTotal() != null ? $trans_onn->getQtyTotal() : 0;
                            $qtyTotalAllRelease += $trans_onn->getQtyRelease() != null ? $trans_onn->getQtyRelease() : 0;
                            $sumTransDetail = $ctrl_trans_detail->getSumOnlineTrans($trans_onn->getId());
                            $totalan_qty = 0;
                            $totalan_hg = 0;
                            $setColor = $trans_onn->getTrans_status();
                            foreach ($sumTransDetail as $row) {
                                // echo $row->getTrans_descript() . "-" . $row->getHarga() . "-" . $row->getQty() . "<br>";
                                $totalan_qty += $row->getQty();
                                $totalan_hg += ($row->getHarga() * $row->getQty());
                            }
                            $grandTotal += $totalan_hg != null ? $totalan_hg : 0;
                            ?>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo $no++; ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo $trans_onn->getTanggal(); ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo $trans_onn->getNo_trans(); ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo "OUT ONLINE TGL." . $trans_onn->getTanggal() . " OLEH " . $trans_onn->getCreated_by(); ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo $trans_onn->getQtyTotal(); ?> Pcs
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo $trans_onn->getQtyRelease(); ?> Pcs
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo "0"; ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo "0"; ?>
                            </td>
                            <td style="<?php echo $setColor == 2 ? "color:red" : '' ?>">
                                <?php echo number_format(floatVal($totalan_hg)); ?>
                            </td>
                            <td>
                                <?php if ($row->getTrans_descript() != null) { ?>
                                    <img src="./img/icon/ico_<?php echo $row->getTrans_descript(); ?>.png"
                                        style="width:80px; height:70px;">
                                <?php } ?>
                            </td>
                            <td><button type="button" class="btn btn-default" title="Detail Transaksi" data-toggle="modal"
                                    data-target="#modalTrfOff<?php echo $trans_onn->getId() ?>"><span
                                        class="glyphicon glyphicon-eye-open"></span> Details</button>
                                <div class="modal fade" id="modalTrfOff<?php echo $trans_onn->getId() ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title fs-5" id="exampleModalLabel">Detail Penjualan
                                                    Online
                                                </h3>
                                                <?php ?>
                                                <?php if ($setColor == 2): ?>
                                                    <span class="badge badge-danger">Cancel</span>
                                                <?php elseif ($setColor == 1): ?>
                                                    <span class="badge badge-success">Success</span>
                                                <?php else:?>    
                                                <?php endif;?>
                                            </div>
                                            <div class="modal-body">
                                                <h4><span class="glyphicon glyphicon-th-list"></span>
                                                    <?php echo $trans_onn->getNo_trans(); ?>

                                                </h4>
                                                <?php if ($trans_onn->getId() == null || $trans_onn->getId() == "") {
                                                    ?>
                                                    <div class="table-responsive">

                                                        <table class="table table-striped">
                                                            <td align="center"><b>Detail Penjualan Tidak Tersedia</b>
                                                            </td>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row table-row">
                                                        <!-- <div class="table-responsive"> -->

                                                        <table class="table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left">No</th>
                                                                    <th class="text-left">Kode Part</th>
                                                                    <th class="text-left">Part</th>
                                                                    <?php if ($trans_onn->getTrans_status() == '0') { ?>
                                                                        <th class="text-left">Stok</th>
                                                                    <?php } ?>
                                                                    <th class="text-left">Qty</th>
                                                                    <th class="text-left">Harga</th>
                                                                    <th class="text-left">Diskon (%)</th>
                                                                    <th class="text-left">Jumlah</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">#</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $showDtlTrans = $ctrl_trans_detail->showDataDtlArray($trans_onn->getId());
                                                                $index = 1;
                                                                $totalPnjl = 0;
                                                                foreach ($showDtlTrans as $val_part) {
                                                                    $namePart = $ctrl_mst_part->showDataByKode($val_part->getKd_product());
                                                                    $totalPnjl += $val_part->getHarga() * $val_part->getQty();
                                                                    $ggetStok = $ctrl_mst_stok->showData($val_part->getKd_product());
                                                                    $sts = "";
                                                                    $stringsts = "";
                                                                    $clrsts = "";
                                                                    if ($trans_onn->getTrans_status() == '0') {
                                                                        $sts = "fa fa-clock-o";
                                                                        $stringsts = "Pending";
                                                                        $clrsts = "color:red;";
                                                                    } else if ($trans_onn->getTrans_status() == '1') {
                                                                        $sts = "fa fa-check";
                                                                        $stringsts = "Success";
                                                                        $clrsts = "color:green;";
                                                                    } else if ($trans_onn->getTrans_status() == '2') {
                                                                        $sts = "fa fa-close";
                                                                        $stringsts = "Cancelled";
                                                                        $clrsts = "color:red;";
                                                                    }

                                                                    if ($trans_onn->getTrans_status() == '0' && $ggetStok->getQty_stock() < $val_part->getQty()) {
                                                                        $colorrows = "color:red;";
                                                                        $setColspan = 7;
                                                                    } else {
                                                                        $colorrows = "";
                                                                        $setColspan = 6;
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <?php echo $index++; ?>
                                                                        </td>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo $val_part->getKd_product() ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo $namePart->getNm_product(); ?>
                                                                            </b>
                                                                        </td>
                                                                        <?php if ($trans_onn->getTrans_status() == '0') { ?>
                                                                            <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                                <b>
                                                                                    <?php echo $ggetStok->getQty_stock(); ?> Pcs
                                                                                </b>
                                                                            </td>
                                                                        <?php } ?>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo $val_part->getQty() ?> Pcs
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo number_format(floatVal($val_part->getHarga())) ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo number_format(floatVal(0)) ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-left" style="<?php echo $colorrows; ?>">
                                                                            <b>
                                                                                <?php echo number_format(floatVal($val_part->getHarga() * $val_part->getQty())) ?>
                                                                            </b>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span style="<?php echo $clrsts; ?>"
                                                                                class="<?php echo $sts; ?>"
                                                                                title="<?php echo $stringsts; ?>"></span>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($trans_onn->getTrans_status() == '0') { ?>
                                                                                <button class="btn btn-default"
                                                                                    title="Edit Quantity Detail Out Online"
                                                                                    data-toggle="modal"
                                                                                    data-target="#QtyEdtOln<?php echo $val_part->getId(); ?>"><span
                                                                                        class="glyphicon glyphicon-edit"></span>
                                                                                    Edit</button>
                                                                            <?php } ?>
                                                                            <div class="modal fade"
                                                                                id="QtyEdtOln<?php echo $val_part->getId(); ?>"
                                                                                tabindex="-1" aria-labelledby="exampleModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4>Edit Quantity</h4>
                                                                                            <button type="button" class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="Close">
                                                                                                <span
                                                                                                    aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                            <br>
                                                                                            <p>
                                                                                                <?php echo $val_part->getKd_product() . "-" . $namePart->getNm_product(); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form name="frmEdit" id="frmEdit"
                                                                                                method="POST"
                                                                                                action="index.php?model=transaction_detail&action=simpanEditRestock">
                                                                                                <input type="hidden" name="idItem"
                                                                                                    id="idItem"
                                                                                                    value="<?php echo $val_part->getId(); ?>" />
                                                                                                <label
                                                                                                    for="exampleFormControlTextarea1">Quantity
                                                                                                    Online</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    name="qtyEdtRes" id="qtyEdtRes"
                                                                                                    onkeypress="validate(event);"
                                                                                                    value="<?php echo $val_part->getQty(); ?>" />
                                                                                                <div class="modal-footer">
                                                                                                    <button type="submit"
                                                                                                        class="btn btn-primary"><span
                                                                                                            class="glyphicon glyphicon-ok"></span>
                                                                                                        Simpan</button>
                                                                                                    <button type="button"
                                                                                                        class="btn btn-secondary"
                                                                                                        data-dismiss="modal"><span
                                                                                                            class="glyphicon glyphicon-remove"></span>
                                                                                                        Batal</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                } ?>
                                                                <tr>
                                                                    <td colspan="<?php echo $setColspan; ?>" class="text-left">
                                                                        <b>Total</b>
                                                                    </td>
                                                                    <td class="text-center"><b>
                                                                            <?php echo number_format(floatVal($totalPnjl)); ?>
                                                                        </b></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!-- </div> -->
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                                    class="glyphicon glyphicon-eye-close"></span> Close</button>
                                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                        </div>
                                    </div>
                                </div>
                                <?php if ($trans_onn->getTrans_status() == '0') { ?>
                                    <button type="button" class="btn btn-green" title="Release Transaksi"
                                        onclick="ReleaseTrans('<?php echo $trans_onn->getId(); ?>','<?php echo $trans_onn->getNo_trans(); ?>')"><span
                                            class="glyphicon glyphicon-thumbs-up"></span> Confirm</button>
                                    <button type="button" class="btn btn-red" title="Cancel Transaksi"
                                        onclick="CancelTrans('<?php echo $trans_onn->getId(); ?>','<?php echo $trans_onn->getNo_trans(); ?>')"><span
                                            class="glyphicon glyphicon-remove"></span> Cancel</button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    <tr>
                        <td colspan="4"><b>Total</b></td>
                        <td colspan="1"><b>
                                <?php echo $qtyTotalAll; ?> Pcs
                            </b></td>
                        <td colspan="1"><b>
                                <?php echo $qtyTotalAllRelease; ?> Pcs
                            </b>
                        </td>
                        <td colspan="1"><b>
                                <?php echo '0'; ?>
                            </b>
                        </td>
                        <td colspan="1"><b>
                                <?php echo '0'; ?>
                            </b>
                        </td>
                        </td>
                        <td colspan="3"><b>
                                <?php echo number_format(floatVal($grandTotal)); ?>
                            </b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
<script language="javascript" type="text/javascript">
    function serchData() {
        var mktplc = $('#mktplc option:selected').val();
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        var param = {};

        param['mktplc'] = mktplc;
        param['dari'] = dari;
        param['sampai'] = sampai;

        Swal.fire({
            title: 'Searching...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });
        swal.showLoading();

        $.post('index.php?model=transaction&action=showAllJQuery_trans_onn_by_search', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function resetFilter() {
        showMenu('content', 'index.php?model=transaction&action=showAllJQuery_trans_onln');
    }

    function ReleaseTrans(id, notrans) {
        Swal.fire({
            title: "Are you sure Confirm Closing Online No." + notrans + " ?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                site = "index.php?model=transaction&action=confirmOnline&id=" + id;
                target = "content";
                showMenu(target, site);
                // Swal.fire({
                //     title: "Success Confirm",
                //     text: "Transaksi Online No." + notrans + " has been Confirmed.",
                //     icon: "success"
                // });
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
            title: "Are you sure Cancel Transaksi Online No." + notrans + " ?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                site = "index.php?model=transaction&action=CancelSO&id=" + id;
                target = "content";
                showMenu(target, site);
                Swal.fire({
                    title: "Cancelled!",
                    text: "Transaksi Online No." + notrans + " has been Cancelled.",
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

    function editQtyOnline (){
        site = "index.php?model=transaction_detail&action=deleteFormJQuery" + id;
        target = "content";
        showMenu(target, site);
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