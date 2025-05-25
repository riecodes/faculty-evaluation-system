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