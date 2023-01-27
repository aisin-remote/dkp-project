<?php

class ContentStopShift
{
    public function getListShift()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT DISTINCT shift_id FROM m_prd_shift "
            . "ORDER BY shift_id ASC ";

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

    public function getSrna()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT srna_id, name1 FROM m_prd_stop_reason_action "
            . "WHERE type1 = 'S' "
            . "ORDER BY srna_id ASC ";

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

    public function getListTime($shift = null)
    {
        // echo $shift;
        // die();
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM m_prd_shift ";

        if (!empty($shift)) {
            $sql .= " WHERE shift_id = '$shift' ";
        }

        $sql .= " ORDER BY CAST(time_id AS INTEGER) ASC ";

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

    public function getList()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, (select name1 from m_prd_stop_reason_action where srna_id = a.srna_id) as srna_name, (select time_start from m_prd_shift where shift_id = a.shift_id and time_id = a.time_id) as start1, (select time_end from m_prd_shift where shift_id = a.shift_id and time_id = a.time_id) as end1 "
            . "FROM m_prd_shift_stop a "
            . "ORDER BY a.shift_id ASC, a.srna_id ASC, a.time_id ASC ";

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

    public function getById($id, $id2, $id3)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM m_prd_shift_stop WHERE shift_id = '$id' AND time_id = '$id2' AND srna_id = '$id3' ";
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

    public function insert($param = array())
    {
        // var_dump($param);
        // die();
        $return = array();
        if (empty($param)) {
            $return["status"] = false;
            $return["message"] = "Data Empty";
        } else {
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "INSERT INTO m_prd_shift_stop (shift_id, srna_id, time_id, start_time, end_time, stop_time ) "
                . "values (:shift_id, :srna_id, :time_id, :start_time, :end_time, '" . $param["stop_time"] . "' )";

            // echo $sql;
            // die();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":shift_id", strtoupper(trim($param["shift_id"])), PDO::PARAM_STR);
            $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            $stmt->bindValue(":start_time", $param["start_time"], PDO::PARAM_STR);
            $stmt->bindValue(":end_time", $param["end_time"], PDO::PARAM_STR);

            if ($stmt->execute()) {

                $return["status"] = true;
            } else {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                error_log($error[2]);
            }
            $stmt = null;
            $conn = null;
        }
        return $return;
    }

    public function update($param = array())
    {
        $return = array();
        if (empty($param)) {
            $return["status"] = false;
            $return["message"] = "Data Empty";
        } else {
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "UPDATE m_prd_shift_stop SET shift_id = :shift_id, srna_id = :srna_id, time_id = :time_id, start_time = :start_time, end_time = :end_time, stop_time = '" . $param["stop_time"] . "' "
                . "WHERE shift_id = '" . $param["id"] . "' ";

            // echo $sql;
            // die();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $param["id"], PDO::PARAM_STR);
            $stmt->bindValue(":shift_id", $param["shift_id"], PDO::PARAM_STR);
            $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            $stmt->bindValue(":start_time", $param["start_time"], PDO::PARAM_STR);
            $stmt->bindValue(":end_time", $param["end_time"], PDO::PARAM_STR);

            if ($stmt->execute()) {
                $return["status"] = true;
            } else {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                error_log($error[2]);
            }
            $stmt = null;
            $conn = null;
        }
        return $return;
    }
}
