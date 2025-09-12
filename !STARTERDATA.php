<?php
include 'db_connect.php';

$success = false;
$message = '';
$debug = [];

if(isset($_POST['insert_data'])) {
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // 1. Insert Academic List
        $academic_sql = "INSERT INTO `academic_list` (`year`, `semester`, `is_default`, `status`) VALUES
            ('2024-2025', 1, 1, 1),
            ('2024-2025', 2, 0, 0),
            ('2023-2024', 2, 0, 2),
            ('2023-2024', 1, 0, 2)";
        $conn->query($academic_sql);
        $debug[] = "Academic list inserted: " . $conn->affected_rows . " rows";
        
        // 2. Insert Class List
        $class_sql = "INSERT INTO `class_list` (`curriculum`, `level`, `section`) VALUES
            ('BSIT', '1', 'A'),
            ('BSIT', '1', 'B'),
            ('BSIT', '1', 'C'),
            ('BSIT', '2', 'A'),
            ('BSIT', '2', 'B'),
            ('BSIT', '3', 'A'),
            ('BSIT', '3', 'B'),
            ('BSIT', '4', 'A'),
            ('BSIT', '4', 'B')";
        $conn->query($class_sql);
        $debug[] = "Class list inserted: " . $conn->affected_rows . " rows";
        
        // 3. Insert Subject List
        $subject_sql = "INSERT INTO `subject_list` (`code`, `subject`, `description`) VALUES
            ('IT101', 'Introduction to Computing', 'Basic concepts of computing and information technology'),
            ('IT102', 'Computer Programming 1', 'Introduction to programming concepts and basic algorithms'),
            ('IT103', 'Computer Programming 2', 'Object-oriented programming and data structures'),
            ('IT104', 'Web Development 1', 'HTML, CSS, and basic web development concepts'),
            ('IT105', 'Database Management Systems', 'Introduction to database concepts and SQL'),
            ('IT201', 'Data Structures and Algorithms', 'Advanced data structures and algorithm analysis'),
            ('IT202', 'Web Development 2', 'Advanced web development and JavaScript frameworks'),
            ('IT203', 'Systems Analysis and Design', 'Software development lifecycle and methodologies'),
            ('IT204', 'Networking 1', 'Computer networks and communication protocols'),
            ('IT205', 'Operating Systems', 'Operating system concepts and management'),
            ('IT301', 'Software Engineering', 'Software development methodologies and practices'),
            ('IT302', 'Mobile Application Development', 'Mobile app development for Android and iOS'),
            ('IT303', 'Information Security', 'Cybersecurity principles and practices'),
            ('IT304', 'Cloud Computing', 'Cloud services and deployment models'),
            ('IT305', 'Artificial Intelligence', 'AI concepts and machine learning basics'),
            ('IT401', 'IT Project Management', 'Project planning, execution, and management'),
            ('IT402', 'IT Capstone Project', 'Final year project development'),
            ('IT403', 'IT Internship', 'Industry immersion and practical experience'),
            ('IT404', 'IT Research Methods', 'Research methodologies in IT'),
            ('IT405', 'IT Ethics and Professional Practice', 'Professional ethics and industry standards')";
        $conn->query($subject_sql);
        $debug[] = "Subject list inserted: " . $conn->affected_rows . " rows";
        
        // Commit transaction
        $conn->commit();
        $success = true;
        $message = "Starter data successfully inserted!";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
        $debug[] = "Error occurred: " . $e->getMessage();
    }
}
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-center">Insert Starter Data</h4>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <?php if($message): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger' ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($debug)): ?>
                        <div class="alert alert-info">
                            <h5>Debug Information:</h5>
                            <ul>
                                <?php foreach($debug as $d): ?>
                                    <li><?php echo $d; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group text-center">
                            <button type="submit" name="insert_data" class="btn btn-primary" onclick="return confirm('This will insert starter data into your database. Do you want to continue?')">
                                Insert Starter Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 