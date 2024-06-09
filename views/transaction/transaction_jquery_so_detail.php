<html>

<head>
    <title>Modal Detail Transaction</title>
</head>

<body>
    <table aria-colspan="8" style="width: 75%;">
        <tr>
            <td align="left">
                <h5><b><u>Detail Transaksi
                            <?php echo $getTrans->getNo_trans(); ?>
                        </u></b>
                </h5>
            </td>

            <td align="right">
                <button class="btn btn-red" onclick="ClosedetailTrans()"><span
                        class="glyphicon glyphicon-eye-close"></span>
                    Close</button>
            </td>
        </tr>
    </table>
    <br>
    <table id="detailTrans" class="table" aria-colspan="8" style="width: 75%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Part</th>
                <th>Part</th>
                <th>Qty Stock</th>
                <th>Qty SO</th>
                <th>Qty After SO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php

                $mdl_product = new master_product();
                $ctrl_product = new master_productController($mdl_product, $this->dbh);

                $mdl_tr_log = new transaction_log();
                $ctrl_tr_log = new transaction_logController($mdl_tr_log, $this->dbh);

                $number = 1;
                foreach ($showDetail as $valDetail) {
                    $getProduct = $ctrl_product->showDataByKode($valDetail->getKd_product());
                    $getTrLog = $ctrl_tr_log->showDataByTransId($valDetail->getId(),$valDetail->getKd_product());
                    ?>
                    <td>
                        <?php echo $number++; ?>
                    </td>
                    <td>
                        <?php echo $valDetail->getKd_product(); ?>
                    </td>
                    <td>
                        <?php echo $getProduct->getNm_product(); ?>
                    </td>
                    <td>
                        <?php echo $getTrLog->getQty_before(); ?>
                    </td>
                    <td>
                        <?php echo $valDetail->getQty(); ?>
                    </td>
                    <td>
                        <?php echo $getTrLog->getQty_after(); ?>
                    </td>
                    <td>
                        <span class="fa fa-check"></span>
                    </td>
                </tr>
            <?php }

                ?>

        </tbody>
    </table>
</body>

</html>
<script language="javascript" type="text/javascript">
    function ClosedetailTrans() {
        var area = document.getElementById('detTrans');
        area.style.display = "none";
    }
</script>