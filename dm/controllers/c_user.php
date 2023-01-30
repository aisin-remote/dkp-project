<?php
if ($action == "user") {
  $template["group"] = "Settings";
  $template["menu"] = "User";
  $class = new User();
  $role = new Role();
  $data["list"];
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["user_role"] = $_POST["roles"];
      $param["user_device"] = $_POST["devices"];
      $save = array();

      if (isset($_POST["stats"])) {
        $param["stats"] = "A";
      } else {
        $param["stats"] = "I";
      }

      if (!empty($_POST["password1"])) {
        if ($_POST["password1"] == $_POST["password2"]) {
          $param["usrpw"] = $_POST["password1"];
        } else {
          header("Location: ?action=" . $action . "&id=" . $id . "&error=Password%20Missmatch");
          die();
        }
      }
      if ($id == "0") {
        $param["crt_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->insert($param);
      } else {
        $param["chg_by"] = $_SESSION[LOGIN_SESSION];
        $save = $class->update($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    } else {
      $data["user_role"] = array();
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
        $data["user_role"] = $class->getUserRole($id);
      }
      $data["role"] = $role->getList();
      require(TEMPLATE_PATH . "/t_user_edit.php");
    }
  } else {
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_user_list.php");
  }
}
