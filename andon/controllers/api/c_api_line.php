<?php
if ($action == "api_get_line") {
  $line = new Line();

  $listline = $line->getLine();
  echo json_encode($listline);
}