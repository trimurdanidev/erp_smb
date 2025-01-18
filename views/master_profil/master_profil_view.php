<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profile User</title>
</head>

<body>
    <?php
    // echo $this->user;
    $mdl_mst_profil = new master_profil();
    $ctrl_mst_profil = new master_profilController($mdl_mst_profil, $this->dbh);
    $showProfil = $ctrl_mst_profil->showDataByUser($this->user);

    // echo $showProfil->getAvatar();
    ?>
    <table>
        <tr>
            <td align="center" colspan="3">
                <?php
                if ($this->user != $showProfil->getUser() || $showProfil->getId() == null) { ?>
                    <img src='images_small/user_icon.png' class="img-rounded" height="200px" width="200px"
                        onClick="openFormImage('index.php?model=master_profil&action=showImageProfil&idProf=<?php echo $showProfil->getId();?>')">
                <?php } else { ?>
                    <img src='uploads/image_profil/<?php echo $showProfil->getAvatar() ?>' class="img-rounded"
                        height="200px" width="200px"
                        onClick="openFormImage('index.php?model=master_profil&action=showImageProfil&idProf=<?php echo $showProfil->getId();?>')">

                    <?php
                }
                ?>
                <br>
                <br>
                <button class="btn btn-primary"
                    onclick="showMenu('uploadphoto','index.php?model=master_profil&action=formUpload')"
                    title="Ganti Foto Profil"><span class="glyphicon glyphicon-camera"></span> Change Photo</button>
                <br>
                <?php
                // }       
                ?>
                <br>
                <div id="uploadphoto"></div>

            </td>
        </tr>

        <tr>
            <td>
            </td>
            <td class="textBold">User</td>
            <td>: <?php echo $master_user->getUser(); ?></td>
        </tr>

        <tr>
            <td class="textBold">&nbsp;</td>
            <td class="textBold">Description</td>
            <td>: <?php echo $master_user->getDescription(); ?></td>
        </tr>

        <tr>
            <td class="textBold">&nbsp;</td>
            <td class="textBold">Full Name</td>
            <td>: <?php echo $master_user->getUsername(); ?></td>
        </tr>

        <tr>
            <td class="textBold">&nbsp;</td>
            <td class="textBold">Nik</td>
            <td>: <?php echo $master_profil->getNik(); ?></td>
        </tr>

        <tr>
            <td class="textBold">&nbsp;</td>
            <td class="textBold">Department</td>
            <td>
                :
                <?php echo "(" . $master_profil->getDepartmentid() . ") " . $master_department_controller->showData($master_profil->getDepartmentid())->getDescription() ?>
            </td>

        </tr>

        <tr>
            <td class="textBold">&nbsp;</td>
            <td class="textBold">Unit</td>
            <td>
                :
                <?php echo "(" . $master_profil->getUnitid() . ") " . $master_unit_controller->showData($master_profil->getUnitid())->getUnitname() ?>
            </td>
        </tr>
    </table>
</body>

</html>
<br>
<br>