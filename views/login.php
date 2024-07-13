<li class="dropdown user user-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="glyphicon glyphicon-user"></i>
        <span>LOGIN <i class="caret"></i></span>
    </a>
    <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header bg-light-blue">
            <br>
            <img src="img/icon_key.png" class="img-circle" alt="Key Image" />
            <br>
            <p><i>Please Login</i></p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
            <div class="col-xs-12 text-info">
                <form id="frmlogin" method="post" action="index.php?model=login&action=checkLogin">
                    <table width="250" cellpadding="5" cellspacing="5">
                        <tr>
                            <td valign="top">
                                <div class="form-group"> User </div>
                            </td>
                            <td width="10" valign="top">
                                :
                            </td>
                            <td>
                                <div class="form-group"> <input type="text" name="user" id="user" size="15"></div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <div class="form-group"> Password</div>
                            </td>
                            <td valign="top">
                                :
                            </td>
                            <td>
                                <div class="form-group"> <input type="password" name="password" id="password" size="15">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                            </td>
                            <td><input type="submit" class="btn btn-orange" value="Login"
                                    onmouseover="this.className='BtnOrangeHov'"
                                    onmouseout="this.className='BtnOrange'" />

                            </td>

                        </tr>

                    </table>

                </form>
                <p>Forget Password ?</p>       
                <button class="btn btn-default" onclick="resetPassword()"><i class="fa fa-whatsapp" style="font-size:24px"></i>
                    Reset Password By WA</button>

            </div>
        </li>

    </ul>
</li>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script language="javascript" type="text/javascript">
    function resetPassword() {
        var param = {};
        param['user'] = document.getElementById('user').value;
        param['id'] = 0;
        
        if (document.getElementById('user').value == null || document.getElementById('user').value == "") {
            alert('Masukkan Username Anda!!');
        } else {
            // alert('action Reset');
            var ask = confirm(document.getElementById('user').value+" Anda Yakin Reset Password Akun Anda ?");

            if (ask == true) {
                $.post('index.php?model=master_user&action=resetPassword', param, function (data) {

                });
            }
        }
    }
</script>