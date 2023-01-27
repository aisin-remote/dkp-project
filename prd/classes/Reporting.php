<?php

class Reporting
{
    public function getById($line_id, $prd_dt, $shift)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, "
            . "(SELECT name1 as ld_name from m_prd_operator where empid = a.ldid), "
            . "(SELECT name1 as jp_name from m_prd_operator where empid = a.jpid), "
            . "(SELECT name1 as op1_name from m_prd_operator where empid = a.op1id), "
            . "(SELECT name1 as op2_name from m_prd_operator where empid = a.op2id), "
            . "(SELECT name1 as op3_name from m_prd_operator where empid = a.op3id), "
            . "(SELECT name1 as op4_name from m_prd_operator where empid = a.op4id) "
            . "FROM t_prd_daily_h a "
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
        $sql = "SELECT a.line_id, a.prd_dt, a.shift, a.prd_seq, a.time_start, a.time_end, a.cctime, a.pln_qty, a.prd_qty, a.prd_time, "
            . "(SELECT name1 FROM m_dm_dies_asset WHERE dies_id::character varying = a.dies_id), "
            . "(SELECT COALESCE(SUM(ng_qty), 0) as tot_ng FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift), "
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
        $sql = "SELECT a.*, "
            . "(SELECT group_id FROM m_dm_dies_asset WHERE dies_id::character varying = a.dies_id), "
            . "(SELECT model_id FROM m_dm_dies_asset WHERE dies_id::character varying = a.dies_id), "
            . "(SELECT dies_no FROM m_dm_dies_asset WHERE dies_id::character varying = a.dies_id), "
            . "(SELECT SUM(stop_time) as loss_time FROM t_prd_daily_stop WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift), "
            . "(SELECT COUNT(ng_type) as ril FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type LIKE 'RIL%' ), "
            . "(SELECT COUNT(ng_type) as rol1 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL1' ), "
            . "(SELECT COUNT(ng_type) as rol2 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL2' ), "
            . "(SELECT COUNT(ng_type) as rol3 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL3' ), "
            . "(SELECT COUNT(ng_type) as rol3 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL3' ), "
            . "(SELECT COUNT(ng_type) as rol4 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL4' ), "
            . "(SELECT COUNT(ng_type) as rol5 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL5' ), "
            . "(SELECT COUNT(ng_type) as rol6 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL6' ), "
            . "(SELECT COUNT(ng_type) as rol7 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL7' ), "
            . "(SELECT COUNT(ng_type) as rol8 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL8' ), "
            . "(SELECT COUNT(ng_type) as rol9 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL9' ), "
            . "(SELECT COUNT(ng_type) as rol10 FROM t_prd_daily_ng WHERE line_id = a.line_id AND prd_dt = a.prd_dt AND shift = a.shift AND ng_type = 'ROL10' ) "
            . "FROM t_prd_daily_i a "
            . "WHERE  a.line_id = '$line_id' AND a.prd_dt = '$prd_dt' AND a.shift = '$shift' ";

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
