-- Drop existing primary key if any
ALTER TABLE `evaluation_answers` DROP PRIMARY KEY;

-- Add composite primary key
ALTER TABLE `evaluation_answers` 
ADD PRIMARY KEY (`evaluation_id`, `question_id`);

-- Add foreign key constraint
ALTER TABLE `evaluation_answers`
ADD CONSTRAINT `fk_evaluation_answers_evaluation_list` 
FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`evaluation_id`) 
ON DELETE CASCADE; 