<?php

class Report
{
    public function getList()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, (select name1 from m_io_mara where matnr = a.matnr) "
            . "FROM t_io_ldlist_dtl a "
            . "WHERE 1=1 ";
        $sql .= " ORDER by a.matnr ASC ";
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
