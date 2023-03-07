<?php 
if($action == "login") {
  $data["group"] = "ROOT";
  $data["title"] = "Please Login";
  
  if(isset($_SESSION[LOGIN_SESSION])) {
    header("Location: ".$last_url);
  }
  
  if(isset($_POST["signin"])) {
    $userid = $_POST["userid"];
    $userpw = $_POST["userpw"];
    
    $user = new User();
    
    $login = $user->login($userid, $userpw);
    if($login["status"] == true) {
      $_SESSION[LOGIN_SESSION] = strtoupper($userid);
      $_SESSION["USERNAME"] = $login["data"]["name1"];
      
      header("Location: ?action=home");
    } else {
      header("Location: ?action=$action&error=".$login["message"]);
    }
  } else {
    require( TEMPLATE_PATH . "/t_login.php" );
  }
}

if($action == "logout") {
  unset($_SESSION[LOGIN_SESSION]);
  unset($_SESSION["USERNAME"]);
  header("Location: ?action=login");
}
?>