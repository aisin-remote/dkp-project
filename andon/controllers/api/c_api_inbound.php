<?php
if ($action == "api_get_ib") {
    $param = $_REQUEST;
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "SELECT SUM(qty) as total from t_prd_ib
            where line_id = 'E11' and crt_dt = '2023-03-23' and shift = '1' and 
            shkzg = 'C' and rev_ind != 'y' ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":line_id", $param["line_id"], PDO::PARAM_STR);
    $stmt->bindValue(":crt_dt", $param["date"], PDO::PARAM_STR);
    $stmt->bindValue(":shift", $param["shift"], PDO::PARAM_STR);
    if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return["total"] = $row["total"];
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

if ($action == "api_update_rev") {
    $param = $_REQUEST;
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $query = "UPDATE t_prd_ib SET rev_ind = :rev WHERE ib_id = (SELECT MAX(ib_id) FROM t_prd_ib where ib_type = :type) ";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":rev", $param["rev"], PDO::PARAM_STR);
    $stmt->bindValue(":type", $param["type"], PDO::PARAM_STR);
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