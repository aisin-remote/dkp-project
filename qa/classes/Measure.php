<?php
class Measure
{
    public function getHeaderById($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, TO_CHAR(a.prd_dt, 'DD-MM-YYYY') as date, b.partno, b.name1 as partname, c.name1 as inspector, c.empid, d.pval1, b.tmpfl 
        from qas.t_tmpl_h a
        left join qas.m_tmpl_h b on b.partno = a.partno
        left join m_prd_operator c on c.empid = a.inspector
        left join m_param d on d.seq = a.shift and d.pid = 'SHIFT'
        where a.doc_no = '$id' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListGrupById($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, b.* from qas.t_tmpl_i a
        left join qas.m_tmpl_i b on b.grupid = a.grupid and b.partno = a.partno
        where a.doc_no = '$id' order by a.grupid::integer asc ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getGrupById($id, $grup)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, b.* from qas.t_tmpl_i a
        left join qas.m_tmpl_i b on b.grupid = a.grupid and b.partno = a.partno
        where a.doc_no = '$id' and a.grupid = '$grup' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListMap($id)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, a.cellid, b.desc1, b.cell_map from qas.t_tmpl_map a
        left join qas.m_tmpl_map b on b.cellid = a.cellid and b.partno = a.partno
        where a.doc_no = '$id' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getListMapByGrup($id, $grup)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, a.cellid, b.desc1, b.cell_map from qas.t_tmpl_map a
        left join qas.m_tmpl_map b on b.cellid = a.cellid and b.partno = a.partno and b.grupid = a.grupid 
        where a.doc_no = '$id' and a.grupid = '$grup' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getMapByGrup($id, $grup)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select a.*, a.cellid, b.desc1, b.cell_map from qas.t_tmpl_map a
        left join qas.m_tmpl_map b on b.cellid = a.cellid and b.partno = a.partno and b.grupid = a.grupid  
        where a.doc_no = '$id' and a.grupid = '$grup' order by regexp_replace(b.cellid, '[^0-9]+', '', 'g')::integer, b.cellid asc ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $return[] = $row;
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function insertHeader($id, $date, $shift, $operator, $doc_no, $sign, $rasio)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.t_tmpl_h (prd_dt, shift, partno, inspector, doc_no, status, sign_pos, rasio_pos) 
        values (current_timestamp, '" . $shift . "', '" . $id . "', '" . $operator . "', '" . $doc_no . "', 'N', '" . $sign . "', '" . $rasio . "') 
        returning doc_no";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return["doc_no"] = $row["doc_no"];
            }
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function insertGrup($id, $date, $shift, $operator, $param = array(), $doc_no)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.t_tmpl_i (prd_dt, shift, partno, inspector, grupid, doc_no, bgcolor) values";
        $arr_insert = array();
        $i = 0;
        foreach ($param["grupid"] as $grup) {
            $arr_insert[] = "(current_timestamp, '" . $shift . "', '" . $id . "', '" . $operator . "', '" . $grup . "', '" . $doc_no . "', 'bg-primary')";
            $i++;
        }
        $sql .= implode(",", $arr_insert);
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function insertItem($id, $date, $shift, $operator, $param = array(), $doc_no)
    {
        $return = array();
        // print_r($param);
        // die();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "insert into qas.t_tmpl_map (partno, prd_dt, shift, inspector, grupid, cellid, doc_no) values";
        $arr_insert = array();
        $i = 0;
        foreach ($param as $item) {
            $arr_insert[] = "('" . $id . "', current_timestamp, '" . $shift . "', '" . $operator . "', '" . $item["grupid"] . "', '" . $item["cellid"] . "', '" . $doc_no . "')";
            $i++;
        }
        $sql .= implode(",", $arr_insert);
        // echo $sql;
        // die();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function insertValue($id, $shift, $date, $grup, $operator, $value, $item_list, $doc_no)
    {
        $return = array();
        // print_r($item_list);
        // die();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $conn->exec("delete from qas.t_tmpl_map 
        where doc_no = '$doc_no' and grupid = '$grup'");
        $sql = "insert into qas.t_tmpl_map (partno, prd_dt, shift, inspector, grupid, cellid, value, doc_no) values";
        $arr_insert = array();
        $i = 0;
        foreach ($value as $row) {
            $arr_insert[] = "('" . $id . "', current_timestamp, '" . $shift . "', '" . $operator . "', '" . $grup . "', '" . $item_list[$i]["cellid"] . "', " . $row . ", '" . $doc_no . "')";
            $i++;
        }
        $sql .= implode(",", $arr_insert);
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function updateValue($id, $shift, $date, $grup, $operator, $value, $item_list, $doc_no)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $i = 0;
        foreach ($value as $row) {
            if (empty($row)) {
                $sql = "update qas.t_tmpl_map set value = NULL where doc_no = '$doc_no' and grupid = '$grup' and cellid = '" . $item_list[$i]["cellid"] . "' ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            } else {
                $sql = "update qas.t_tmpl_map set value = " . $row . " where doc_no = '$doc_no' and grupid = '$grup' and cellid = '" . $item_list[$i]["cellid"] . "' ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }
            $i++;
        }
        $return["status"] = true;
        // try {
        //     //code...
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     $return["status"] = false;
        //     $return["message"] = $th->getMessage();
        // }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function updateStatus($id, $status)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "update qas.t_tmpl_h set status = '$status' where doc_no = '$id'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function generateDocNo()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "select COALESCE(MAX (doc_no::bigint), cast(to_char(current_timestamp, 'YYMMDD')||'0000' as bigint)) + 1 as lastid
        from qas.t_tmpl_h where doc_no like to_char(current_timestamp, 'YYMMDD')||'%'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return = $row["lastid"];
            }
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function updateBgcolor($doc_no, $grupid, $color)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "update qas.t_tmpl_i set bgcolor = '$color' where doc_no = '$doc_no' and grupid = '$grupid' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function approve($id, $ttd, $accur)
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "update qas.t_tmpl_h set approval = 'Y', apr_by = '" . $_SESSION[LOGIN_SESSION] . "', apr_dt = current_timestamp, ttd = '" . $ttd . "', accur = '" . $accur . "' where status = 'C' and doc_no = '" . $id . "' ";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            $return["status"] = true;
        } else {
            $return["status"] = false;
            $return["message"] = $stmt->errorInfo();
        }
        $stmt = null;
        $conn = null;
        return $return;
    }

    public function getShiftOri()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM m_param WHERE pid = 'SHIFTORI' and TO_CHAR(current_timestamp, 'HH24MISS') between pval1 and pval2  ORDER BY seq";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute() or die($stmt->errorInfo()[2])) {
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