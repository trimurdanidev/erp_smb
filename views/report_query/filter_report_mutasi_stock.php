<script type="text/javascript">

</script>
</div>
<script language="javascript" type="text/javascript">
    // $(function() {
    //     $('#tanggal1,#tanggal2').datepicker({
    //             altFormat: 'd/m/yy',
    //             dateFormat: 'yy-mm-dd',
    //             yearRange: '-20:+20',
    //             changeYear: true,
    //             changeMonth: true
    //     });


    // });
    function showMenu(position, page) {
        ajax_loadContent(position, page);
    }

    function getawaltime() {
        var a_p = "";
        var today = new Date();
        var curr_hour = today.getHours();
        var curr_minute = today.getMinutes();
        var curr_second = today.getSeconds();
        if (curr_hour < 12) {
            a_p = "AM";
        } else {
            a_p = "PM";
        }
        if (curr_hour == 0) {
            curr_hour = 12;
        }
        if (curr_hour > 12) {
            curr_hour = curr_hour - 12;
        }
        curr_hour = checkTime(curr_hour);
        curr_minute = checkTime(curr_minute);
        curr_second = checkTime(curr_second);
        $('#mulai').html('<b>Start: </b>' + curr_hour + ":" + curr_minute + ":" + curr_second + " ");
    }
    function getakhirtime() {
        var a_p = "";
        var today = new Date();
        var curr_hour = today.getHours();
        var curr_minute = today.getMinutes();
        var curr_second = today.getSeconds();
        if (curr_hour < 12) {
            a_p = "AM";
        } else {
            a_p = "PM";
        }
        if (curr_hour == 0) {
            curr_hour = 12;
        }
        if (curr_hour > 12) {
            curr_hour = curr_hour - 12;
        }
        curr_hour = checkTime(curr_hour);
        curr_minute = checkTime(curr_minute);
        curr_second = checkTime(curr_second);
        $('#akhir').html('<b>end</b> : ' + curr_hour + ":" + curr_minute + ":" + curr_second + " ");
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    function resultReportcsv() {
        var parameter1 = document.getElementById('company').value;
        var parameter2 = document.getElementById('branch').value;
        var parameter3 = document.getElementById('bulan').value;
        var parameter4 = document.getElementById('tahun').value;


        var id = <?php echo $id; ?>;
        var page = "index.php?model=report_query&action=showGenerateTable&id=" + id + "&parameter1=" + parameter1 + "&parameter2=" + parameter2 + "&parameter3=" + parameter3 + "&parameter4=" + parameter4;

        var l = 20;
        var t = 20;
        var w = 20;
        var h = 20;
        var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no, toolbox=no,resizable=no" + ",left=" + l + ",top=" + t + ",width=" + w + ",height=" + h;


        popup = window.open(page, "", windowprops);
    }


</script>
<script>
    function resultReport() {
        var Part = new $('#part').val();
        const keys = {};
        keys['id'] = Part.toString();



        var parameter1 = $('#tanggal1').val();//AWAL
        var parameter2 = $('#tanggal2').val();//AKHIR
        var parameter3 = Part.toString();//PART


        var id = <?php echo $id; ?>;
        var param = {};

        param['parameter1'] = parameter1 + '';
        param['parameter2'] = parameter2 + '';
        param['parameter3'] = parameter3 + '';

        param['id'] = id;
        $('#awal').html('');
        $('#akhir').html('');
        document.getElementById('btnresult').disabled = true;
        document.getElementById('btnresult').value = 'process...';
        $('#resultreport').html('<img src="./js/loading.gif">');
        getawaltime();
        $.post('index.php?model=report_query&action=showGenerateTable', param, function (data) {
            getakhirtime();
            document.getElementById('btnresult').disabled = false;
            document.getElementById('btnresult').value = 'SUBMIT';
            $('#resultreport').html(data);

        });

    }
    function resultReportexcel() {
        var hc = $('#havechild').val() + '~';
        var valhc = hc.replace(',~', '');

        var parameter1 = $('#tanggal1').val();
        var parameter2 = $('#tanggal2').val();
        var parameter3 = $('#part').val();


        var id = <?php echo $id; ?>;
        var filename = '<?php echo $report_query->getReportname(); ?>';
        // var page = "index.php?model=report_query&action=showExportTableExcell&id=" + id + "&parameter1=" + parameter1 + "&parameter2=" + parameter2 + "&parameter3=" + parameter3 + "&parameter4=" + parameter4 + "&parameter5=" + parameter5 + "&parameter6=" + parameter6 + "&parameter7=" + parameter7 + "&parameter8=" + parameter8 + "&parameter9=" + parameter9 + "&parameter10=" + parameter10 + "&parameter11=" + parameter11 + "&parameter12=" + parameter12 + "&parameter13=" + parameter13 + "&parameter14=" + parameter14 + '&filename=' + filename + '&parameter15=' + parameter15;
        var page = "index.php?model=report_query&action=showExportTableExcell&id=" + id + "&parameter1=" + parameter1 + "&parameter2=" + parameter2 + "&parameter3=" + parameter3 + '&filename=' + filename;

        var l = 20;
        var t = 20;
        var w = 20;
        var h = 20;
        var windowprops = "location=no,scrollbars=yes,menubars=no,toolbars=no, toolbox=no,resizable=no" + ",left=" + l + ",top=" + t + ",width=" + w + ",height=" + h;


        popup = window.open(page, "", windowprops);
    }

</script>
<?php
$bulan = isset($_REQUEST['bulan']);

$mdl_master_product = new master_product();
$ctrl_master_product = new master_productController($mdl_master_product, $this->dbh);
$shoqData = $ctrl_master_product->searchAutoSel();
?>
<h1>
    <?php echo $report_query->getReportname() ?>
</h1>
<br>
<table>
    <tr>
        <td>Pilih Tanggal Dari</td>
        <td>:</td>
        <td>
            <input type="date" id="tanggal1" name="tanggal1" size="10" value="<?php echo date('Y-m-d') ?>"
                class="form form-control">
        </td>
        <td>s/d</td>
        <td>:</td>
        <td>
            <input type="date" id="tanggal2" name="tanggal2" size="10" value="<?php echo date('Y-m-d') ?>"
                class="form form-control">
        </td>
    </tr>
    <tr>
        <td>Part</td>
        <td>:</td>
        <td><select name="part" id="part" class="from form-control" required>
                <option value="">Pilih Part</option>
                <?php foreach ($shoqData as $listProd) { ?>
                    <option value="<?php echo $listProd->getKd_product(); ?>"><?php echo $listProd->getNm_product(); ?>
                    </option>
                <?php } ?>
            </select></td>
    </tr>

</table>
<br>
<p><input type="button" class="btn BtnBlue" value="SUBMIT" id="btnresult" onclick="resultReport()">
    <input type="button" class="btn BtnOrange" value="Excel" onclick="resultReportexcel()">
</p>
<div id="mulai"></div>
<div id="akhir"></div>

<div class='cleaner'></div>

<br>

<div id="resultreport" style="overflow: auto; width: 95%;height: 30%;"></div>

<br>
<br>