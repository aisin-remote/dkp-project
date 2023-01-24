<?php

class License {

  //put your code here
  public function activateLicense($lic_srl) {
    $url = "https://license.ega-id.com/api.php?action=activate_license";
    $curl = curl_init();
    $device_id = $this->UniqueMachineID();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
          'lic_srl'=>$lic_srl,
          'device_id'=>$device_id,
          'app'=>APP
        ),
        CURLOPT_HTTPHEADER => array(
          'Content-Type' => "application/x-www-form-urlencoded"
        )
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if(!empty($err)) {
      $result["status"] = false;
      $result["message"] = $err;
      return $result;
    }
    $result = json_decode($response, true);
    
    if($result["status"] == true) {
      $license_data = $result["data"];
      //insert license data to local DB
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO m_license(lic_id,lic_srl,lic_app,lic_type,lic_vol,exp_dat) values(:lic_id,:lic_srl,:lic_app,:lic_type,:lic_vol,TO_DATE(:exp_dat,'YYYY-MM-DD'))";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lic_id", $license_data["lic_id"], PDO::PARAM_STR);
      $stmt->bindValue(":lic_srl", $license_data["lic_srl"], PDO::PARAM_STR);
      $stmt->bindValue(":lic_app", $license_data["lic_app"], PDO::PARAM_STR);
      $stmt->bindValue(":lic_type", $license_data["lic_type"], PDO::PARAM_STR);
      $stmt->bindValue(":lic_vol", $license_data["lic_vol"], PDO::PARAM_STR);
      $stmt->bindValue(":exp_dat", $license_data["exp_dat"], PDO::PARAM_STR);
      if($stmt->execute()) {
        $return["status"] = true;
        $return["message"] = "License Added";
      } else {
        $error = $stmt->errorInfo();
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
      }
    } else {
      $return["status"] = false;
      $return["message"] = $result["message"];
    }
    return $return;
  }

  function UniqueMachineID() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {  
      $uuid = trim(preg_replace('/\s\s+/', ' ', shell_exec("wmic csproduct get uuid")));
      $result = explode(" ",$uuid)[1];
    } else {
      $result = trim(shell_exec('cat /etc/machine-id 2>/dev/null'));
    }
    return $result;
  }
  
  function getList() {
    $return = array();
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT *, TO_CHAR(exp_dat,'DD-MM-YYYY') as expired_date FROM m_license WHERE lic_app = '".APP."' ORDER BY exp_dat ASC";
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
  
  function isSoftwareActivated() {
    $return = false;
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_license WHERE lic_type = 'SOFTWARE' AND lic_app = '".APP."' AND TO_CHAR(exp_dat,'YYYYMMDD') > '".date("Ymd")."' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $data = array();
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $data = $row;
      }
    }
    $stmt = null;
    $conn = null;
    if(!empty($data["lic_id"])) {
      $return = true;
    }
    return $return;
  }
}

?>