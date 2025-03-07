<?php
$idAbsen = $_REQUEST['idAbsen'];
$imgAbsen = $_REQUEST['img'];
$long = $_REQUEST['long'];
$lat = $_REQUEST['lat'];

?>
<table border="0" width="90%" align="center" style="border-collapse:collapse">
    <tr>
        <td colspan="3" align="center">Detail Absensi</td>
    </tr>
    <tr>
        <!-- <div class="card"> -->
        <td colspan="3" align="center">

            <img src="http://202.10.42.150:8080/storage/<?php echo $imgAbsen; ?>" alt="Absensi" width="40%"
                height="250px" object-fit="cover" border-radius="8px">
        </td>
        <!-- </div> -->
    </tr>
    <tr>
        <td colspan="3" align="center">

            <a href="https://www.google.com/maps?q=<?php echo $lat . "," . $long; ?>" class="button"
                target="_blank"><span class=" 	glyphicon glyphicon-map-marker"></span>Lihat Lokasi</a>
        </td>
    </tr>
    <!-- <tr>
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
    </tr> -->
    <tr>
        <td height="5"></td>
    </tr>
    <tr>
        <td colspan="3" align="center"><input type="button" value="Tutup" class="btn" onclick="Popup.hide('popup')">
        </td>
    </tr>
</table>