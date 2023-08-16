<?php
class Report
{
    public function getListHeader($date_from = "*", $date_to = "*", $shift = "*", $partid = "*", $status = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, TO_CHAR(a.prd_dt, 'DD-MM-YYYY') as date, TO_CHAR(a.prd_dt, 'HH24:MI:SS') as time, b.partno, b.name1 as partname, c.name1 as inspector, c.empid, d.pval1 from qas.t_tmpl_h a
        left join qas.m_tmpl_h b on b.partno = a.partno
        left join m_prd_operator c on c.empid = a.inspector
        left join m_param d on d.seq = a.shift and d.pid = 'SHIFT'
        where 1=1 ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift !== "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($partid !== "*") {
            $sql .= " AND a.partno = '$partid' ";
        }
        if ($status !== "*") {
            $sql .= " AND a.approval = '$status' ";
        }
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                if ($row["status"] == "N") {
                    $row["stats"] = "On Progress";
                } else {
                    $row["stats"] = "Completed";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListTrans($date_from = "*", $date_to = "*", $shift = "*", $partid = "*", $status = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, b.*, c.name1 as inspector from qas.t_tmpl_h a
        left join qas.m_tmpl_h b on b.partno = a.partno
        left join m_prd_operator c on c.empid = a.inspector
        where 1=1 ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift !== "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($partid !== "*") {
            $sql .= " AND a.partno = '$partid' ";
        }
        if ($status !== "*") {
            $sql .= " AND a.approval = '$status' ";
        }
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
}
?>