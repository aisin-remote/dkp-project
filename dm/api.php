<?php

foreach (glob(CONTROLLER_PATH . "/api/*.php") as $filename) {
  include $filename;
}
?>