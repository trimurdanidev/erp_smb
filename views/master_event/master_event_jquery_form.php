<script language="javascript" type="text/javascript">
        (function() {
            $('form').ajaxForm({
                beforeSubmit: function() {
                },
                complete: function(xhr) {
                        Swal.fire($.trim(xhr.responseText));
                        showMenu('content', 'index.php?model=master_event&action=showAllJQuery&skip=<?php echo $skip ?>&search=<?php echo $search ?>');
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


<form name="frmmaster_event" id="frmmaster_event" method="post" action="index.php?model=master_event&action=saveFormJQuery">
    <table >
        <tr> 
            <td class="textBold">Id</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="id" id="id" value="<?php echo $master_event_->getId();?>" size="40" ReadOnly  ></td>
        </tr>

        <tr> 
            <td class="textBold">Tanggal_event_start</td> 
            <td><input type="text" name="tanggal_event_start" id="tanggal_event_start" value="<?php echo $master_event_->getTanggal_event_start();?>" size="10" readonly >
            <script>
                $(function() {
                    $('#tanggal_event_start').datepicker({
                        dateFormat: 'yy-mm-dd',
                        yearRange: '-100:+20',
                        changeYear: true,
                        changeMonth: true
                    });
                });
            </script>
            </td> 
        </tr>

        <tr> 
            <td class="textBold">Tanggal_event_end</td> 
            <td><input type="text" name="tanggal_event_end" id="tanggal_event_end" value="<?php echo $master_event_->getTanggal_event_end();?>" size="10" readonly >
            <script>
                $(function() {
                    $('#tanggal_event_end').datepicker({
                        dateFormat: 'yy-mm-dd',
                        yearRange: '-100:+20',
                        changeYear: true,
                        changeMonth: true
                    });
                });
            </script>
            </td> 
        </tr>

        <tr> 
            <td class="textBold">Name_event</td> 
            <td><input type="text"  name="name_event" id="name_event" value="<?php echo $master_event_->getName_event();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Descript_event</td> 
            <td><input type="text"  name="descript_event" id="descript_event" value="<?php echo $master_event_->getDescript_event();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Aktif_event</td> 
            <td><input type="text" style="text-align: right;" onkeypress="validate(event);"  name="aktif_event" id="aktif_event" value="<?php echo $master_event_->getAktif_event();?>" size="11"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_by</td> 
            <td><input type="text"  name="created_by" id="created_by" value="<?php echo $master_event_->getCreated_by();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_by</td> 
            <td><input type="text"  name="updated_by" id="updated_by" value="<?php echo $master_event_->getUpdated_by();?>" size="40"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Created_at</td> 
            <td><input type="text"  name="created_at" id="created_at" value="<?php echo $master_event_->getCreated_at();?>" size="10"   ></td>
        </tr>

        <tr> 
            <td class="textBold">Updated_at</td> 
            <td><input type="text"  name="updated_at" id="updated_at" value="<?php echo $master_event_->getUpdated_at();?>" size="10"   ></td>
        </tr>


        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Submit" class="btn btn-danger btn-sm" ></td>
        </tr>
    </table>
</form>

<br>
<br>
