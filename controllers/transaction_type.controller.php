<?php
require_once './models/master_user.class.php';
require_once './controllers/master_user.controller.php';
require_once './models/transaction_type.class.php';
require_once './controllers/transaction_type.controller.generate.php';
if (!isset($_SESSION)) {
    session_start();
}

class transaction_typeController extends transaction_typeControllerGenerate
{
    function saveFormPost()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $type_name = isset($_POST['type_name']) ? $_POST['type_name'] : "";
        $created_by = $this->user;
        $created_at = date('Y-m-d h:i:s');
        $updated_by = "";
        $updated_at = "";

        $this->transaction_type->setId($id);
        $this->transaction_type->setType_name($type_name);
        $this->transaction_type->setCreated_by($created_by);
        $this->transaction_type->setCreated_at($created_at);
        $this->transaction_type->setUpdated_by($updated_by);
        $this->transaction_type->setUpdated_at($updated_at);
        $this->saveData();
    }

}
?>