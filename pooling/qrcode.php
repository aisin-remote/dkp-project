<?php
include "vendors/phpqrcode/qrlib.php";
$qrcode = $_GET["code"];
$tempDir = "media/";
ob_start("callback");
$debugLog = ob_get_contents();
ob_end_clean();
QRcode::png($qrcode);
?>