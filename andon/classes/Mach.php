<?php
    class Mach {
        public function getList() {
            $return = array();
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "SELECT a.*, a.name1 as mach_name, b.*, b.name1 as line_name from m_prd_mach a
                    INNER JOIN m_prd_line b ON b.line_id = a.line_id ";
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

        public function updateStats($line_id, $stats) {
            $return = array();
            $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $sql = "UPDATE m_prd_mach SET stats = $stats WHERE line_id = '$line_id' ";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                $return = "success";
            } else {
                $return = "failed";
            }
            $stmt = null;
            $conn = null;
            return $return;
        }
    }
?>