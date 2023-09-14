<nav class="sb-topnav navbar navbar-expand navbar-light bg-white shadow-sm">
  <a class="navbar-brand" href="../"><img src="media/images/logo.svg" height="30" alt=""/></a>
  
  <?php if(isset($_SESSION[LOGIN_SESSION])) { ?>
  <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="material-icons text-24px text-uli-blue">menu</i></button>
  <!-- Navbar Search-->
  <ul class="navbar-nav ml-auto ml-md-0 text-ega-blue">
    <li class="nav-item">      
      <a class="nav-link text-ega-blue" href="?action=home"><?php echo PAGE_TITLE; ?></a>      
    </li>
  </ul>
  
  <div class="d-none d-md-inline-block form-inline mx-auto text-ega-blue">
    &nbsp;
  </div>
  <!-- Navbar-->
  
  <ul class="navbar-nav ml-auto ml-md-0 text-ega-blue">
    <li class="nav-item">
      <a class="nav-link text-ega-blue" href="?action=profile" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="material-icons text-24px">account_circle</i></a> 
    </li>
    <li class="nav-item">      
      <a class="nav-link text-ega-blue" href="?action=logout" data-toggle="tooltip" data-placement="bottom" title="Log Out"><i class="material-icons text-24px">logout</i></a>      
    </li>
  </ul>
  <?php } ?>
</nav>