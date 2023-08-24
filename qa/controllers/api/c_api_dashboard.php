<?php
if ($action == "api_dashboard") {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select a.partno, b.name1, AVG(a.accur) as rasio, to_char(a.prd_dt, 'YYYYMM') as prd_month, a.doc_no 
    from qas.t_tmpl_h a
    left join qas.m_tmpl_h b on b.partno = a.partno
    where 1=1 ";
    if (!empty($_REQUEST["tanggal"])) {
        $tanggal = $_REQUEST["tanggal"];
        $sql .= " and to_char(a.prd_dt, 'YYYY-MM') = '$tanggal'";
    } else {
        $sql .= " and to_char(a.prd_dt, 'YYYYMM') = to_char(current_timestamp, 'YYYYMM')";
    }
    $sql .= " group by 1,2,4,5";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return["data"][] = $row;
        }
    }

    $sql1 = "select a.partno, b.name1
    from qas.m_planning a
    left join qas.m_tmpl_h b on b.partno = a.partno
    where 1=1 ";
    if (!empty($_REQUEST["tanggal"])) {
        $tanggal = $_REQUEST["tanggal"];
        $sql1 .= " and CONCAT(to_char(current_timestamp, 'YYYY'), '-', LPAD(a.month,2,'0')) = '$tanggal' ";
    } else {
        $sql1 .= " LPAD(a.month,2,'0') = to_char(current_timestamp, 'YYYYMM') ";
    }
    $stmt1 = $conn->prepare($sql1);
    if ($stmt1->execute()) {
        while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
            $return["head"][] = $row1;
        }
    }
    echo json_encode($return);
}
?>