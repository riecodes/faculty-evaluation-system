-- Starter data for Faculty Evaluation System
-- Insert in order: academic_list > class_list > subject_list

-- 1. Academic List (Current and Previous Years)
INSERT INTO `academic_list` (`year`, `semester`, `is_default`, `status`) VALUES
('2024-2025', 1, 1, 1),  -- Current semester (default)
('2024-2025', 2, 0, 0),  -- Next semester
('2023-2024', 2, 0, 2),  -- Previous semester
('2023-2024', 1, 0, 2);  -- Previous semester

-- 2. Class List (BSIT Program)
INSERT INTO `class_list` (`curriculum`, `level`, `section`) VALUES
-- First Year
('BSIT', '1', 'A'),
('BSIT', '1', 'B'),
('BSIT', '1', 'C'),
-- Second Year
('BSIT', '2', 'A'),
('BSIT', '2', 'B'),
-- Third Year
('BSIT', '3', 'A'),
('BSIT', '3', 'B'),
-- Fourth Year
('BSIT', '4', 'A'),
('BSIT', '4', 'B');

-- 3. Subject List (Core IT Subjects)
INSERT INTO `subject_list` (`code`, `subject`, `description`) VALUES
-- First Year Subjects
('IT101', 'Introduction to Computing', 'Basic concepts of computing and information technology'),
('IT102', 'Computer Programming 1', 'Introduction to programming concepts and basic algorithms'),
('IT103', 'Computer Programming 2', 'Object-oriented programming and data structures'),
('IT104', 'Web Development 1', 'HTML, CSS, and basic web development concepts'),
('IT105', 'Database Management Systems', 'Introduction to database concepts and SQL'),
-- Second Year Subjects
('IT201', 'Data Structures and Algorithms', 'Advanced data structures and algorithm analysis'),
('IT202', 'Web Development 2', 'Advanced web development and JavaScript frameworks'),
('IT203', 'Systems Analysis and Design', 'Software development lifecycle and methodologies'),
('IT204', 'Networking 1', 'Computer networks and communication protocols'),
('IT205', 'Operating Systems', 'Operating system concepts and management'),
-- Third Year Subjects
('IT301', 'Software Engineering', 'Software development methodologies and practices'),
('IT302', 'Mobile Application Development', 'Mobile app development for Android and iOS'),
('IT303', 'Information Security', 'Cybersecurity principles and practices'),
('IT304', 'Cloud Computing', 'Cloud services and deployment models'),
('IT305', 'Artificial Intelligence', 'AI concepts and machine learning basics'),
-- Fourth Year Subjects
('IT401', 'IT Project Management', 'Project planning, execution, and management'),
('IT402', 'IT Capstone Project', 'Final year project development'),
('IT403', 'IT Internship', 'Industry immersion and practical experience'),
('IT404', 'IT Research Methods', 'Research methodologies in IT'),
('IT405', 'IT Ethics and Professional Practice', 'Professional ethics and industry standards'); 