<?php
    require_once './models/master_user.class.php';
    require_once './controllers/master_user.controller.php';
    require_once './models/transaction_buyer.class.php';
    require_once './controllers/transaction_buyer.controller.generate.php';
    if (!isset($_SESSION)){
        session_start();
    }

    class transaction_buyerController extends transaction_buyerControllerGenerate
    {

    }
?>
