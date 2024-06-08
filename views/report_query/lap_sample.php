<script type="text/javascript" >    

    function exportReportExcell(){
        var parameter1 = $('#tahun').val();
        var parameter2 = $('#bulan').val();
       
        var id=<?php echo $report_query->getId();?>;
        var fileName = "<?php echo $report_query->getReportname();?>"
       
        var page = "index.php?model=report_query&action=showExportTableExcell&id="+id+"&parameter1=" + parameter1+"&parameter2=" + parameter2 + "&filename=" + fileName;
        window.open(page);
    }
   
    function resultReport(){
        var parameter1 = $('#tahun').val();
        var parameter2 = $('#bulan').val();
       
       
        var id=<?php echo $report_query->getId();?>;
        var page = "index.php?model=report_query&action=showGenerateTable&id="+id+"&parameter1=" + parameter1+"&parameter2=" + parameter2;        
        showMenu("resultreport", page);
    }
</script>
<h1><?php echo $report_query->getReportname();?></h1>
<table>
   
    <tr>
        <td>Tahun</td>
        <td>:</td>
        <td>
            <input type="text" name="tahun" id="tahun" value="<?php echo date("Y");?>" size="10">
                                     
        </td>
    </tr>
    <tr>
        <td>Bulan</td>
        <td>:</td>
        <td>
            <input type="text" name="bulan" id="bulan" value="<?php echo date("m");?>" size="10">
                                     
        </td>
    </tr>
   
     
</table>

<p>
    <input type="button" class="btn BtnBlue" value="SUBMIT" onclick="resultReport()">
    <input type="button" class="btn BtnGreen" value="EXPORT EXCEL" onclick="exportReportExcell()">
</p>
<div id="resultreport"></div>
<br>
<br>