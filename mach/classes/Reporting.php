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
                FROM mach.t_prd_daily_h a
                LEFT JOIN mach.m_prd_operator ld ON ld.empid = a.ldid
                LEFT JOIN mach.m_prd_operator jp ON jp.empid = a.jpid
                LEFT JOIN mach.m_prd_operator op1 ON op1.empid = a.op1id
                LEFT JOIN mach.m_prd_operator op2 ON op2.empid = a.op2id
                LEFT JOIN mach.m_prd_operator op3 ON op3.empid = a.op3id
                LEFT JOIN mach.m_prd_operator op4 ON op4.empid = a.op4id
                LEFT JOIN mach.m_prd_operator op5 ON op5.empid = a.op5id
                LEFT JOIN mach.m_prd_operator op6 ON op6.empid = a.op6id
                LEFT JOIN mach.m_prd_operator op7 ON op7.empid = a.op7id
                LEFT JOIN mach.m_prd_operator op8 ON op8.empid = a.op8id "
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
        (select sum(stop_time) as stop_time from mach.t_prd_daily_stop where prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id),
        (select coalesce(sum(ng_qty),0) as ng_tot FROM mach.t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id), 
        (select coalesce(sum(ng_qty),0) as ng_ril FROM mach.t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id AND ng_type LIKE 'RIL%'), 
        (select coalesce(sum(ng_qty),0) as ng_rol FROM mach.t_prd_daily_ng WHERE prd_dt = t.prd_dt AND shift = t.shift AND line_id = t.line_id AND ng_type LIKE 'ROL%'), 
        round((t.prd_qty * t.cctime / 60 / t.prd_time * 100)::numeric, 2) as eff from 
        (select TO_CHAR(a.prd_dt, 'YYYY') as prd_year, TO_CHAR(a.prd_dt,'MM') as prd_month, f.pval1,
        a.prd_dt, a.line_id, b.name1 as line_name, a.shift, d.name1 as ld_name, e.name1 as jp_name,
        AVG(a.cctime) as cctime, sum(a.prd_time) as prd_time, sum(a.pln_qty) as pln_qty, sum(a.prd_qty) as prd_qty
        from mach.t_prd_daily_i a 
        inner join mach.m_prd_line b ON b.line_id = a.line_id and b.line_ty = 'MCH'
        inner join mach.t_prd_daily_h c on c.prd_dt = a.prd_dt and c.line_id = a.line_id and c.shift = a.shift
        left join mach.m_prd_operator d on d.empid = c.ldid
        left join mach.m_prd_operator e on e.empid = c.jpid
        inner join mach.m_param f on f.pid = 'SHIFT' and f.seq = a.shift
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
                $ril = $row["ng_ril"];
                $rol = $row["ng_rol"];

                $persen_ril = $ril * $cctime / 60 / $row["prd_time"] * 100;
                $roundril = round($persen_ril, 2);
                $persen_rol = $rol * $cctime / 60 / $row["prd_time"] * 100;
                $roundrol = round($persen_rol, 2);

                if ($prd_time == 0) {
                    $efficiency = 0;
                } else {
                    $efficiency = ($prd_qty * ($cctime / 60)) / $prd_time;
                }

                $roundEff = round($efficiency, 3);
                $totalEff = $roundEff * 100;

                $row["ril%"] = $roundril;
                $row["rol%"] = $roundrol;
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
        c.name1, c.model_id, coalesce(d.ng_qty, 0) as tot_ng, coalesce(f.ng_qty, 0) as tot_ng2, coalesce(e.stop_time, 0) as loss_time, coalesce(SUM(x.ng_qty), 0) as steuchi, coalesce(a.wip, 0) as wip, a.real_dt 
        FROM mach.t_prd_daily_i a 
        LEFT JOIN mach.m_user b ON a.apr_by = b.usrid 
        LEFT JOIN mach.m_dm_dies_asset c ON a.dies_id::integer = c.dies_id 
        LEFT JOIN (
                        SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(ng_qty), 0) as ng_qty
                        FROM mach.t_prd_daily_ng
                        GROUP BY line_id, prd_dt, shift, prd_seq
                    ) d ON a.line_id = d.line_id AND a.prd_dt = d.prd_dt AND a.shift = d.shift AND a.prd_seq = d.prd_seq
                    LEFT JOIN (
                        SELECT line_id, prd_dt, shift, prd_seq, COALESCE(SUM(stop_time), 0) as stop_time
                        FROM mach.t_prd_daily_stop
                        GROUP BY line_id, prd_dt, shift, prd_seq
                    ) e ON a.line_id = e.line_id AND a.prd_dt = e.prd_dt AND a.shift = e.shift AND a.prd_seq = e.prd_seq 
                    LEFT JOIN (
                        SELECT a.line_id, a.prd_dt, a.shift, COALESCE(SUM(a.ng_qty), 0) as ng_qty
                        FROM mach.t_prd_daily_ng a
                        LEFT JOIN (
                            SELECT line_id, line_ty, name1 AS line_name
                            FROM mach.m_prd_line
                            WHERE line_ty = 'MCH'
                        ) b ON a.line_id = b.line_id
                        WHERE a.line_id = b.line_id
                        GROUP BY a.line_id, a.prd_dt, a.shift
                    ) f ON a.line_id = f.line_id AND a.prd_dt = f.prd_dt AND a.shift = f.shift 
                    LEFT JOIN ( 
                        SELECT e.pval2, d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as ng_qty 
                        FROM mach.t_prd_daily_ng a 
                        INNER JOIN mach.t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq
                        INNER JOIN mach.m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying
                        INNER JOIN mach.m_param e ON a.ng_type = e.pval1
                        WHERE a.ng_type LIKE 'ROL%' AND a.line_id SIMILAR TO '[0-9]+' AND e.pval2 LIKE '%STEUCHI%'
                        GROUP BY 1,2,3,4,5
                    ) x ON a.line_id = x.line_id AND a.prd_dt = x.prd_dt AND a.shift = x.shift AND c.model_id = x.model_id 
                    WHERE a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift'
                    GROUP BY 1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17
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
        a.prd_dt, a.line_id, b.name1 as line_name, a.shift, c.group_id, c.model_id, c.dies_no, 
        coalesce(AVG(a.cctime),0) as cctime, 
        coalesce(sum(a.prd_time),0) as prd_time, 
        coalesce(sum(a.pln_qty),0) as pln_qty, 
        coalesce(sum(a.prd_qty),0) as prd_qty, 
        coalesce(sum(a.wip),0) as wip,
        sum((select coalesce(sum(stop_time),0) FROM mach.t_prd_daily_stop s inner join mach.m_prd_stop_reason_action b ON b.srna_id = s.stop_id AND b.app_id = 'AISIN_MACH' AND b.type1 = 'S' where s.prd_dt = a.prd_dt AND s.shift = a.shift AND s.line_id = a.line_id AND s.prd_seq = a.prd_seq AND b.type2 = 'P' )) as loss_time_p,
        sum((select coalesce(sum(stop_time),0) FROM mach.t_prd_daily_stop s inner join mach.m_prd_stop_reason_action b ON b.srna_id = s.stop_id AND b.app_id = 'AISIN_MACH' AND b.type1 = 'S' where s.prd_dt = a.prd_dt AND s.shift = a.shift AND s.line_id = a.line_id AND s.prd_seq = a.prd_seq AND b.type2 = 'U' )) as loss_time,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq)) as ng_tot,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type like 'RIL%')) as ng_ril,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type like 'ROL%')) as ng_rol,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL1')) as ng_rol1,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL2')) as ng_rol2,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL3')) as ng_rol3,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL4')) as ng_rol4,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL5')) as ng_rol5,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL6')) as ng_rol6,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL7')) as ng_rol7,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL8')) as ng_rol8,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL9')) as ng_rol9,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL10')) as ng_rol10,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'ROL11')) as ng_rol11,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL1')) as ng_ril1,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL2')) as ng_ril2,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL3')) as ng_ril3,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL4')) as ng_ril4,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL5')) as ng_ril5,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL6')) as ng_ril6,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL7')) as ng_ril7,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL8')) as ng_ril8,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL9')) as ng_ril9,
        sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL10')) as ng_ril10,
		sum((select coalesce(sum(ng_qty),0) FROM mach.t_prd_daily_ng WHERE prd_dt = a.prd_dt AND shift = a.shift AND line_id = a.line_id AND prd_seq = a.prd_seq AND ng_type = 'RIL11')) as ng_ril11
        from mach.t_prd_daily_i a 
        inner join mach.m_prd_line b ON b.line_id = a.line_id
        inner join mach.m_dm_dies_asset c on c.dies_id = CAST(a.dies_id as bigint)
        where a.prd_dt = '$prd_dt' and a.shift = '$shift' AND a.line_id = '$line_id'
        group by 1,2,3,4,5,6,7,8,9) t ";

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
        $sql = "SELECT SUM(prd_time) as nett_opr FROM mach.t_prd_daily_i "
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

    public function getReportDetail($date_from = "*", $date_to = "*", $shift = "*", $line_id = "*", $group = "*", $model = "*", $dies_no = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.*, c.*, c.name1 as dies_name, d.*, e.*, e.name1 as line_name, f.name1 as operator, h.name1 as spv_name,
                (select count(*) as stop_count from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select COALESCE(SUM(stop_time), 0) as loss_time from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select COALESCE(SUM(stop_time), 0) as setup from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and cat_stop_id = 'STOP7'),
                (select COALESCE(SUM(stop_time), 0) as baratsuki from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and cat_stop_id = 'STOP9'),
                (select COALESCE(SUM(stop_time), 0) as dandori from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and stop_id = '7390'),
                (select COALESCE(SUM(stop_time), 0) as limas from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and cat_stop_id = 'STOP8'),
                (select COALESCE(SUM(ng_qty), 0) as ng_count from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select COALESCE(SUM(ng_qty), 0) as ng_ril from mach.t_prd_daily_ng where line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and ng_type like 'RIL%'),
                (select COALESCE(SUM(ng_qty), 0) as ng_rol from mach.t_prd_daily_ng where line_id = a.line_id and prd_dt = a.prd_dt and shift = a.shift and prd_seq = a.prd_seq and ng_type like 'ROL%'),
                (select COALESCE(SUM(aa.stop_time), 0) as planning from mach.t_prd_daily_stop aa 
                left join mach.m_prd_stop_reason_action ab on ab.srna_id = aa.stop_id
                where aa.line_id = a.line_id AND aa.prd_dt = a.prd_dt AND aa.shift = a.shift and aa.prd_seq = a.prd_seq and ab.type2 = 'P'),
                (select COALESCE(SUM(ba.stop_time), 0) as unplanning from mach.t_prd_daily_stop ba 
                left join mach.m_prd_stop_reason_action bb on bb.srna_id = ba.stop_id
                where ba.line_id = a.line_id AND ba.prd_dt = a.prd_dt AND ba.shift = a.shift and ba.prd_seq = a.prd_seq and bb.type2 = 'U'), 
                g.name1 as apr_name
                from mach.t_prd_daily_i a
                left join mach.t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                left join mach.m_dm_dies_asset c on CAST(c.dies_id as varchar) = a.dies_id
                left join mach.m_param d on d.pid = 'SHIFT' and d.seq = a.shift
                inner join mach.m_prd_line e on e.line_id = a.line_id and e.line_ty = 'MCH'
                left join mach.m_prd_operator f on f.empid = b.jpid
                left join mach.m_user g on g.usrid = a.apr_by
                left join mach.m_user h on h.usrid = b.apr_spv_by
                where 1=1 ";

        if ($date_from != "*" && $date_to != "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift != "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($line_id != "*") {
            $sql .= " AND a.line_id = '$line_id' ";
        }
        if ($group != "*") {
            $sql .= " AND c.group_id = '$group' ";
        }
        if ($model != "*") {
            $sql .= " AND c.model_id = '$model' ";
        }
        if ($dies_no != "*") {
            $sql .= " AND c.dies_id = $dies_no ";
        }

        $sql .= " ORDER BY a.real_dt, a.time_start asc ";
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

                $persen_ril = $row["ng_ril"] * $cctime / 60 / $row["prd_time"] * 100;
                $roundril = round($persen_ril, 2);
                $persen_rol = $row["ng_rol"] * $cctime / 60 / $row["prd_time"] * 100;
                $roundrol = round($persen_rol, 2);
                $loss_persen = $row["loss_time"] / $row["prd_time"];
                $roundloss = round($loss_persen, 2);
                $persen_baratsuki = $row["baratsuki"] / $row["prd_time"];
                $roundbarats = round($persen_baratsuki, 2);
                $persen_setup = $row["setup"] / $row["prd_time"];
                $roundsetup = round($persen_setup, 2);
                $persen_limas = $row["limas"] / $row["prd_time"];
                $roundlimas = round($persen_limas, 2);
                $persen_dandori = $row["dandori"] / $row["prd_time"];
                $rounddandori = round($persen_dandori, 2);

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

                $row["ril%"] = $roundril;
                $row["rol%"] = $roundrol;
                $row["losstime%"] = $roundloss;
                $row["baratsuki%"] = $roundbarats;
                $row["setup%"] = $roundsetup;
                $row["limas%"] = $roundlimas;
                $row["dandori%"] = $rounddandori;
                $row["eff"] = $totalEff;

                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getReportStop($date_from = "*", $date_to = "*", $shift = "*", $line_id = "*", $group = "*", $model = "*", $dies_no = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.*, c.*, d.name1 as dies_name, d.*, e.*, f.name1 as line_name, g.name1 as operator, g.*, h.*, h.name1 as stop, k.name1 as action, j.name1 as eksekutor,
                (select count(*) as stop_count from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(stop_time) as loss_time from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
                (select SUM(ng_qty) as ng_count from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq), l.name1 as cat_stop
                from mach.t_prd_daily_i a
                inner join mach.t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
                inner join mach.t_prd_daily_stop c on c.prd_dt = a.prd_dt and c.shift = a.shift and c.line_id = a.line_id and c.prd_seq = a.prd_seq
                inner join mach.m_dm_dies_asset d on d.dies_id = CAST(a.dies_id as bigint)
                inner join mach.m_param e on e.pid = 'SHIFT' and e.seq = a.shift
                inner join mach.m_prd_line f on f.line_id = a.line_id and f.line_ty = 'MCH'
                inner join mach.m_prd_operator g on g.empid = b.jpid
                inner join mach.m_prd_stop_reason_action h on h.srna_id = c.stop_id and h.app_id = 'AISIN_MACH'
                left join mach.t_prd_daily_exec i on i.line_id = a.line_id and i.prd_dt = a.prd_dt and i.shift = a.shift and i.prd_seq = c.prd_seq and i.stop_seq = c.stop_seq
                left join mach.m_prd_operator j on j.empid = i.empid
                left join mach.m_prd_stop_reason_action k on k.srna_id = c.action_id and k.app_id =  'AISIN_MACH'
                left join mach.m_cat_problem l on l.cat_id = c.cat_stop_id
                where 1=1 ";

        if ($date_from != "*" && $date_to != "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift != "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($line_id != "*") {
            $sql .= " AND a.line_id = '$line_id' ";
        }
        if ($group != "*") {
            $sql .= " AND d.group_id = '$group' ";
        }
        if ($model != "*") {
            $sql .= " AND d.model_id = '$model' ";
        }
        if ($dies_no != "*") {
            $sql .= " AND d.dies_id = '$dies_no' ";
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

    public function getReportRIL($date_from = "*", $date_to = "*", $shift = "*", $line_id = "*", $group = "*", $model = "*", $dies_no = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, d.group_id, d.model_id, d.dies_no, d.name1 as dies_name, e.pval1, f.name1 as line_name,
        (select count(*) as stop_count from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
        (select SUM(stop_time) as loss_time from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
        (select SUM(ng_qty) as ng_ril from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type LIKE 'RIL%'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril1 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL1'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril2 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL2'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril3 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL3'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril4 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL4'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril5 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL5'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril6 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL6'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril7 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL7'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril8 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL8'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril9 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL9'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril10 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL10'),
        (select COALESCE(SUM(ng_qty), 0) as ng_ril11 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'RIL11')
        from mach.t_prd_daily_i a
        inner join mach.t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
        inner join mach.t_prd_daily_ng c on c.prd_dt = a.prd_dt and c.shift = a.shift and c.line_id = a.line_id and c.prd_seq = a.prd_seq and ng_type LIKE 'RIL%'
        inner join mach.m_dm_dies_asset d on d.dies_id = CAST(a.dies_id as bigint)
        inner join mach.m_param e on e.pid = 'SHIFT' and e.seq = a.shift
        inner join mach.m_prd_line f on f.line_id = a.line_id and f.line_ty = 'MCH'
        inner join mach.m_prd_operator g on g.empid = b.jpid
        where 1=1 ";

        if ($date_from != "*" && $date_to != "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift != "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($line_id != "*") {
            $sql .= " AND a.line_id = '$line_id' ";
        }
        if ($group != "*") {
            $sql .= " AND d.group_id = '$group' ";
        }
        if ($model != "*") {
            $sql .= " AND d.model_id = '$model' ";
        }
        if ($dies_no != "*") {
            $sql .= " AND d.dies_id = '$dies_no' ";
        }

        $sql .= " group by 1,2,3,4,5,6,7,8,9,10,11,12 ";

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

    public function getReportROL($date_from = "*", $date_to = "*", $shift = "*", $line_id = "*", $group = "*", $model = "*", $dies_no = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, d.group_id, d.model_id, d.dies_no, d.name1 as dies_name, e.pval1, f.name1 as line_name,
        (select count(*) as stop_count from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
        (select SUM(stop_time) as loss_time from mach.t_prd_daily_stop where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq),
        (select SUM(ng_qty) as ng_rol from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type LIKE 'ROL%'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol1 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL1'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol2 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL2'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol3 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL3'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol4 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL4'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol5 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL5'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol6 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL6'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol7 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL7'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol8 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL8'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol9 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL9'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol10 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL10'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol11 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL11'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol12 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL12'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol13 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL13'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol14 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL14'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol15 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL15'),
        (select COALESCE(SUM(ng_qty), 0) as ng_rol16 from mach.t_prd_daily_ng where line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift and prd_seq = a.prd_seq and ng_type = 'ROL16')
        from mach.t_prd_daily_i a
        inner join mach.t_prd_daily_h b on b.prd_dt = a.prd_dt and b.shift = a.shift and b.line_id = a.line_id
        right join mach.t_prd_daily_ng c on c.prd_dt = a.prd_dt and c.shift = a.shift and c.line_id = a.line_id and c.prd_seq = a.prd_seq and ng_type LIKE 'ROL%'
        inner join mach.m_dm_dies_asset d on d.dies_id = CAST(a.dies_id as bigint)
        inner join mach.m_param e on e.pid = 'SHIFT' and e.seq = a.shift
        inner join mach.m_prd_line f on f.line_id = a.line_id and f.line_ty = 'MCH'
        inner join mach.m_prd_operator g on g.empid = b.jpid
        where 1=1 ";

        if ($date_from != "*" && $date_to != "*") {
            $sql .= " AND TO_CHAR(a.prd_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if ($shift != "*") {
            $sql .= " AND a.shift = '$shift' ";
        }
        if ($line_id != "*") {
            $sql .= " AND a.line_id = '$line_id' ";
        }
        if ($group != "*") {
            $sql .= " AND d.group_id = '$group' ";
        }
        if ($model != "*") {
            $sql .= " AND d.model_id = '$model' ";
        }
        if ($dies_no != "*") {
            $sql .= " AND d.dies_id = '$dies_no' ";
        }

        $sql .= " group by 1,2,3,4,5,6,7,8,9,10,11,12 ";

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

    public function getSteuchiList($line_id, $prd_dt, $shift, $prd_seq)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.prd_seq, e.pval2, d.model_id, a.line_id, a.prd_dt, a.shift, COUNT(a.ng_type) as rol, SUM(a.ng_qty) as steuchi
        FROM mach.t_prd_daily_ng a 
        INNER JOIN mach.t_prd_daily_i c ON a.line_id = c.line_id AND a.prd_dt = c.prd_dt AND a.shift = c.shift AND a.prd_seq = c.prd_seq 
        INNER JOIN mach.m_dm_dies_asset d ON c.dies_id = d.dies_id::character varying 
        INNER JOIN mach.m_param e ON a.ng_type = e.pval1 
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

    public function getListShift()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM mach.m_param WHERE pid = 'SHIFT' ORDER BY seq ASC";

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

    public function getDiesExcel($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select CONCAT(c.group_id, ' ', c.model_id) as dies_id, 
        coalesce(sum(a.pln_qty),0) as pln_qty
        from mach.t_prd_daily_i a 
        inner join mach.m_prd_line b ON b.line_id = a.line_id
        inner join mach.m_dm_dies_asset c on c.dies_id = CAST(a.dies_id as bigint)
        where a.prd_dt = '$prd_dt' and a.shift = '$shift' AND a.line_id = '$line_id'
        group by 1 ";

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

    public function getListLog()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT *, TO_CHAR(crt_dt, 'DD-MM-YYYY') as date, TO_CHAR(crt_dt, 'HH24:MM:SS') as time FROM mach.t_logger ORDER BY id DESC";

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