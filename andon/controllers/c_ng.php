<?php
if ($action == "ng") {
    $template["group"] = "Master Data";
    $template["menu"] = "NG";
    $ng = new Production();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == "0") {
                $save = $ng->insertNGType($param);
                if ($save == true) {
                    header("location: ?action=ng&success=Data berhasil disimpan!");
                } else {
                    header("location: ?action=ng&error=".$save["message"]."");
                }
            } else {
                $save = $ng->updateNGType($param);
                if ($save == true) {
                    header("location: ?action=ng&success=Data berhasil diupdate!");
                } else {
                    header("location: ?action=ng&error=".$save["message"]."");
                }
            }
        } else {
            $data["data"] = $ng->getNGType($id);
            require(TEMPLATE_PATH . "/t_ng_edit.php");
        }
    } else {
        $data["ng"] = $ng->getNGTypeList();
        require(TEMPLATE_PATH . "/t_ng_list.php");
    }
}
?>