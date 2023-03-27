<?php
    class Param {
        public function getParam() {
            $return = array();
            $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
            $sql = "SELECT * FROM m_param WHERE pid = 'LINE_STATUS' ORDER BY seq ASC";

            $stmt = $conn->prepare($sql);
            if($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                    $return[] = $row;
                }
            }
            $stmt = null;
            $conn = null;
            return $return;
        }

        public function getListShift() {
            $return = array();
            $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
            $sql = "SELECT * FROM m_param WHERE pid = 'SHIFT' ORDER BY seq ASC";

            $stmt = $conn->prepare($sql);
            if($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                    $return[] = $row;
                }
            }
            $stmt = null;
            $conn = null;
            return $return;
        }
    }
