<?php 
if($action == "api_get_customer") {
  $lifnr = trim($_REQUEST["lifnr"]);
  $customer = new Customer();
  $data = $customer->getById($lifnr);
  
  echo json_encode($data);
}
?>