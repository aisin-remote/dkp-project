<!DOCTYPE html>
<html lang="en">
  <?php include 'common/t_css.php' ?> 
  <link href="vendors/ega/css/login.css" rel="stylesheet" type="text/css"/>
  <body class="text-center">
    <div class="form-signin">
      <h5 class="text-white" style="font-family: 'Exo';
                                    font-style: normal;
                                    font-weight: 700;
                                    font-size: 30px;
                                    text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);"><?php echo PAGE_TITLE; ?></h5>
      <div class="card mt-3" style="border-radius: 20px;padding-top:20px;padding-bottom: 20px;">
        <div class="card-body">
          <div class="container">
            <form  action="login" method="POST">
            <img class="mb-3" src="media/images/logo.svg" alt="Company Logo" width="150">
            <p class="h5 mb-3 font-weight-normal text-muted">Login to your Account</p>
            <div class="mb-2 text-left">
              <label for="userid">User ID</label>
              <input type="text" id="userid" name="userid" class="mb-1 form-control" placeholder="User ID" required autofocus>
            </div>
            <div class="mb-2 text-left">
              <label for="userpw">Password</label>
              <input type="password" id="userw" name="userpw" class="mb-1 form-control" placeholder="Password" required>
            </div>
            <button class="mt-3 btn btn btn-template px-5" type="submit" name="signin">Log In</button>
            <?php 
            if(isset($_GET["error"])) {
              echo '<p class="mt-2 mb-3 text-danger">'.$_GET["error"].'</p>';
            }
            ?>
            </form>
          </div>          
        </div>
      </div>
      <p class="mt-5 text-white"><?php echo FOOTER; ?></p>
    </div>
    
    <?php include 'common/t_js.php'; ?>
    <script>
      $(document).ready(function(){

      });
    </script>
  </body>
</html>
