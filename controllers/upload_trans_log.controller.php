<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/upload_trans_log.class.php';
require_once './controllers/upload_trans_log.controller.generate.php';
if (!isset($_SESSION)) {
    session_start();
}

class upload_trans_logController extends upload_trans_logControllerGenerate
{

    function showDataAll_SOLimit()
    {

        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $sql = "SELECT * FROM upload_trans_log WHERE trans_type='3' ORDER BY id desc";
        $sql .= " limit " . $skip . ", " . $limit;

        return $this->createList($sql);
    }

    function showDataAll_restokLimit()
    {

        $skip = isset($_REQUEST["skip"]) ? $_REQUEST["skip"] : 0;
        $limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : $this->limit;
        $sql = "SELECT * FROM upload_trans_log WHERE trans_type='4' ORDER BY id desc";
        $sql .= " limit " . $skip . ", " . $limit;

        // echo $sql;

        return $this->createList($sql);
    }
}
?>