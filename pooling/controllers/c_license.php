<?php 
if($action == "license") {
  $template["group"] = "Settings";
  $template["menu"] = "Activate License";
  $class = new License();
  if(isset($_POST["license_key"])) {
    $lic_srl = trim($_POST["license_key"]);
    $save = $class->activateLicense($lic_srl);
    if($save["status"] == "true") {
      $message["color"] = "success";
      $message["text"] = "<strong>Success! </strong>"."License Added";
    } else {
      $message["color"] = "danger";
      $message["text"] = "<strong>Error! </strong>".$save["message"];
    }
  }
  $data["list"] = $class->getList();
  require( TEMPLATE_PATH . "/t_license_list.php" );
}
?>