<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATA KARYAWAN</title>
</head>

<body>
    <?php
    $mdl_master_user = new master_user();
    $ctrl_master_user = new master_userController($mdl_master_user, $this->dbh);
    $selectKry = $ctrl_master_user->showDataAllERP();

    $master_department = new master_department();
    $master_department_ctrl = new master_departmentController($master_department, $this->dbh);
    $showAllDept = $master_department_ctrl->showAllDept();
    // echo "<pre>";
    // echo print_r($selectKry);
    // echo "</pre>";
    
    ?>

    <h1>DATA KARYAWAN</h1>
    <div id="header_list">
        <div id="table_monitor_abs" class="table-responsive">
            <table class="table" border="0" width="80%">
                <!-- <tr>
                <td>
                        <span class="glyphicon glyphicon-search"></span>
                        Search Data
                    </td>
                </tr> -->
                <tr>
                    <?php
                    $deptSet = isset($_REQUEST["dept"]) ? $_REQUEST["dept"] : "";
                    $karyawan = isset($_REQUEST["kry"]) ? $_REQUEST["kry"] : "";
                    // echo $karyawan . "====<br>";
                    ?>
                    <td><span class="glyphicon glyphicon-search"></span></td>
                    <td>
                        <select name="dept" id="dept" class="form form-control" onchange="serchData()">
                            <option value="">All Department</option>
                            <?php foreach ($showAllDept as $dept) {
                                ?>
                                <option value="<?php echo $dept['departmentid']; ?>" <?php echo $deptSet == $dept['departmentid'] ? 'selected' : ''; ?>>
                                    <?php echo $dept['description'] ?>
                                </option>
                            <?php } ?>
                    </td>
                    <td>
                        <select name="kry" id="kry" class="form form-control" onchange="serchData()">
                            <option value="">All Karyawan</option>
                            <?php foreach ($selectKry as $user) {
                                ?>
                                <option value="<?php echo $user->user; ?>" <?php echo $karyawan == $user->user ? 'selected' : ''; ?>>
                                    <?php echo $user->user ?>
                                </option>
                            <?php } ?>
                    </td>
                    <td>
                        <input type="hidden" name="getKat" id="getKar" value="<?php echo $karyawan; ?>">
                    </td>
                    <td>
                        <button type="button" onclick="resetFilter()" class="btn btn-default"><span
                                class="glyphicon glyphicon-repeat"></span>
                            Reset
                        </button>
                    </td>
            </table>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table" width="95%">
                <tr>
                    <td align="left">
                        <img alt="Move First" src="./img/icon/icon_move_first.gif"
                            onclick="showMenu('content', 'index.php?model=master_user&action=dataAllKaryawan&search=<?php echo $search ?>');">
                        <img alt="Move Previous" src="./img/icon/icon_move_prev.gif"
                            onclick="showMenu('content', 'index.php?model=master_user&action=dataAllKaryawan&skip=<?php echo $previous ?>&search=<?php echo $search ?>');">
                        Page <?php echo $pageactive ?> / <?php echo $pagecount ?>
                        <img alt="Move Next" src="./img/icon/icon_move_next.gif"
                            onclick="showMenu('content', 'index.php?model=master_user&action=dataAllKaryawan&skip=<?php echo $next ?>&search=<?php echo $search ?>');">
                        <img alt="Move Last" src="./img/icon/icon_move_last.gif"
                            onclick="showMenu('content', 'index.php?model=master_user&action=dataAllKaryawan&skip=<?php echo $last ?>&search=<?php echo $search ?>');">
                    </td>
                </tr>
            </table>
            <table class="table table-striped" style="width: 95%;">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Nama Karyawan</th>
                        <th class="text-left">username</th>
                        <th class="text-left">No.Telepon</th>
                        <th class="text-left">Bagian</th>
                        <th class="text-left">Lokasi Kerja</th>
                        <th class="text-left">Kunci Lokasi Kerja</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <?php if ($master_user_list == 'Tidak Ada Data Yang Ditampilkan.') {
                            echo "";
                        } else { ?>
                            <button type="button" onclick="exportExcel()" class="btn btn-green" disabled="disabled"><span
                                    class="glyphicon glyphicon-export"></span>
                                Export Excel
                            </button>
                            <br>
                            <?php
                            // echo "<pre>";
                            // echo print_r($master_user_list);
                            // echo "</pre>";
                            $no = 0;
                            foreach ($master_user_list as $list_data_kar) {
                                $no++;
                                // $pi = $no + 1;
                                $bg = ($no % 2 != 0) ? "#E1EDF4" : "#F0F0F0";
                                ?>
                            <tr bgcolor="<?php echo $bg; ?>">
                                <td><?php echo $no ?></td>
                                <td><?php echo $list_data_kar['username']; ?></td>
                                <td><?php echo $list_data_kar['user'] ?></td>
                                <td><?php echo $list_data_kar['phone'] ?></td>
                                <!-- <td><?php //echo $list_data_kar->departmentid; ?></td> -->
                                <td><?php echo $list_data_kar['description']; ?></td>
                                <td>
                                    <?php
                                    $lat = $list_data_kar['latitude'];
                                    $lng = $list_data_kar['longitude'];

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
                                <td><?php if ($list_data_kar['is_mobile'] == 0) { ?>
                                        <button class="btn btn-info"
                                            onclick="funcMobile('<?php echo $list_data_kar['is_mobile']; ?>','<?php echo $list_data_kar['id']; ?>')"><span
                                                class="badge rounded-pill bg-primary">Aktif</span></button>
                                    <?php } else if ($list_data_kar['is_mobile'] == 1) { ?>
                                            <button class="btn btn-red"
                                                onclick="funcMobile('<?php echo $list_data_kar['is_mobile']; ?>','<?php echo $list_data_kar['id']; ?>')"><span
                                                    class="badge rounded-pill bg-secondary">Tidak Aktif</span></button>
                                        </td>
                                <?php } else
                                    echo "-"; ?>
                                <td><button type="button" class="btn btn-default" title="Detail Karyawan" data-toggle="modal"
                                        data-target="#modalDtlKar<?php echo $list_data_kar['id'] ?>"><span
                                            class="glyphicon glyphicon-eye-open"></span> Detail
                                    </button>
                                    <div class="modal fade" id="modalDtlKar<?php echo $list_data_kar['id'] ?>" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title fs-5" id="exampleModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Detail
                                                        Karyawan <?php echo $list_data_kar['username'] ?>
                                                    </h3>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive ">
                                                        <label for="name">Nama</label>
                                                        <input type="text" class="form- form-control"
                                                            value="<?php echo $list_data_kar['username'] ?>" disabled>
                                                        <br>
                                                        <label for="name">No Telepon</label>
                                                        <input type="text" class="form- form-control"
                                                            value="<?php echo $list_data_kar['phone'] ?>" disabled>
                                                        <br>
                                                        <label for="name">Alamat</label>
                                                        <input type="text" class="form- form-control"
                                                            value="<?php echo $list_data_kar['address'] ?>" disabled>
                                                        <br>
                                                        <label for="name">No KTP</label>
                                                        <input type="text" class="form- form-control"
                                                            value="<?php echo $list_data_kar['no_ktp'] ?>" disabled>
                                                        <br>
                                                        <label for="name">Bagian</label>
                                                        <input type="text" class="form- form-control"
                                                            value="<?php echo $list_data_kar['description'] ?>" disabled>
                                                        <br>
                                                        <br>
                                                        <button type="button" class="btn btn-primary" disabled><span class=" 	glyphicon glyphicon-ok-circle"></span> Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                                                        class="glyphicon glyphicon-eye-close"></span> Close</button>
                                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                            </div>
                                </td>
                            <?php }
                        }
                        ?>
                    </tr>

        </div>
        <br>
    </div>
