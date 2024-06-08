<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/master_event.class.php';
    require_once './controllers/master_event.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class master_eventController extends master_eventControllerGenerate
    {

    }
?>
