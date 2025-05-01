<?php include('db_connect.php'); ?>
<?php
/**
 * Converts a number to its ordinal representation (1st, 2nd, 3rd, etc.)
 * Used for displaying semester numbers in a readable format
 * @param int $num The number to convert
 * @return string The number with its ordinal suffix
 */
function ordinal_suffix1($num)
{
  $num = $num % 100; // Protect against large numbers by getting last 2 digits
  if ($num < 11 || $num > 13) {
    switch ($num % 10) {
      case 1:
        return $num . 'st';
      case 2:
        return $num . 'nd';
      case 3:
        return $num . 'rd';
    }
  }
  return $num . 'th';
}

// Array of possible evaluation status messages
$arrayStatus = array("Not Yet Started", "On-going", "Closed");
?>

<style>
  .text-success, .border-success {
    color: #417029 !important;
    border-color: #417029 !important;
  }
  .bg-primary {
    background-color: #183f74 !important;
  }
  .text-primary {
    color: #183f74 !important;
  }
  .small-box .icon {
    cursor: pointer;
  }
</style>

<div class="container-fluid px-4">
  <!-- Page header with title -->
  <div class="bg-white p-3 mb-4 border shadow-sm">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="text-success h3 mb-0">Welcome back, <b style="color: #183f74;"><?php echo $_SESSION['login_name'] ?></b>!</h1>

      
      
      <!-- Academic year and evaluation status information -->
      <div class="bg-primary text-white p-3 rounded">
        <h6 class="mb-1">
          <b>Academic Year: <?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</b>
        </h6>
        <small>
          <b>Evaluation Status: <?php echo $arrayStatus[$_SESSION['academic']['status']] ?></b>
        </small>
      </div>
    </div>
  </div>

  <!-- Statistics Dashboard -->
  <div class="row">
    <!-- Faculty Count -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $conn->query("SELECT * FROM faculty_list ")->num_rows; ?></h3>
          <p>Total Faculties</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-user-friends text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Student Count -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $conn->query("SELECT * FROM student_list")->num_rows; ?></h3>
          <p>Total Students</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa ion-ios-people-outline text-primary"></i>
        </div>
      </div>
    </div>

    <!-- User Count -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $conn->query("SELECT * FROM users")->num_rows; ?></h3>
          <p>Total Users</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-users text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Class Count -->
    <div class="col-12 col-sm-6 col-md-3 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $conn->query("SELECT * FROM class_list")->num_rows; ?></h3>
          <p>Total Classes</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-list-alt text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>