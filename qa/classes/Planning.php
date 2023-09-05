<?php
class Planning
{
    public function getList()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.partno, b.name1, a.month from qas.m_planning a
        left join qas.m_tmpl_h b on b.partno = a.partno
        order by a.partno";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function getPlanById($partno, $month)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.partno, b.name1, a.month from qas.m_planning a
        left join qas.m_tmpl_h b on b.partno = a.partno
        where a.partno = :partno and a.month = :month";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":partno", $partno);
        $stmt->bindParam(":month", $month);
        if ($stmt->execute()) {
            $return = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $return;
    }

    public function insert($param)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.m_planning (partno, month, crtdt, crtby) values (:partno, :month, current_timestamp, :crtby)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":partno", $param["partno"]);
        $stmt->bindParam(":month", $param["month"]);
        $stmt->bindParam(":crtby", $_SESSION["USERNAME"]);

        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        return $return;
    }

    public function update($param)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "update qas.m_planning set partno = :partno, month = :month where partno = :partno and month = :month";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":partno", $param["partno"]);
        $stmt->bindParam(":month", $param["month"]);

        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        return $return;
    }

    public function isExist($id, $month)
    {
        $return = false;
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT count(*) as cnt FROM qas.m_planning WHERE partno = :id and month = :month";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        $stmt->bindValue(":month", $month, PDO::PARAM_STR);
        $count = 0;
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $count = intval($row["cnt"]);
            }
        }
        if ($count > 0) {
            $return = true;
        }
        $stmt = null;
        $conn = null;
        return $return;
    }
}
?>