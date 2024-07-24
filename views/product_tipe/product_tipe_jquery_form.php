<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        Swal.fire($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=product_tipe&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmproduct_tipe" id="frmproduct_tipe" method="post" action="index.php?model=product_tipe&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text" class="form form-control" style="text-align: right;" onkeypress="validate(event);"  name="id" id="id" value="<?php echo $product_tipe_->getId();?>" size="40" ReadOnly  placeholder="autonumber"></td>
        </tr>

        <tr> 
            <td class="textBold">Tipe Produk</td> 
            <td><input type="text" class="form form-control" name="tipe_name" id="tipe_name" value="<?php echo $product_tipe_->getTipe_name();?>" size="40" placeholder="Masukkan Tipe Produk" required ></td>
        </tr>

        <tr> 
            <td class="textBold">Logo Tipe Produk</td> 
            <td><input type="file" class="form form-control" name="tipe_image" id="tipe_image" value="<?php echo $product_tipe_->getTipe_image();?>" size="40"   ></td>
        </tr>

        <!-- <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $product_tipe_->getCreated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $product_tipe_->getUpdated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $product_tipe_->getCreated_at();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $product_tipe_->getUpdated_at();?>" size="10"   ></td>
        </tr> -->


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-facebook" ></td>
        </tr>
    </table>
</form>

<br>
<br>
