<script>
    function jvpilih(x){
        $('#<?php echo $idnya;?>').val($('#idnya'+x).val());
        $('#<?php echo $nilainya;?>').val($('#nilainya'+x).val());
        
        $('#dialog').dialog("close");
        
    }
</script>
<script>

jvSearch();
function jvGoto(x){
    var sk=$('#pagenow').val().split("/");
    if(x=='first'){
        $('#pagenow').val('1/'+sk[1]);
    }
    if(x=='prev'){
        if(sk[0]!=1){
            var f=parseInt(sk[0])-1;
        }        
        else{
            var f=parseInt(sk[0]);
        }
        $('#pagenow').val(f+'/'+sk[1]);
    }
    if(x=='next'){
        if(sk[0]!=sk[1]){
            var f=parseInt(sk[0])+1;
        }        
        else{
            var f=parseInt(sk[0]);
        }
        $('#pagenow').val(f+'/'+sk[1]);
    }
    if(x=='last'){
        $('#pagenow').val(sk[1]+'/'+sk[1]);
    }
    jvSearch();
}
function jvSearch(){
    var param={};
    param['search']=$('#txtKeyword').val();
    param['limit']=$('#limit option:selected').val();
    var sk=$('#pagenow').val().split("/");
    param['skip']=sk[0];    
    $('#tlist-load').css('display','');
    $('#listmodal').css('display','none');
    $.post('index.php?model=<?php echo $modul?>&action=search<?php echo $modul?>&modul=<?php echo $modul?>',param,function(data){
        $('#tlist-load').css('display','none');
        $('#listmodal').css('display','');
        var arrHasil = JSON.parse(data);

        $('#listmodal').html(arrHasil.list);
        $('.jumlahdata').html('Total data : '+arrHasil.num);
        $('#pagenow').val(arrHasil.pagenow+'/'+arrHasil.jumpage);
        $('#pagenow').css('text-align','center');

    });
}
$(function() {   
    
    $("#btnCariButton").click(function(){
        jvSearch();
    });
    $("#btnRefresh").click(function(){
        jvSearch();
    });
    $("#limit").change(function(){
        jvSearch();
    });
    $("#btnCari").click(function(){
        if($('#divSearch').attr('class')=='hidden'){
            $('#divSearch').attr('class','show');
        }else{
            $('#divSearch').attr('class','hidden');
        }
        
    });
});


</script>
<style>
   
   #tlist tbody tr:hover td { background: pink; }
</style>
<div id="divContainer">
        <a href="javascript::" style="float:left; margin: 5px;" id="btnRefresh" class="tombol ui-state-default ui-corner-all">
            <span class="ui-icon ui-icon-refresh"></span></a>&nbsp;
        &nbsp;
        <div style="float:left;" id="divSearch" class="show">    
            <table cellpadding="3">        
                <tbody>
                    <tr valign="middle">            
                        <td>Keyword</td>            
                        <td>                
                            <input id="txtKeyword" style="width:200px" class="required text ui-widget-content ui-corner-all" type="text">                
                            
                            <a href="javascript::" id="btnCariButton" class="tombol ui-state-default ui-corner-all" >
                                Go</a>            
                        </td>
                    </tr>     
                </tbody>
            </table>
        </div>
        <span id="listmodal">
        </span>
        <div id="tlist-load"  style="display: none;">
            <img src="./js/loading.gif">
        </div>
        <div id="tlist-pager" class="pager">    
            <form>     
                <img src="./images/navigate_left2-red.png" class="first" page="first" onclick="jvGoto('first');">      
                <img src="./images/navigate_left-red.png" class="prev" page="prev" onclick="jvGoto('prev');">        
                <input style="text-align: center;"  id="pagenow" style="width:100px;" readonly="readonly" class="pagedisplay text ui-widget-content ui-corner-all" type="text">        
                <img src="./images/navigate_right-red.png" class="next" page="next" onclick="jvGoto('next');">
                <img src="./images/navigate_right2-red.png" class="last" page="last"  onclick="jvGoto('last');">        
                &nbsp;&nbsp;        
                <select id="limit" class="pagesize select ui-widget-content ui-corner-all">            
                    <option value="5">5</option>       
                <?php
                for($i=10;$i<=100;$i+=10){
                    if($i==5){
                        echo '<option selected value="'.$i.'">'.$i.'</option>     ';
                    }else{
                        echo '<option value="'.$i.'">'.$i.'</option>     ';
                    }
                }
                
                ?>
                    
                </select>    
                <span class="jumlahdata">Total data : </span>
            </form>
        </div>
        <p id="data">&nbsp;</p>
</div>

            