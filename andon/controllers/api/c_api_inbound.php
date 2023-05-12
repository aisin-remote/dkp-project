<?php
if ($action == "api_get_ib") {
    $param = $_REQUEST;
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    // $query = "SELECT SUM(qty) as totalok from t_prd_ib
    //         where line_id = :line_id and TO_CHAR(crt_dt, 'YYYYMMDD') = :crt_dt and shift = :shift and 
    //         ib_type = 'P' and rev_ind != 'Y' ";
    $query = "SELECT sum(prd_qty) as totalok from t_prd_daily_i where TO_CHAR(prd_dt, 'YYYYMMDD') = :crt_dt and shift = :shift and line_id = :line_id ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":crt_dt", $param["date"], PDO::PARAM_STR);
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return["totalok"] = $row["totalok"];
        }
    }
    $query = "SELECT sum(ng_qty) as totalng from t_prd_daily_ng where TO_CHAR(prd_dt, 'YYYYMMDD') = :crt_dt and shift = :shift and line_id = :line_id ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":crt_dt", $param["date"], PDO::PARAM_STR);
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return["totalng"] = $row["totalng"];
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
              VALUES (:ib_type, :line_id, :ng_type, :qty, current_timestamp, :shkzg, :shift) ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":ib_type", $param["ib_type"], PDO::PARAM_STR);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":ng_type", $param["ng_type"], PDO::PARAM_STR);
    $stmt->bindValue(":qty", $param["qty"]);
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

if ($action == "api_update_rev") {
    $param = $_REQUEST;
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "UPDATE t_prd_ib SET rev_ind = :rev 
            WHERE ib_id = (select max(ib_id) FROM t_prd_ib where ib_type = :type AND line_id = :line_id AND rev_ind = 'N') ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":rev", $param["rev"], PDO::PARAM_STR);
    $stmt->bindValue(":type", $param["type"], PDO::PARAM_STR);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
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