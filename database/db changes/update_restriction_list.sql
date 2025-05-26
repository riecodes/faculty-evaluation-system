-- Add student_id column to restriction_list table
ALTER TABLE restriction_list 
ADD COLUMN student_id INT NULL AFTER type,
ADD CONSTRAINT fk_restriction_student 
FOREIGN KEY (student_id) 
REFERENCES student_list(id) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

-- Add index for better performance
CREATE INDEX idx_restriction_student ON restriction_list(student_id); 