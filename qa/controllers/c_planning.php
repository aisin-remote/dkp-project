<?php
if ($action == "planning") {
    $template["group"] = "Master Data";
    $template["menu"] = "Planning";
    $class = new Planning();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        if (isset($_POST["save"])) {
            $param = $_POST;
            if ($id == 0) {
                $save = $class->insert($param);
                if ($save["status"] == "success") {
                    header("Location: ?action=" . $action . "&success=" . $save["message"]);
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            } else {
                $save = $class->update($param);
                if ($save["status"] == "success") {
                    header("Location: ?action=" . $action . "&success=" . $save["message"]);
                } else {
                    header("Location: ?action=" . $action . "&error=" . $save["message"]);
                }
            }
        } else {
            $data["data"] = $class->getPlanById($id, $_GET["month"]);
            require(TEMPLATE_PATH . "/t_planning_edit.php");
        }
    } else {
        $data["list"] = $class->getList();
        require(TEMPLATE_PATH . "/t_planning_list.php");
    }
}
?>