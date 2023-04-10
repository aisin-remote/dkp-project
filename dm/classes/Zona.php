<?php

class Zona {

  public function getList() {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_zona ORDER BY seq ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $conn = null;
    return $return;
  }

  public function getList2() {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_zona ORDER BY seq2 ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return[] = $row;
      }
    }
    $conn = null;
    return $return;
  }
  
  public function isUsed($zona_id, $dies_id) {
    $return = [];
    $return["count"] = 0;
    $return["desc"] = "";
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "select count(*) as cnt, (select \"desc\" FROM m_zona where zona_id = '$zona_id')  from public.m_dm_dies_asset where zona_id = '$zona_id' and dies_id <> '$dies_id'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return["count"] = intval($row["cnt"]);
        $return["desc"] = $row["desc"];
      }
    }
    $conn = null;
    return $return;
  }
  
  public function getById($id) {
    $return = array();
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM m_zona WHERE zona_id = '$id' ";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $return = $row;
      }
    }
    $conn = null;
    return $return;
  }

}

?>