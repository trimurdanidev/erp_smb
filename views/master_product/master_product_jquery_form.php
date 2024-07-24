<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        Swal.fire($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=master_product&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmmaster_product" id="frmmaster_product" method="post" action="index.php?model=master_product&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="id" id="id" value="<?php echo $master_product_->getId();?>" size="40" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Kd_product</td> 
            <td><input type="text"  name="kd_product" id="kd_product" value="<?php echo $master_product_->getKd_product();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Nm_product</td> 
            <td><input type="text"  name="nm_product" id="nm_product" value="<?php echo $master_product_->getNm_product();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Image_product</td> 
            <td><input type="text"  name="image_product" id="image_product" value="<?php echo $master_product_->getImage_product();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Hrg_modal</td> 
            <td><input type="text"  name="hrg_modal" id="hrg_modal" value="<?php echo $master_product_->getHrg_modal();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Hrg_jual</td> 
            <td><input type="text"  name="hrg_jual" id="hrg_jual" value="<?php echo $master_product_->getHrg_jual();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Kategori_id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="kategori_id" id="kategori_id" value="<?php echo $master_product_->getKategori_id();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Tipe_id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="tipe_id" id="tipe_id" value="<?php echo $master_product_->getTipe_id();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Sts_aktif</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="sts_aktif" id="sts_aktif" value="<?php echo $master_product_->getSts_aktif();?>" size="11"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $master_product_->getCreated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $master_product_->getUpdated_by();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $master_product_->getCreated_at();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $master_product_->getUpdated_at();?>" size="10"   ></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
