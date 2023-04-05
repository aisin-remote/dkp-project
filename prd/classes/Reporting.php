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
                    WHERE line_ty = 'DM'
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
                    $efficiency = ($prd_qty * ($cctime / 60)) / $prd_time;
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
            . "c.name1, c.model_id, coalesce(d.ng_qty, 0) as tot_ng, coalesce(f.ng_qty, 0) as tot_ng2, coalesce(e.stop_time, 0) as loss_time, coalesce(SUM(x.ng_qty), 0) as steuchi "
            . "FROM t_prd_daily_i a "
            . "LEFT JOIN m_user b ON a.apr_by = b.usrid "
            . "LEFT JOIN m_dm_dies_asset c ON a.dies_id::integer = c.dies_id "
            . "LEFT JOIN (
                SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(ng_qty), 0) as ng_qty
                FROM t_prd_daily_ng
                GROUP BY line_id, prd_dt, shift, prd_seq
            ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift AND a.prd_seq = d.prd_seq "
            . "LEFT JOIN (
                SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(stop_time), 0) as stop_time
                FROM t_prd_daily_stop
                GROUP BY line_id, prd_dt, shift, prd_seq
            ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift AND a.prd_seq = e.prd_seq "
            . "LEFT JOIN (
                SELECT a.line_id, a.prd_dt, a.shift, COALESCE(SUM(a.ng_qty), 0) as ng_qty
                FROM t_prd_daily_ng a
                LEFT JOIN (
                    SELECT line_id, line_ty, name1 AS line_name
                    FROM m_prd_line
                    WHERE line_ty = 'DM'
                ) b ON a.line_id = b.line_id
                WHERE a.line_id = b.line_id
                GROUP BY a.line_id, a.prd_dt, a.shift
            ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift "
            . "LEFT JOIN ( 
                SELECT e.pval2, d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a 
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                INNER JOIN m_param e ON a.ng_type = e.pval1
                WHERE a.ng_type LIKE 'ROL%' AND a.line_id SIMILAR TO '[0-9]+' AND e.pval2 LIKE '%STEUCHI%'
                GROUP BY 1,2,3,4,5
            ) x ON a.line_id = x.line_id AND a.prd_dt = x.prd_dt AND a.shift = x.shift AND c.model_id = x.model_id "
            . "WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' ";
        $sql .= " GROUP BY 1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17 ";
        $sql .= " ORDER BY  a.prd_seq";

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
        $sql = "SELECT SUM(a.prd_qty) as prd_qty, SUM(a.prd_time) as nett_opr, a.cctime, b.group_id, b.group_id, b.model_id, b.dies_no, coalesce(c.loss_time_p, 0) as loss_time_p, coalesce(d.loss_time, 0) as loss_time, e.tot_ng, COALESCE(f.ng_qty, 0) as ril, COALESCE(z.ng_qty, 0) as rol, 
                    coalesce(g.ng_qty, 0) as rol1, coalesce(h.ng_qty, 0) as rol2, coalesce(i.ng_qty, 0) as rol3, coalesce(j.ng_qty, 0) as rol4, 
                    coalesce(k.ng_qty, 0) as rol5, coalesce(l.ng_qty, 0) as rol6, coalesce(m.ng_qty, 0) as rol7, coalesce(n.ng_qty, 0) as rol8, 
                    coalesce(o.ng_qty, 0) as rol9, coalesce(p.ng_qty, 0) as rol10 
            FROM t_prd_daily_i a 
            INNER JOIN m_dm_dies_asset b ON b.dies_id::character varying = a.dies_id 
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, SUM(a.stop_time) AS loss_time_p 
                FROM t_prd_daily_stop a 
                INNER JOIN m_prd_stop_reason_action b ON a.stop_id = b.srna_id
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id::bigint = d.dies_id
                WHERE b.type2 = 'P' AND b.app_id = 'AISIN_PRD' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND b.model_id = c.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, SUM(a.stop_time) AS loss_time 
                FROM t_prd_daily_stop a 
                INNER JOIN m_prd_stop_reason_action b ON a.stop_id = b.srna_id 
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id::bigint = d.dies_id
                WHERE b.type2 = 'U' AND b.app_id = 'AISIN_PRD' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift AND b.model_id = d.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, SUM(a.ng_qty) as tot_ng 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id::bigint = d.dies_id
                WHERE a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift AND b.model_id = e.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as ril, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'RIL%' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift AND b.model_id = f.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a 
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL%' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) z ON a.line_id = z.line_id AND a.prd_dt = z.prd_dt AND a.shift = z.shift AND b.model_id = z.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol1, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a 
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL1' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) g ON a.line_id = g.line_id AND a.prd_dt = g.prd_dt AND a.shift = g.shift AND b.model_id = g.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol2, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL2' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) h ON a.line_id = h.line_id AND a.prd_dt = h.prd_dt AND a.shift = h.shift AND b.model_id = h.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol3, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL3' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) i ON a.line_id = i.line_id AND a.prd_dt = i.prd_dt AND a.shift = i.shift AND b.model_id = i.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol4, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a 
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL4' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) j ON a.line_id = j.line_id AND a.prd_dt = j.prd_dt AND a.shift = j.shift AND b.model_id = j.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol5, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL5' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) k ON a.line_id = k.line_id AND a.prd_dt = k.prd_dt AND a.shift = k.shift AND b.model_id = k.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol6, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL6' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) l ON a.line_id = l.line_id AND a.prd_dt = l.prd_dt AND a.shift = l.shift AND b.model_id = l.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol7, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL7' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) m ON a.line_id = m.line_id AND a.prd_dt = m.prd_dt AND a.shift = m.shift AND b.model_id = m.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol8, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL8' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) n ON a.line_id = n.line_id AND a.prd_dt = n.prd_dt AND a.shift = n.shift AND b.model_id = m.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol9, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL9' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4
            ) o ON a.line_id = o.line_id AND a.prd_dt = o.prd_dt AND a.shift = o.shift AND b.model_id = m.model_id
            LEFT JOIN ( 
                SELECT d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol10, SUM(a.ng_qty) as ng_qty 
                FROM t_prd_daily_ng a
                INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                WHERE a.ng_type LIKE 'ROL10' AND a.line_id SIMILAR TO '[0-9]+'
                GROUP BY 1,2,3,4 
            ) p ON a.line_id = p.line_id AND a.prd_dt = p.prd_dt AND a.shift = p.shift 
            WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' "
            . "GROUP BY 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22 ";

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nett_opr = $row["nett_opr"];
                $tot_ng = $row["tot_ng"];
                $prd_qty = $row["prd_qty"];
                $cctime = $row["cctime"];
                $losstime = $row["loss_time"];
                $ril = $row["ril"];
                $rol = $row["rol"];

                $tot_qty = $prd_qty + $tot_ng;
                $waktu_shift = $nett_opr + $row["loss_time_p"];
                $efficiency = (($prd_qty * $cctime) / 60) / $nett_opr;
                $roundEff2 = round($efficiency, 3);
                $totalEff2 = $roundEff2 * 100;
                $loss_persen = $losstime / $nett_opr * 100;
                $roundloss = round($loss_persen, 3);
                $persen_ril = $ril * $cctime / 60 / $nett_opr * 100;
                $roundril = round($persen_ril, 2);
                $persen_rol = $rol * $cctime / 60 / $nett_opr * 100;
                $roundrol = round($persen_rol, 2);
                $total = $totalEff2 + $roundloss + $roundril + $roundrol;

                $row["total%"] = $total;
                $row["ril%"] = $roundril;
                $row["rol%"] = $roundrol;
                $row["loss%"] = $roundloss;
                $row["eff"] = $totalEff2;
                $row["tot_qty"] = $tot_qty;
                $row["nett_opr"] = $nett_opr;
                $row["waktu_shift"] = $waktu_shift;
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
        $sql = "SELECT a.*, b.*, c.*, c.name1 as dies_name, d.*, e.*, e.name1 as line_name, f.name1 as operator,
                (select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq), g.name1 as apr_name
                from t_prd_daily_i a
                left join t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                left join m_dm_dies_asset c on c.dies_id = CAST(a.dies_id as bigint)
                left join m_param d on d.pid = 'SHIFT' and d.seq = a.shift
                left join m_prd_line e on e.line_id = a.line_id and e.line_ty = 'DM'
                left join m_prd_operator f on f.empid = b.jpid
                left join m_user g on g.usrid = a.apr_by
                where 1=1 ";
        // $sql = "SELECT a.prd_dt, a.prd_time, a.shift, c.name1 AS line_name, e.name1 AS operator, b.name1 AS dies_name, a.time_start, a.time_end, a.cctime, a.pln_qty, a.prd_qty, coalesce(f.ng_qty, 0) as tot_ng, COALESCE(g.stop_time, 0) as loss_time, h.name1 AS apr_name 
        //         FROM t_prd_daily_i a
        //         INNER JOIN m_dm_dies_asset b ON b.dies_id = CAST(a.dies_id as bigint)
        //         INNER JOIN m_prd_line c ON c.line_id = a.line_id AND c.line_ty = 'DM'
        //         INNER JOIN t_prd_daily_h d ON d.prd_dt = a.prd_dt AND d.shift = a.shift AND d.line_id = a.line_id
        //         INNER JOIN m_prd_operator e ON e.empid = d.jpid
        //         LEFT JOIN t_prd_daily_ng f ON f.prd_dt = a.prd_dt AND f.shift = a.shift AND f.line_id = a.line_id
        //         LEFT JOIN t_prd_daily_stop g ON g.prd_dt = a.prd_dt AND g.shift = a.shift AND g.line_id = a.line_id
        //         LEFT JOIN m_user h ON h.usrid = a.apr_by
        //         WHERE 1=1 ";

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

        $sql .= " ORDER BY a.shift, a.line_id, a.prd_seq ASC ";

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
        $sql = "SELECT a.*, b.*, c.*, d.name1 as dies_name, d.*, e.*, f.name1 as line_name, g.name1 as operator, g.*, h.*, h.name1 as stop, i.name1 as eksekutor,
                (select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq)
                from t_prd_daily_i a
                inner join t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                inner join t_prd_daily_stop c on c.prd_dt = a.prd_dt and c.shift = a.shift and c.line_id = a.line_id and c.prd_seq = a.prd_seq
                inner join m_dm_dies_asset d on d.dies_id = CAST(a.dies_id as bigint)
                inner join m_param e on e.pid = 'SHIFT' and e.seq = a.shift
                inner join m_prd_line f on f.line_id = a.line_id and f.line_ty = 'DM'
                inner join m_prd_operator g on g.empid = b.jpid
                inner join m_prd_stop_reason_action h on h.srna_id = c.stop_id
                left join m_prd_operator i on i.empid = c.exe_empid
                where 1=1 ";

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

    public function getSteuchiList($line_id, $prd_dt, $shift, $prd_seq)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.prd_seq, e.pval2, d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as steuchi
        FROM t_prd_daily_ng a 
        INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq 
        INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying 
        INNER JOIN m_param e ON a.ng_type = e.pval1 
        WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' AND a.prd_seq = '$prd_seq' AND a.ng_type LIKE 'ROL%' AND a.line_id SIMILAR TO '[0-9]+' AND e.pval2 LIKE '%STEUCHI%' 
        GROUP BY 1,2,3,4,5,6 ";
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute() or die($stmt->errorInfo()[2])) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
}