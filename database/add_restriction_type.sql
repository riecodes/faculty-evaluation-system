-- Add type column to restriction_list table
ALTER TABLE `restriction_list` 
ADD COLUMN `type` ENUM('regular', 'irregular') NOT NULL DEFAULT 'regular' AFTER `subject_id`;

-- Add index for faster queries
ALTER TABLE `restriction_list` 
ADD INDEX `idx_restriction_type` (`type`); 