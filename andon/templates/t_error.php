<!DOCTYPE html>
<html lang="en">
  <?php include "common/t_css.php"; ?>
  <body>
    <?php include "common/t_nav_top.php"; ?>
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid text-center">
            <H1 class="mt-5">ERROR!!!</h1>
            <p><?php echo $error; ?></p>
            <p><?php echo $data["error"]; ?></p>
            <p><button class="btn btn-danger" onclick="goBack()"><i class="material-icons">backspace</i></button></p>
          </div>
        </main>          
      </div>
    </div>
    <?php include 'common/t_js.php'; ?>
    <script>
    function goBack() {
      window.history.back();
    }
    </script>
  </body>
</html>
