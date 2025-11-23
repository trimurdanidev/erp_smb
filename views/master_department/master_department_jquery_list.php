<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Master Department</title>
</head>
<?php
$mdl_mst_dept = new master_department();
$ctrl_mst_dept = new master_departmentController($mdl_mst_dept, $this->dbh);

?>

<body>
    <h1>Department / Bagian</h1>
    <div id="header_list">
        <div id="table_data_dept" class="table-responsive">
            <table class="table" border="0" width="70%">
                <tr>
                    <td><span class="glyphicon glyphicon-search"></span></td>
                    <td><input type="text" class="form form-control" name="search" title="Cari Bagian / Department"
                            id="search" value="<?php echo $search ?>" onkeypress="searchData();">
                    </td>
                    <td>
                        <!-- Tombol Tambah -->
                        <button type="button" class="btn btn-orange" data-toggle="modal" data-target="#modalTbhBag">
                            <span class="glyphicon glyphicon-plus"></span> Tambah
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modalTbhBag" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <form method="POST" action="index.php?model=master_department&action=saveFormPost">
                                        <!-- Submit ke PHP -->

                                        <div class="modal-header">
                                            <h3 class="modal-title">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                                Form Tambah Department / Bagian
                                            </h3>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">

                                            <label>ID</label>
                                            <input type="text" name="departmentid" class="form-control" value=""
                                                placeholder="Autonumber" readonly>

                                            <br>

                                            <label>Kode Department</label>
                                            <input type="text" name="departmentcode" class="form-control"
                                                placeholder="Autogenerate" readonly>

                                            <br>

                                            <label>Nama Department</label>
                                            <input type="text" name="description" class="form-control" required>

                                            <br>
                                            <label for="name" style="color:blue;">Titik Koordinat</label><br>

                                            <label>Latitude</label>
                                            <input type="text" name="longitude" class="form-control" required>

                                            <br>
                                            <label>Longitude</label>
                                            <input type="text" name="latitude" class="form-control" required>

                                            <br>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">
                                                <span class="glyphicon glyphicon-ok-circle"></span> Simpan
                                            </button>

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                <span class="glyphicon glyphicon-eye-close"></span> Close
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>


        </div>
        </td>
        </tr>
        </table>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table" width="95%">
            <tr>
                <td align="left">
                    <img alt="Move First" src="./img/icon/icon_move_first.gif"
                        onclick="showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&search=<?php echo $search ?>');">
                    <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                        onclick="showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                    Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                    <img
                        onclick="showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                    <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                        onclick="showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    <a href="index.php?model=master_department&action=export&search=<?php echo $search ?>">Export</a>
                    <a href="index.php?model=master_department&action=printdata&search=<?php echo $search ?>"
                        target="_"><img src="./images/icon_print.png" /></a>
                </td>

            </tr>
        </table>
        <table class="table table-striped" style="width: 95%;">
            <thead>
                <tr>
                    <th class="text-left">No.</th>
                    <th class="text-left">Kode Department/Bagian</th>
                    <th class="text-left">Nama Department/Bagian</th>
                    <th class="text-left">Lokasi Kerja</th>
                    <th class="text-left">Created By</th>
                    <th class="text-center">#</th>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <?php

            $no = 1;

            if (empty($master_department_list)) {
                echo "Tidak Ada Data Yang Ditampilkan.";
            } else {
                foreach ($master_department_list as $master_department => $key) {


                    $pi = $no + 1;
                    $bg = ($pi % 2 != 0) ? "#E1EDF4" : "#F0F0F0";
                    ?>
                    <tr bgcolor="<?php echo $bg; ?>">
                        <!-- <td><a href='#'
                                        onclick="showMenu('header_list', 'index.php?model=master_department&action=showDetailJQuery&id=<?php echo $key['departmentid']; ?>')"><?php echo $no; ?></a>
                                </td> -->
                        <td><?php echo $no ?></td>
                        <td><?php echo $key['departmentcode']; ?></td>
                        <td><?php echo $key['description']; ?></td>
                        <td>
                            <?php
                            $lat = $key['latitude'];
                            $lng = $key['longitude'];

                            // default jika tidak ada atau invalid
                            if ($lat === false || $lng === false || $lat === null || $lng === null) {
                                // contoh default (Jakarta)
                                $lat = -6.200000;
                                $lng = 106.816666;
                            }

                            $zoom = 15;

                            $mapSrc = "https://www.google.com/maps?q={$lat},{$lng}&z={$zoom}&output=embed";
                            ?>
                            <div class="map-wrap">
                                <iframe title="Lokasi" src="<?php echo htmlspecialchars($mapSrc); ?>" allowfullscreen
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </td>
                        <td><?php echo $key['created_by']; ?></td>

                        <td>
                            <button type="button" class="btn btn-default" title="Detail Department" data-toggle="modal"
                                data-target="#modalDtlBag<?php echo $key['departmentid'] ?>"><span
                                    class="glyphicon glyphicon-eye-open"></span> Detail
                            </button>
                            <div class="modal fade" id="modalDtlBag<?php echo $key['departmentid'] ?>" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title fs-5" id="exampleModalLabel"><span
                                                    class="glyphicon glyphicon-info-sign"></span> Form Detail
                                            </h3>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive ">
                                                <label for="name">ID</label>
                                                <input type="text" class="form form-control" id="departmentid" name='departmentid'
                                                    value="<?php echo $key['departmentid'] ?>" disabled>
                                                <br>
                                                <label for="name">Kode Department / Bagian</label>
                                                <input type="text" class="form- form-control" id="departmentcode" name='departmentcode'
                                                    value="<?php echo $key['departmentcode'] ?>"
                                                    placeholder="Kode Department / Bagian" disabled>
                                                <br>
                                                <label for="name">Nama Department / Bagian</label>
                                                <input type="text" class="form form-control" id="description" name='description'
                                                    value="<?php echo $key['description'] ?>"
                                                    placeholder="Nama Department / Bagian">
                                                <br>
                                                <label for="name" style="color:blue;">Titik Koordinat</label><br>
                                                <label for="name">Latitude</label>
                                                <input type="text" title="Lat Untuk Ambil Koordinat Lokasi Kerja"
                                                    class="form form-control" id="latitude" name='latitude' value="<?php echo $key['latitude'] ?>"
                                                    placeholder="Lattitude">
                                                <label for="name">Longitude</label>
                                                <input type="text" title="Long Untuk Ambil Koordinat Lokasi Kerja"
                                                    class="form form-control" id="longitude" name='longitude' value='<?php echo $key['longitude']; ?>'
                                                    placeholder="Longitude">
                                                <br>
                                                <br>
                                                <br>
                                                <button type="button" class="btn btn-facebook"
                                                    onclick="EditData('<?php echo $key['departmentid']; ?>','<?php echo $key['description'] ?>','<?php echo $key['latitude'] ?>','<?php echo $key['longitude'] ?>')"><span
                                                        class="glyphicon glyphicon-ok-circle"></span> Simpan Perubahan</button>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                                            class="glyphicon glyphicon-eye-close"></span> Close</button>
                                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                        </td>
                    </tr>
                    <?php
                    $no++;
                }
            }
            ?>
        </table>
    </div>
    </div>
    <br>
