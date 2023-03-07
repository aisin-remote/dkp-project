<?php
if ($action == "line_status") {
  $template["group"] = "Transaction";
  $template["menu"] = "Line Status";
  $line = new Line();
  $param = new Param();

  if (isset($_GET["status"])) {
    $line_id = $_GET["line"];
    $status = $_GET["status"];
    $response = $line->updateStatus($line_id, $status); 
  }

  $data["param"] = $param->getParam();
  $data["line"] = $line->getLine();
  require(TEMPLATE_PATH . "/t_line_st.php");
}
