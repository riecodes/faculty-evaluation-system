# 🎓 Faculty Evaluation System

[![PHP Version](https://img.shields.io/badge/PHP-7.2%2B-blue.svg)](https://php.net)
[![MySQL Version](https://img.shields.io/badge/MySQL-10.4%2B-orange.svg)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-success.svg)]()

A comprehensive web-based system for managing and conducting faculty evaluations in educational institutions. This system allows students to evaluate their faculty members based on various criteria, while providing administrators with detailed reports and analytics.

## 📋 Table of Contents

- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Structure](#-database-structure)
- [User Roles](#-user-roles)
- [Technical Stack](#-technical-stack)
- [Security Features](#-security-features)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

- **Multi-User System**
  - Administrator Dashboard
  - Faculty Portal
  - Student Interface

- **Evaluation Management**
  - Customizable evaluation criteria
  - Rating system (1-5 scale)
  - Detailed evaluation reports
  - Academic year and semester tracking

- **Class Management**
  - Curriculum tracking
  - Section management
  - Subject assignment
  - Faculty-class mapping

- **Reporting System**
  - Real-time evaluation statistics
  - Exportable reports
  - Visual data representation
  - Faculty performance analytics

- **Communication Tools**
  - Email notifications
  - Reminder system
  - Automated alerts

## 💻 System Requirements

- PHP 7.2 or higher
- MySQL 10.4 or higher
- Web server (Apache/Nginx)
- SMTP server for email functionality
- Modern web browser

## 🚀 Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/riecodes/faculty-evaluation-system.git
   ```

2. Set up the database:
   - Create a new MySQL database
   - Import the `database/evaluation_db_main.sql` file

3. Configure the environment:
   - Copy `.env.example` to `.env`
   - Update database credentials
   - Configure mail settings

4. Set up the web server:
   - Point to the project directory
   - Ensure proper permissions

## ⚙️ Configuration

### Database Configuration
Edit `db_connect.php`:
```php
$conn = new mysqli('localhost', 'username', 'password', 'database_name');
```

### Mail Configuration
Update `.env` file with SMTP settings:
```env
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Faculty Evaluation System"
```

## 📊 Database Structure

### Core Tables
- `academic_list` - Academic year and semester management
- `class_list` - Class and section information
- `criteria_list` - Evaluation criteria
- `evaluation_list` - Main evaluation records
- `evaluation_answers` - Individual evaluation responses
- `faculty_list` - Faculty member information
- `student_list` - Student information
- `subject_list` - Subject details
- `restriction_list` - Class-subject-faculty mappings

## 👥 User Roles

### Administrator
- System configuration
- User management
- Report generation
- Academic year management

### Faculty
- View evaluation results
- Access teaching schedule
- Update profile information

### Student
- Complete evaluations
- View evaluation history
- Update profile information

## 🛠 Technical Stack

- **Backend**
  - PHP 7.2+
  - MySQL 10.4+
  - PHPMailer for email functionality

- **Frontend**
  - HTML5
  - CSS3
  - JavaScript
  - jQuery
  - Bootstrap
  - Font Awesome

- **Fonts**
  - Poppins (Google Fonts)

- **Development Tools**
  - Composer for dependency management
  - PHPUnit for testing
  - Dotenv for environment configuration

## 🔒 Security Features

- Password hashing
- Session management
- Input validation
- SQL injection prevention
- XSS protection
- CSRF protection

## 📝 Recent Changes

### Version 1.1.0 (2024-03-19)
- **Duplicate Enrollment Validation**
  - Added validation to prevent duplicate enrollments for irregular students
  - Implemented in `admin_class.php`, `ajax.php`, and `manage_irregular_enrollments.php`
  - Added transaction handling for data consistency
  - Improved error messaging and user feedback

### Version 1.0.9 (2024-03-18)
- **Evaluation Report Improvements**
  - Limited percentage display to 2 decimal places
  - Improved table cell alignment and styling
  - Enhanced comment section formatting
  - Removed duplicate send reminders button

### Version 1.0.8 (2024-03-17)
- **Irregular Student Evaluation**
  - Added "Proceed to Evaluation" button for irregular students
  - Disabled submit evaluation button until proceeding
  - Improved class display for irregular students
  - Enhanced validation for irregular student evaluations

### Version 1.0.7 (2024-03-16)
- **Database Management**
  - Added truncate tables functionality
  - Created script to reset auto_increment values
  - Preserved users and system_settings tables
  - Added foreign key check handling

### Version 1.0.6 (2024-03-15)
- **UI/UX Improvements**
  - Enhanced table layouts and responsiveness
  - Improved form validation feedback
  - Added loading indicators
  - Updated color scheme and typography

### Version 1.0.5 (2024-03-14)
- **Security Enhancements**
  - Implemented input sanitization
  - Added CSRF protection
  - Enhanced password hashing
  - Improved session management

### Version 1.0.4 (2024-03-13)
- **Email System**
  - Implemented PHPMailer integration
  - Added email templates
  - Enhanced reminder system
  - Improved error handling for email sending

### Version 1.0.3 (2024-03-12)
- **Report Generation**
  - Added PDF export functionality
  - Enhanced data visualization
  - Improved report formatting
  - Added batch processing for large datasets

### Version 1.0.2 (2024-03-11)
- **User Management**
  - Added bulk user import
  - Enhanced user profile management
  - Improved password reset functionality
  - Added user activity logging

### Version 1.0.1 (2024-03-10)
- **Initial Release**
  - Basic system functionality
  - Core evaluation features
  - User authentication
  - Database structure

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 📞 Support

For support, email support@example.com or create an issue in the repository.

## 🙏 Acknowledgments

- All contributors who have helped shape this project
- The open-source community for their invaluable tools and resources 