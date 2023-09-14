<?php
if ($action == "home") {
  $template["group"] = "Home";
  $template["menu"] = "Dashboard";
  $group = new ChecklistGroup();
  $checkItem = new ChecklistItem();
  $group_list = $group->getList();
  $item_list = $checkItem->getList($group_list[0]["grp_id"]);
  require(TEMPLATE_PATH . "/t_home.php");
}
