<?php

class Reporting
{
    public function getById($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, 
                    ld.name1 AS ld_name,
                    jp.name1 AS jp_name,
                    op1.name1 AS op1_name,
                    op2.name1 AS op2_name,
                    op3.name1 AS op3_name,
                    op4.name1 AS op4_name
                FROM t_prd_daily_h a
                LEFT JOIN m_prd_operator ld ON ld.empid = a.ldid
                LEFT JOIN m_prd_operator jp ON jp.empid = a.jpid
                LEFT JOIN m_prd_operator op1 ON op1.empid = a.op1id
                LEFT JOIN m_prd_operator op2 ON op2.empid = a.op2id
                LEFT JOIN m_prd_operator op3 ON op3.empid = a.op3id
                LEFT JOIN m_prd_operator op4 ON op4.empid = a.op4id "
            . "WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' ";

        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":line_id", strtoupper($line_id), PDO::PARAM_STR);
        $stmt->bindValue(":prd_dt", strtoupper($prd_dt), PDO::PARAM_STR);
        $stmt->bindValue(":shift", strtoupper($shift), PDO::PARAM_STR);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getList($date_from = "*", $date_to = "*", $prd_year = null, $prd_month = null, $shift = null, $line_id = null, $ldid = null, $jpid = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.prd_dt, TO_CHAR(a.prd_dt, 'YYYY') as prd_year, TO_CHAR(a.prd_dt, 'MM') as prd_month, 
        a.shift, a.line_id, a.ldid, a.jpid, a.cctime, "
            . "(SELECT coalesce(sum(ng_qty),0) as ng_qty from t_prd_daily_ng where line_id = a.line_id AND shift = a.shift AND prd_dt = a.prd_dt ), "
            . "(SELECT coalesce(sum(prd_qty),0) as prd_qty from t_prd_daily_i where line_id = a.line_id AND shift = a.shift AND prd_dt = a.prd_dt), "
            . "(SELECT coalesce(sum(prd_time),0) as prd_time from t_prd_daily_i where line_id = a.line_id AND shift = a.shift AND prd_dt = a.prd_dt), "
            . "(SELECT coalesce(sum(pln_qty),0) as pln_qty from t_prd_daily_i where line_id = a.line_id AND shift = a.shift AND prd_dt = a.prd_dt), "
            . "(SELECT name1 as ld_name from m_prd_operator where empid = a.ldid), "
            . "(SELECT name1 as jp_name from m_prd_operator where empid = a.jpid), "
            . "(SELECT coalesce(sum(stop_time),0) as stop_time from t_prd_daily_stop where line_id = a.line_id AND shift = a.shift AND prd_dt = a.prd_dt) "
            . "FROM t_prd_daily_h a ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " WHERE TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if (!empty($prd_year)) {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYY') = '$prd_year' ";
        }
        if (!empty($prd_month)) {
            $sql .= " AND TO_CHAR(a.prd_dt, 'MM') = '$prd_month' ";
        }
        if (!empty($shift)) {
            $sql .= " AND a.shift = '$shift' ";
        }
        if (!empty($line_id)) {
            $sql .= " AND a.line_id = '$line_id' ";
        }
        if (!empty($ldid)) {
            $sql .= " AND a.ldid = '$ldid' ";
        }
        if (!empty($jpid)) {
            $sql .= " AND a.jpid = '$jpid' ";
        }
        $sql .= "GROUP BY a.line_id, a.prd_dt, a.shift ";
        $sql .= " ORDER BY a.line_id ASC, a.prd_dt ASC, a.shift ASC ";

        // echo '<pre>';
        // echo $sql;
        // '</pre>';
        // die();

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $prd_qty = floatval($row["prd_qty"]);
                $ng_qty = floatval($row["ng_qty"]);
                $cctime = floatval($row["cctime"]);
                $prd_time = floatval($row["prd_time"]);

                if ($prd_time == 0) {
                    $efficiency = 0;
                } else {
                    $efficiency = (($prd_qty - $ng_qty) * $cctime / 60) / $prd_time;
                }

                $roundEff = round($efficiency, 3);
                $totalEff = $roundEff * 100;

                $row["eff"] = $totalEff;
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getList2($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, a.cctime, a.pln_qty, a.prd_qty, a.prd_time, a.apr_by, "
            . "(SELECT name1 FROM m_user WHERE usrid = a.apr_by) as apr_name, "
            . "(SELECT name1 FROM m_dm_dies_asset WHERE dies_id::character varying = a.dies_id), "
            . "(SELECT COALESCE(SUM(ng_qty), 0) as tot_ng FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND prd_seq = a.prd_seq), "
            . "(SELECT SUM(ng_qty) as tot_ng2 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift), "
            . "(SELECT COALESCE(SUM(stop_time), 0) as loss_time FROM t_prd_daily_stop WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND prd_seq = a.prd_seq) "
            . "FROM t_prd_daily_i a "
            . "WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' ";
        $sql .= " ORDER BY  a.prd_seq";

        // echo $sql;
        // die();

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $tot_pln_qty = 0;
            $tot_prd_qty = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $prd_qty = floatval($row["prd_qty"]);
                $ng_qty = floatval($row["ng_qty"]);
                $cctime = floatval($row["cctime"]);
                $prd_time = floatval($row["prd_time"]);
                $pln_qty = floatval($row["pln_qty"]);

                $tot_pln_qty += $pln_qty;
                $row["tot_pln_qty"] = $tot_pln_qty;

                $tot_prd_qty += $prd_qty;
                $row["tot_prd_qty"] = $tot_prd_qty;

                if ($prd_time == 0) {
                    $efficiency = 0;
                } else {
                    $efficiency = (($prd_qty - $ng_qty) * $cctime / 60) / $prd_time;
                }

                $roundEff = round($efficiency, 3);
                $totalEff = $roundEff * 100;

                $row["eff"] = $totalEff;

                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getList3($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.group_id, b.model_id, b.dies_no, c.loss_time_p, d.loss_time, e.tot_ng, 
        COALESCE(f.ril, 0) as ril, g.rol1, h.rol2, i.rol3, j.rol4, k.rol5, l.rol6, m.rol7, n.rol8, o.rol9, p.rol10
                FROM t_prd_daily_i a 
                INNER JOIN m_dm_dies_asset b ON b.dies_id::character varying = a.dies_id
                LEFT JOIN (
                    SELECT a.line_id, a.prd_dt, a.shift, SUM(a.stop_time) AS loss_time_p 
                    FROM t_prd_daily_stop a 
                    INNER JOIN m_prd_stop_reason_action b ON a.stop_id = b.srna_id
                    WHERE b.type2 = 'P' 
                    GROUP BY a.line_id, a.prd_dt, a.shift
                ) c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, SUM(stop_time) as loss_time
                    FROM t_prd_daily_stop
                    GROUP BY line_id, prd_dt, shift
                ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, SUM(ng_qty) as tot_ng
                    FROM t_prd_daily_ng
                    GROUP BY line_id, prd_dt, shift
                ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as ril
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'RIL%'
                    GROUP BY line_id, prd_dt, shift
                ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol1
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL1'
                    GROUP BY line_id, prd_dt, shift
                ) g ON a.line_id = g.line_id AND a.prd_dt = g.prd_dt AND a.shift = g.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol2
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL2'
                    GROUP BY line_id, prd_dt, shift
                ) h ON a.line_id = h.line_id AND a.prd_dt = h.prd_dt AND a.shift = h.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol3
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL3'
                    GROUP BY line_id, prd_dt, shift
                ) i ON a.line_id = i.line_id AND a.prd_dt = i.prd_dt AND a.shift = i.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol4
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL4'
                    GROUP BY line_id, prd_dt, shift
                ) j ON a.line_id = j.line_id AND a.prd_dt = j.prd_dt AND a.shift = j.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol5
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL5'
                    GROUP BY line_id, prd_dt, shift
                ) k ON a.line_id = k.line_id AND a.prd_dt = k.prd_dt AND a.shift = k.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol6
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL6'
                    GROUP BY line_id, prd_dt, shift
                ) l ON a.line_id = l.line_id AND a.prd_dt = l.prd_dt AND a.shift = l.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol7
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL7'
                    GROUP BY line_id, prd_dt, shift
                ) m ON a.line_id = m.line_id AND a.prd_dt = m.prd_dt AND a.shift = m.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol8
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL8'
                    GROUP BY line_id, prd_dt, shift
                ) n ON a.line_id = n.line_id AND a.prd_dt = n.prd_dt AND a.shift = n.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol9
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL9'
                    GROUP BY line_id, prd_dt, shift
                ) o ON a.line_id = o.line_id AND a.prd_dt = o.prd_dt AND a.shift = o.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol10
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL10'
                    GROUP BY line_id, prd_dt, shift
                ) p ON a.line_id = p.line_id AND a.prd_dt = p.prd_dt AND a.shift = p.shift "
            . "WHERE  a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' ";

        // echo $sql;
        // die();
        // echo $sql;
        // die();

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getPrdTime($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT SUM(prd_time) as nett_opr FROM t_prd_daily_i "
            . "WHERE line_id = '$line_id' AND prd_dt = '$prd_dt' AND shift = '$shift' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
}
