<?php

class Avicenna {
  //put your code here
  
  public function getInterlockAvicenna() {
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_param WHERE pid = 'AVICENNA_API' AND seq = 'ON' ";
    $status = "0";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $status = $row["pval1"];
      }
    }
    $stmt = null;
    $conn = null;
    return $status;
  }
  
  public function getAuthToken($usrid) {
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_param WHERE pid = 'AVICENNA_API' AND seq = '1' ";
    $url = "";
    $method = "";
    $access_token = "";
    $post = false;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $url = $row["pval1"];
        $method = $row["pval2"];
        if($method == "POST") {
          $post = true;
        }
      }
    }
    
    $stmt = null;
    $conn = null;
    
    if(!empty($url)) {
      $curl = curl_init();
      
      $headers = [
        "Content-Type: application/json",
        "User-Agent: PostmanRuntime/7.31.1",
        "Accept: appication/json",
        "Connection: keep-alive"
      ];
      
      $arr_body = [];
      $arr_body["npk"] = $usrid;
      $arr_body["password"] = "123456"; 
      $body = json_encode($arr_body);
      
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, $post);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($curl, CURLOPT_TIMEOUT, 10);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      
      $result = curl_exec($curl);
      $param_log = [];
      $param_log["api_url"] = $url;
      $param_log["api_body"] = $body;
      $param_log["api_response"] = $result;
      $param_log["crt_by"] = $usrid;
      $this->insertApiLog($param_log);
      
      curl_close($curl);
      $data = json_decode($result, 1);
      $access_token = $data["access_token"];
    }
    
    return $access_token;
  }
  
  public function explodeKanbanInternal($kanban) {
    $arr_kanban = preg_split('/\s+/', $kanban);
    return $arr_kanban;
  }
  
  public function insertAvicenna($param = []) {
    $data = [];
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_param WHERE pid = 'AVICENNA_API' AND seq = '2' ";
    $url = "";
    $method = "";
    $post = false;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $url = $row["pval1"];
        $method = $row["pval2"];
        if($method == "POST") {
          $post = true;
        }
      }
    }
    
    $stmt = null;
    $conn = null;
    
    if(!empty($url)) {
      $url = str_replace("{1}", $param["kanban_serial"], $url);
      $url = str_replace("{2}", $param["back_number"], $url);
      $url = str_replace("{3}", $param["cycle"], $url);
      $url = str_replace("{4}", $param["customer"], $url);
      $url = str_replace("{5}", $param["npk"], $url);
      $curl = curl_init();
      
      $headers = [
        "Authorization: Bearer ".$param["access_token"],
        "User-Agent: PostmanRuntime/7.31.1",
        "Accept: appication/json",
        "Connection: keep-alive"
      ];
      
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, $post);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($curl, CURLOPT_TIMEOUT, 5);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      
      $result = curl_exec($curl);
      $param_log = [];
      $param_log["api_url"] = $url;
      $param_log["api_body"] = "";
      $param_log["api_response"] = $result;
      $param_log["crt_by"] = $param["npk"];
      $this->insertApiLog($param_log);
      
      curl_close($curl);
      $data = json_decode($result, 1);
    }
    return json_encode($data);
    //return $url;
  }
  
  public function insertApiLog($param = []) {
    $return = [];
    if(empty($param)) {
      $return["status"] = false;
      $return["message"] = "Paramenter Empty";
    } else {
      $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
      $sql = "INSERT INTO t_api_log (api_url, api_body, api_response, crt_by, app_id) VALUES ('".$param["api_url"]."', '".$param["api_body"]."', '".$param["api_response"]."', '".$param["crt_by"]."', '".APP."')";
      //$sql = "INSERT INTO t_api_log (api_url, api_body, api_response, crt_by, app_id) VALUES (:api_url, :api_body, :api_response, :crt_by, '".APP."')";
      
      $stmt = $conn->prepare($sql);
      /*$stmt->bindValue(":api_url", $param["api_url"]);
      $stmt->bindValue(":api_body", $param["api_body"]);
      $stmt->bindValue(":api_response", $param["api_response"]);
      $stmt->bindValue(":crt_by", $param["crt_by"]);*/
      $stmt = $conn->prepare($sql);
      if ($stmt->execute() or die($stmt->errorInfo()[2])) {
        $return["status"] = true;
      } else {
        $error = $stmt->errorInfo();
        $return["status"] = false;
        $return["message"] = trim(str_replace("\n", " ", $error[2]));
        error_log($error[2]);
      }
      $stmt = null;
      $conn = null;
    }
    
    return $return;
  }
  
  public function getInterlockKanbanI() {
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT * FROM m_param WHERE pid = 'CEK_KANBAN_I' AND seq = '1' ";
    $status = 0;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        $status = intval($row["pval1"]);
      }
    }
    $stmt = null;
    $conn = null;
    return $status;
  }
}

?>