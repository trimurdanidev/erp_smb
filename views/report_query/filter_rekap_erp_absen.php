<script type="text/javascript">
    $(function () {
        $('#store_konsinyasi,#store_subchannel,#store_type,#typeoutlet,#brand').multiselect({
            allSelectedText: 'All',
            includeSelectAllOption: true
        }).multiselect('selectAll', false).multiselect('updateButtonText');

    });
    $(function () {
        $('#store_cabang').multiselect({
            allSelectedText: 'All',
            includeSelectAllOption: false
        });

    });
</script>
</div>
<script type="text/javascript">
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

        var parameter1 = $('#tahun').val();//tahun
        var parameter2 = $('#bulan').val();//bulan


        var id = <?php echo $id; ?>;
        var param = {};

        if ($.trim($('#bulan option:selected').val()) == '') {
            alert('Bulan harus dipilih !'); return false;
        }

        if ($.trim($('#tahun option:selected').val()) == '') {
            alert('tahun harus dipilih !'); return false;
        }

        param['parameter1'] = parameter1 + '';
        param['parameter2'] = parameter2 + '';

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

        var parameter1 = $('#tahun').val();//tahun
        var parameter2 = $('#bulan').val();//bulan

        var id = <?php echo $id; ?>;

        if ($.trim($('#bulan option:selected').val()) == '') {
            alert('Bulan harus dipilih !'); return false;
        }

        if ($.trim($('#tahun option:selected').val()) == '') {
            alert('tahun harus dipilih !'); return false;
        }

        var filename = '<?php echo $report_query->getReportname(); ?>';
        var page = "index.php?model=report_query&action=showExportTableExcell&id=" + id + "&parameter1=" + parameter1 + "&parameter2=" + parameter2 + '&filename=' + filename;

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
?>
<h1>
    <?php echo $report_query->getReportname() ?>
</h1>
<br>
<table>
    <tr>
        <td>Pilih Bulan</td>
        <td>:</td>
        <td>
            <select id="bulan" name="bulan" style="border-radius: 2px;padding: 5px;border: solid silver 1px;background: #fcfcfc;">
                <?php
                $bln['1'] = "Januari";
                $bln['2'] = "Februari";
                $bln['3'] = "Maret";
                $bln['4'] = "April";
                $bln['5'] = "Mei";
                $bln['6'] = "Juni";
                $bln['7'] = "Juli";
                $bln['8'] = "Agustus";
                $bln['9'] = "September";
                $bln['10'] = "Oktober";
                $bln['11'] = "November";
                $bln['12'] = "Desember";
                foreach ($bln as $x => $y) {
                    if ($x == date('m')) {
                        echo "<option selected value='" . $x . "'>" . $y . "</option>";
                    } else {
                        echo "<option value='" . $x . "'>" . $y . "</option>";
                    }
                }


                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Pilih Tahun</td>
        <td>:</td>
        <td>
            <select id="tahun" name="tahun" style="border-radius: 2px;padding: 5px;border: solid silver 1px;background: #fcfcfc;">
                <?php

                for ($i = (date('Y') - 1); $i <= date('Y') + 2; $i++) {
                    if ($i == date('Y')) {
                        echo "<option selected value='" . $i . "'>" . $i . "</option>";
                    } else {
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                    }
                }

                ?>
            </select>
        </td>
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