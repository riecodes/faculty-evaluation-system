-- Allow NULL class_id in student_list for irregular students
ALTER TABLE `student_list` 
MODIFY `class_id` int(30) NULL DEFAULT NULL;

-- Create irregular_enrollments table
CREATE TABLE `irregular_enrollments` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `restriction_id` int(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`),
  FOREIGN KEY (`subject_id`) REFERENCES `subject_list` (`id`),
  FOREIGN KEY (`class_id`) REFERENCES `class_list` (`id`),
  FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`),
  FOREIGN KEY (`academic_id`) REFERENCES `academic_list` (`id`),
  FOREIGN KEY (`restriction_id`) REFERENCES `restriction_list` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add indexes for better performance
ALTER TABLE `irregular_enrollments`
ADD INDEX `idx_enrollment_student` (`student_id`),
ADD INDEX `idx_enrollment_academic` (`academic_id`),
ADD INDEX `idx_enrollment_restriction` (`restriction_id`);

-- Add trigger to automatically create restriction when enrollment is created
DELIMITER //
CREATE TRIGGER after_irregular_enrollment_insert
AFTER INSERT ON `irregular_enrollments`
FOR EACH ROW
BEGIN
    INSERT INTO `restriction_list` (
        `academic_id`, 
        `faculty_id`, 
        `class_id`, 
        `subject_id`, 
        `type`, 
        `student_id`
    ) VALUES (
        NEW.academic_id,
        NEW.faculty_id,
        NEW.class_id,
        NEW.subject_id,
        'irregular',
        NEW.student_id
    );
    
    -- Update the enrollment with the newly created restriction_id
    UPDATE `irregular_enrollments`
    SET `restriction_id` = LAST_INSERT_ID()
    WHERE `id` = NEW.id;
END//
DELIMITER ; 