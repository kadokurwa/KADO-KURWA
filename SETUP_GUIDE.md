# Mzumbe University Online Course Registration System
## Complete Setup & Usage Guide

---

## 📋 PROJECT OVERVIEW

This is a **PHP/MySQL** web application that allows students to:
- Register for a new account
- Log in securely
- Browse available courses
- Register for up to 8 courses
- Drop registered courses
- View their profile

---

## 🗂️ PROJECT STRUCTURE

```
ONLINE_REGISTRATION/
├── index.php                          # Main login entry point
├── README.md                          # Project description
├── SETUP_GUIDE.md                     # This file
│
├── src/
│   ├── config/
│   │   ├── config.php                 # Database & app constants
│   │   └── database.php               # PDO database connection
│   │
│   └── pages/
│       ├── login.php                  # Login form & authentication
│       ├── register.php               # Student registration form
│       ├── dashboard.php              # Main dashboard & course management
│       └── logout.php                 # Logout handler
│
├── assets/
│   └── css/
│       └── style.css                  # Styling (improved UI)
│
└── database/
    ├── schema.sql                     # Database table structure
    └── sample_data.sql                # Sample courses
```

---

## ⚙️ SETUP STEPS

### Step 1: Verify Database Setup
1. Open **http://localhost/phpmyadmin**
2. Create a new database called `mzumbe_courses`
3. Go to SQL tab and run `database/schema.sql` to create tables
4. Run `database/sample_data.sql` to add sample courses

### Step 2: Verify Configuration
- Open `src/config/config.php`
- Confirm these settings match your XAMPP installation:
  ```php
  DB_HOST = 'localhost:3306'
  DB_NAME = 'mzumbe_courses'
  DB_USER = 'root'
  DB_PASSWORD = ''
  ```

### Step 3: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** (port 80)
3. Start **MySQL** (port 3306)
4. Verify both show "Running" status

### Step 4: Access the Application
- Open browser and go to: **http://localhost/ONLINE_REGISTRATION/**
- You should see the login page

---

## 🚀 HOW TO USE

### 1. REGISTER A NEW ACCOUNT
1. Click **"Don't have an account? Register here"** link
2. Fill in the registration form:
   - **Registration Number**: `STU001` (example)
   - **First Name**: `John`
   - **Last Name**: `Doe`
   - **Email**: `john@mzumbe.ac.tz`
   - **Department**: `Computer Science`
   - **Academic Level**: `Year 1`
   - **Password**: `password123`
   - **Confirm Password**: `password123`
3. Click **"Create Account"**
4. You'll be redirected to login page

### 2. LOG IN
1. Enter your **Registration Number**: `STU001`
2. Enter your **Password**: `password123`
3. Click **"Login"**
4. You'll see the Dashboard

### 3. VIEW COURSES & REGISTER
On the Dashboard:
- **Top Section**: Shows your student profile (Reg No, Department, Level)
- **Middle Section**: Lists all available courses
- Click **"Register"** button to enroll in a course
- Each registration gets status: **"pending"**

### 4. VIEW REGISTERED COURSES
- Scroll down on Dashboard
- **"My Registered Courses"** section shows courses you're enrolled in
- Click **"Drop Course"** to unenroll

### 5. LOG OUT
- Click **"Logout"** link at top right
- Returns to login page

---

## 📊 DATABASE STRUCTURE

### `students` Table
```
id               - Auto-increment ID
reg_no           - Unique registration number (e.g., STU001)
first_name       - Student first name
last_name        - Student last name
email            - Student email (unique)
password         - Hashed password (using PHP password_hash)
department       - Department (e.g., Computer Science)
academic_level   - Year/Level (e.g., Year 1)
created_at       - Registration timestamp
```

### `courses` Table
```
id               - Auto-increment ID
course_code      - Code (e.g., CSE101)
course_title     - Full course name
credit_hours     - Credits (e.g., 3, 4)
semester         - Semester (e.g., Semester 1)
department       - Department offering course
max_enrollment   - Maximum students allowed
created_at       - Created timestamp
```

### `registrations` Table
```
id               - Auto-increment ID
student_id       - Foreign key to students.id
course_id        - Foreign key to courses.id
registered_at    - When registered (timestamp)
status           - Status: 'pending', 'confirmed', 'cancelled'
```

---

## ✅ KEY FEATURES

✔ **Secure Authentication** - Passwords hashed with PHP `password_hash()`
✔ **Session Management** - Sessions prevent unauthorized access
✔ **Course Limit** - Students can register for max 8 courses
✔ **Duplicate Prevention** - Can't register for same course twice
✔ **Responsive Design** - Works on desktop and mobile
✔ **Clean UI** - Modern, easy-to-use interface

---

## 🔧 TROUBLESHOOTING

### Issue: "Invalid registration number or password"
- **Cause**: Account doesn't exist or wrong credentials
- **Solution**: First register an account, then log in

### Issue: "Database connection failed"
- **Cause**: MySQL not running or wrong credentials
- **Solution**: Start MySQL in XAMPP, verify credentials in `config.php`

### Issue: "Notice: Constant already defined"
- **Cause**: Config file included multiple times
- **Solution**: Already fixed! Uses `defined()` check before defining constants

### Issue: "You cannot register for more than 8 courses"
- **Cause**: Already registered for 8 courses
- **Solution**: Drop a course first, then register for a new one

### Issue: Page shows blank or 404
- **Cause**: Apache not running or wrong URL
- **Solution**: Start Apache in XAMPP, use http://localhost/ONLINE_REGISTRATION/

---

## 📝 TEST ACCOUNTS

After running `sample_data.sql`, test with:
- **Registration Number**: Any you created (e.g., `STU001`)
- **Password**: The password you set during registration

---

## 🛠️ DEVELOPER NOTES

### File Purposes

**index.php**
- Entry point for entire app
- Checks if user is logged in
- Redirects to dashboard if logged in
- Shows login page if not logged in

**config.php**
- Contains database credentials
- Contains app constants
- Uses `defined()` to prevent duplicate definitions

**database.php**
- Creates PDO connection
- Sets error mode and fetch mode
- Dies with error message if connection fails

**login.php**
- Displays login form
- Handles form submission
- Validates credentials against database
- Creates session on successful login

**register.php**
- Displays registration form
- Validates all fields
- Checks if email/reg_no already exists
- Hashes password and stores in database
- Redirects to login on success

**dashboard.php**
- Checks if user is logged in (redirects to login if not)
- Displays student profile information
- Shows all available courses with register buttons
- Shows student's registered courses with drop buttons
- Handles course registration and drop actions

**logout.php**
- Destroys session
- Redirects to login page

### Security Features
- Input validation on all forms
- SQL prepared statements prevent injection
- Passwords hashed with `password_hash(PASSWORD_DEFAULT)`
- Session-based authentication
- `htmlspecialchars()` prevents XSS attacks
- Foreign keys ensure data integrity

---

## 📞 SUPPORT

If you encounter issues:
1. Check this guide first
2. Verify XAMPP Apache and MySQL are running
3. Verify database is created and tables imported
4. Check `src/config/config.php` credentials
5. Check browser console for JavaScript errors (F12)
6. Check XAMPP logs for server errors

---

**Last Updated**: June 4, 2026
**Application**: Mzumbe University Online Course Registration System v1.0
