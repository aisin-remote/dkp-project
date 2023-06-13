<?php

class Transaction
{
    public function getList($lddat_from = "*", $lddat_to = "*", $fil_cust = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, a.ldnum, a.pdsno, a.cycle1, to_char(a.lddat, 'DD-MM-YYYY') as date_only, "
            . " to_char(a.lddat, 'HH24:MI') as time_only, a.stats, "
            . " (select name1 from m_io_lfa1 where lifnr = a.lifnr) as customer "
            . " FROM t_io_ldlist_h a  "
            . " WHERE a.dstat IS NULL ";

        if ($lddat_from !== "*" && $lddat_to !== "*") {
            $sql .= " AND TO_CHAR(a.lddat, 'YYYYMMDD') between '$lddat_from' AND '$lddat_to' ";
        }
        if (!empty($fil_cust)) {
            $sql .= " AND a.lifnr = '$fil_cust' ";
        }

        $sql .= " ORDER by a.ldnum ASC ";

        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["stats"] == "N") {
                    $row["stats"] = "UNCOMPLETED";
                } elseif ($row["stats"] == "C") {
                    $row["stats"] = "COMPLETED";
                }

                if ($row["dstat"] == null || $row["dstat"] == "") {
                    $row["dstat"] = "NOT DELIVERED";
                } elseif ($row["dstat"] == "D") {
                    $row["dstat"] = "DELIVERED";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getById($ldnum)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, a.ldnum, a.pdsno, a.cycle1, to_char(a.lddat, 'DD-MM-YYYY') as date_only, "
            . " to_char(a.lddat, 'HH24:MI') as time_only, a.stats, "
            . " (select name1 from m_io_lfa1 where lifnr = a.lifnr) as customer "
            . " FROM t_io_ldlist_h a  "
            . " WHERE a.ldnum = '$ldnum' ";
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

    public function updateStatus($extract_id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE public.t_io_ldlist_h SET dstat = 'D' "
            . "WHERE ldnum IN('$extract_id')";

        // echo $sql;
        // exit;
        $stmt = $conn->prepare($sql);
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
        return $return;
    }
    public function updateStatus2($extract_id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE public.t_io_ldlist_i SET dstat = 'D', dstat_dats = CURRENT_TIMESTAMP "
            . "WHERE ldnum IN('$extract_id')";

        // echo $sql;
        // exit;
        $stmt = $conn->prepare($sql);
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
        return $return;
    }
    public function updateStatus3($extract_id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE public.t_io_ldlist_dtl SET dstat = 'D', dstat_dats = CURRENT_TIMESTAMP "
            . "WHERE ldnum IN('$extract_id')";

        // echo $sql;
        // exit;
        $stmt = $conn->prepare($sql);
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
        return $return;
    }

    public function getCustomer()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT lifnr, name1 FROM m_io_lfa1 ";

        $sql .= " ORDER by lifnr ASC ";
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

?>