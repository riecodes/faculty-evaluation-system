<style>
  :root {
    --white: #f4f6f9;
    --black: #000;
    --sidebar-bg: #183f74;
  }

  /* Set sidebar background color */
  .main-sidebar {
    background-color: var(--sidebar-bg) !important;
    position: relative;
  }

  /* Admin Panel header text */
  .brand-link h3 {
    color: var(--white) !important;
  }
  
  /* Default text color for sidebar items */
  .nav-sidebar .nav-item>.nav-link,
  .nav-sidebar .nav-treeview .nav-item>.nav-link {
    color: var(--white) !important;
  }

  .nav-sidebar .nav-item>.nav-link i,
  .nav-sidebar .nav-treeview .nav-item>.nav-link i {
    color: var(--white) !important;
  }

  .nav-sidebar .nav-item>.nav-link p,
  .nav-sidebar .nav-treeview .nav-item>.nav-link p {
    color: var(--white) !important;
  }

  /* Black text for submenu items when parent is open */
  .nav-sidebar .nav-item.menu-open>.nav-treeview .nav-item>.nav-link {
    color: var(--white) !important;
    border: none !important;
    /* Remove all borders */
  }

  .nav-sidebar .nav-item.menu-open>.nav-treeview .nav-item>.nav-link i,
  .nav-sidebar .nav-item.menu-open>.nav-treeview .nav-item>.nav-link p {
    color: black !important;
  }

  /* Add border to active submenu items */
  .nav-sidebar .nav-item.menu-open>.nav-treeview .nav-item>.nav-link.active {
    border-left: 4px solid var(--sidebar-bg) !important;
    margin: 5px;
  }

  /* Remove any borders from treeview items */
  .nav-sidebar .nav-treeview .nav-item>.nav-link {
    border: none !important;
    box-shadow: none !important;
  }

  .nav-sidebar .nav-item {
    position: relative;
    width: 100%;
    list-style: none;
    border-top-left-radius: 30px;
    border-bottom-left-radius: 30px;
  }

  .nav-sidebar .nav-item>.nav-link {
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    padding: 0.8rem 1rem;
    transition: all 0.3s ease;
  }

  /* Better caret alignment */
  .nav-sidebar .nav-item>.nav-link p {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
  }

  .nav-sidebar .nav-item>.nav-link .right {
    margin-left: auto;
    margin-top: 4px;
  }


  .nav-sidebar .nav-treeview {
    padding-left: 1rem;
  }

  .nav-sidebar .nav-item .nav-link i {
    margin-right: 0.8rem;
    font-size: 1.2rem;
  }

  .nav-sidebar .nav-item .nav-link p {
    margin: 0;
  }

  /* Hover and Active states */
  .nav-sidebar .nav-item:hover,
  .nav-sidebar .nav-item.active {
    background-color: var(--white);
  }

  .nav-sidebar .nav-item:hover a,
  .nav-sidebar .nav-item.active a {
    color: var(--black) !important;
  }

  .nav-sidebar .nav-item:hover i,
  .nav-sidebar .nav-item.active i {
    color: var(--black) !important;
  }

  .nav-sidebar .nav-item:hover p,
  .nav-sidebar .nav-item.active p {
    color: var(--black) !important;
  }

  /* Curved edge effect for both hover and active */
  .nav-sidebar .nav-item:hover::before,
  .nav-sidebar .nav-item.active::before {
    content: "";
    position: absolute;
    right: 0;
    top: -50px;
    width: 50px;
    height: 50px;
    background-color: transparent;
    border-radius: 50%;
    box-shadow: 35px 35px 0 10px var(--white);
    pointer-events: none;
  }

  .nav-sidebar .nav-item:hover::after,
  .nav-sidebar .nav-item.active::after {
    content: "";
    position: absolute;
    right: 0;
    bottom: -50px;
    width: 50px;
    height: 50px;
    background-color: transparent;
    border-radius: 50%;
    box-shadow: 35px -35px 0 10px var(--white);
    pointer-events: none;
  }

  /* Curved edge effect for both hover and active */

  /* Override AdminLTE's default active state */
  .nav-sidebar .nav-item .nav-link.active,
  .nav-sidebar .nav-item .nav-link.active:hover {
    background-color: transparent !important;
    color: var(--black) !important;
  }


  .nav-sidebar .nav-item .nav-link.active p,
  .nav-sidebar .nav-item .nav-link.active:hover p {
    color: var(--black) !important;
  }

  .sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('img/univ.png');
        background-size: fill;
        background-position: center;
        opacity: 0.15;
        z-index: ;
    }
</style>

<aside class="main-sidebar">
  <div class="dropdown panel">
    <a href="./" class="brand-link">
      <h3 class="text-center p-0 m-0"><b>Faculty Panel</b></h3>
    </a>
  </div>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
        data-accordion="false">
        <li class="">
          <a>
            <i class=""></i>
            <p>
            <span style="color: var(--sidebar-bg)">s</span>
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="./index.php?page=result" class="nav-link nav-result">
            <i class="nav-icon fas fa-th-list"></i>
            <p>
              Evaluation Result
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<script>
  $(document).ready(function () {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';

    if (s !== '') page = page + '_' + s;

    $('.nav-link').removeClass('active');
    $('.nav-item').removeClass('active');

    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active').closest('.nav-item').addClass('active');

      if ($('.nav-link.nav-' + page).closest('.nav-treeview').length > 0) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').addClass('menu-open')
          .prev('.nav-link').addClass('active').closest('.nav-item').addClass('active');
      }

      $('.nav-link.nav-' + page).parents('.nav-item').addClass('menu-open');
    }
  });
</script>