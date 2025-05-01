<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    date_default_timezone_set("Asia/Manila");

    ob_start();
    /**
     * Dynamic Page Title Generation
     * - Checks if a 'page' parameter exists in the URL
     * - If exists: Converts underscores to spaces and capitalizes each word (e.g., 'user_list' becomes 'User List')
     * - If not exists: Defaults to "Home" as the page title
     * This title appears in both the browser tab and potentially in the page header
     */
    $title = isset($_GET['page']) ? ucwords(str_replace("_", ' ', $_GET['page'])) : "Home";
    ?>
    <title><?php echo $title ?> | <?php echo $_SESSION['system']['name'] ?></title>
    <?php ob_end_flush() ?>

    <!-- Google Font: Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap">
    <style>
        body, .main-header, .main-sidebar, .content-wrapper, .card, .btn, .form-control, .nav-link, .dropdown-item, .modal-title, .table, .alert, .badge, .toast, .swal2-title, .swal2-content, .select2-container--default .select2-selection--single, .select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            font-family: 'Poppins', sans-serif !important;
        }
        .font-weight-bold, b, strong {
            font-weight: 600 !important;
        }
        .font-weight-normal {
            font-weight: 400 !important;
        }
        /* Page Title Styling */
        .content-header h1 {
            color: #183f74;
            font-weight: 600;
            font-size: 1.5rem;
            position: relative;
            padding-left: 15px;
        }
        .content-header h1:before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 25px;
            width: 4px;
            background: #417029;
            border-radius: 2px;
        }
        .content-header hr.border-primary {
            border-color: #417029 !important;
            opacity: 0.1;
        }
    </style>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Jquery-UI -->
    <link rel="stylesheet" href="assets/plugins/jquery-ui/jquery-ui.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="assets/plugins/dropzone/min/dropzone.min.css">
    <!-- DateTimePicker -->
    <link rel="stylesheet" href="assets/dist/css/jquery.datetimepicker.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Switch Toggle -->
    <link rel="stylesheet" href="assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="assets/dist/css/styles.css">
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- summernote -->
    <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
    <!-- Jquery-UI -->

</head>