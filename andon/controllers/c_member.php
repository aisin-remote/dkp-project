<?php
if ($action == "member_operator") {
  $template["group"] = "Master Data";
  $template["menu"] = "Member Operator";
  $template["submenu"] = "New";
  $data["list"];
  $class = new Member();

  if (isset($_POST["chg_status"])) {
    $all_id = $_POST["chk_id"];
    $extract_id = implode("','", $all_id);

    $update = $class->updateStatus($extract_id);

    if ($update["status"] == true) {
      header("Location: ?action=" . $action . "&success=Status%20Updated");
    } else {
      header("Location: ?action=" . $action . "&error=" . $update["message"]);
    }
  }
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $param["crt_by"] = $_SESSION[LOGIN_SESSION];
      $param["chg_by"] = $_SESSION[LOGIN_SESSION];
      $save = array();
      if ($id == "0") {
        $save = $class->insert($param);
      } else {
        $save = $class->update($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    } else {
      if ($id == "0") {
        $data["data"] = array();
      } else {
        $data["data"] = $class->getById($id);
      }

      $role_list = $class->getListRole();
      $empid = $_GET["empid"];

      require(TEMPLATE_PATH . "/t_member_operator_edit.php");
    }
  } else {
    $data["list"] = $class->getList();
    require(TEMPLATE_PATH . "/t_member_operator.php");
    unset($data["list"]);
  }
}

if ($action == "group_opr") {
  $template["group"] = "Master Data";
  $template["menu"] = "Group Operator";
  $class = new Member();
  $line = new Dies();

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if (isset($_POST["save"])) {
      $param = $_POST;
      $save = array();
      if ($id == "0") {
        $save = $class->insertGroup($param);
      } else {
        $param["id"] = $id;
        $param["line1"] = $_GET["line"];
        $param["group1"] = $_GET["group"];
        $save = $class->updateGroup($param);
      }
      if ($save["status"] == true) {
        header("Location: ?action=" . $action . "&success=Data%20Saved");
      } else {
        header("Location: ?action=" . $action . "&id=" . $id . "&error=" . $save["message"]);
      }
    }

    if ($id == "0") {
      $data = array();
    } else {
      $line_id = $_GET["line"];
      $group = $_GET["group"];
      $data = $class->getGroupById($id, $line_id, $group);
    }
    $opr_list = $class->getList("OP", "A");
    $line_list = $line->getListLine();
    require(TEMPLATE_PATH . "/t_group_opr_edit.php");
  } else {
    $list = $class->getListGroup();
    require(TEMPLATE_PATH . "/t_group_opr.php");
  }
}