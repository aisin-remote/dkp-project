<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="just-padding">

        <div class="list-group list-group-root well">
          <a href="?action=home" class="list-group-item <?php if ($action == "home") {
                                                          echo "active";
                                                        } ?>"><i class="material-icons">home</i> Dashboard</a>
          <?php
          if (isset($_SESSION["MENUGROUP"])) {
            foreach ($_SESSION["MENUGROUP"] as $grp) {
              if ($grp["groupid"] == $menu_group) {
                echo '<a href="#item-' . $grp["groupid"] . '" class="list-group-item d-flex justify-content-between align-items-center" data-toggle="collapse" aria-expanded="true">' . $grp["groupdsc"] . '<span class="badge"><i class="material-icons">chevron_right</i></span></a>';
                echo '<div class="list-group collapse show" id="item-' . $grp["groupid"] . '">';
              } else {
                echo '<a href="#item-' . $grp["groupid"] . '" class="list-group-item d-flex justify-content-between align-items-center" data-toggle="collapse">' . $grp["groupdsc"] . '<span class="badge"><i class="material-icons">chevron_right</i></span></a>';
                echo '<div class="list-group collapse" id="item-' . $grp["groupid"] . '">';
              }

              foreach ($_SESSION["USERMENU"] as $menu) {
                if ($menu["groupid"] == $grp["groupid"]) {
                  if ($action == strtolower($menu["menuid"])) {
                    echo '<a href="?action=' . $menu["menuid"] . '" class="list-group-item list-group-item-action active"><span class="text-nowrap">' . $menu["name1"] . '</span></a>';
                  } else {
                    echo '<a href="?action=' . $menu["menuid"] . '" class="list-group-item list-group-item-action"><span class="text-nowrap">' . $menu["name1"] . '</span></a>';
                  }
                }
              }
              echo '</div>';
            }
          }
          ?>
        </div>

      </div>


    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      <?php echo $_SESSION["USERNAME"]; ?>
    </div>
  </nav>
</div>