</body>

</html>
<script type="text/javascript">
    function deletedata(id, skip, search) {
        var ask = confirm("Do you want to delete ID " + id + " ?");
        if (ask == true) {
            site = "index.php?model=master_department&action=deleteFormJQuery&skip=" + skip + "&search=" + search + "&id=" + id;
            target = "content";
            showMenu(target, site);
        }
    }
    function searchData() {
        var searchdata = document.getElementById("search").value;
        site = 'index.php?model=master_department&action=showAllDeptERP&search=' + searchdata;
        target = "content";
        showMenu(target, site);
    }

    function EditData(id,name,lat,long) {
            var description= document.getElementById('description').value;
            var latitude = document.getElementById('latitude').value;
            var longitude = document.getElementById("longitude").value;
            var param = {};
            
            param['id'] = id;
            param['description'] = name;
            param['latitude'] = lat;
            param['longitude'] = long;

        Swal.fire({
            title: "Anda Yakin Edit Data Ini?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Ya",
            denyButtonText: `Tidak`
        }).then((result) => {

        
            if (result.isConfirmed) {
                // $.post('index.php?model=master_department&action=saveFormPost', param, function (data) {
                    //  $('#modalDtlBag').html(data);
                      Swal.fire({
                        title : 'Upss Belum Tersedia!',
                        icon : 'info',
                        text : 'Silahkan Hubungi IT'
                        });
            // });
                $("#modalDtlBag").hide();
                $('.modal-backdrop').hide();
                // showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&search');

            } else if (result.isDenied) {
                Swal.fire("Dibatalkan", "", "info");
            }
        });
    }

    (function () {
        $('form').ajaxForm({
            beforeSubmit: function () {

            },
            complete: function (xhr) {
                Swal.fire($.trim(xhr.responseText));
                $("#modalTbhBag").hide();
                $('.modal-backdrop').hide();
                showMenu('content', 'index.php?model=master_department&action=showAllDeptERP&search');
            }
        });
    })();

    $(document).ready(function () {
        $('#search').keypress(function (e) {
            if (e.which == 13) {
                searchData();
            }
        });
    });
</script>
<style>
    /* responsive iframe container */
    .map-wrap {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* 16:9 */
    }

    .map-wrap iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }
</style>