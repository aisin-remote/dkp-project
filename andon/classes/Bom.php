<?php
class Bom
{
    public function getListHeader()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.* FROM m_prd_bom_h a
                inner join wms.m_mara b on b.matnr = a.matnr 
                WHERE a.app_id = '" . APP . "' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function getHeader($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.* FROM m_prd_bom_h a
                inner join wms.m_mara b on b.matnr = a.matnr 
                WHERE a.matnr = '$id' AND a.app_id = '" . APP . "' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function getDetail($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.meins as uom, b.* FROM m_prd_bom_i a
                LEFT JOIN wms.m_mara b ON b.matnr = a.matn1
                WHERE a.matnr = '$id'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function getDetailPerMats($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.meins as uom, b.* FROM m_prd_bom_i a
                LEFT JOIN wms.m_mara b ON b.matnr = a.matn1
                WHERE a.matn1 = '$id'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }

    public function insertHeader($param)
    {
        // print("<pre>" . print_r($param, true) . "</pre>");;
        // die();
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "INSERT INTO m_prd_bom_h (matnr, menge, meins, app_id) VALUES (:matnr, :menge, :meins, :app_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
        $stmt->bindValue(":menge", $param["menge_h"], PDO::PARAM_STR);
        $stmt->bindValue(":meins", $param["meins"], PDO::PARAM_STR);
        $stmt->bindValue(":app_id", APP, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $error = $stmt->errorInfo();
            $return["status"] = false;
            $return["message"] = trim(str_replace("\n", " ", $error[2]));
            error_log($error[2]);
        }
        return $return;
    }

    public function insertDetail($param)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "INSERT INTO m_prd_bom_i (matnr, matn1, menge, meins) VALUES (:matnr, :matn1, :menge, :meins)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":matnr", $param["matnr"], PDO::PARAM_STR);
        $stmt->bindValue(":matn1", $param["matn1"], PDO::PARAM_STR);
        $stmt->bindValue(":menge", $param["menge"], PDO::PARAM_STR);
        $stmt->bindValue(":meins", $param["meins"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $error = $stmt->errorInfo();
            $return["status"] = false;
            $return["message"] = trim(str_replace("\n", " ", $error[2]));
            error_log($error[2]);
        }
        return $return;
    }

    public function updateItem($matnr, $param)
    {
        // print("<pre>" . print_r($param, true) . "</pre>");
        // die();
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $conn->exec("DELETE FROM m_prd_bom_i WHERE matnr = '" . $matnr . "'");

        $sql = "INSERT INTO m_prd_bom_i (matnr, matn1, menge, meins) VALUES ";
        $arr_insert = [];
        $i = 0;
        foreach ($param as $row) {
            $arr_insert[] = "('$matnr','" . $row["matn1"] . "','" . $row["menge"] . "','" . $row["meins"] . "')";
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
        return $return;
    }
}
?>