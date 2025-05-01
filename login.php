<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){

$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
    $_SESSION['system'][$k] = $v;
}
// }
ob_end_flush();
?>
<?php
if (isset($_SESSION['login_id']))
    header("location:index.php?page=home");

?>
<?php include 'header.php' ?>

<style>
    /* Full page background styling */
    body {
    background-color: #417029 !important;
    background: url('img/univ.png');
    background-size: cover;
    position: absolute;
    height: 100vh;
    width: 100vw;
    background-position: center;
    
}

    /* Colored overlay */
    body.login-page::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background-color: #8d9cbe;
        opacity: 0.5; /* Adjust overlay opacity as needed */
        z-index: 0;
    }

    /* Ensure content is above the overlay */
    .university-logo, .login-container {
        position: absolute;
        z-index: 1;
        right: 100px;
    }

    /* University logo styling */
    .university-logo {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 100;
    }

    .university-logo img {
        height: 60px;
        width: auto;
    }

    /* Login form styling */
    .login-form-container {
        background-color: #417029;
        border-radius: 10px;
        padding: 30px;
        width: 400px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .login-form-container h2 {
        color: white;
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
    }

    .form-label {
        color: white;
        font-weight: 500;
    }

    .form-control {
        border-radius: 5px;
    }

    .input-group-text {
        background-color: white;
    }

    .btn-login {
        background-color: white;
        color: #333;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        padding: 10px;
        width: 100%;
    }

    .btn-login:hover {
        background-color: #f8f9fa;
    }

    .custom-select {
        border-radius: 5px;
        height: calc(1.5em + 0.75rem + 2px);
    }
</style>

<body class="hold-transition login-page">
    <!-- University Logo -->
    <div class="university-logo">
        <img src="img/panpacific-logo.png" alt="Panpacific University Logo">
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-form-container">
            <h2>LOGIN TO YOUR ACCOUNT</h2>
            <form action="" id="login-form">
                <div class="form-group">
                    <label class="form-label">Email address :</label>
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" required placeholder="Enter your email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password :</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" required placeholder="Enter your password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Log In as :</label>
                    <select name="login" class="form-control custom-select">
                        <option value="3">Student</option>
                        <option value="2">Faculty</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="icheck-white">
                        <input type="checkbox" id="remember">
                        <label for="remember" class="text-white">
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-login">LOG IN</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#login-form').submit(function (e) {
                e.preventDefault()
                start_load()
                if ($(this).find('.alert-danger').length > 0)
                    $(this).find('.alert-danger').remove();
                $.ajax({
                    url: 'ajax.php?action=login',
                    method: 'POST',
                    data: $(this).serialize(),
                    error: err => {
                        console.log(err)
                        end_load();
                    },
                    success: function (resp) {
                        if (resp == 1) {
                            location.href = 'index.php?page=home';
                        } else {
                            $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
                            end_load();
                        }
                    }
                })
            })
        })
    </script>
    <?php include 'footer.php' ?>
</body>
</html>