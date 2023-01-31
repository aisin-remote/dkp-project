<?php

class Report
{
    public function getList($lddat_from = "*", $lddat_to = "*", $fil_cust = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.ldnum, a.pdsno, a.cycle1, to_char(a.lddat, 'MM-DD-YYYY') as date_only, "
            . " to_char(a.lddat, 'HH24:MI') as time_only, a.stats, "
            . " (select name1 from m_io_lfa1 where lifnr = a.lifnr) as customer "
            . " FROM t_io_ldlist_h a  "
            . " WHERE 1=1 ";

        if ($lddat_from !== "*" && $lddat_to !== "*") {
            $sql .= " AND TO_CHAR(a.lddat, 'YYYYMMDD') between '$lddat_from' AND '$lddat_to' ";
        }
        if (!empty($fil_cust)) {
            $sql .= " AND (select name1 from m_io_lfa1 where lifnr = a.lifnr) = '$fil_cust' ";
        }

        $sql .= " ORDER by a.ldnum ASC ";
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["stats"] == "N") {
                    $row["stats"] = "UNCOMPLETED";
                } elseif ($row["stats"] == "C") {
                    $row["stats"] = "COMPLETED";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
    public function getList2($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, (select name1 from m_io_mara where matnr = a.matnr) "
            . "FROM t_io_ldlist_dtl a "
            . "WHERE a.ldnum = '$id' ";
        $sql .= " ORDER by a.trseq ASC ";
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

    public function getCustomer()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT name1 FROM m_io_lfa1 ";

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
