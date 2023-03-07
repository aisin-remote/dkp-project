<?php

class ContentStopShift
{
    public function getListShift()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT DISTINCT shift_id FROM m_prd_shift WHERE app_id = '" . APP . "' "
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
            . "WHERE type1 = 'S' AND app_id = '" . APP . "' "
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
            $sql .= " WHERE shift_id = '$shift' AND app_id = '" . APP . "' ";
        } else {
            $sql .= " WHERE app_id = '" . APP . "' ";
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
        $sql = "SELECT DISTINCT a.*, b.name1, c.time_start, c.time_end
                FROM m_prd_shift_stop a
                LEFT JOIN m_prd_stop_reason_action b ON b.srna_id = a.srna_id
                LEFT JOIN m_prd_shift c ON c.shift_id = a.shift_id AND c.time_id = a.time_id
                WHERE a.app_id = '".APP."' ";

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
        $sql = "SELECT * FROM m_prd_shift_stop WHERE shift_id = '$id' AND time_id = '$id2' AND srna_id = '$id3' AND app_id = '" . APP . "' ";
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
            $sql = "INSERT INTO m_prd_shift_stop (shift_id, srna_id, time_id, start_time, end_time, stop_time, app_id ) "
                . "values (:shift_id, :srna_id, :time_id, :start_time, :end_time, '" . $param["stop_time"] . "', '" . APP . "' )";

            // echo $sql;
            // die();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":shift_id", strtoupper(trim($param["shift_id"])), PDO::PARAM_STR);
            $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            $stmt->bindValue(":start_time", $param["time_start"], PDO::PARAM_STR);
            $stmt->bindValue(":end_time", $param["time_end"], PDO::PARAM_STR);

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
            // $sql = "UPDATE m_prd_shift_stop SET shift_id = :shift_id, srna_id = :srna_id, time_id = :time_id, start_time = :start_time, end_time = :end_time, stop_time = '" . $param["stop_time"] . "' "
            //     . "WHERE shift_id = '" . $param["id"] . "' AND srna_id = '" . $param["id3"] . "' AND app_id = '" . APP . "' ";
            $sql = "UPDATE m_prd_shift_stop SET shift_id = '".$param["shift_id"]."', srna_id = '".$param["srna_id"]."', time_id = '".$param["time_id"]."', start_time = '".$param["time_start"]."', end_time = '".$param["time_end"]."', stop_time = '".$param["stop_time"]."'
                    WHERE shift_id = '".$param["shiftid"]."' AND srna_id = '".$param["srnaid"]."' AND app_id = '".APP."'";

            // echo $sql;
            // die();
            $stmt = $conn->prepare($sql);
            // $stmt->bindValue(":id", $param["id"], PDO::PARAM_STR);
            // $stmt->bindValue(":shift_id", $param["shift_id"], PDO::PARAM_STR);
            // $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            // $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            // $stmt->bindValue(":start_time", $param["start_time"], PDO::PARAM_STR);
            // $stmt->bindValue(":end_time", $param["end_time"], PDO::PARAM_STR);

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