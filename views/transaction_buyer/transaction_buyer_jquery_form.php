<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        Swal.fire($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=transaction_buyer&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmtransaction_buyer" id="frmtransaction_buyer" method="post" action="index.php?model=transaction_buyer&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="id" id="id" value="<?php echo $transaction_buyer_->getId();?>" size="16" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Trans_id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="trans_id" id="trans_id" value="<?php echo $transaction_buyer_->getTrans_id();?>" size="16"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Buyer_name</td> 
            <td><input type="text"  name="buyer_name" id="buyer_name" value="<?php echo $transaction_buyer_->getBuyer_name();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Buyer_phone</td> 
            <td><input type="text"  name="buyer_phone" id="buyer_phone" value="<?php echo $transaction_buyer_->getBuyer_phone();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Buyer_address</td> 
            <td><input type="text"  name="buyer_address" id="buyer_address" value="<?php echo $transaction_buyer_->getBuyer_address();?>" size="40"   ></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
