<?php
class Line
{
    public function getLine()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.*, b.* FROM m_prd_line a LEFT JOIN m_andon_status b ON b.andon_id = a.line_st WHERE a.line_ty = 'ECU' ORDER BY a.seq ASC";

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

    public function getLineById($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT a.* FROM m_prd_line a WHERE a.line_ty = 'ECU' and a.line_id = $id ";

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

    public function updateStatus($line_id, $status)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE m_prd_line SET line_st = :status WHERE line_id = :line_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":line_id", $line_id, PDO::PARAM_STR);
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

    public function getListStatus() {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM m_andon_status ORDER BY andon_id ASC";

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
