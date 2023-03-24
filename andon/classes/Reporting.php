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
        $sql = "SELECT a.prd_dt, h.name1 AS line_name, TO_CHAR(a.prd_dt, 'YYYY') as prd_year, TO_CHAR(a.prd_dt, 'MM') as prd_month, 
                       a.shift, a.line_id, a.ldid, a.jpid, a.cctime, coalesce(b.ng_qty, 0) as ng_qty, 
                       coalesce(c.prd_qty,0) as prd_qty, coalesce(c.prd_time,0) as prd_time, 
                       coalesce(c.pln_qty,0) as pln_qty, coalesce(d.stop_time, 0) as stop_time, e.ld_name, f.jp_name
                FROM t_prd_daily_h a 
                LEFT JOIN (
                    SELECT line_id, shift, prd_dt, coalesce(sum(ng_qty),0) as ng_qty 
                    FROM t_prd_daily_ng
                    GROUP BY line_id, shift, prd_dt
                ) b ON a.line_id = b.line_id AND a.shift = b.shift AND a.prd_dt = b.prd_dt
                LEFT JOIN (
                    SELECT line_id, shift, prd_dt, coalesce(sum(prd_qty),0) as prd_qty, 
                           coalesce(sum(prd_time),0) as prd_time, coalesce(sum(pln_qty),0) as pln_qty
                    FROM t_prd_daily_i
                    GROUP BY line_id, shift, prd_dt
                ) c ON a.line_id = c.line_id AND a.shift = c.shift AND a.prd_dt = c.prd_dt
                LEFT JOIN (
                    SELECT line_id, shift, prd_dt, coalesce(sum(stop_time),0) as stop_time
                    FROM t_prd_daily_stop
                    GROUP BY line_id, shift, prd_dt
                ) d ON a.line_id = d.line_id AND a.shift = d.shift AND a.prd_dt = d.prd_dt
                LEFT JOIN (
                    SELECT empid, name1 as ld_name 
                    FROM m_prd_operator
                ) e ON a.ldid = e.empid
                LEFT JOIN (
                    SELECT empid, name1 as jp_name 
                    FROM m_prd_operator
                ) f ON a.jpid = f.empid 
                LEFT JOIN (
                    SELECT line_id, line_ty
                    FROM m_prd_line
                    WHERE line_ty = 'ECU'
                ) g ON a.line_id = g.line_id
                LEFT JOIN m_prd_line h ON h.line_id = a.line_id 
                WHERE a.line_id = g.line_id ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
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
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, a.cctime, "
            . "a.pln_qty, coalesce(a.prd_qty, 0) as prd_qty, a.prd_time, a.apr_by, b.name1 as apr_name, "
            . "c.name1, coalesce(d.ng_qty, 0) as tot_ng, coalesce(f.ng_qty, 0) as tot_ng2, coalesce(e.stop_time, 0) as loss_time, coalesce(e.stop_count, 0) as stop_cnt "
            . "FROM t_prd_daily_i a "
            . "LEFT JOIN m_user b ON a.apr_by = b.usrid "
            . "LEFT JOIN wms.m_mara c ON a.dies_id = c.matnr "
            . "LEFT JOIN (
                SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(ng_qty), 0) as ng_qty
                FROM t_prd_daily_ng
                GROUP BY line_id, prd_dt, shift, prd_seq
            ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift AND a.prd_seq = d.prd_seq "
            . "LEFT JOIN (
                SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(stop_time), 0) as stop_time, COALESCE(COUNT(*), 0) as stop_count
                FROM t_prd_daily_stop
                GROUP BY line_id, prd_dt, shift, prd_seq
            ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift AND a.prd_seq = e.prd_seq "
            . "LEFT JOIN (
                SELECT a.line_id, a.prd_dt, a.shift, COALESCE(SUM(a.ng_qty), 0) as ng_qty
                FROM t_prd_daily_ng a
                LEFT JOIN (
                    SELECT line_id, line_ty, name1 AS line_name
                    FROM m_prd_line
                    WHERE line_ty = 'ECU'
                ) b ON a.line_id = b.line_id
                WHERE a.line_id = b.line_id
                GROUP BY a.line_id, a.prd_dt, a.shift
            ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift "
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
        $sql = "SELECT a.*, b.mtart, b.name1, c.loss_time_p, coalesce(d.loss_time, 0) as loss_time, e.tot_ng, 
        COALESCE(f.ng_qty, 0) as ril, coalesce(g.ng_qty, 0) as rol1, coalesce(h.ng_qty, 0) as rol2, coalesce(i.ng_qty, 0) as rol3, coalesce(j.ng_qty, 0) as rol4, coalesce(k.ng_qty, 0) as rol5, coalesce(l.ng_qty, 0) as rol6, coalesce(m.ng_qty, 0) as rol7, coalesce(n.ng_qty, 0) as rol8, coalesce(o.ng_qty, 0) as rol9, coalesce(p.ng_qty, 0) as rol10
                FROM t_prd_daily_i a 
                INNER JOIN wms.m_mara b ON b.matnr = a.dies_id
                LEFT JOIN (
                    SELECT a.line_id, a.prd_dt, a.shift, SUM(a.stop_time) AS loss_time_p 
                    FROM t_prd_daily_stop a 
                    INNER JOIN m_prd_stop_reason_action b ON a.stop_id = b.srna_id
                    WHERE b.type2 = 'P' 
                    GROUP BY a.line_id, a.prd_dt, a.shift
                ) c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift
                LEFT JOIN (
                    SELECT a.line_id, a.prd_dt, a.shift, SUM(a.stop_time) AS loss_time 
                    FROM t_prd_daily_stop a 
                    INNER JOIN m_prd_stop_reason_action b ON a.stop_id = b.srna_id
                    WHERE b.type2 = 'U' 
                    GROUP BY a.line_id, a.prd_dt, a.shift
                ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, SUM(ng_qty) as tot_ng
                    FROM t_prd_daily_ng
                    GROUP BY line_id, prd_dt, shift
                ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as ril, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'RIL%'
                    GROUP BY line_id, prd_dt, shift
                ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol1, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL1'
                    GROUP BY line_id, prd_dt, shift
                ) g ON a.line_id = g.line_id AND a.prd_dt = g.prd_dt AND a.shift = g.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol2, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL2'
                    GROUP BY line_id, prd_dt, shift
                ) h ON a.line_id = h.line_id AND a.prd_dt = h.prd_dt AND a.shift = h.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol3, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL3'
                    GROUP BY line_id, prd_dt, shift
                ) i ON a.line_id = i.line_id AND a.prd_dt = i.prd_dt AND a.shift = i.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol4, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL4'
                    GROUP BY line_id, prd_dt, shift
                ) j ON a.line_id = j.line_id AND a.prd_dt = j.prd_dt AND a.shift = j.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol5, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL5'
                    GROUP BY line_id, prd_dt, shift
                ) k ON a.line_id = k.line_id AND a.prd_dt = k.prd_dt AND a.shift = k.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol6, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL6'
                    GROUP BY line_id, prd_dt, shift
                ) l ON a.line_id = l.line_id AND a.prd_dt = l.prd_dt AND a.shift = l.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol7, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL7'
                    GROUP BY line_id, prd_dt, shift
                ) m ON a.line_id = m.line_id AND a.prd_dt = m.prd_dt AND a.shift = m.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol8, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL8'
                    GROUP BY line_id, prd_dt, shift
                ) n ON a.line_id = n.line_id AND a.prd_dt = n.prd_dt AND a.shift = n.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol9, SUM(ng_qty) as ng_qty
                    FROM t_prd_daily_ng
                    WHERE ng_type LIKE 'ROL9'
                    GROUP BY line_id, prd_dt, shift
                ) o ON a.line_id = o.line_id AND a.prd_dt = o.prd_dt AND a.shift = o.shift
                LEFT JOIN (
                    SELECT line_id, prd_dt, shift, COUNT(ng_type) as rol10, SUM(ng_qty) as ng_qty
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
                $rol = $row["rol1"] + $row["rol2"] + $row["rol3"] + $row["rol4"] + $row["rol5"] + $row["rol6"] + $row["rol7"] + $row["rol8"] + $row["rol9"] + $row["rol10"];
                $row["rol"] = $rol;
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

    public function getReportDetail($date_from = "*", $date_to = "*", $shift = null, $line_id = null, $ldid = null, $jpid = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.prd_dt, a.prd_time, a.shift, c.name1 AS line_name, e.name1 AS operator, b.name1 AS dies_name, a.time_start, a.time_end, a.cctime, a.pln_qty, a.prd_qty, h.name1 AS apr_name,
                (select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq)
                        FROM t_prd_daily_i a
                        LEFT JOIN wms.m_mara b ON b.matnr = a.dies_id
                        INNER JOIN m_prd_line c ON c.line_id = a.line_id AND c.line_ty = 'ECU'
                        INNER JOIN t_prd_daily_h d ON d.prd_dt = a.prd_dt AND d.shift = a.shift AND d.line_id = a.line_id
                        INNER JOIN m_prd_operator e ON e.empid = d.jpid
                        LEFT JOIN t_prd_daily_ng f ON f.prd_dt = a.prd_dt AND f.shift = a.shift AND f.line_id = a.line_id
                        LEFT JOIN t_prd_daily_stop g ON g.prd_dt = a.prd_dt AND g.shift = a.shift AND g.line_id = a.line_id
                        LEFT JOIN m_user h ON h.usrid = a.apr_by ";

        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
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

        $sql .= " ORDER BY a.shift, a.prd_seq ASC ";

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

    public function getReportStop($date_from = "*", $date_to = "*", $shift = null, $line_id = null, $ldid = null, $jpid = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.prd_dt, a.shift, c.name1 AS line_name, e.name1 AS operator, b.name1 AS dies_name, a.time_start, a.time_end, a.cctime, a.pln_qty, a.prd_qty, a.apr_by, f.start_time, f.end_time, f.stop_time, f.qty_stc, g.type3, g.type4, g.name1 AS stop, h.name1 AS action, f.remarks, i.name1 AS eksekutor 
                FROM t_prd_daily_i a
                LEFT JOIN wms.m_mara b ON b.matnr = a.dies_id
                INNER JOIN m_prd_line c ON c.line_id = a.line_id AND c.line_ty = 'ECU'
                INNER JOIN t_prd_daily_h d ON d.prd_dt = a.prd_dt AND d.shift = a.shift AND d.line_id = a.line_id
                INNER JOIN m_prd_operator e ON e.empid = d.jpid
                LEFT JOIN t_prd_daily_stop f ON f.prd_dt = a.prd_dt AND f.shift = a.shift AND f.line_id = a.line_id
                LEFT JOIN m_prd_stop_reason_action g ON g.srna_id = f.stop_id
                LEFT JOIN m_prd_stop_reason_action h ON h.srna_id = f.action_id
                LEFT JOIN m_prd_operator i ON i.empid = f.exe_empid	
                WHERE 1=1 ";

        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
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

        $sql .= " ORDER BY a.shift, a.prd_seq ASC ";

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

                // echo $totalEff;
                // die();

                $row["eff"] = $totalEff;

                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getLossTime($date_from = "*", $date_to = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, TO_CHAR(a.start_time, 'YYYY-MM-DD HH24:MI:SS') as start, TO_CHAR(a.proses_time, 'YYYY-MM-DD HH24:MI:SS') as proses, TO_CHAR(a.end_time, 'YYYY-MM-DD HH24:MI:SS') as end, b.*, b.name1 as line_name, c.*, c.name1 as mach_name, round(cast(EXTRACT(EPOCH FROM (a.end_time - a.start_time)/60::numeric) as numeric),2) AS duration, d.* from t_stop_time a
                left join m_prd_line b on b.line_id = a.line_id
                left join m_prd_mach c on c.mach_id = a.mach_id
                left join m_andon_status d on d.andon_id = a.andon_id 
                where 1=1 ORDER BY a.start_time DESC ";

        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["status"] == "X") {
                    $row["status"] = "Transfer";
                } else if ($row["status"] == "C") {
                    $row["status"] = "Complete";
                } else if ($row["status"] == "P") {
                    $row["status"] = "Proses";
                } else if ($row["status"] == "N") {
                    $row["status"] = "New";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
}