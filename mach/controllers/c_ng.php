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
            $data["data"] = $ng->getNGId($id);
            require(TEMPLATE_PATH . "/t_ng_edit.php");
        }
    } else {
        $data["ng"] = $ng->getNGType();
        require(TEMPLATE_PATH . "/t_ng_list.php");
    }
}

if ($action == "ng_pos") {
    $template["group"] = "Master Data";
    $template["menu"] = "NG Position";
    $ng = new Production();
    $dies = new Dies();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == "0") {
                $save = $ng->insertNGPos($param);
                if ($save == true) {
                    header("location: ?action=ng_pos&success=Data berhasil disimpan!");
                } else {
                    header("location: ?action=ng_pos&error=".$save["message"]."");
                }
            } else {
                $save = $ng->updateNGPos($param);
                if ($save == true) {
                    header("location: ?action=ng_pos&success=Data berhasil diupdate!");
                } else {
                    header("location: ?action=ng_pos&error=".$save["message"]."");
                }
            }
        } else {
            // if ($id == "1") {
            //     $group = $_GET["group"];
            //     $model = $_GET["model"];
            //     $no = $_GET["no"];
            // }
            $group_list = $dies->getDiesGroup();
            $model_list = $dies->getAllModel();
            $data["data"] = $ng->getNGPosById($id);
            require(TEMPLATE_PATH . "/t_ng_pos_edit.php");
        }
    } else {
        $data["ng"] = $ng->getNGPos();
        require(TEMPLATE_PATH . "/t_ng_pos_list.php");
    }
}
?>