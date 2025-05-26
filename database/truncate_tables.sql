-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate tables and reset auto_increment
TRUNCATE TABLE academic_list;
ALTER TABLE academic_list AUTO_INCREMENT = 1;

TRUNCATE TABLE class_list;
ALTER TABLE class_list AUTO_INCREMENT = 1;

TRUNCATE TABLE criteria_list;
ALTER TABLE criteria_list AUTO_INCREMENT = 1;

TRUNCATE TABLE evaluation_answers;
ALTER TABLE evaluation_answers AUTO_INCREMENT = 1;

TRUNCATE TABLE evaluation_list;
ALTER TABLE evaluation_list AUTO_INCREMENT = 1;

TRUNCATE TABLE faculty_list;
ALTER TABLE faculty_list AUTO_INCREMENT = 1;

TRUNCATE TABLE irregular_enrollments;
ALTER TABLE irregular_enrollments AUTO_INCREMENT = 1;

TRUNCATE TABLE question_list;
ALTER TABLE question_list AUTO_INCREMENT = 1;

TRUNCATE TABLE restriction_list;
ALTER TABLE restriction_list AUTO_INCREMENT = 1;

TRUNCATE TABLE student_list;
ALTER TABLE student_list AUTO_INCREMENT = 1;

TRUNCATE TABLE subject_list;
ALTER TABLE subject_list AUTO_INCREMENT = 1;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1; 