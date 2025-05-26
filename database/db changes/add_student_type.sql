-- Add type column to student_list table
ALTER TABLE `student_list` 
ADD COLUMN `type` ENUM('regular', 'irregular') NOT NULL DEFAULT 'regular' AFTER `class_id`;

-- Add index for faster queries
ALTER TABLE `student_list` 
ADD INDEX `idx_student_type` (`type`);

-- Update existing records to set type as regular
UPDATE `student_list` SET `type` = 'regular' WHERE `type` IS NULL; 