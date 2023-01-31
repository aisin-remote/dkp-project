<?php 
if($action == "upload_app") {
  $template["group"] = "Settings";
  $template["menu"] = "Upload Scanner App";
  $json_array = array();
  $app_version = "";
  $success = "";
  $error = "";
  if(isset($_FILES["app_file"])) {
    $app_version = $_POST["app_version"];
    chmod("android/", 0777);
    $target_file = "android/app.apk";
    if(file_exists($target_file)) {
      unlink($target_file);
    }
    try {
      move_uploaded_file($_FILES["app_file"]["tmp_name"], $target_file);
      $json_array["version_number"] = $app_version;
      $json_array["version_code"] = str_replace(".", "0", $app_version);
      file_put_contents("android/version.json", json_encode($json_array));      
    } catch (Exception $exc) {
      $error = $exc->getTraceAsString();
    } 
    if(empty($error)) {
      $success = "File uploaded, version = $app_version";
    }
  }
    
  $version_file = file_get_contents("android/version.json");
  $version_arr = json_decode($version_file, true);
  $app_version = $version_arr["version_number"];
  
  require( TEMPLATE_PATH . "/t_upload_app.php" );
}
?>