<script language="javascript" type="text/javascript">
        jQuery(function(){
            $("#frmmaster_profil").submit(function(){
                var post_data = $(this).serialize();
                var form_action = $(this).attr("action");
                var form_method = $(this).attr("method");
                $.ajax({
                     type : form_method,
                     url : form_action, 
                     cache: false, 
                     data : post_data,
                     success : function(x){
                         Swal.fire(x);
                         showMenu('content', 'index.php?model=master_profil&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
                     }, 
                     error : function(){
                        Swal.fire("Error");
                     }
                });
                return false;
             });
        });
</script>

<br>


<form name="frmmaster_profil" id="frmmaster_profil" method="post" action="index.php?model=master_profil&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text"  name="id" id="id" value="<?php echo $master_profil_->getId();?>" size="11" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Nik</td> 
            <td><input type="text"  name="nik" id="nik" value="<?php echo $master_profil_->getNik();?>" size="30"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Avatar</td> 
            <td><textarea rows="10" cols="50" name="avatar" id="avatar"><?php echo $master_profil_->getAvatar();?></textarea></td>
        </tr>

        <tr> 
            <td class="textBold">Departmentid</td> 
            <td><input type="text"  name="departmentid" id="departmentid" value="<?php echo $master_profil_->getDepartmentid();?>" size="2"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Unitid</td> 
            <td><input type="text"  name="unitid" id="unitid" value="<?php echo $master_profil_->getUnitid();?>" size="2"   ></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
<br>
<br>
