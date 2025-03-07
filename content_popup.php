<?php
    $idAbsen = $_SERVER['idAbsen'];
    $imgAbsen = $_REQUEST['img'];
    $long = isset($_GET['long']);
    $lat = isset($_GET['lat']);

?>
<table border="0" width="90%" align="center" style="border-collapse:collapse">
    <tr>
        <td colspan="3" align="center">Detail Absensi</td>
        <?php echo print_r($idAbsen)."<br>"?>
    </tr>
    <tr>
        <td>Nama Customer</td>
        <td>:</td>
        <td>Sigit Kurniawan</td>
    </tr>
    <tr>
        <td>Customer Of</td>
        <td>:</td>
        <td>Martha Tilaar Shop Kelapa Gading Mall</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>:</td>
        <td>
            Kav. Rawa Bebek<br>
            Pulogebang - Jakarta Timur
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td>skurniawan@martinaberto.co.id</td>
    </tr>
    <tr><td height="5"></td></tr>
    <tr>
        <td colspan="3" align="center"><input type="button" value="TutupJJJSJJJJ" class="btn" onclick="Popup.hide('popup')"></td>
    </tr>
</table>