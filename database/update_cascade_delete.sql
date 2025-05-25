-- First, let's clean up orphaned questions
DELETE FROM question_list 
WHERE criteria_id NOT IN (SELECT id FROM criteria_list);

-- Add foreign key constraint with cascade delete
ALTER TABLE question_list
DROP FOREIGN KEY IF EXISTS fk_question_criteria;

ALTER TABLE question_list
ADD CONSTRAINT fk_question_criteria
FOREIGN KEY (criteria_id) 
REFERENCES criteria_list(id)
ON DELETE CASCADE;

-- Add foreign key constraint for evaluation_answers
ALTER TABLE evaluation_answers
DROP FOREIGN KEY IF EXISTS fk_answer_question;

ALTER TABLE evaluation_answers
ADD CONSTRAINT fk_answer_question
FOREIGN KEY (question_id) 
REFERENCES question_list(id)
ON DELETE CASCADE; 