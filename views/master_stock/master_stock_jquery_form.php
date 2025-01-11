<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        alert($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=master_stock&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmmaster_stock" id="frmmaster_stock" method="post" action="index.php?model=master_stock&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Kd_product</td> 
            <td><input type="text"  name="kd_product" id="kd_product" value="<?php echo $master_stock_->getKd_product();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Qty_stock</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="qty_stock" id="qty_stock" value="<?php echo $master_stock_->getQty_stock();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Qty_stock_promo</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="qty_stock_promo" id="qty_stock_promo" value="<?php echo $master_stock_->getQty_stock_promo();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $master_stock_->getCreated_by();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $master_stock_->getUpdated_by();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $master_stock_->getCreated_at();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $master_stock_->getUpdated_at();?>" size="10"   ></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
