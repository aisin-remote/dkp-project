<?php
class Parameter {
    public function getParam() {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM m_param where pid = 'IOTEMP' ";
        $sql1 = "SELECT * FROM m_param where pid = 'IOTEMP_SET' ";
        $st = $conn->prepare($sql);
        $st1 = $conn->prepare($sql1);
        if ($st->execute() && $st1->execute()) {
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $return["shift"] = $row;
            }
            while ($row1 = $st1->fetch(PDO::FETCH_ASSOC)) {
                $return["range"] = $row1;
            }
        }
        $conn = null;
        return $return;
    }

    public function updateShift($param) {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE m_param SET pval1 = :pval1 WHERE pid = 'IOTEMP' ";
        $st = $conn->prepare($sql);
        $st->bindValue(":pval1", $param["shift"]);
        if ($st->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $st->errorInfo();
        }
        $conn = null;
        return $return;
    }

    public function updateRange($param) {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE m_param SET pval1 = :pval1, pval2 = :pval2, pval3 = :pval3, pval4 = :pval4 WHERE pid = 'IOTEMP_SET' ";
        $st = $conn->prepare($sql);
        $st->bindValue(":pval1", $param["min_temp"]);
        $st->bindValue(":pval2", $param["max_temp"]);
        $st->bindValue(":pval3", $param["min_humid"]);
        $st->bindValue(":pval4", $param["max_humid"]);
        if ($st->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $st->errorInfo();
        }
        $conn = null;
        return $return;
    }
}
?>