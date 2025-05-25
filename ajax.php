<?php
ob_start();
date_default_timezone_set("Asia/Manila");

require_once 'assets/plugins/phpmailer/src/PHPMailer.php';
require_once 'assets/plugins/phpmailer/src/SMTP.php';
require_once 'assets/plugins/phpmailer/src/Exception.php';
require_once 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if ($action == 'login') {
    $login = $crud->login();
    if ($login)
        echo $login;
}
if ($action == 'login2') {
    $login = $crud->login2();
    if ($login)
        echo $login;
}
if ($action == 'logout') {
    $logout = $crud->logout();
    if ($logout)
        echo $logout;
}
if ($action == 'logout2') {
    $logout = $crud->logout2();
    if ($logout)
        echo $logout;
}
if ($action == 'signup') {
    $save = $crud->signup();
    if ($save)
        echo $save;
}
if ($action == 'save_user') {
    $save = $crud->save_user();
    if ($save)
        echo $save;
}
if ($action == 'update_user') {
    $save = $crud->update_user();
    if ($save)
        echo $save;
}
if ($action == 'delete_user') {
    $save = $crud->delete_user();
    if ($save)
        echo $save;
}
if ($action == 'save_subject') {
    $save = $crud->save_subject();
    if ($save)
        echo $save;
}
if ($action == 'delete_subject') {
    $save = $crud->delete_subject();
    if ($save)
        echo $save;
}
if ($action == 'save_class') {
    $save = $crud->save_class();
    if ($save)
        echo $save;
}
if ($action == 'delete_class') {
    $save = $crud->delete_class();
    if ($save)
        echo $save;
}
if ($action == 'save_academic') {
    $save = $crud->save_academic();
    if ($save)
        echo $save;
}
if ($action == 'delete_academic') {
    $save = $crud->delete_academic();
    if ($save)
        echo $save;
}
if ($action == 'make_default') {
    $save = $crud->make_default();
    if ($save)
        echo $save;
}
if ($action == 'save_criteria') {
    $save = $crud->save_criteria();
    if ($save)
        echo $save;
}
if ($action == 'delete_criteria') {
    $save = $crud->delete_criteria();
    if ($save)
        echo $save;
}
if ($action == 'save_question') {
    $save = $crud->save_question();
    if ($save)
        echo $save;
}
if ($action == 'delete_question') {
    $save = $crud->delete_question();
    if ($save)
        echo $save;
}

if ($action == 'save_criteria_question') {
    $save = $crud->save_criteria_question();
    if ($save)
        echo $save;
}
if ($action == 'save_criteria_order') {
    $save = $crud->save_criteria_order();
    if ($save)
        echo $save;
}

if ($action == 'save_question_order') {
    $save = $crud->save_question_order();
    if ($save)
        echo $save;
}
if ($action == 'save_faculty') {
    $save = $crud->save_faculty();
    if ($save)
        echo $save;
}
if ($action == 'delete_faculty') {
    $save = $crud->delete_faculty();
    if ($save)
        echo $save;
}
if ($action == 'save_student') {
    $save = $crud->save_student();
    if ($save)
        echo $save;
}
if ($action == 'delete_student') {
    $save = $crud->delete_student();
    if ($save)
        echo $save;
}
if ($action == 'deactivate_faculty') {
    $save = $crud->deactivate_faculty();
    if ($save)
        echo $save;
}
if ($action == 'activate_faculty') {
    $save = $crud->activate_faculty();
    if ($save)
        echo $save;
}
if ($action == 'deactivate_student') {
    $save = $crud->deactivate_student();
    if ($save)
        echo $save;
}
if ($action == 'activate_student') {
    $save = $crud->activate_student();
    if ($save)
        echo $save;
}
if ($action == 'save_restriction') {
    $save = $crud->save_restriction();
    if ($save)
        echo $save;
}
if ($action == 'save_evaluation') {
    extract($_POST);
    $data = "";
    $student_id = $_SESSION['login_id'];
    $i = 0;
    
    // Check if evaluation already exists
    $chk = $conn->query("SELECT * FROM evaluation_list WHERE academic_id = $academic_id AND student_id = $student_id AND faculty_id = $faculty_id AND subject_id = $subject_id AND class_id = $class_id");
    
    if($chk->num_rows > 0) {
        $data = "You have already evaluated this faculty for this subject.";
    } else {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Insert evaluation answers
            while($i < count($qid)) {
                $values = "('$academic_id','$student_id','$faculty_id','$subject_id','$class_id','{$qid[$i]}','{$rate[$qid[$i]]}')";
                $save = $conn->query("INSERT INTO evaluation_answers (academic_id,student_id,faculty_id,subject_id,class_id,question_id,rate) VALUES " . $values);
                
                if(!$save) {
                    throw new Exception("Error saving evaluation answer");
                }
                $i++;
            }
            
            // Commit transaction
            $conn->commit();
            $data = 1;
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $data = "Error: " . $e->getMessage();
        }
    }
    echo $data;
}

if ($action == 'get_class') {
    $get = $crud->get_class();
    if ($get)
        echo $get;
}
if ($action == 'get_report') {
    $get = $crud->get_report();
    if ($get)
        echo $get;
}

if ($action == 'import_students_csv') {
    $result = $crud->import_students_csv($_FILES['studentCSV']);
    echo json_encode($result);
}

if ($action == 'import_faculty_csv') {
    $result = $crud->import_faculty_csv($_FILES['facultyCSV']);
    echo json_encode($result);
}

if ($action == 'mark_irregular') {
    echo $crud->mark_irregular($_POST['id']);
}
if ($action == 'mark_regular') {
    echo $crud->mark_regular($_POST['id']);
}

ob_end_flush();
?>