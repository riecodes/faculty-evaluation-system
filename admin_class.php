<?php
session_start();
ini_set('display_errors', 1);
class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }
    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    function login()
    {
        extract($_POST);
        $type = array("", "users", "faculty_list", "student_list");
        $type2 = array("", "admin", "faculty", "student");
        
        // Add status check for faculty and student
        $status_check = "";
        if($login == 2) { // Faculty
            $status_check = " and status = 1";
        } else if($login == 3) { // Student
            $status_check = " and status = 1";
        }
        
        $qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM {$type[$login]} where email = '" . $email . "' and password = '" . md5($password) . "'" . $status_check);
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            $_SESSION['login_type'] = $login;
            $_SESSION['login_view_folder'] = $type2[$login] . '/';
            $academic = $this->db->query("SELECT * FROM academic_list where is_default = 1 ");
            if ($academic->num_rows > 0) {
                foreach ($academic->fetch_array() as $k => $v) {
                    if (!is_numeric($k))
                        $_SESSION['academic'][$k] = $v;
                }
            }
            return 1;
        } else {
            // Check if account exists but is deactivated
            $check_deactivated = $this->db->query("SELECT * FROM {$type[$login]} where email = '" . $email . "' and password = '" . md5($password) . "' and status = 0");
            if($check_deactivated->num_rows > 0) {
                return 3; // Account deactivated
            }
            return 2; // Invalid credentials
        }
    }
    function logout()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }
    function login2()
    {
        extract($_POST);
        $qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['rs_' . $key] = $value;
            }
            return 1;
        } else {
            return 3;
        }
    }
    function save_user()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!empty($password)) {
            $data .= ", password=md5('$password') ";

        }
        $check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";

        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set $data");
        } else {
            $save = $this->db->query("UPDATE users set $data where id = $id");
        }

        if ($save) {
            return 1;
        }
    }
    function signup()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
                if ($k == 'password') {
                    if (empty($v))
                        continue;
                    $v = md5($v);

                }
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }

        $check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";

        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set $data");

        } else {
            $save = $this->db->query("UPDATE users set $data where id = $id");
        }

        if ($save) {
            if (empty($id))
                $id = $this->db->insert_id;
            foreach ($_POST as $key => $value) {
                if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            $_SESSION['login_id'] = $id;
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_avatar'] = $fname;
            return 1;
        }
    }

    function update_user()
    {
        extract($_POST);
        $data = "";
        $type = array("", "users", "faculty_list", "student_list");
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";

        }
        if (!empty($password))
            $data .= " ,password=md5('$password') ";
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
        } else {
            echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
            $save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
        }

        if ($save) {
            foreach ($_POST as $key => $value) {
                if ($key != 'password' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
                $_SESSION['login_avatar'] = $fname;
            return 1;
        }
    }
    function delete_user()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM users where id = " . $id);
        if ($delete)
            return 1;
    }
    function save_system_settings()
    {
        extract($_POST);
        $data = '';
        foreach ($_POST as $k => $v) {
            if (!is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if ($_FILES['cover']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
            $move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
            $data .= ", cover_img = '$fname' ";

        }
        $chk = $this->db->query("SELECT * FROM system_settings");
        if ($chk->num_rows > 0) {
            $save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
        } else {
            $save = $this->db->query("INSERT INTO system_settings set $data");
        }
        if ($save) {
            foreach ($_POST as $k => $v) {
                if (!is_numeric($k)) {
                    $_SESSION['system'][$k] = $v;
                }
            }
            if ($_FILES['cover']['tmp_name'] != '') {
                $_SESSION['system']['cover_img'] = $fname;
            }
            return 1;
        }
    }
    function save_image()
    {
        extract($_FILES['file']);
        if (!empty($tmp_name)) {
            $fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
            $move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
            $hostName = $_SERVER['HTTP_HOST'];
            $path = explode('/', $_SERVER['PHP_SELF']);
            $currentPath = '/' . $path[1];
            if ($move) {
                return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
            }
        }
    }
    function save_subject()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $chk = $this->db->query("SELECT * FROM subject_list where code = '$code' and id != '{$id}' ")->num_rows;
        if ($chk > 0) {
            return 2;
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO subject_list set $data");
        } else {
            $save = $this->db->query("UPDATE subject_list set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }
    function delete_subject()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM subject_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_class()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $chk = $this->db->query("SELECT * FROM class_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
        if ($chk > 0) {
            return 2;
        }
        if (isset($user_ids)) {
            $data .= ", user_ids='" . implode(',', $user_ids) . "' ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO class_list set $data");
        } else {
            $save = $this->db->query("UPDATE class_list set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }
    function delete_class()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM class_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_academic()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        $chk = $this->db->query("SELECT * FROM academic_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
        if ($chk > 0) {
            return 2;
        }
        $hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
        if ($hasDefault == 0) {
            $data .= " , is_default = 1 ";
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO academic_list set $data");
        } else {
            $save = $this->db->query("UPDATE academic_list set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }
    function delete_academic()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM academic_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function make_default()
    {
        extract($_POST);
        $update = $this->db->query("UPDATE academic_list set is_default = 0");
        $update1 = $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
        $qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
        if ($update && $update1) {
            foreach ($qry as $k => $v) {
                if (!is_numeric($k))
                    $_SESSION['academic'][$k] = $v;
            }

            return 1;
        }
    }
    function save_criteria()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        
        $chk = $this->db->query("SELECT * FROM criteria_list where (" . str_replace(",", 'and', $data) . ") and id != '{$id}' ")->num_rows;
        if ($chk > 0) {
            return 2;
        }

        if (empty($id)) {
            $lastOrder = $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
            $lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
            $data .= ", order_by='$lastOrder' ";
            $sql = "INSERT INTO criteria_list set $data";
            error_log("SQL Query: " . $sql);
            $save = $this->db->query($sql);
            if (!$save) {
                error_log("MySQL Error: " . $this->db->error);
            }
        } else {
            $sql = "UPDATE criteria_list set $data where id = $id";
            error_log("SQL Query: " . $sql);
            $save = $this->db->query($sql);
            if (!$save) {
                error_log("MySQL Error: " . $this->db->error);
            }
        }
        if ($save) {
            return 1;
        }
        return 0;
    }
    function delete_criteria()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM criteria_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_criteria_order()
    {
        extract($_POST);
        $data = "";
        foreach ($criteria_id as $k => $v) {
            $update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
        }
        if (isset($update) && count($update)) {
            return 1;
        }
    }

    function save_question()
    {
        extract($_POST);
        $data = " academic_id = '$academic_id' ";
        $data .= ", criteria_id = '$criteria_id' ";
        $data .= ", question = '$question' ";
        $data .= ", type = '$type' ";
        if(empty($id)) {
            $lastOrder = $this->db->query("SELECT * FROM question_list where academic_id = $academic_id order by abs(order_by) desc limit 1");
            $lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
            $data .= ", order_by='$lastOrder' ";
            $save = $this->db->query("INSERT INTO question_list set ".$data);
        } else {
            $save = $this->db->query("UPDATE question_list set ".$data." where id = ".$id);
        }
        if($save) {
            return 1;
        }
    }
    function delete_question()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM question_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_question_order()
    {
        extract($_POST);
        $data = "";
        if(isset($_POST['order']) && is_array($_POST['order'])) {
            foreach($_POST['order'] as $qid => $order) {
                $update = $this->db->query("UPDATE question_list set order_by = $order where id = $qid");
                if(!$update) {
                    return 0;
                }
            }
            return 1;
        }
        return 0;
    }
    function save_faculty()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!empty($password)) {
            $data .= ", password=md5('$password') ";

        }
        $check = $this->db->query("SELECT * FROM faculty_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        $check = $this->db->query("SELECT * FROM faculty_list where school_id ='$school_id' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 3;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";

        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO faculty_list set $data");
        } else {
            $save = $this->db->query("UPDATE faculty_list set $data where id = $id");
        }

        if ($save) {
            return 1;
        }
    }
    function delete_faculty()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM faculty_list where id = " . $id);
        if ($delete)
            return 1;
    }
    function save_student()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!empty($password)) {
            $data .= ", password=md5('$password') ";

        }
        $check = $this->db->query("SELECT * FROM student_list where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if ($check > 0) {
            return 2;
            exit;
        }
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";

        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO student_list set $data");
        } else {
            $save = $this->db->query("UPDATE student_list set $data where id = $id");
        }

        if ($save) {
            return 1;
        }
    }
    function delete_student()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM student_list where id = " . $id);
        if ($delete)
            return 1;
    }
    function save_task()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if ($k == 'description')
                    $v = htmlentities(str_replace("'", "&#x2019;", $v));
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO task_list set $data");
        } else {
            $save = $this->db->query("UPDATE task_list set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }
    function delete_task()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM task_list where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_progress()
    {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if ($k == 'progress')
                    $v = htmlentities(str_replace("'", "&#x2019;", $v));
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (!isset($is_complete))
            $data .= ", is_complete=0 ";
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO task_progress set $data");
        } else {
            $save = $this->db->query("UPDATE task_progress set $data where id = $id");
        }
        if ($save) {
            if (!isset($is_complete))
                $this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
            else
                $this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
            return 1;
        }
    }
    function delete_progress()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM task_progress where id = $id");
        if ($delete) {
            return 1;
        }
    }
    function save_restriction()
    {
        extract($_POST);
        $data = "";
        $academic_id = $this->db->real_escape_string($academic_id);
        
        // Start transaction
        $this->db->query("START TRANSACTION");
        
        try {
            // Get existing restrictions for this academic year
            $existing = array();
            $qry = $this->db->query("SELECT id, faculty_id, class_id, subject_id, type FROM restriction_list WHERE academic_id = {$academic_id}");
            while($row = $qry->fetch_assoc()) {
                $key = $row['faculty_id'] . '_' . $row['class_id'] . '_' . $row['subject_id'] . '_' . $row['type'];
                $existing[$key] = $row['id'];
            }
            
            // Process submitted restrictions
            $submitted = array();
            if(isset($faculty_id) && is_array($faculty_id)) {
                foreach($faculty_id as $k => $v) {
                    $faculty_id[$k] = $this->db->real_escape_string($v);
                    $class_id[$k] = $this->db->real_escape_string($class_id[$k]);
                    $subject_id[$k] = $this->db->real_escape_string($subject_id[$k]);
                    $rid[$k] = $this->db->real_escape_string($rid[$k]);
                    $type = isset($type[$k]) ? $this->db->real_escape_string($type[$k]) : 'regular';
                    
                    $key = $faculty_id[$k] . '_' . $class_id[$k] . '_' . $subject_id[$k] . '_' . $type;
                    
                    // Check if this combination already exists (excluding current restriction if editing)
                    if(isset($existing[$key]) && $existing[$key] != $rid[$k]) {
                        throw new Exception("Duplicate faculty-subject-class combination found");
                    }
                    
                    $submitted[$key] = $rid[$k];
                }
            }
            
            // Delete restrictions that are no longer in the list
            foreach($existing as $key => $id) {
                if(!isset($submitted[$key])) {
                    $this->db->query("DELETE FROM restriction_list WHERE id = {$id}");
                }
            }
            
            // Insert or update restrictions
            if(isset($faculty_id) && is_array($faculty_id)) {
                foreach($faculty_id as $k => $v) {
                    if(empty($rid[$k])) {
                        // New restriction
                        $data = " academic_id = '{$academic_id}' ";
                        $data .= ", faculty_id = '{$faculty_id[$k]}' ";
                        $data .= ", class_id = '{$class_id[$k]}' ";
                        $data .= ", subject_id = '{$subject_id[$k]}' ";
                        $data .= ", type = '{$type}' ";
                        
                        $save = $this->db->query("INSERT INTO restriction_list set ".$data);
                        if(!$save) {
                            throw new Exception("Error saving new restriction");
                        }
                    }
                }
            }
            
            // Commit transaction
            $this->db->query("COMMIT");
            return 1;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->query("ROLLBACK");
            return 2;
        }
    }
    function save_evaluation() {
        extract($_POST);
        
        $debug_info = array();
        $debug_info[] = "POST data: " . print_r($_POST, true);
        $debug_info[] = "Rate array: " . print_r($rate, true);
        
        $data = " student_id = {$_SESSION['login_id']} ";
        $data .= ", academic_id = $academic_id ";
        $data .= ", subject_id = $subject_id ";
        $data .= ", class_id = $class_id ";
        $data .= ", faculty_id = $faculty_id ";
        
        // Check if student is irregular
        $student_type = $this->db->query("SELECT type FROM student_list WHERE id = {$_SESSION['login_id']}")->fetch_assoc()['type'];
        $is_irregular = ($student_type == 'irregular');
        
        // Check if evaluation already exists
        $chk = $this->db->query("SELECT * FROM evaluation_list 
            WHERE academic_id = $academic_id 
            AND student_id = {$_SESSION['login_id']} 
            AND faculty_id = $faculty_id 
            AND subject_id = $subject_id 
            AND class_id = $class_id");
        
        if($chk->num_rows > 0) {
            return "You have already evaluated this faculty for this subject.";
        }
        
        // Begin transaction
        $this->db->query("START TRANSACTION");
        
        try {
            // For irregular students, check if restriction exists first
            if($is_irregular) {
                $restriction_check = $this->db->query("SELECT id FROM restriction_list 
                    WHERE academic_id = $academic_id 
                    AND faculty_id = $faculty_id 
                    AND subject_id = $subject_id 
                    AND class_id = $class_id 
                    AND type = 'irregular'");
                
                if($restriction_check->num_rows > 0) {
                    $restriction_id = $restriction_check->fetch_assoc()['id'];
                    // Update student_id for existing restriction
                    $this->db->query("UPDATE restriction_list SET student_id = {$_SESSION['login_id']} WHERE id = $restriction_id");
                } else {
                    // Create new restriction entry
                    $restriction_data = " academic_id = $academic_id ";
                    $restriction_data .= ", faculty_id = $faculty_id ";
                    $restriction_data .= ", subject_id = $subject_id ";
                    $restriction_data .= ", class_id = $class_id ";
                    $restriction_data .= ", type = 'irregular'";
                    $restriction_data .= ", student_id = {$_SESSION['login_id']}";
                    
                    $save_restriction = $this->db->query("INSERT INTO restriction_list SET " . $restriction_data);
                    
                    if(!$save_restriction) {
                        throw new Exception("Error creating restriction entry");
                    }
                    
                    $restriction_id = $this->db->insert_id;
                }
            } else {
                // For regular students, get the existing restriction
                $restriction_check = $this->db->query("SELECT id FROM restriction_list 
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
            
            $data .= ", restriction_id = $restriction_id ";
            $save = $this->db->query("INSERT INTO evaluation_list set $data");
            
            if ($save) {
                $eid = $this->db->insert_id;
                foreach ($qid as $k => $v) {
                    $data = " evaluation_id = $eid ";
                    $data .= ", question_id = $v ";
                    
                    // Get question type
                    $qtype = $this->db->query("SELECT type FROM question_list WHERE id = $v")->fetch_array()['type'];
                    
                    // Debug info
                    $debug_info[] = "Processing question $v of type $qtype";
                    $debug_info[] = "Rate data for question $v: " . print_r($rate[$v], true);
                    
                    if($qtype == 'checklist') {
                        $data .= ", rate = {$rate[$v]} ";
                        $data .= ", comment = NULL ";
                    } else if($qtype == 'textfield') {
                        // For textfield type, keep rate as 0 and set the comment
                        $data .= ", rate = 0 ";
                        
                        // Get the comment text from the rate array
                        $comment_text = isset($rate[$v]) ? trim($rate[$v]) : '';
                        
                        // Debug info
                        $debug_info[] = "Comment text for question $v: " . $comment_text;
                        
                        if(!empty($comment_text)) {
                            $comment = $this->db->real_escape_string($comment_text);
                            $data .= ", comment = '$comment' ";
                            $debug_info[] = "Setting comment for question $v to: " . $comment;
                        } else {
                            $data .= ", comment = NULL ";
                            $debug_info[] = "No comment text for question $v";
                        }
                    }
                    
                    // Debug info
                    $debug_info[] = "SQL for question $v: INSERT INTO evaluation_answers set $data";
                    
                    $ins[] = $this->db->query("INSERT INTO evaluation_answers set $data");
                    
                    // Debug info for any database errors
                    if($this->db->error) {
                        $debug_info[] = "Database error for question $v: " . $this->db->error;
                    }
                }
                
                if (isset($ins)) {
                    $this->db->query("COMMIT");
                    return json_encode(array(
                        'status' => 1,
                        'debug' => implode("\n", $debug_info)
                    ));
                }
            }
            
            throw new Exception("Error saving evaluation");
            
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $debug_info[] = "Error in save_evaluation: " . $e->getMessage();
            return json_encode(array(
                'status' => 0,
                'error' => $e->getMessage(),
                'debug' => implode("\n", $debug_info)
            ));
        }
    }
    function get_class()
    {
        extract($_POST);
        $data = array();
        $get = $this->db->query("SELECT DISTINCT c.id, concat(c.curriculum,' ',c.level,' - ',c.section) as class, 
            s.id as sid, concat(s.code,' - ',s.subject) as subj 
            FROM restriction_list r 
            INNER JOIN class_list c ON c.id = r.class_id 
            INNER JOIN subject_list s ON s.id = r.subject_id 
            WHERE r.faculty_id = {$fid} 
            AND r.academic_id = {$_SESSION['academic']['id']}
            GROUP BY c.id, s.id");
        while ($row = $get->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode($data);
    }
    function get_report()
    {
        extract($_POST);
        $data = array();
        $get = $this->db->query("SELECT ea.*, ql.type as question_type FROM evaluation_answers ea 
                                INNER JOIN question_list ql ON ea.question_id = ql.id 
                                WHERE ea.evaluation_id in (
                                    SELECT evaluation_id FROM evaluation_list 
                                    WHERE academic_id = {$_SESSION['academic']['id']} 
                                    AND faculty_id = $faculty_id 
                                    AND subject_id = $subject_id 
                                    AND class_id = $class_id
                                )");
        $answered = $this->db->query("SELECT * FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id and subject_id = $subject_id and class_id = $class_id");
        $rate = array();
        $comments = array();
        
        while ($row = $get->fetch_assoc()) {
            if($row['question_type'] == 'checklist') {
            if (!isset($rate[$row['question_id']][$row['rate']]))
                $rate[$row['question_id']][$row['rate']] = 0;
            $rate[$row['question_id']][$row['rate']] += 1;
            } else {
                if (!isset($comments[$row['question_id']]))
                    $comments[$row['question_id']] = array();
                $comments[$row['question_id']][] = $row['comment'];
        }
        }

        $ta = $answered->num_rows;
        $r = array();
        foreach ($rate as $qk => $qv) {
            foreach ($qv as $rk => $rv) {
                $r[$qk][$rk] = ($rate[$qk][$rk] / $ta) * 100;
            }
        }
        $data['tse'] = $ta;
        $data['data'] = $r;
        $data['comments'] = $comments;

        return json_encode($data);
    }
    function deactivate_faculty()
    {
        extract($_POST);
        $update = $this->db->query("UPDATE faculty_list set status = 0 where id = $id");
        if($update)
            return 1;
    }

    function activate_faculty()
    {
        extract($_POST);
        $update = $this->db->query("UPDATE faculty_list set status = 1 where id = $id");
        if($update)
            return 1;
    }

    function deactivate_student()
    {
        extract($_POST);
        $update = $this->db->query("UPDATE student_list set status = 0 where id = $id");
        if($update)
            return 1;
    }

    function activate_student()
    {
        extract($_POST);
        $update = $this->db->query("UPDATE student_list set status = 1 where id = $id");
        if($update)
            return 1;
    }

    function import_students_csv($file) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }

        if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
            $header = fgetcsv($handle); // Get header row
            $required_fields = ['school_id', 'firstname', 'lastname', 'class_id', 'email', 'password', 'status'];
            
            // Validate header
            if (count(array_diff($required_fields, $header)) > 0) {
                return ['status' => 'error', 'message' => 'Invalid CSV format. Please use the sample template.'];
            }

            $success = 0;
            $failed = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                $row = array_combine($header, $data);
                
                // Validate required fields
                if (empty($row['school_id']) || empty($row['firstname']) || empty($row['lastname']) || 
                    empty($row['email']) || empty($row['password']) || empty($row['class_id'])) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Missing required fields";
                    continue;
                }

                // Check for duplicate email
                $check = $this->db->query("SELECT * FROM student_list where email = '" . $this->db->real_escape_string($row['email']) . "'")->num_rows;
                if ($check > 0) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Email {$row['email']} already exists";
                    continue;
                }

                // Check for duplicate school_id
                $check = $this->db->query("SELECT * FROM student_list where school_id = '" . $this->db->real_escape_string($row['school_id']) . "'")->num_rows;
                if ($check > 0) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": School ID {$row['school_id']} already exists";
                    continue;
                }

                // Validate class_id exists
                $check = $this->db->query("SELECT * FROM class_list where id = '" . (int)$row['class_id'] . "'")->num_rows;
                if ($check == 0) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Invalid Class ID {$row['class_id']}";
                    continue;
                }

                // Prepare data for insertion
                $data = " school_id = '" . $this->db->real_escape_string($row['school_id']) . "' ";
                $data .= ", firstname = '" . $this->db->real_escape_string($row['firstname']) . "' ";
                $data .= ", lastname = '" . $this->db->real_escape_string($row['lastname']) . "' ";
                $data .= ", email = '" . $this->db->real_escape_string($row['email']) . "' ";
                $data .= ", password = '" . md5($row['password']) . "' "; // Hash the password
                $data .= ", class_id = '" . (int)$row['class_id'] . "' ";
                $data .= ", status = '" . (isset($row['status']) ? (int)$row['status'] : 1) . "' ";

                $save = $this->db->query("INSERT INTO student_list set $data");
                if ($save) {
                    $success++;
                } else {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Database error - " . $this->db->error;
                }
            }
            fclose($handle);
            
            return [
                'status' => 'success',
                'message' => "Import completed. Successfully imported {$success} records. Failed: {$failed}",
                'errors' => $errors
            ];
        }
        return ['status' => 'error', 'message' => 'Failed to open CSV file'];
    }

    function import_faculty_csv($file) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }

        if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
            $header = fgetcsv($handle); // Get header row
            $required_fields = ['school_id', 'firstname', 'lastname', 'email', 'password', 'status'];
            
            // Validate header
            if (count(array_diff($required_fields, $header)) > 0) {
                return ['status' => 'error', 'message' => 'Invalid CSV format. Please use the sample template.'];
            }

            $success = 0;
            $failed = 0;
            $errors = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                $row = array_combine($header, $data);
                
                // Validate required fields
                if (empty($row['school_id']) || empty($row['firstname']) || empty($row['lastname']) || 
                    empty($row['email']) || empty($row['password'])) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Missing required fields";
                    continue;
                }

                // Check for duplicate email
                $check = $this->db->query("SELECT * FROM faculty_list where email = '" . $this->db->real_escape_string($row['email']) . "'")->num_rows;
                if ($check > 0) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Email {$row['email']} already exists";
                    continue;
                }

                // Check for duplicate school_id
                $check = $this->db->query("SELECT * FROM faculty_list where school_id = '" . $this->db->real_escape_string($row['school_id']) . "'")->num_rows;
                if ($check > 0) {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": School ID {$row['school_id']} already exists";
                    continue;
                }

                // Prepare data for insertion
                $data = " school_id = '" . $this->db->real_escape_string($row['school_id']) . "' ";
                $data .= ", firstname = '" . $this->db->real_escape_string($row['firstname']) . "' ";
                $data .= ", lastname = '" . $this->db->real_escape_string($row['lastname']) . "' ";
                $data .= ", email = '" . $this->db->real_escape_string($row['email']) . "' ";
                $data .= ", password = '" . md5($row['password']) . "' "; // Hash the password
                $data .= ", status = '" . (isset($row['status']) ? (int)$row['status'] : 1) . "' ";

                $save = $this->db->query("INSERT INTO faculty_list set $data");
                if ($save) {
                    $success++;
                } else {
                    $failed++;
                    $errors[] = "Row " . ($success + $failed) . ": Database error - " . $this->db->error;
                }
            }
            fclose($handle);
            
            return [
                'status' => 'success',
                'message' => "Import completed. Successfully imported {$success} records. Failed: {$failed}",
                'errors' => $errors
            ];
        }
        return ['status' => 'error', 'message' => 'Failed to open CSV file'];
    }

    function save_criteria_question() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_numeric($k)) {
                if (empty($data)) {
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO criteria_questions set $data");
        } else {
            $save = $this->db->query("UPDATE criteria_questions set $data where id = $id");
        }
        if ($save) {
            return 1;
        }
    }

    function mark_irregular($id = '') {
        extract($_POST);
        $data = "";
        if(empty($id)) {
            $data = "Invalid student ID";
        } else {
            $save = $this->db->query("UPDATE student_list SET type = 'irregular' WHERE id = $id");
            if($save) {
                $data = 1;
            } else {
                $data = "Error updating student status";
            }
        }
        return $data;
    }

    function mark_regular($id = '') {
        extract($_POST);
        $data = "";
        if(empty($id)) {
            $data = "Invalid student ID";
        } else {
            $save = $this->db->query("UPDATE student_list SET type = 'regular' WHERE id = $id");
            if($save) {
                $data = 1;
            } else {
                $data = "Error updating student status";
            }
        }
        return $data;
    }

    function get_faculty_subjects() {
        extract($_POST);
        $data = array();
        
        // Validate faculty_id
        if(!isset($faculty_id) || empty($faculty_id)) {
            return json_encode(['error' => 'Faculty ID is required']);
        }
        
        // Sanitize faculty_id
        $faculty_id = $this->db->real_escape_string($faculty_id);
        
        try {
            // Validate academic_id session
            if(!isset($_SESSION['academic']['id'])) {
                throw new Exception('Academic session not found');
            }
            
            $academic_id = $this->db->real_escape_string($_SESSION['academic']['id']);
            
            // Modified query to get all subjects
            $get = $this->db->query("SELECT DISTINCT s.id, s.code, s.subject 
                FROM subject_list s 
                ORDER BY s.code");
                
            if(!$get) {
                throw new Exception($this->db->error);
            }
            
            while ($row = $get->fetch_assoc()) {
                $data[] = $row;
            }
            
            return json_encode($data);
            
        } catch (Exception $e) {
            error_log("Error in get_faculty_subjects: " . $e->getMessage());
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function get_faculty_sections() {
        extract($_POST);
        $data = array();
        
        try {
            // Log the incoming request
            error_log("get_faculty_sections called with POST data: " . print_r($_POST, true));
            
            // Validate academic_id session
            if(!isset($_SESSION['academic']['id'])) {
                throw new Exception('Academic session not found');
            }
            
            // Validate subject_id
            if(!isset($subject_id) || empty($subject_id)) {
                throw new Exception('Subject ID is required');
            }
            
            $academic_id = $this->db->real_escape_string($_SESSION['academic']['id']);
            $subject_id = $this->db->real_escape_string($subject_id);
            
            // Get sections from class_list
            $query = "SELECT DISTINCT c.id, concat(c.curriculum,' ',c.level,' - ',c.section) as class 
                FROM class_list c 
                ORDER BY c.curriculum, c.level, c.section";
            
            error_log("Executing query: " . $query);
            
            $get = $this->db->query($query);
                
            if(!$get) {
                throw new Exception("Database error: " . $this->db->error);
            }
            
            while ($row = $get->fetch_assoc()) {
                $data[] = $row;
            }
            
            error_log("Query returned " . count($data) . " results");
            
            // Ensure we return a valid JSON response even if no data
            if(empty($data)) {
                error_log("No sections found, returning empty array");
                return json_encode(array());
            }
            
            $response = json_encode($data);
            error_log("Returning response: " . $response);
            return $response;
            
        } catch (Exception $e) {
            error_log("Error in get_faculty_sections: " . $e->getMessage());
            return json_encode(array('error' => $e->getMessage()));
        }
    }

    function check_evaluation() {
        extract($_POST);
        $check = $this->db->query("SELECT * FROM evaluation_list 
            WHERE academic_id = {$_SESSION['academic']['id']} 
            AND student_id = {$_SESSION['login_id']} 
            AND faculty_id = {$faculty_id} 
            AND subject_id = {$subject_id} 
            AND class_id = {$class_id}");
        return $check->num_rows > 0 ? 1 : 0;
    }

    function delete_restriction()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM restriction_list where id = ".$id);
        if($delete)
            return 1;
    }
}