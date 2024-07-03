<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/master_pay_transfer.class.php';
    require_once './controllers/master_pay_transfer.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class master_pay_transferController extends master_pay_transferControllerGenerate
    {

    }
?>
