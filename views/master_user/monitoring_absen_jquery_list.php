<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorig Absensi</title>
</head>

<body>
    <?php
    $mdl_master_user = new master_user();
    $ctrl_master_user = new master_userController($mdl_master_user, $this->dbh);
    $selectKry = $ctrl_master_user->showDataAllERP();
    // echo "<pre>";
    // echo print_r($selectKry);
    // echo "</pre>";
    ?>

    <h1>MONITORING ABSENSI</h1>
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
                    $fromDate = isset($_REQUEST["dari"]) ? $_REQUEST["dari"] : "";
                    $toDate = isset($_REQUEST["sampai"]) ? $_REQUEST["sampai"] : "";
                    $karyawan = isset($_REQUEST["kry"]) ? $_REQUEST["kry"] : "";
                    ?>
                    <td><span class="glyphicon glyphicon-search"></span></td>
                    <td>
                        <input type="date" name="dari" id="dari" size="5"
                            value="<?php echo $fromDate == date('Y-m-d') ? date('Y-m-d') : $fromDate; ?>"
                            class="form form-control" placeholder="from Date">
                    </td>
                    <td>S/D</td>
                    <td><input type="date" name="sampai" id="sampai" size="5"
                            value="<?php echo $toDate == date('Y-m-d') ? date('Y-m-d') : $toDate; ?>"
                            class="form form-control" placeholder="To Date" onchange="serchData()">
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
                        <input type="hidden" name="getFrom" id="getFrom" value="<?php echo $fromDate; ?>">
                        <input type="hidden" name="getTo" id="getTo" value="<?php echo $toDate; ?>">
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


            <?php echo $master_user_list; ?>

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
        showMenu('content', 'index.php?model=master_user&action=monitoringAbsen');
    }

    function serchData() {
        var karyawan = $('#kry option:selected').val();
        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        var param = {};

        param['kry'] = karyawan;
        param['dari'] = dari;
        param['sampai'] = sampai;

        Swal.fire({
            title: 'Searching...',
            html: 'Please wait...',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
        });
        swal.showLoading();

        $.post('index.php?model=master_user&action=monitoringAbsen', param, function (data) {
            $('#content').html(data);
            swal.close();
        });
    }

    function searchData() {
        var searchdata = document.getElementById("search").value;
        site = 'index.php?model=master_user&action=monitoringAbsen&search=' + searchdata;
        target = "content";
        showMenu(target, site);
    }
</script>