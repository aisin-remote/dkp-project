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
    }
?>