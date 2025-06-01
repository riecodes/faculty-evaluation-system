-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 01:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_db_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` int(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `status`) VALUES
(1, '2024-2025', 1, 1, 1),
(2, '2024-2025', 2, 0, 0),
(3, '2023-2024', 2, 0, 2),
(4, '2023-2024', 1, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_list`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `type` enum('checklist','textfield') NOT NULL DEFAULT 'checklist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`, `type`) VALUES
(1, 'This is a checklists', 0, 'checklist'),
(2, 'This is a Comments', 1, 'textfield');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`, `comment`) VALUES
(4, 1, 5, NULL),
(4, 2, 5, NULL),
(4, 3, 0, 'for robert'),
(5, 1, 4, NULL),
(5, 2, 4, NULL),
(5, 3, 0, 'for john');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(4, 1, 1, 1, 3, 3, 14, '2025-05-27 09:42:17'),
(5, 1, 1, 1, 2, 1, 13, '2025-05-27 09:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Deactivated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`, `status`) VALUES
(1, 'F2023001', 'John', 'Doe', 'john@faculty.com', '363b122c528f54df4a0446b6bab05515', 'no-image-available.png', '2025-05-26 22:50:16', 1),
(2, 'F2023002', 'Jane', 'Smith', 'jane@faculty.com', '363b122c528f54df4a0446b6bab05515', 'no-image-available.png', '2025-05-26 22:50:16', 1),
(3, 'F2023003', 'Robert', 'Johnson', 'robert@faculty.com', '4b43b0aee35624cd95b910189b3dc231', 'no-image-available.png', '2025-05-26 22:50:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `irregular_enrollments`
--

CREATE TABLE `irregular_enrollments` (
  `id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `restriction_id` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `irregular_enrollments`
--

INSERT INTO `irregular_enrollments` (`id`, `student_id`, `subject_id`, `class_id`, `faculty_id`, `academic_id`, `restriction_id`) VALUES
(4, 3, 1, 1, 2, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL,
  `type` enum('checklist','textfield') NOT NULL DEFAULT 'checklist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`, `type`) VALUES
(1, 1, 'Presentation materials', 1, 1, 'checklist'),
(2, 1, 'Good in explaining', 0, 1, 'checklist'),
(3, 1, 'Overall impression of the faculty', 0, 2, 'textfield');

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `type` enum('regular','irregular') NOT NULL DEFAULT 'regular',
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`, `type`, `student_id`) VALUES
(6, 1, 2, 1, 1, 'irregular', NULL),
(12, 1, 2, 1, 1, 'regular', NULL),
(13, 1, 1, 1, 2, 'regular', NULL),
(14, 1, 3, 1, 3, 'regular', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) DEFAULT NULL,
  `type` enum('regular','irregular') NOT NULL DEFAULT 'regular',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Deactivated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `type`, `avatar`, `date_created`, `status`) VALUES
(1, 'S2023001', 'Alice', 'Brown', 'alice@student.com', '0cc175b9c0f1b6a831c399e269772661', 1, 'regular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(2, 'S2023002', 'Michael', 'Wilson', 'michael@student.com', '6f8f57715090da2632453988d9a1501b', 1, 'regular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(3, 'S2023003', 'Sarah', 'David', 'sarah@student.com', '03c7c0ace395d80182db07ae2c30f034', NULL, 'irregular', 'no-image-available.png', '2025-05-26 22:49:36', 1),
(4, '1513', 'jervic', 'manalo', 'eirmonpaculan11@gmail.com', '363b122c528f54df4a0446b6bab05515', 0, 'irregular', 'no-image-available.png', '2025-05-26 23:08:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_list`
--

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
(20, 'IT405', 'IT Ethics and Professional Practice', 'Professional ethics and industry standards');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Faculty Evaluation Performance System', 'noreply@panpacific.com', '(+63)09735366442', 'MacArthur Hwy, Urdaneta, Pangasinan', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', '1607135820_avatar.jpg', '2020-11-26 10:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  ADD PRIMARY KEY (`evaluation_id`,`question_id`),
  ADD KEY `fk_answer_question` (`question_id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `irregular_enrollments`
--
ALTER TABLE `irregular_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `idx_enrollment_student` (`student_id`),
  ADD KEY `idx_enrollment_academic` (`academic_id`),
  ADD KEY `idx_enrollment_restriction` (`restriction_id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_criteria` (`criteria_id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_restriction_type` (`type`),
  ADD KEY `idx_restriction_student` (`student_id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_student_type` (`type`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `irregular_enrollments`
--
ALTER TABLE `irregular_enrollments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `restriction_list`
--
ALTER TABLE `restriction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_evaluation_answers_evaluation_list` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`evaluation_id`) ON DELETE CASCADE;

--
-- Constraints for table `irregular_enrollments`
--
ALTER TABLE `irregular_enrollments`
  ADD CONSTRAINT `irregular_enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`),
  ADD CONSTRAINT `irregular_enrollments_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject_list` (`id`),
  ADD CONSTRAINT `irregular_enrollments_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `class_list` (`id`),
  ADD CONSTRAINT `irregular_enrollments_ibfk_4` FOREIGN KEY (`faculty_id`) REFERENCES `faculty_list` (`id`),
  ADD CONSTRAINT `irregular_enrollments_ibfk_5` FOREIGN KEY (`academic_id`) REFERENCES `academic_list` (`id`),
  ADD CONSTRAINT `irregular_enrollments_ibfk_6` FOREIGN KEY (`restriction_id`) REFERENCES `restriction_list` (`id`);

--
-- Constraints for table `question_list`
--
ALTER TABLE `question_list`
  ADD CONSTRAINT `fk_question_criteria` FOREIGN KEY (`criteria_id`) REFERENCES `criteria_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD CONSTRAINT `fk_restriction_student` FOREIGN KEY (`student_id`) REFERENCES `student_list` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
