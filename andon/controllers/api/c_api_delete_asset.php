<?php
if ($action == "api_delete_asset") {
    $dies_id = $_GET['dies_id'];
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT COUNT(*) as cnt FROM t_prd_daily_i WHERE dies_id = '$dies_id'";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":dies_id", strtoupper($dies_id), PDO::PARAM_STR);
    $count = 0;
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count = $row["cnt"];
        }
    }
    $return["count"] = $count;
    echo json_encode($return);
}
