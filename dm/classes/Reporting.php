<?php

class Reporting
{
    public function getById($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, (select group_id from m_dm_dies_asset where dies_id = a.dies_id) as group, (select model_id from m_dm_dies_asset where dies_id = a.dies_id) as model, (select dies_no from m_dm_dies_asset where dies_id = a.dies_id) as dies_num FROM t_dm_cs_h a "
            . "WHERE pmtid = '$id' ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", strtoupper($id), PDO::PARAM_STR);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListChecksheet($date_from = "*", $date_to = "*", $pmtid = null, $group_id = null, $model_id = null, $dies_no = null, $pmtype = null, $pmstat = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.group_id, b.model_id, b.dies_no, b.name1, TO_CHAR(a.pmtdt, 'DD-MM-YYYY') as pmt_date FROM t_dm_cs_h a "
            . "INNER JOIN m_dm_dies_asset b on b.dies_id = a.dies_id "
            . "WHERE 1=1 ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.pmtdt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if (!empty($pmtid)) {
            $sql .= " AND a.pmtid = '$pmtid' ";
        }
        if (!empty($group_id)) {
            $sql .= " AND b.group_id = '$group_id' ";
        }
        if (!empty($model_id)) {
            $sql .= " AND b.model_id = '$model_id' ";
        }
        if (!empty($dies_no)) {
            $sql .= " AND b.dies_no = '$dies_no' ";
        }
        if (!empty($pmtype)) {
            $sql .= " AND a.pmtype = '$pmtype' ";
        }
        if (!empty($pmstat)) {
            $sql .= " AND a.pmstat = '$pmstat' ";
        }
        $sql .= " ORDER by pmtid ASC ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['pmstat'] == 'C') {
                    $row['pmstat'] = 'Completed';
                } elseif ($row['pmstat'] == 'N') {
                    $row['pmstat'] = 'On Progress';
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListPergantianPart($date_from = "*", $date_to = "*", $group_id = null, $model_id = null, $dies_no = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT TO_CHAR(a.crt_dt, 'YYYY-MM-DD') as pc_date, 
        TO_CHAR(a.crt_dt, 'HH24:MI:SS') as pc_time, 
        d.group_id, d.model_id, d.dies_no, c.part_grp, c.name1, a.desc1, a.crt_by "
            . "FROM t_dm_pc_h a, t_dm_pc_i b, m_dm_dies_part c, m_dm_dies_asset d "
            . "WHERE a.dies_id = CAST(d.dies_id AS varchar(20)) AND b.part_id = c.part_id AND a.pchid = b.pchid ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.crt_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if (!empty($group_id)) {
            $sql .= " AND d.group_id = '$group_id' ";
        }
        if (!empty($model_id)) {
            $sql .= " AND d.model_id = '$model_id' ";
        }
        if (!empty($dies_no)) {
            $sql .= " AND d.dies_no = '$dies_no' ";
        }
        $sql .= " ORDER by a.dies_id ASC ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['part_grp'] == 'F') {
                    $row['part_grp'] = 'Fix';
                } elseif ($row['part_grp'] == 'M') {
                    $row['part_grp'] = 'Move';
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListOri($date_from = "*", $date_to = "*", $group_id = null, $model_id = null, $dies_no = null, $ori_type = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.ori_id, a.ori_dt, TO_CHAR(a.crt_dt, 'HH24:MI:SS') as ori_time, b.group_id, b.model_id, b.dies_no, a.ori_typ, a.crt_by "
            . "FROM t_dm_ori a, m_dm_dies_asset b "
            . "WHERE a.dies_id = b.dies_id ";
        if ($date_from !== "*" && $date_to !== "*") {
            $sql .= " AND TO_CHAR(a.ori_dt, 'YYYYMMDD') between '$date_from' AND '$date_to' ";
        }
        if (!empty($group_id)) {
            $sql .= " AND b.group_id = '$group_id' ";
        }
        if (!empty($model_id)) {
            $sql .= " AND b.model_id = '$model_id' ";
        }
        if (!empty($dies_no)) {
            $sql .= " AND b.dies_no = '$dies_no' ";
        }
        if (!empty($ori_type)) {
            $sql .= " AND a.ori_typ = '$ori_type' ";
        }

        // echo $sql;
        // die();
        $sql .= " ORDER by a.ori_id ASC";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["ori_typ"] == "P") {
                    $row["ori_typ"] = "Preventive";
                } elseif ($row["ori_typ"] == "R") {
                    $row["ori_typ"] = "Repair";
                } elseif ($row["ori_typ"] == "I") {
                    $row["ori_typ"] = "Improvement";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListStroke($group_id = null, $model_id = null, $dies_no = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT group_id, model_id, dies_no, SUM(stktot) AS stktot, SUM(ng_qty) AS stkng, SUM(stktot) - SUM(ng_qty) AS stkok, stkrun "
            . "FROM m_dm_dies_asset "
            . "WHERE 1=1";
        if (!empty($group_id)) {
            $sql .= " AND group_id = '$group_id' ";
        }
        if (!empty($model_id)) {
            $sql .= " AND model_id = '$model_id' ";
        }
        if (!empty($dies_no)) {
            $sql .= " AND dies_no = '$dies_no' ";
        }
        $sql .= " GROUP BY dies_id";
        $sql .= " ORDER by dies_id ASC ";

        // echo $sql;
        // die();
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
