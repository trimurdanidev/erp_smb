<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        alert($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=product_kategori&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmproduct_kategori" id="frmproduct_kategori" method="post" action="index.php?model=product_kategori&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">ID</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="id" id="id" class="form form-control" placeholder="Autonumber" value="<?php echo $product_kategori_->getId();?>" size="40" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Kategori Barang</td> 
            <td><input type="text"  name="kategori_name" id="kategori_name" class="form form-control" value="<?php echo $product_kategori_->getKategori_name();?>" size="40"  required ></td>
        </tr>

        <tr> 
            <td class="textBold">Gambar Kategori</td> 
            <td><input type="file"  name="kategori_image" id="kategori_image" class="form form-control" value="<?php echo $product_kategori_->getKategori_image();?>" size="40" required  ></td>
        </tr>
<!-- 
        <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $product_kategori_->getCreated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $product_kategori_->getUpdated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $product_kategori_->getCreated_at();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $product_kategori_->getUpdated_at();?>" size="10"   ></td>
        </tr> -->


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
