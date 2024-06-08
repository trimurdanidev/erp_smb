<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/master_stock.class.php';
    require_once './controllers/master_stock.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class master_stockController extends master_stockControllerGenerate
    {

    }
?>
