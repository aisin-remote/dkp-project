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
                    op4.name1 AS op4_name,
                    op5.name1 AS op5_name,
                    op6.name1 AS op6_name,
                    op7.name1 AS op7_name,
                    op8.name1 AS op8_name
                FROM t_prd_daily_h a
                LEFT JOIN m_prd_operator ld ON ld.empid = a.ldid
                LEFT JOIN m_prd_operator jp ON jp.empid = a.jpid
                LEFT JOIN m_prd_operator op1 ON op1.empid = a.op1id
                LEFT JOIN m_prd_operator op2 ON op2.empid = a.op2id
                LEFT JOIN m_prd_operator op3 ON op3.empid = a.op3id
                LEFT JOIN m_prd_operator op4 ON op4.empid = a.op4id
                LEFT JOIN m_prd_operator op5 ON op5.empid = a.op5id
                LEFT JOIN m_prd_operator op6 ON op6.empid = a.op6id
                LEFT JOIN m_prd_operator op7 ON op7.empid = a.op7id
                LEFT JOIN m_prd_operator op8 ON op8.empid = a.op8id "
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
        $sql = "select t.*,
                (select sum(stop_time) as stop_time from t_prd_daily_stop where prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id),
                (select coalesce(sum(ng_qty),0) as ng_tot FROM t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id), 
                (select coalesce(sum(ng_qty),0) as ng_ril FROM t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id AND ng_type LIKE 'RIL%'), 
                (select coalesce(sum(ng_qty),0) as ng_rol FROM t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id AND ng_type LIKE 'ROL%'), 
                round((t.prd_qty * t.cctime / 60 / t.prd_time * 100)::numeric, 2) as eff from 
                (select TO_CHAR(a.prd_dt, 'YYYY') as prd_year, TO_CHAR(a.prd_dt,'MM') as prd_month, f.pval1,
                a.prd_dt, a.line_id, b.name1 as line_name, a.shift, d.name1 as ld_name, e.name1 as jp_name,
                AVG(a.cctime) as cctime, sum(a.prd_time) as prd_time, sum(a.pln_qty) as pln_qty, sum(a.prd_qty) as prd_qty
                from t_prd_daily_i a 
                inner join m_prd_line b ON b.line_id = a.line_id and b.line_ty = 'ECU'
                inner join t_prd_daily_h c on c.prd_dt = a.prd_dt and c.line_id = a.line_id and c.shift = a.shift
                left join m_prd_operator d on d.empid = c.ldid
                left join m_prd_operator e on e.empid = c.jpid
                inner join m_param f on f.pid = 'SHIFT' and f.seq = a.shift
                where 1=1 ";
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
        $sql .= " group by 1,2,3,4,5,6,7,8,9) t ";

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

                // $row["eff"] = $totalEff;
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
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, a.cctime, 
        a.pln_qty, coalesce(a.prd_qty, 0) as prd_qty, a.prd_time, a.apr_by, b.name1 as apr_name, 
        c.name1, c.mtart, c.backno, coalesce(d.ng_qty, 0) as tot_ng, coalesce(f.ng_qty, 0) as tot_ng2, coalesce(e.stop_time, 0) as loss_time, coalesce(SUM(x.ng_qty), 0) as steuchi, coalesce(a.wip, 0) as wip, a.real_dt 
        FROM t_prd_daily_i a 
        LEFT JOIN m_user b ON a.apr_by = b.usrid 
        LEFT JOIN wms.m_mara c ON a.dies_id = c.matnr
        LEFT JOIN (
                        SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(ng_qty), 0) as ng_qty
                        FROM t_prd_daily_ng
                        GROUP BY line_id, prd_dt, shift, prd_seq
                    ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift AND a.prd_seq = d.prd_seq
                    LEFT JOIN (
                        SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(stop_time), 0) as stop_time
                        FROM t_prd_daily_stop
                        GROUP BY line_id, prd_dt, shift, prd_seq
                    ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift AND a.prd_seq = e.prd_seq 
                    LEFT JOIN (
                        SELECT a.line_id, a.prd_dt, a.shift, COALESCE(SUM(a.ng_qty), 0) as ng_qty
                        FROM t_prd_daily_ng a
                        LEFT JOIN (
                            SELECT line_id, line_ty, name1 AS line_name
                            FROM m_prd_line
                            WHERE line_ty = 'ECU'
                        ) b ON a.line_id = b.line_id
                        WHERE a.line_id = b.line_id
                        GROUP BY a.line_id, a.prd_dt, a.shift
                    ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift 
                    LEFT JOIN ( 
                        SELECT e.pval2, d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as ng_qty 
                        FROM t_prd_daily_ng a 
                        INNER JOIN t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                        INNER JOIN m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                        INNER JOIN m_param e ON a.ng_type = e.pval1
                        WHERE a.ng_type LIKE 'ROL%' AND a.line_id SIMILAR TO '[0-9]+' AND e.pval2 LIKE '%STEUCHI%'
                        GROUP BY 1,2,3,4,5
                    ) x ON a.line_id = x.line_id AND a.prd_dt = x.prd_dt AND a.shift = x.shift
                    WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift'
                    GROUP BY 1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17,18
                    ORDER BY a.real_dt, a.time_start asc ";

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
                } else if ($pln_qty < 0) {
                    $efficiency = 0;
                } else {
                    $efficiency = ($prd_qty * $cctime / 60) / $prd_time * 100;
                }

                $roundEff = round($efficiency, 2);
                // $totalEff = $roundEff * 100;

                $row["eff"] = $roundEff;

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
        $sql = "select t.*,  
        round((t.prd_qty * t.cctime / 60 / t.prd_time * 100)::numeric, 2) as eff from 
        (select TO_CHAR(a.prd_dt,'DD-MM-YYYY') as prd_dty, TO_CHAR(a.prd_dt,'MM') as prd_dtm, 
        a.prd_dt, a.line_id, b.name1 as line_name, a.shift, c.mtart, c.name1, 
        coalesce(AVG(a.cctime),0) as cctime, 
        coalesce(sum(a.prd_time),0) as prd_time, 
        coalesce(sum(a.pln_qty),0) as pln_qty, 
        coalesce(sum(a.prd_qty),0) as prd_qty, 
        coalesce(sum(a.wip),0) as wip,
        sum((select coalesce(sum(stop_time),0) FROM t_prd_daily_stop s inner join m_prd_stop_reason_action b ON b.srna_id = s.stop_id AND b.app_id = 'AISIN_PRD' AND b.type1 = 'S' where s.prd_dt = a.prd_dt AND s.shift = a.shift AND s.line_id = a.line_id AND s.prd_seq = a.prd_seq AND b.type2 = 'P' )) as loss_time_p,
        sum((select coalesce(sum(stop_time),0) FROM t_prd_daily_stop s inner join m_prd_stop_reason_action b ON b.srna_id = s.stop_id AND b.app_id = 'AISIN_PRD' AND b.type1 = 'S' where s.prd_dt = a.prd_dt AND s.shift = a.shift AND s.line_id = a.line_id AND s.prd_seq = a.prd_seq AND b.type2 = 'U' )) as loss_time,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq)) as ng_tot,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type like 'RIL%')) as ng_ril,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type like 'ROL%')) as ng_rol,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL1')) as ng_rol1,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL2')) as ng_rol2,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL3')) as ng_rol3,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL4')) as ng_rol4,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL5')) as ng_rol5,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL6')) as ng_rol6,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL7')) as ng_rol7,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL8')) as ng_rol8,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL9')) as ng_rol9,
        sum((select coalesce(sum(ng_qty),0) FROM t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL10')) as ng_rol10
        from t_prd_daily_i a 
        inner join m_prd_line b ON b.line_id = a.line_id and b.line_ty = 'ECU'
        inner join wms.m_mara c on c.matnr = a.dies_id
        where a.prd_dt = '$prd_dt' and a.shift = '$shift' AND a.line_id = '$line_id'
        group by 1,2,3,4,5,6,7,8) t ";

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nett_opr = $row["nett_opr"];
                $tot_ng = $row["ng_tot"];
                $prd_qty = $row["prd_qty"];
                $cctime = $row["cctime"];
                $losstime = $row["loss_time"];
                $ril = $row["ng_ril"];
                $rol = $row["ng_rol"];
                $wip = $row["wip"];

                $tot_qty = $prd_qty + $tot_ng + $wip;
                $waktu_shift = $row["prd_time"] + $row["loss_time_p"];
                // $efficiency = (($prd_qty * $cctime) / 60) / $row["prd_time"];
                // $roundEff2 = round($efficiency, 2);
                // $totalEff2 = $roundEff2 * 100;
                $loss_persen = $losstime / $row["prd_time"] * 100;
                $roundloss = round($loss_persen, 2);
                $persen_ril = $ril * $cctime / 60 / $row["prd_time"] * 100;
                $roundril = round($persen_ril, 2);
                $persen_rol = $rol * $cctime / 60 / $row["prd_time"] * 100;
                $roundrol = round($persen_rol, 2);
                $persen_wip = $wip * $cctime / 60 / $row["prd_time"] * 100;
                $roundwip = round($persen_wip, 2);
                $total = round($row["eff"] + $roundloss + $roundril + $roundrol + $roundwip, 2);

                $row["total%"] = $total;
                $row["ril%"] = $roundril;
                $row["rol%"] = $roundrol;
                $row["loss%"] = $roundloss;
                $row["wip%"] = $roundwip;
                // $row["eff"] = $totalEff2;
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

    public function getReportDetail($date_from = "*", $date_to = "*", $shift = null, $line_id = null, $ldid = null, $jpid = null, $time_start = null, $time_end = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.*, c.*, c.name1 as material, d.*, e.*, e.name1 as line_name, f.name1 as operator,
                (select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq), g.name1 as apr_name
                from t_prd_daily_i a
                inner join t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                inner join wms.m_mara c on c.matnr = a.dies_id
                inner join m_param d on d.pid = 'SHIFT' and d.seq = a.shift
                inner join m_prd_line e on e.line_id = a.line_id and e.line_ty = 'ECU'
                inner join m_prd_operator f on f.empid = b.jpid
                left join m_user g on g.usrid = a.apr_by
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

        if (!empty($time_start) && !empty($time_end)) {
            $sql .= " AND a.time_start between '$time_start' AND '$time_end' ";
        }

        $sql .= " ORDER BY a.real_dt, a.time_start asc";
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
                    $efficiency = ($prd_qty * $cctime / 60) / $prd_time;
                }

                $roundEff = round($efficiency, 2);
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
        $sql = "SELECT a.*, b.*, c.*, d.name1 as dies_name, d.*, e.*, f.name1 as line_name, g.name1 as operator, g.*, h.*, h.name1 as stop, k.name1 as action, j.name1 as eksekutor,
                (select count(*) as stop_count from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq)
                from t_prd_daily_i a
                inner join t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                inner join t_prd_daily_stop c on c.prd_dt = a.prd_dt and c.shift = a.shift and c.line_id = a.line_id and c.prd_seq = a.prd_seq
                inner join wms.m_mara d on d.matnr = a.dies_id
                inner join m_param e on e.pid = 'SHIFT' and e.seq = a.shift
                inner join m_prd_line f on f.line_id = a.line_id and f.line_ty = 'ECU'
                inner join m_prd_operator g on g.empid = b.jpid
                inner join m_prd_stop_reason_action h on h.srna_id = c.stop_id and h.app_id = 'AISIN_ADN'
                left join t_prd_daily_exec i on i.line_id = a.line_id and i.prd_dt = a.prd_dt and i.shift = a.shift and i.prd_seq = c.prd_seq and i.stop_seq = c.stop_seq
                left join m_prd_operator j on j.empid = i.empid
                left join m_prd_stop_reason_action k on k.srna_id = c.action_id and k.app_id =  'AISIN_ADN'
                where 1=1  ";

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
                where 1=1 ";

        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.start_time, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }

        $sql .= "ORDER BY a.start_time DESC ";

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

    public function getTimeShift()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select time_start from m_prd_shift where shift_id IN('1','2','3') and app_id = 'AISIN_ADN' order by time_start";
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

    public function getMatsExcel($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select c.name1, c.backno, 
        coalesce(sum(a.pln_qty),0) as pln_qty
        from t_prd_daily_i a 
        inner join m_prd_line b ON b.line_id = a.line_id
        inner join wms.m_mara c on c.matnr = a.dies_id
        where a.prd_dt = '$prd_dt' and a.shift = '$shift' AND a.line_id = '$line_id'
        group by 1,2 ";

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

    public function getNGList($line_id, $date, $shift, $prd_seq)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, b.*
        from t_prd_daily_ng a
        left join m_prd_ng_type b on b.ng_type_id = a.ng_type and b.line_id = '$line_id' and b.app_id = 'AISIN_ADN'
        where a.prd_dt = '$date' and a.line_id = '$line_id' and a.shift = '$shift' and a.prd_seq = '$prd_seq' ";
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

    public function getTemp($date_from = "*", $date_to = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select *, to_char(dats, 'DD-MM-YYYY HH24:MI:SS') as date from t_temp where 1=1 ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(dats, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        $sql .= " order by dats desc ";
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