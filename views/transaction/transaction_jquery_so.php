<html>

<head>
    <title>Stock Opname</title>
</head>
<?php
$mdl_transaction = new transaction();
$ctrl_transaction = new transactionController($mdl_transaction, $this->dbh);

$mdl_trans_detail = new transaction_detail();
$ctrl_trans_dettail = new transaction_detailController($mdl_trans_detail, $this->dbh);
?>

<body>
    <h2>Transaction Stock Opname</h2>
    <div id="upload">

        <div class="table-responsive">
            <form name="frmUpload" id="frmUpload" action="index.php?model=transaction&action=saveUploadSo" method="POST"
                enctype="multipart/form-data">
                <table border="1" cellpadding="2" style="border-collapse: collapse;" width="50%">
                    <!-- <tr>
                <td><button class="btn btn-orange" onclick="downloadTemplate()"
                title="Download Format Template Excel SO"> <span class="glyphicon glyphicon-save"></span>
                Download Template</button>
                    </td>
                </tr> -->
                    <tr>
                        <td><a
                                href="index.php?model=transaction&action=export_stock_opname&search=<?php echo $search; ?>"><span
                                    class="glyphicon glyphicon-export"></span> Download Template & Stok Update</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="textBold">Upload Excel Stock Opname</td>
                        <td>
                            <input type="file" style="text-align: left;" class="form form-control" name="stock_so"
                                id="stock_so" size="10"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                title="Pilih Document Excel SO" required />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left"><button type="submit" name="submit" id="submit" value=""
                                class="btn btn-facebook" title="Proses Upload Excel SO"><span
                                    class="glyphicon glyphicon-upload"></span>
                                Proses</button>
                        </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>
    <br>
    <br>
    <div id="upload_log">
        <div class="table-responsive">
            <form id="myForm">
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
                                <option value="2" <?php echo $stsId == '2' ? "selected" : ""; ?>>Cancelled</option>
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
                </table>
            </form>
        </div>
        <br>
        <div class="table-responsive">
            <table width="95%">
                <tr>
                    <td align="left">
                        <img alt="Move First" src="./img/icon/icon_move_first.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so&search=<?php echo $search ?>');">
                        <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                        Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                        <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                        <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                            onclick="showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    </td>
                </tr>
            </table>
            <table class="table table-striped" style="width: 95%;">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">No Transaksi</th>
                        <th class="text-left">Deskripsi Upload</th>
                        <th class="text-left">Jumlah Data</th>
                        <th class="text-left">Qty Total</th>
                        <th class="text-left">Status</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $no = 1;
                        foreach ($transaction_list as $upload_list) {
                            $getTrans = $ctrl_transaction->showDataLogUpload($upload_list->getId());
                            $sts = "";
                            $stringsts = "";
                            if ($getTrans->getTrans_status() == '0') {
                                $sts = "fa fa-clock-o";
                                $stringsts = "Pending";
                            } else if ($getTrans->getTrans_status() == '1') {
                                $sts = "fa fa-check";
                                $stringsts = "Success";
                            } else if ($getTrans->getTrans_status() == '2') {
                                $sts = "fa fa-close";
                                $stringsts = "Cancelled";
                            }

                            ?>
                            <td>
                                <?php echo $no++; ?>
                            </td>
                            <td>
                                <?php echo $getTrans->getTanggal(); ?>
                            </td>
                            <td>
                                <?php echo $getTrans->getNo_trans(); ?>
                            </td>
                            <td>
                                <?php echo $upload_list->getTrans_descrip(); ?>
                            </td>
                            <td>
                                <?php echo $upload_list->getJumlah_data(); ?> Baris
                            </td>
                            <td>
                                <?php echo $getTrans->getQtyTotal(); ?> Pcs
                            </td>
                            <td><b><span class="<?php echo $sts; ?>" style="color: green;"></span>
                                    <?php echo $stringsts; ?>
                                </b></td>
                            <td><a href="#detTrans"><button type="button" class="btn btn-default" title="Detail Transaksi"
                                        onclick="detailTrans(<?php echo $getTrans->getId(); ?>)"><span
                                            class="glyphicon glyphicon-eye-open"></span> Details</button></a>
                                <?php if ($getTrans->getTrans_status() == '0') { ?>
                                    <button type="button" class="btn btn-green" title="Release Transaksi"
                                        onclick="ReleaseTrans('<?php echo $getTrans->getId(); ?>')"><span
                                            class="glyphicon glyphicon-thumbs-up"></span> Release</button>
                                    <button type="button" class="btn btn-orange" title="Cancel Transaksi"
                                        onclick="CancelSOTrans('<?php echo $getTrans->getId(); ?>')"><span
                                            class="glyphicon glyphicon-ban-circle"></span> Cancel</button>
                                <?php } ?>
                            </td>

                        </tr>
                        <?php
                        }
                        ?>
                </tbody>
            </table>
        </div>
        <div id="detTrans"></div>
</body>

</html>
<br>
<script language="javascript" type="text/javascript">
    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {

                if (confirm('Anda yakin save data ? ') == false) {
                    return false;
                }

                Swal.fire({
                    title: 'Uploading...',
                    html: 'Please wait...',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                });
                swal.showLoading();

                // this.carsSvc.getCars().then((cars: ICar[]) => {
                //     swal.close();
                //     this.setData(cars);
                //     // show a new success swal here?
                // });

                $('#submit').prop('disabled', true);
            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                // Swal.fire({
                //     title: "Upload Succes",
                //     text : "",
                //     icon : "success",
                // });
                showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
            }
        });
    })();
    function validate(evt) {
        var e = evt || window.event;
        var key = e.keyCode || e.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
            e.returnValue = false;
            if (e.preventDefault) e.preventDefault();
        }
    }

    function downloadTemplate() {
        window.open('./uploads/Template/FOMAT_STOCK_OPNAME.xls');
    }

    function ClosedetailTrans() {
        var area = document.getElementById('detTrans');
        area.style.display = "none";
    }

    function detailTrans(id) {
        var area = document.getElementById('detTrans');
        var tipe = 3;
        if (area.style.display = "block") {
            showMenu('detTrans', 'index.php?model=transaction&action=showDetailJQuery&id=' + id + '&tipe=' + tipe);
        }

    }

    function CancelSOTrans(id) {
        Swal.fire({
            title: "Are you sure Cancel Stock Opname?",
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
                    text: "Stock Opname has been Cancelled.",
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

    function ReleaseTrans(id) {
        Swal.fire({
            title: "Are you sure Release Stock Opname?",
            text: "",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                site = "index.php?model=transaction&action=confirmSO&id=" + id;
                target = "content";
                showMenu(target, site);
                Swal.fire({
                    title: "Released!",
                    text: "Stock Opname has been Released.",
                    icon: "success"
                });
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Not Released!",
                    text: "",
                    icon: "info"
                });
            }
        });
    }

    function serchData() {
        var sts = $('#sts option:selected').val();
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
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

        $.post('index.php?model=transaction&action=showAllJQuery_so_by_data', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function resetFilter() {
        showMenu('content', 'index.php?model=transaction&action=showAllJQuery_so');
    }
</script>