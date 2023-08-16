<?php

class ContentStopShift
{
    public function getListShift()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT DISTINCT shift_id FROM mach.m_prd_shift WHERE app_id = 'AISIN_MACH' "
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
        $sql = "SELECT srna_id, name1 FROM mach.m_prd_stop_reason_action "
            . "WHERE type1 = 'S' AND app_id = 'AISIN_MACH' "
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
        $sql = "SELECT * FROM mach.m_prd_shift ";

        if (!empty($shift)) {
            $sql .= " WHERE shift_id = '$shift' AND app_id = 'AISIN_MACH' ";
        } else {
            $sql .= " WHERE app_id = 'AISIN_MACH' ";
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
        $sql = "SELECT a.*, b.name1 as srna_name, c.time_Start as start1, c.time_end as end1 "
            . "FROM mach.m_prd_shift_stop a "
            . "LEFT JOIN mach.m_prd_stop_reason_action b ON a.srna_id = b.srna_id AND b.app_id = 'AISIN_MACH' "
            . "LEFT JOIN mach.m_prd_shift c ON a.shift_id = c.shift_id and a.time_id = c.time_id AND c.app_id = 'AISIN_MACH' "
            . "WHERE a.app_id = 'AISIN_MACH'  "
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
        $sql = "SELECT * FROM mach.m_prd_shift_stop WHERE shift_id = '$id' AND time_id = '$id2' AND srna_id = '$id3' AND app_id = 'AISIN_MACH' ";
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
            $sql = "INSERT INTO mach.m_prd_shift_stop (shift_id, srna_id, time_id, start_time, end_time, stop_time, app_id ) "
                . "values (:shift_id, :srna_id, :time_id, :start_time, :end_time, '" . $param["stop_time"] . "', 'AISIN_MACH' )";

            // echo $sql;
            // die();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":shift_id", strtoupper(trim($param["shift_id"])), PDO::PARAM_STR);
            $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            $stmt->bindValue(":start_time", $param["time_start"], PDO::PARAM_STR);
            $stmt->bindValue(":end_time", $param["time_end"], PDO::PARAM_STR);

            if ($param["stop_time"] > 60) {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                $return["message"] = "Stop time tidak boleh lebih dari 60 menit!";
            } elseif ($param["stop_time"] < 0) {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                $return["message"] = "Stop time tidak boleh kurang dari 0 menit!";
            } else {
                if ($stmt->execute()) {
                    $return["status"] = true;
                } else {
                    $error = $stmt->errorInfo();
                    $return["status"] = false;
                    $return["message"] = trim(str_replace("\n", " ", $error[2]));
                    $return["error_code"] = $error[0];
                    if ($return["error_code"] == 23505) {
                        $return["message"] = "Data dengan shift ID " . $param["shift_id"] . ", time ID " . $param["time_id"] . ", dan srna ID " . $param["srna_id"] . " sudah terdaftar!";
                    }
                    error_log($error[2]);
                }
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
            $sql = "UPDATE mach.m_prd_shift_stop SET shift_id = :shift_id, srna_id = :srna_id, time_id = :time_id, start_time = :start_time, end_time = :end_time, stop_time = '" . $param["stop_time"] . "' "
                . "WHERE shift_id = :shift_id AND srna_id = :srna_id AND time_id = :time_id AND app_id = 'AISIN_MACH' ";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $param["id"], PDO::PARAM_STR);
            $stmt->bindValue(":shift_id", $param["shift_id"], PDO::PARAM_STR);
            $stmt->bindValue(":srna_id", $param["srna_id"], PDO::PARAM_STR);
            $stmt->bindValue(":time_id", $param["time_id"], PDO::PARAM_STR);
            $stmt->bindValue(":start_time", $param["time_start"], PDO::PARAM_STR);
            $stmt->bindValue(":end_time", $param["time_end"], PDO::PARAM_STR);

            if ($param["stop_time"] > 60) {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                $return["message"] = "Stop time tidak boleh lebih dari 60 menit!";
            } elseif ($param["stop_time"] < 0) {
                $error = $stmt->errorInfo();
                $return["status"] = false;
                $return["message"] = trim(str_replace("\n", " ", $error[2]));
                $return["message"] = "Stop time tidak boleh kurang dari 0 menit!";
            } else {
                if ($stmt->execute()) {
                    $return["status"] = true;
                } else {
                    $error = $stmt->errorInfo();
                    $return["status"] = false;
                    $return["message"] = trim(str_replace("\n", " ", $error[2]));
                    $return["error_code"] = $error[0];
                    if ($return["error_code"] == 23505) {
                        $return["message"] = "Data dengan shift ID " . $param["shift_id"] . ", time ID " . $param["time_id"] . ", dan srna ID " . $param["srna_id"] . " sudah terdaftar!";
                    }
                    error_log($error[2]);
                }
            }
            $stmt = null;
            $conn = null;
        }
        return $return;
    }
}