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
$astat = array("Not Yet Started", "Started", "Closed");
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

  <!-- Student Information Dashboard -->
  <div class="row">
    <!-- Current Class -->
    <?php 
    $student_id = $_SESSION['login_id'];
    $qry = $conn->query("SELECT class_id FROM student_list WHERE id = '$student_id'")->fetch_assoc();
    $class_id = $qry['class_id'];
    $class = $conn->query("SELECT concat(curriculum,' ',level,' - ',section) as class_name FROM class_list WHERE id = '$class_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $class['class_name']; ?></h3>
          <p>Current Class</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-users text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Subjects Enrolled -->
    <?php 
    $subjects = $conn->query("SELECT COUNT(DISTINCT(subject_id)) as total FROM restriction_list WHERE class_id = '$class_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $subjects['total']; ?></h3>
          <p>Subjects Enrolled</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-book text-primary"></i>
        </div>
      </div>
    </div>

    <!-- Evaluations Completed -->
    <?php 
    $evaluations = $conn->query("SELECT COUNT(*) as total FROM evaluation_list WHERE student_id = '$student_id'")->fetch_assoc();
    ?>
    <div class="col-12 col-sm-6 col-md-4 mb-4">
      <div class="small-box bg-white shadow-sm border">
        <div class="inner">
          <h3 class="text-success"><?php echo $evaluations['total']; ?></h3>
          <p>Evaluations Completed</p>
        </div>
        <div class="icon cursor-pointer">
          <i class="fa fa-check-circle text-primary"></i>
        </div>
      </div>
    </div>
  </div>
</div>