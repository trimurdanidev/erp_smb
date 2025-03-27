<script language="javascript" type="text/javascript">
    jQuery(function () {
        $("#changepassword").submit(function () {
            var post_data = $(this).serialize();
            var form_action = $(this).attr("action");
            var form_method = $(this).attr("method");
            $.ajax({
                type: form_method,
                url: form_action,
                cache: false,
                data: post_data,
                success: function (x) {
                    if (x.trim() == "Data is updated") {
                        showMenu('contentmenu', 'index.php?model=master_module&action=showMenu&id=1');
                    } else {
                        Swal.fire(x);
                    }
                },
                error: function () {
                    Swal.fire("Error");
                }
            });
            return false;
        });
    });
</script>

<div class="table-responsive">
    <form name="changepassword" id="changepassword" method="post"
        action="index.php?model=master_user&action=changePassword">
        Change Password
        <br>
        Please Insert Old Password
        <br>
        <input type="password" class="form form-control" name="oldpassword" id="oldpassword" value="*****" > *
        <br>
        Please Input New Password
        <br>
        <input type="password" class="form form-control" name="newpassword" id="newpassword" value="" required> *
        <br>
        Retype New Password
        <br>
        <input type="password" class="form form-control" name="retypepassword" id="retypepassword" value="" required> *
        <br>
        <input type="submit" class="btn btn-facebook" name="changepass" id="changepass" value="Change Password">
    </form>
</div>
<br>
<br>