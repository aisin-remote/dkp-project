<?php
if ($action == "line_status") {
  $template["group"] = "Transaction";
  $template["menu"] = "Line Status";
  $line = new Line();

  if (isset($_GET["status"])) {
    $line_id = $_GET["line"];
    $status = $_GET["status"];
    $response = $line->updateStatus($line_id, $status); 
  }

  $status = $line->getListStatus();
  $data["line"] = $line->getLine();
  require(TEMPLATE_PATH . "/t_line_st.php");
}
