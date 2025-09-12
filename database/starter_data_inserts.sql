-- Starter Data Inserts for Faculty Evaluation System
-- This file contains only the INSERT INTO statements for initial data

-- Insert data for academic_list
INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `status`) VALUES
(1, '2024-2025', 1, 1, 1),
(2, '2024-2025', 2, 0, 0),
(3, '2023-2024', 2, 0, 2),
(4, '2023-2024', 1, 0, 2);

-- Insert data for class_list
INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`) VALUES
(1, 'BSIT', '1', 'A'),
(2, 'BSIT', '1', 'B'),
(3, 'BSIT', '1', 'C'),
(4, 'BSIT', '2', 'A'),
(5, 'BSIT', '2', 'B'),
(6, 'BSIT', '3', 'A'),
(7, 'BSIT', '3', 'B'),
(8, 'BSIT', '4', 'A'),
(9, 'BSIT', '4', 'B');

-- Insert data for criteria_list
INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`, `type`) VALUES
(1, 'This is a checklists', 0, 'checklist'),
(2, 'This is a Comments', 1, 'textfield');


-- Insert data for evaluation_list
INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(4, 1, 1, 1, 3, 3, 14, '2025-05-27 09:42:17'),
(5, 1, 1, 1, 2, 1, 13, '2025-05-27 09:42:32');

-- Insert data for faculty_list
INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`, `status`) VALUES
(1, 'F2023001', 'John', 'Doe', 'john@faculty.com', '363b122c528f54df4a0446b6bab05515', 'no-image-available.png', '2025-05-26 22:50:16', 1),
(2, 'F2023002', 'Jane', 'Smith', 'jane@faculty.com', '363b122c528f54df4a0446b6bab05515', 'no-image-available.png', '2025-05-26 22:50:16', 1),
(3, 'F2023003', 'Robert', 'Johnson', 'robert@faculty.com', '4b43b0aee35624cd95b910189b3dc231', 'no-image-available.png', '2025-05-26 22:50:16', 1);

-- Insert data for irregular_enrollments
INSERT INTO `irregular_enrollments` (`id`, `student_id`, `subject_id`, `class_id`, `faculty_id`, `academic_id`, `restriction_id`) VALUES
(4, 3, 1, 1, 2, 1, 6);

-- Insert data for question_list
INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`, `type`) VALUES
(1, 1, 'Presentation materials', 1, 1, 'checklist'),
(2, 1, 'Good in explaining', 0, 1, 'checklist'),
(3, 1, 'Overall impression of the faculty', 0, 2, 'textfield');

-- Insert data for restriction_list
INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`, `type`, `student_id`) VALUES
(6, 1, 2, 1, 1, 'irregular', NULL),
(12, 1, 2, 1, 1, 'regular', NULL),
(13, 1, 1, 1, 2, 'regular', NULL),
(14, 1, 3, 1, 3, 'regular', NULL);

-- Insert data for student_list
INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `type`, `avatar`, `date_created`, `status`) VALUES
(1, 'S2023001', 'Alice', 'Brown', 'alice@student.com', '0cc175b9c0f1b6a831c399e269772661', 1, 'regular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(2, 'S2023002', 'Michael', 'Wilson', 'michael@student.com', '6f8f57715090da2632453988d9a1501b', 1, 'regular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(3, 'S2023003', 'Sarah', 'David', 'sarah@student.com', '03c7c0ace395d80182db07ae2c30f034', NULL, 'irregular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(4, '1513', 'jervic', 'manalo', 'eirmonpaculan11@gmail.com', '363b122c528f54df4a0446b6bab05515', 0, 'irregular', 'no-image-available.png', '2025-05-26 23:08:53', 1);

-- Insert data for subject_list
INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(1, 'IT101', 'Introduction to Computing', 'Basic concepts of computing and information technology'),
(2, 'IT102', 'Computer Programming 1', 'Introduction to programming concepts and basic algorithms'),
(3, 'IT103', 'Computer Programming 2', 'Object-oriented programming and data structures'),
(4, 'IT104', 'Web Development 1', 'HTML, CSS, and basic web development concepts'),
(5, 'IT105', 'Database Management Systems', 'Introduction to database concepts and SQL'),
(6, 'IT201', 'Data Structures and Algorithms', 'Advanced data structures and algorithm analysis'),
(7, 'IT202', 'Web Development 2', 'Advanced web development and JavaScript frameworks'),
(8, 'IT203', 'Systems Analysis and Design', 'Software development lifecycle and methodologies'),
(9, 'IT204', 'Networking 1', 'Computer networks and communication protocols'),
(10, 'IT205', 'Operating Systems', 'Operating system concepts and management'),
(11, 'IT301', 'Software Engineering', 'Software development methodologies and practices'),
(12, 'IT302', 'Mobile Application Development', 'Mobile app development for Android and iOS'),
(13, 'IT303', 'Information Security', 'Cybersecurity principles and practices'),
(14, 'IT304', 'Cloud Computing', 'Cloud services and deployment models'),
(15, 'IT305', 'Artificial Intelligence', 'AI concepts and machine learning basics'),
(16, 'IT401', 'IT Project Management', 'Project planning, execution, and management'),
(17, 'IT402', 'IT Capstone Project', 'Final year project development'),
(18, 'IT403', 'IT Internship', 'Industry immersion and practical experience'),
(19, 'IT404', 'IT Research Methods', 'Research methodologies in IT'),
(20, 'IT405', 'IT Ethics and Professional Practice', 'Professional ethics and industry standards'),
(21, 'IT406', 'Advanced Database Systems', 'Advanced database concepts, optimization, and distributed databases');
