<?php

class Report
{
    public function getList($lddat_from = "*", $lddat_to = "*", $fil_cust = null)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, a.ldnum, a.pdsno, a.cycle1, to_char(a.lddat, 'MM-DD-YYYY') as date_only, "
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
        $sql = "SELECT a.*, a.matn1 as custpart, b.* FROM t_io_ldlist_i a
                LEFT JOIN m_io_mara b on b.matnr = a.matnr
                where a.ldnum = '$id' ";
        $sql .= " ORDER by a.ldseq ASC ";
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["dstat"] == "D") {
                    $row["dstat"] = "Delivered";
                } else {
                    $row["dstat"] = "Not Delivered";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getList3($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, to_char(a.dstat_dats, 'MM-DD-YYYY') as date_only, to_char(a.dstat_dats, 'HH24:MI') as time_only, b.*, c.name1
                from t_io_ldlist_dtl a
                left join t_io_ldlist_i b on b.ldnum = a.ldnum AND b.ldseq = a.ldseq
                left join m_io_mara c on c.matnr = b.matnr
                where a.ldnum = '$id' ";
        $sql .= " ORDER by a.ldseq ASC ";
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["dstat"] == "D") {
                    $row["dstat"] = "Delivered";
                } else {
                    $row["dstat"] = "Not Delivered";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListDetail($lddat_from = "*", $lddat_to = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, a.matn1 as custpart, b.*, c.*, c.ldnum, c.pdsno, c.cycle1, to_char(c.lddat, 'MM-DD-YYYY') as date_only,
                to_char(c.lddat, 'HH24:MI') as time_only, d.name1 as customer FROM t_io_ldlist_i a
                LEFT JOIN m_io_mara b on b.matnr = a.matnr
                LEFT JOIN t_io_ldlist_h c on c.ldnum = a.ldnum
                LEFT JOIN m_io_lfa1 d on d.lifnr = c.lifnr
                where 1=1 ";
        // echo $sql;
        // die();
        if ($lddat_from !== "*" && $lddat_to !== "*") {
            $sql .= " AND TO_CHAR(c.lddat, 'YYYYMMDD') between '$lddat_from' AND '$lddat_to' ";
        }
        $sql .= " ORDER by a.ldseq ASC ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["dstat"] == "D") {
                    $row["dstat"] = "Delivered";
                } else {
                    $row["dstat"] = "Not Delivered";
                }
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListDetail2($lddat_from = "*", $lddat_to = "*")
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, a.matn1 as custpart, b.*, c.*, c.ldnum, c.pdsno, c.cycle1, to_char(c.lddat, 'MM-DD-YYYY') as date_only,
                to_char(c.lddat, 'HH24:MI') as time_only, d.name1 as customer, e.kanban_i, e.kanban_e FROM t_io_ldlist_i a
                LEFT JOIN m_io_mara b on b.matnr = a.matnr
                LEFT JOIN t_io_ldlist_h c on c.ldnum = a.ldnum
                LEFT JOIN m_io_lfa1 d on d.lifnr = c.lifnr
                LEFT JOIN t_io_ldlist_dtl e on e.ldnum = a.ldnum AND e.ldseq = a.ldseq
                where 1=1 ";
        // echo $sql;
        // die();
        if ($lddat_from !== "*" && $lddat_to !== "*") {
            $sql .= " AND TO_CHAR(c.lddat, 'YYYYMMDD') between '$lddat_from' AND '$lddat_to' ";
        }
        $sql .= " ORDER by a.ldseq ASC ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["dstat"] == "D") {
                    $row["dstat"] = "Delivered";
                } else {
                    $row["dstat"] = "Not Delivered";
                }
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