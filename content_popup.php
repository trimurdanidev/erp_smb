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
        <img src="http://202.10.42.150:8080/storage/<?php echo $attendanceImage; ?>" alt="Absensi">
        <!-- </div> -->
    </tr>
    <tr>

        <a href="https://www.google.com/maps?q=<?php echo $lat . "," . $long; ?>" class="button" target="_blank"><span
                class=" 	glyphicon glyphicon-map-marker"></span>Lihat Lokasi</a>
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
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f3f4f6;
    }

    .card {
        width: 400px;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        text-align: center;
        padding: 20px;
    }

    .card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
    }

    .button {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
</style>