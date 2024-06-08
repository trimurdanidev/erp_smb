<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/upload_trans_log.class.php';
    require_once './controllers/upload_trans_log.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class upload_trans_logController extends upload_trans_logControllerGenerate
    {

    }
?>