</body>

</html>
<script type="text/javascript">
    function deletedata(id, skip, search) {
        var ask = confirm("Do you want to delete ID " + id + " ?");
        if (ask == true) {
            site = "index.php?model=master_user&action=deleteFormJQuery&skip=" + skip + "&search=" + search + "&id=" + id;
            target = "content";
            showMenu(target, site);
        }
    }

    function resetFilter() {
        showMenu('content', 'index.php?model=master_user&action=dataAllKaryawan');
    }

    function serchData() {
        var karyawan = $('#kry option:selected').val();
        var dept = $('#dept option:selected').val();
        var param = {};

        param['kry'] = karyawan;
        param['dept'] = dept;

        Swal.fire({
            title: 'Searching...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });
        swal.showLoading();

        $.post('index.php?model=master_user&action=dataAllKaryawan', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function searchData() {
        var searchdata = document.getElementById("search").value;
        site = 'index.php?model=master_user&action=dataAllKaryawan&search=' + searchdata;
        target = "content";
        showMenu(target, site);
    }

    function exportExcel() {
        var karyawan = $('#kry option:selected').val();
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        var param = {};
        var filename = 'DETAIL ABSENSI';

        param['kry'] = karyawan;
        param['dari'] = dari;
        param['sampai'] = sampai;

        Swal.fire({
            title: 'Exporting...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });
        swal.showLoading();

        $.post('index.php?model=master_user&action=showExportTable', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function funcMobile(stats, iduser) {
        if (stats == 1) {

            Swal.fire({
                title: "Anda Yakin Ingin Mengaktifkan Kunci Lokasi Kerja?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Ya",
                denyButtonText: `Tidak`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {


                    site = "index.php?model=master_user&action=changeMobile&id=" + iduser + "&stats=" + stats;
                    target = "content";
                    Swal.fire({
                        title: 'Saving...',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                    });
                    swal.showLoading();
                    showMenu(target, site);

                    Swal.fire("Berhasil!", "Kunci Lokasi Di Aktifkan", "success");
                } else if (result.isDenied) {
                    Swal.fire("Dibatalkan", "", "info");
                }
            });
        } else {
            Swal.fire({
                title: "Anda Yakin Ingin Menonaktifkan Kunci Lokasi Kerja?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Ya",
                denyButtonText: `Tidak`
            }).then((result) => {
                if (result.isConfirmed) {
                    site = "index.php?model=master_user&action=changeMobile&id=" + iduser + "&stats=" + stats;
                    target = "content";
                    showMenu(target, site);

                    Swal.fire("Berhasil!", "Kunci Lokasi Di Nonaktfikan", "success");

                } else if (result.isDenied) {
                    Swal.fire("Dibatalkan", "", "info");
                }
            });
        }
    }
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