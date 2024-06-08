<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        alert($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=report_query&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
                }
             });
        })();
        function validate(evt){
            var e = evt || window.event;
            var key = e.keyCode || e.which;
            if((key <48 || key >57) && !(key ==8 || key ==9 || key ==13  || key ==37  || key ==39 || key ==46)  ){
                e.returnValue = false;
                if(e.preventDefault)e.preventDefault();
            }
        }
</script>

<br>


<form name="frmreport_query" id="frmreport_query" method="post" action="index.php?model=report_query&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text" style="text-align: right;background-color:silver;" onkeypress="validate(event);"  name="id" id="id" value="<?php echo $report_query_->getId();?>" size="11" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Report Name</td> 
            <td><input type="text"  name="reportname" id="reportname" value="<?php echo $report_query_->getReportname();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Header</td> 
            <td><input type="text"  name="header" id="header" value="<?php echo $report_query_->getHeader();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Query</td> 
            <td><textarea  name="query" id="query" cols="50" rows="7" ><?php echo $report_query_->getQuery();?></textarea></td>
        </tr>

        <tr> 
            <td class="textBold">is Crosstab</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="crosstab" id="crosstab" value="<?php echo $report_query_->getCrosstab();?>" size="11"   ></td>
        </tr>

        <tr> 
            <td class="textBold">is Total</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="total" id="total" value="<?php echo $report_query_->getTotal();?>" size="11"   ></td>
        </tr>

        <tr> 
            <td class="textBold">is Subtotal</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="subtotal" id="subtotal" value="<?php echo $report_query_->getSubtotal();?>" size="11"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Entrytime</td> 
            <td><?php echo $report_query_->getEntrytime();?></td>
        </tr>

        <tr> 
            <td class="textBold">Entryuser</td> 
            <td><?php echo $report_query_->getEntryuser();?></td>
        </tr>

        <tr> 
            <td class="textBold">Entryip</td> 
            <td><?php echo $report_query_->getEntryip();?></td>
        </tr>

        <tr> 
            <td class="textBold">Updatetime</td> 
            <td><?php echo $report_query_->getUpdatetime();?></td>
        </tr>

        <tr> 
            <td class="textBold">Updateuser</td> 
            <td><?php echo $report_query_->getUpdateuser();?></td>
        </tr>

        <tr> 
            <td class="textBold">Updateip</td> 
            <td><?php echo $report_query_->getUpdateip();?></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
