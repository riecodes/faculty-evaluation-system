<style>
    .user-img {
        border-radius: 50%;
        height: 35px;
        width: 35px;
        object-fit: cover;
    }
    .navbar-primary {
        background-color: #417029 !important;
        position: relative;
    }
    .navbar-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('img/univ.png') center;
        opacity: 0.15;
        z-index: 0;
    }
    .navbar-primary * {
        position: relative;
        z-index: 1;
    }
    .univ-logo {
        height: 40px;
        width: auto;
        margin-right: 10px;
        opacity: 0.9;
    }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <?php if (isset($_SESSION['login_id'])): ?>
            <li class="nav-item">
                <a class="nav-link text-white" data-widget="pushmenu" href="" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        <?php endif; ?>
        <li>
            <a class="nav-link text-white font-weight-bold h5 mb-0" href="./" role="button">
                <?php echo $_SESSION['system']['name'] ?>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center text-white" data-toggle="dropdown" href="javascript:void(0)">
                <div class="d-flex align-items-center">
                    <img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="user-img border border-white mr-2">
                    <span class="font-weight-bold mr-2"><?php echo ucwords($_SESSION['login_firstname']) ?></span>
                    <i class="fa fa-angle-down"></i>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="javascript:void(0)" id="manage_account">
                    <i class="fa fa-cog mr-2"></i> Manage Account
                </a>
                <a class="dropdown-item" href="ajax.php?action=logout">
                    <i class="fa fa-power-off mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
<script>
    $('#manage_account').click(function () {
        uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
    })
</script>