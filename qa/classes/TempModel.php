<?php
class TempModel
{
    public function getList()
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_h";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function getHeaderById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_h where partno = '" . $id . "' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function getGrupById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_i where partno = '" . $id . "' order by grupid asc";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function getGrup($id, $tmpid)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_i where partno = '" . $tmpid . "' and grupid = '" . $id . "' order by grupid asc";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function getItemById($grup, $id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_map where partno = '" . $id . "' and grupid = '" . $grup . "' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function getListItemById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select * from qas.m_tmpl_map where partno = '" . $id . "'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function insertHeader($param = array())
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.m_tmpl_h (partno, name1, crtby, tmpfl)
            values ('" . $param["partno"] . "', '" . $param["partname"] . "', '" . LOGIN_SESSION . "', '" . $param["excel"] . "') returning partno";
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            // $return["id"] = $conn->lastInsertId("partno");
            while ($row = $stmt->fetch()) {
                $return["id"] = $row["partno"];
            }
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

    public function insertGrup($param = array(), $id)
    {
        $return = array();
        // print_r($param["grup"]);
        // die();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.m_tmpl_i (tmpid, desc1, grupid, img) values (";
        $arr_insert = array();
        $i = 0;
        foreach ($param["grup"] as $grup) {
            $i++;
            $arr_insert[] = "'" . $id . "', '" . $grup . "', '" . $i . "', '" . $param["image"][$i - 1] . "'";
        }
        // echo "<pre>";
        // print_r($arr_insert);
        // echo "</pre>";
        // die();
        $sql .= implode("),(", $arr_insert);
        $sql .= ")";
        // echo $sql;
        // die();
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

    public function insertMap($param = array(), $partno, $grupid)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.m_tmpl_map (partno, grupid, cellid, desc1, cell_map) values 
        ('$partno', '$grupid', '" . $param["cellid"] . "', '" . $param["desc1"] . "', '" . $param["field"] . "')";
        // echo $sql;
        // die();
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

    public function updateHeader($param = array(), $id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "update qas.m_tmpl_h set partno = '" . $param["partno"] . "', name1 = '" . $param["partname"] . "', chgby = '" . LOGIN_SESSION . "', chgdt = current_timestamp, tmpfl = '" . $param["excel"] . "', sign_pos = '".$param["sign"]."', rasio_pos = '".$param["rasio"]."' where partno = '$id' ";
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

    public function updateGrup($param = array(), $id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $conn->exec("DELETE from qas.m_tmpl_i where tmpid = $id ");
        $sql = "insert into qas.m_tmpl_i (tmpid, desc1, grupid, img) values ";
        $arr_insert = array();
        $i = 0;
        foreach ($param["grup"] as $grup) {
            $arr_insert[] = "((select max(tmpid) from qas.m_tmpl_h), '" . $grup . "', '" . $i + 1 . "', '" . $param["image"][$i] . "')";
            $i++;
        }
        $sql .= implode(",", $arr_insert);
        // echo $sql;
        // die();
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

    public function updateMap($param = array(), $partno, $grupid)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $conn->exec("DELETE from qas.m_tmpl_map where partno = '" . $param["partno"] . "' and grupid = '" . $param["grupid"] . "'");
        $sql = "insert into qas.m_tmpl_map (partno, grupid, cellid, desc1, cell_map) values ";
        $arr_insert = array();
        $i = 0;
        foreach ($param["field"] as $field) {
            if (!empty($field)) {
                $arr_insert[] = "('" . $partno . "', '" . $grupid . "', " . $param["cellid"][$i] . ", '" . $param["desc"][$i] . "', '" . $field . "')";
                $i++;
            }
        }

        $sql .= implode(",", $arr_insert);
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

    public function countGrup($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select count(*) from qas.m_tmpl_i where tmpid = $id ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $result;
    }

    public function mapIsExist($partno, $grupid, $cellid)
    {
        $return = false;
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select count(*) as cnt from qas.m_tmpl_map where partno = '$partno' and grupid = '$grupid' and cellid = '$cellid' ";
        $stmt = $conn->prepare($sql);
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