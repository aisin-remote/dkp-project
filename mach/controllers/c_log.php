<?php
if ($action == "log_activity") {
    $template["group"] = "Reporting";
    $template["menu"] = "Log Activity";
    $log = new Reporting();

    $data_log = $log->getListLog();
    require(TEMPLATE_PATH . "/t_log.php");
}
?>