<?php
if ($action == "line_status") {
  $template["group"] = "Transaction";
  $template["menu"] = "Line Status";
  $line = new Line();
  $mach = new Mach();

  if (isset($_GET["status"])) {
    $line_id = $_GET["line"];
    $status = $_GET["status"];
    $response = $line->updateStatus($line_id, $status);
    if ($response == true) {
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $conn->exec("UPDATE m_prd_mach SET stats = $status WHERE line_id = '$line_id' ");
    }
  }

  $status = $line->getListStatus();
  $data["line"] = $line->getLine();
  require(TEMPLATE_PATH . "/t_line_st.php");
}
