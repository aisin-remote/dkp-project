<?php
class Material
{
    public function getListMaterial()
    {
        $return = array();
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM wms.m_mara WHERE mtart = 'FIN'";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return[] = $row;
            }
        }
        return $return;
    }
}
?>