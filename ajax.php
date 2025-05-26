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
    extract($_POST);
    $data = "";
    
    // Validate academic_id
    if(!isset($_GET['id']) || empty($_GET['id'])) {
        error_log("Error: academic_id is missing");
        echo "Error: Academic ID is required";
        exit;
    }
    
    $academic_id = intval($_GET['id']);
    
    // Debug logging
    error_log("Save Restriction Debug - POST data: " . print_r($_POST, true));
    error_log("Academic ID: " . $academic_id);
    
    // Validate required arrays
    if(!isset($faculty_id) || !isset($class_id) || !isset($subject_id) || !isset($type)) {
        error_log("Error: Missing required arrays in POST data");
        echo "Error: Missing required data";
        exit;
    }
    
    // Ensure all arrays are properly initialized
    $faculty_id = is_array($faculty_id) ? $faculty_id : array();
    $class_id = is_array($class_id) ? $class_id : array();
    $subject_id = is_array($subject_id) ? $subject_id : array();
    $type = is_array($type) ? $type : array();
    $rid = is_array($rid) ? $rid : array();
    
    // Log array contents
    error_log("Arrays to process:");
    error_log("faculty_id: " . print_r($faculty_id, true));
    error_log("class_id: " . print_r($class_id, true));
    error_log("subject_id: " . print_r($subject_id, true));
    error_log("type: " . print_r($type, true));
    error_log("rid: " . print_r($rid, true));
    
    // Validate array lengths
    $count = count($faculty_id);
    if($count === 0 || $count !== count($class_id) || $count !== count($subject_id) || $count !== count($type)) {
        error_log("Error: Array length mismatch");
        echo "Error: Invalid data format";
        exit;
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // First, get all existing restrictions for this academic year
        $existing = $conn->query("SELECT id, faculty_id, class_id, subject_id, type 
            FROM restriction_list 
            WHERE academic_id = $academic_id");
        
        if(!$existing) {
            throw new Exception("Error fetching existing restrictions: " . $conn->error);
        }
        
        $existing_restrictions = array();
        while($row = $existing->fetch_assoc()) {
            $key = $row['faculty_id'] . '_' . $row['class_id'] . '_' . $row['subject_id'] . '_' . $row['type'];
            $existing_restrictions[$key] = $row['id'];
        }
        
        error_log("Existing restrictions: " . print_r($existing_restrictions, true));
        
        // Process each restriction
        for($i = 0; $i < $count; $i++) {
            // Validate array indices exist
            if(!isset($faculty_id[$i]) || !isset($class_id[$i]) || !isset($subject_id[$i]) || !isset($type[$i])) {
                throw new Exception("Invalid data at index $i");
            }
            
            $faculty = intval($faculty_id[$i]);
            $class = intval($class_id[$i]);
            $subject = intval($subject_id[$i]);
            $type_val = $conn->real_escape_string($type[$i]);
            $rid_val = isset($rid[$i]) ? intval($rid[$i]) : '';
            
            $key = $faculty . '_' . $class . '_' . $subject . '_' . $type_val;
            
            error_log("Processing restriction $i: " . $key);
            
            if(empty($rid_val)) {
                // New restriction
                if(isset($existing_restrictions[$key])) {
                    error_log("Duplicate found for key: " . $key);
                    throw new Exception("Duplicate faculty-subject-class-type combination found.");
                }
                
                $insert_query = "INSERT INTO restriction_list (academic_id, faculty_id, class_id, subject_id, type) 
                    VALUES ($academic_id, $faculty, $class, $subject, '$type_val')";
                error_log("Insert query: " . $insert_query);
                
                $save = $conn->query($insert_query);
                
                if(!$save) {
                    error_log("Insert error: " . $conn->error);
                    throw new Exception("Error saving restriction: " . $conn->error);
                }
                
                error_log("Successfully inserted restriction with ID: " . $conn->insert_id);
            } else {
                // Update existing restriction
                $update_query = "UPDATE restriction_list 
                    SET faculty_id = $faculty, 
                        class_id = $class, 
                        subject_id = $subject, 
                        type = '$type_val' 
                    WHERE id = $rid_val";
                error_log("Update query: " . $update_query);
                
                $save = $conn->query($update_query);
                
                if(!$save) {
                    error_log("Update error: " . $conn->error);
                    throw new Exception("Error updating restriction: " . $conn->error);
                }
                
                error_log("Successfully updated restriction ID: " . $rid_val);
            }
        }
        
        // Verify the data after save
        $verify_query = "SELECT r.*, 
            f.firstname, f.lastname,
            c.curriculum, c.level, c.section,
            s.code, s.subject
            FROM restriction_list r
            LEFT JOIN faculty_list f ON f.id = r.faculty_id
            LEFT JOIN class_list c ON c.id = r.class_id
            LEFT JOIN subject_list s ON s.id = r.subject_id
            WHERE r.academic_id = $academic_id
            ORDER BY r.id ASC";
        
        error_log("Verifying saved data with query: " . $verify_query);
        $verify_result = $conn->query($verify_query);
        
        if($verify_result) {
            $saved_data = array();
            while($row = $verify_result->fetch_assoc()) {
                $saved_data[] = $row;
            }
            error_log("Verified saved data: " . print_r($saved_data, true));
        }
        
        $conn->commit();
        $data = 1;
        error_log("Save restriction successful");
    } catch (Exception $e) {
        $conn->rollback();
        $data = 2;
        error_log("Save restriction error: " . $e->getMessage());
    }
    
    echo $data;
}
if ($action == 'save_evaluation') {
    extract($_POST);
    $data = "";
    $student_id = $_SESSION['login_id'];
    $i = 0;
    
    // Check if student is irregular
    $student_type = $conn->query("SELECT type FROM student_list WHERE id = $student_id")->fetch_assoc()['type'];
    $is_irregular = ($student_type == 'irregular');
    
    // Check if evaluation already exists
    $chk = $conn->query("SELECT * FROM evaluation_list WHERE academic_id = $academic_id AND student_id = $student_id AND faculty_id = $faculty_id AND subject_id = $subject_id AND class_id = $class_id");
    
    if($chk->num_rows > 0) {
        $data = "You have already evaluated this faculty for this subject.";
    } else {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // For irregular students, check if restriction exists first
            if($is_irregular) {
                $restriction_check = $conn->query("SELECT id FROM restriction_list 
                    WHERE academic_id = $academic_id 
                    AND faculty_id = $faculty_id 
                    AND subject_id = $subject_id 
                    AND class_id = $class_id 
                    AND type = 'irregular'");
                
                if($restriction_check->num_rows > 0) {
                    $restriction_id = $restriction_check->fetch_assoc()['id'];
                } else {
                    // Create new restriction entry
                    $restriction_data = " academic_id = $academic_id ";
                    $restriction_data .= ", faculty_id = $faculty_id ";
                    $restriction_data .= ", subject_id = $subject_id ";
                    $restriction_data .= ", class_id = $class_id ";
                    $restriction_data .= ", type = 'irregular'";
                    
                    $save_restriction = $conn->query("INSERT INTO restriction_list SET " . $restriction_data);
                    
                    if(!$save_restriction) {
                        throw new Exception("Error creating restriction entry");
                    }
                    
                    $restriction_id = $conn->insert_id;
                }
            } else {
                // For regular students, get the existing restriction
                $restriction_check = $conn->query("SELECT id FROM restriction_list 
                    WHERE academic_id = $academic_id 
                    AND faculty_id = $faculty_id 
                    AND subject_id = $subject_id 
                    AND class_id = $class_id 
                    AND type = 'regular'");
                
                if($restriction_check->num_rows > 0) {
                    $restriction_id = $restriction_check->fetch_assoc()['id'];
                } else {
                    throw new Exception("No valid restriction found for regular student");
                }
            }
            
            // Create evaluation entry
            $eval_data = " student_id = $student_id ";
            $eval_data .= ", academic_id = $academic_id ";
            $eval_data .= ", faculty_id = $faculty_id ";
            $eval_data .= ", subject_id = $subject_id ";
            $eval_data .= ", class_id = $class_id ";
            $eval_data .= ", restriction_id = $restriction_id";
            
            $save_eval = $conn->query("INSERT INTO evaluation_list SET " . $eval_data);
            
            if(!$save_eval) {
                throw new Exception("Error creating evaluation entry");
            }
            
            $evaluation_id = $conn->insert_id;
            
            // Insert evaluation answers
            while($i < count($qid)) {
                // Get question type
                $qtype = $conn->query("SELECT type FROM question_list WHERE id = {$qid[$i]}")->fetch_array()['type'];
                
                if($qtype == 'checklist') {
                    $rate_val = intval($rate[$qid[$i]]);
                    $comment_val = 'NULL';
                } else if($qtype == 'textfield') {
                    $rate_val = 0;
                    $comment_val = "'" . $conn->real_escape_string($rate[$qid[$i]]) . "'";
                }
                
                $save = $conn->query("INSERT INTO evaluation_answers (evaluation_id, question_id, rate, comment) 
                    VALUES ($evaluation_id, {$qid[$i]}, $rate_val, $comment_val)");
                
                if(!$save) {
                    throw new Exception("Error saving evaluation answer: " . $conn->error);
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

if ($action == 'get_faculty_subjects') {
    $response = $crud->get_faculty_subjects();
    echo $response;
}
if ($action == 'get_faculty_sections') {
    $response = $crud->get_faculty_sections();
    echo $response;
}

if ($action == 'check_restriction_duplicate') {
    extract($_POST);
    // Debug output
    error_log("Checking duplicate for: faculty_id=$faculty_id, class_id=$class_id, subject_id=$subject_id, type=$type, academic_id=$academic_id");
    
    // Properly escape the type value and make it case-insensitive
    $type = $conn->real_escape_string(strtolower($type));
    
    $check = $conn->query("SELECT id FROM restriction_list 
        WHERE academic_id = $academic_id 
        AND faculty_id = $faculty_id 
        AND class_id = $class_id 
        AND subject_id = $subject_id 
        AND LOWER(type) = '$type'");
    
    // Debug output
    error_log("Query result: " . $check->num_rows . " rows found");
    if($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        error_log("Found duplicate with ID: " . $row['id']);
    }
    
    echo $check->num_rows > 0 ? 1 : 0;
}

if ($action == 'check_restrictions') {
    $academic_id = isset($_POST['academic_id']) ? intval($_POST['academic_id']) : 0;
    
    if($academic_id <= 0) {
        echo json_encode(['error' => 'Invalid academic ID']);
        exit;
    }
    
    $query = "SELECT r.*, 
        f.firstname, f.lastname,
        c.curriculum, c.level, c.section,
        s.code, s.subject
        FROM restriction_list r
        LEFT JOIN faculty_list f ON f.id = r.faculty_id
        LEFT JOIN class_list c ON c.id = r.class_id
        LEFT JOIN subject_list s ON s.id = r.subject_id
        WHERE r.academic_id = $academic_id
        ORDER BY r.id ASC";
    
    error_log("Checking restrictions query: " . $query);
    
    $result = $conn->query($query);
    
    if(!$result) {
        error_log("Error checking restrictions: " . $conn->error);
        echo json_encode(['error' => 'Database error: ' . $conn->error]);
        exit;
    }
    
    $restrictions = [];
    while($row = $result->fetch_assoc()) {
        $restrictions[] = [
            'id' => $row['id'],
            'faculty' => $row['firstname'] . ' ' . $row['lastname'],
            'class' => $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section'],
            'subject' => $row['code'] . ' - ' . $row['subject'],
            'type' => $row['type']
        ];
    }
    
    error_log("Found " . count($restrictions) . " restrictions");
    echo json_encode($restrictions);
}

if ($action == 'delete_restriction') {
    $delete = $crud->delete_restriction();
    if($delete)
        echo json_encode(['success' => true, 'message' => 'Restriction deleted successfully']);
    else
        echo json_encode(['error' => 'Failed to delete restriction']);
    exit;
}

if ($action == 'save_enrollment') {
    $save = $crud->save_enrollment();
    if ($save)
        echo $save;
}

if ($action == 'get_enrollment') {
    $get = $crud->get_enrollment();
    if ($get)
        echo $get;
}

if ($action == 'delete_enrollment') {
    $delete = $crud->delete_enrollment();
    if ($delete)
        echo $delete;
}

ob_end_flush();
?>