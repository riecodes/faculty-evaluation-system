<?php include('db_connect.php');
function ordinal_suffix1($num)
{
  $num = $num % 100; // protect against large numbers
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
$astat = array("Not Yet Started", "On-going", "Closed");
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
          <b>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></b>
        </small>
      </div>
    </div>
  </div>

  <!-- Faculty Information Dashboard -->
  <div class="row">
    <!-- Classes Handled -->
    <?php 
    $faculty_id = $_SESSION['login_id'];
    $classes = $conn->query("SELECT COUNT(DISTINCT(class_id)) as total FROM restriction_list WHERE faculty_id = '$faculty_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $classes['total']; ?></h3>
          <p>Classes Handled</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-list-alt text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Subjects Taught -->
    <?php 
    $subjects = $conn->query("SELECT COUNT(DISTINCT(subject_id)) as total FROM restriction_list WHERE faculty_id = '$faculty_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $subjects['total']; ?></h3>
          <p>Subjects Taught</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-book text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Evaluations Received -->
    <?php 
    $evaluations = $conn->query("SELECT COUNT(*) as total FROM evaluation_list WHERE faculty_id = '$faculty_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $evaluations['total']; ?></h3>
          <p>Evaluations Received</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-chart-bar text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>