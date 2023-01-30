<nav class="sb-topnav navbar navbar-expand navbar-light bg-light shadow-sm">
  <a class="navbar-brand" href=".."><img src="media/images/logo.svg" height="30" alt="" /></a>

  <?php if (isset($_SESSION[LOGIN_SESSION])) { ?>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="material-icons text-24px text-uli-blue">menu</i></button>
    <!-- Navbar Search-->
    <div class="d-none d-md-inline-block form-inline mx-auto text-ega-blue text-center">
      <h5 class='mb-0' style="font-weight: 700; "><?php echo PAGE_TITLE; ?></h5>
    </div>
    <!-- Navbar-->

    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item">
        <button class="btn btn-link my-sm-0 " id="fs-btn"><i class="material-icons">fullscreen</i></button>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?action=profile" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="material-icons text-24px">account_circle</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?action=logout" data-toggle="tooltip" data-placement="bottom" title="Log Out"><i class="material-icons text-24px">logout</i></a>
      </li>
    </ul>
  <?php } ?>
</nav>