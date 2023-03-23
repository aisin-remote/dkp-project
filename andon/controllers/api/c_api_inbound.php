<?php
if ($action == "api_get_ib") {
    $param = $_REQUEST;
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT * from t_prd_ib
              where line_id = :line_id and crt_dt = :crt_dt and shift = :shift ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":crt_dt", $param["date"], PDO::PARAM_STR);
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row["ib_type"] == "P" && $row["shkzg"] == "C") {
                $return["prime"] += $row["qty"];
            } else if ($row["ib_type"] == "N" && $row["shkzg"] == "C") {
                $return["ng"] += $row["qty"];
            }
        }
    }
    echo json_encode($return);
}

if ($action == "api_post_ib") {
    $param = $_REQUEST;
    // print_r($param);
    // die();
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "INSERT INTO t_prd_ib (ib_type, line_id, ng_type, qty, crt_dt, shkzg, shift)
              VALUES (:ib_type, :line_id, :ng_type, :qty, :crt_dt, :shkzg, :shift) ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":ib_type", $param["ib_type"], PDO::PARAM_STR);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":ng_type", $param["ng_type"], PDO::PARAM_STR);
    $stmt->bindValue(":qty", $param["qty"]);
    $stmt->bindValue(":crt_dt", $param["crt_dt"], PDO::PARAM_STR);
    $stmt->bindValue(":shkzg", $param["shkzg"], PDO::PARAM_STR);
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        $return["status"] = true;
    } else {
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
        error_log($error[2]);
    }
    echo json_encode($return);
}
?>