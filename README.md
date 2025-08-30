# GoodFix - University Complaint Management System

GoodFix is a web-based complaint management system designed specifically for universities to help students easily submit their issues and allow staff/administrators to efficiently track and manage all complaints in one centralized location.

## 🚀 Features

### Student Features
- **Easy Complaint Submission**: Simple form to submit complaints with detailed information
- **Real-time Tracking**: Track complaint status using complaint ID, email, or student ID
- **Priority Levels**: Categorize complaints by urgency (Low, Medium, High, Urgent)
- **Multiple Categories**: Support for Academic, Facility, Technology, Administrative, and Other complaint types
- **Email Notifications**: Receive updates about complaint status changes
- **Responsive Design**: Works seamlessly on desktop and mobile devices

### Admin Features
- **Comprehensive Dashboard**: Overview of all complaints with statistics and charts
- **Advanced Filtering**: Filter complaints by status, priority, type, or search terms
- **Status Management**: Update complaint status (Pending → In Progress → Resolved → Closed)
- **Priority Management**: Adjust complaint priority levels
- **Detailed Views**: Complete complaint information with timeline tracking
- **Direct Communication**: Email students directly from the admin panel
- **Pagination**: Handle large numbers of complaints efficiently

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5.3, JavaScript
- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+
- **Icons**: Bootstrap Icons
- **No Framework Dependencies**: Pure PHP implementation for easy understanding and maintenance

## 📁 Project Structure

```
web_devproject/
├── admin/                      # Admin panel files
│   ├── complaint_detail.php    # Detailed complaint view
│   ├── dashboard.php           # Admin dashboard
│   ├── login.php               # Admin login
│   ├── logout.php              # Admin logout
│   └── view_complaints.php     # Complaints listing
├── assets/                     # Static assets (if needed)
├── css/
│   └── style.css               # Custom styles
├── includes/                   # Shared PHP files
│   ├── auth.php                # Authentication functions
│   ├── db.php                  # Database connection
│   ├── footer.php              # Footer template
│   └── header.php              # Header template
├── js/
│   └── script.js               # Custom JavaScript
├── sql/
│   └── complaints.sql          # Database schema and sample data
├── user/                       # Student-facing pages
│   ├── index.php               # Homepage
│   ├── submit_complaint.php    # Complaint submission form
│   ├── submit_success.php      # Success page
│   └── track_complaint.php     # Complaint tracking
├── index.php                   # Home Page
└── README.md                   # This file
```

## 🚀 Installation & Setup

### Prerequisites
- Web server (Apache/Nginx)
- PHP 8.0 or higher
- MySQL 5.7 or higher
- PDO PHP extension

### Step 1: Clone/Download
```bash
git clone https://github.com/nusrathdev/web_devproject.git
cd web_devproject
```

### Step 2: Database Setup
1. Create a MySQL database named `goodfix_db`
2. Import the SQL file:
```bash
mysql -u your_username -p goodfix_db < sql/complaints.sql
```

### Step 3: Configure Database Connection
Edit `includes/db.php` and update the database credentials:
```php
$host = 'localhost';        // Your db host
$dbname = 'goodfix_complaints'; // db name
$username = 'your_username';     // db username
$password = 'your_password';     // db password
```

### Step 4: Set Permissions
Ensure the web server has read access to all files:
```bash
chmod -R 755 web_devproject/
```

### Step 5: Access the Application
- **Student Portal**: `http://userdomain/user/index.php`
- **Admin Panel**: `http://admindomain/admin/login.php`

## 🔐 Default Admin Credentials

```
Username: admin
Password: password
```

**⚠️ Important**: Change these credentials immediately after installation!

## 📊 Database Schema

### `admins` Table
- `id` (INT, Primary Key, Auto Increment)
- `username` (VARCHAR 50, Unique)
- `password` (VARCHAR 255, Hashed)
- `created_at` (TIMESTAMP)

### `complaints` Table
- `id` (INT, Primary Key, Auto Increment)
- `student_name` (VARCHAR 100)
- `student_id` (VARCHAR 20)
- `email` (VARCHAR 100)
- `faculty` (VARCHAR 50)
- `complaint_type` (VARCHAR 50)
- `subject` (VARCHAR 200)
- `description` (TEXT)
- `status` (ENUM: pending, in_progress, resolved, closed)
- `priority` (ENUM: low, medium, high, urgent)
- `submitted_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

## 🎨 Customization

### Styling
- Main styles are in `css/style.css`
- Uses CSS custom properties for easy theme customization
- Bootstrap 5 classes for responsive design

### Adding New Facultys
Edit the faculty dropdown in `user/submit_complaint.php`:
```php
<option value="New Faculty">New Faculty</option>
```

### Adding New Complaint Types
Edit the complaint type dropdown in `user/submit_complaint.php`:
```php
<option value="New Type">New Type</option>
```

## 🔧 Configuration Options

### Email Configuration
To enable email notifications, configure SMTP settings in your PHP installation or use a mail service.

### File Upload (Future Enhancement)
The system is designed to easily add file upload functionality for complaint attachments.

## 🚦 Usage Guide

### For Students
1. Visit the homepage
2. Click "Submit Complaint"
3. Fill out the form with complaint details
4. Submit and save your complaint ID
5. Use "Track Complaint" to check status

### For Administrators
1. Login to admin panel
2. View dashboard for overview
3. Manage complaints in "View Complaints"
4. Update status and priority as needed
5. Contact students directly via email

## 🔒 Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with `htmlspecialchars()`
- Session-based authentication
- Input validation and sanitization

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check database credentials in `includes/db.php`
- Ensure MySQL server is running
- Verify database exists

**Permission Denied**
- Check file permissions
- Ensure web server can read PHP files

**Admin Login Issues**
- Verify admin credentials in database
- Check if session cookies are enabled

### Debug Mode
Add this to `includes/db.php` for debugging:
```php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Future Enhancements

- File attachment support for complaints
- Email notification system
- Complaint categories and subcategories
- Advanced reporting and analytics
- Multi-language support
- REST API for mobile app integration

## 📄 License

This project GoodFix - Complaint Management System is developed by

P. Pirathiska (UWU/ICT/23/095)

ULF. Sayila (UWU/ICT/23/093)

MAM. Ashfak (UWU/ICT/23/027)

MJM. Mafahir (UWU/ICT/23/092)

MHM. Nusrath (UWU/ICT/23/090)

© 2025. All rights reserved.

This project is intended for academic purposes at Uva Wellassa University of Sri Lanka.
Unauthorized copying, modification, or distribution of this work, in whole or in part, is strictly prohibited without prior permission from the authors.

---

**GoodFix** - Making university life better, one complaint at a time! 🎓✨
