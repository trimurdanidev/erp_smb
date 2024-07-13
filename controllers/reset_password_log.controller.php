<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/reset_password_log.class.php';
    require_once './controllers/reset_password_log.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class reset_password_logController extends reset_password_logControllerGenerate
    {

    }
?>
