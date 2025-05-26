-- Add status column to faculty_list
ALTER TABLE `faculty_list` 
ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Deactivated';

-- Add status column to student_list
ALTER TABLE `student_list` 
ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Deactivated'; 