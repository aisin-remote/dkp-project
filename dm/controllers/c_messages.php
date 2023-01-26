<?php 
if($action == "message") {
  $template["group"] = "Maintenance Activity";
  
  $class = new Message();
  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    if($id == "0") {
      $template["menu"] = "New Subject";
      if(isset($_POST["save"])) {
        $param_hdr = [];
        $param_hdr["subject"] = $_POST["subject"];
        $param_hdr["crt_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->insertHeader($param_hdr);
        if($save["status"] == true) {
          $id_header = $save["id"];
          $param_itm = [];
          $param_itm["msg_id"] = $id_header;
          $param_itm["msg_txt"] = $_POST["msg_txt"];
          $param_itm["crt_by"] = $_SESSION[LOGIN_SESSION];
          $save = $class->insertItem($param_itm);
          if($save["status"] == true) {
            $success = "New Subject Added";
            header("Location: " . $action . "?success=$success");
          } else {
            $error = $save["message"];
            header("Location: " . $action . "?id=" . $id . "&error=" . $error);
          }
        } else {
          $error = $save["message"];
          header("Location: " . $action . "?id=" . $id . "&error=" . $error);
        }
      } else {
        require(TEMPLATE_PATH . "/t_messages_new.php");
      }
    } else {
      $template["menu"] = "View Details";
      if(isset($_POST["save"])) {
        $param_itm["msg_id"] = $id;
        $param_itm["msg_txt"] = $_POST["msg_txt"];
        $param_itm["crt_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->insertItem($param_itm);
        if($save["status"] == true) {
          $success = "New Subject Added";
          header("Location: " . $action . "?id=" . $id . "&success=$success");
        } else {
          $error = $save["message"];
          header("Location: " . $action . "?id=" . $id . "&error=" . $error);
        }
      } else {
        $data["list"] = $class->getItemList($id);
        require(TEMPLATE_PATH . "/t_messages_detail.php");
      }
        
    }
  } else {
    $template["menu"] = "Messages (Forum)";
    $data["list"] = $class->getHeaderList();
    require(TEMPLATE_PATH . "/t_messages.php");
  }
}
?>