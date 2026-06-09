# PROJECT ORGANIZATION & FILE GUIDE

## 📁 COMPLETE FOLDER STRUCTURE (With Descriptions)

```
ONLINE_REGISTRATION/                          [Root Folder]
│
├─ index.php                                   [ENTRY POINT - Login page]
│  └─ Purpose: Check if user logged in, show login form
│
├─ README.md                                   [Project Overview]
├─ SETUP_GUIDE.md                              [Complete Setup Instructions]
├─ PROJECT_STRUCTURE.md                        [This file]
│
├─ src/                                        [Source Code Folder]
│  │
│  ├─ config/                                  [Configuration Folder]
│  │  ├─ config.php
│  │  │  └─ Contains: Database credentials, app constants
│  │  │     Defines: DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, BASE_URL, APP_NAME
│  │  │
│  │  └─ database.php
│  │     └─ Contains: PDO database connection
│  │        Creates: $pdo variable for all database queries
│  │
│  └─ pages/                                   [Application Pages Folder]
│     │
│     ├─ login.php
│     │  ├─ Title: Student Login
│     │  ├─ Purpose: Display login form & authenticate students
│     │  ├─ Form Fields: Registration Number, Password
│     │  ├─ Process:
│     │  │  1. Get form input
│     │  │  2. Query database for student by reg_no
│     │  │  3. Verify password with password_verify()
│     │  │  4. Create session if correct
│     │  │  5. Redirect to dashboard
│     │  └─ Errors: Shows if credentials invalid
│     │
│     ├─ register.php
│     │  ├─ Title: Student Registration
│     │  ├─ Purpose: Allow new students to create accounts
│     │  ├─ Form Fields:
│     │  │  - Registration Number (unique)
│     │  │  - First Name
│     │  │  - Last Name
│     │  │  - Email (unique)
│     │  │  - Department
│     │  │  - Academic Level
│     │  │  - Password
│     │  │  - Confirm Password
│     │  ├─ Process:
│     │  │  1. Validate all fields not empty
│     │  │  2. Check password match
│     │  │  3. Check if reg_no or email already exists
│     │  │  4. Hash password with password_hash()
│     │  │  5. Insert into database
│     │  │  6. Redirect to login
│     │  └─ Errors: Shows if validation fails
│     │
│     ├─ dashboard.php
│     │  ├─ Title: Student Dashboard (Main Page)
│     │  ├─ Purpose: Show student profile, courses, & registration management
│     │  ├─ Sections:
│     │  │  A) Student Profile (top)
│     │  │     - Shows: Reg No, Department, Academic Level
│     │  │
│     │  │  B) Available Courses (middle)
│     │  │     - Shows: All 5 sample courses
│     │  │     - Columns: Code, Title, Credits, Semester, Department
│     │  │     - Action: Register button for each course
│     │  │
│     │  │  C) My Registered Courses (bottom)
│     │  │     - Shows: Courses student enrolled in
│     │  │     - Action: Drop button for each course
│     │  │
│     │  ├─ Logic:
│     │  │  - Requires login (redirects if not)
│     │  │  - Add course: Check max 8 limit, check not duplicate
│     │  │  - Drop course: Remove from registrations table
│     │  │
│     │  └─ Messages: Shows success/error for actions
│     │
│     └─ logout.php
│        ├─ Title: Logout Handler
│        ├─ Purpose: End user session
│        └─ Process:
│           1. Destroy session
│           2. Redirect to login.php
│
├─ assets/                                     [Styling & Media Folder]
│  └─ css/
│     └─ style.css
│        └─ Contains: All styling for the entire website
│           Includes: Responsive design, colors, spacing, animations
│
└─ database/                                   [Database Files Folder]
   │
   ├─ schema.sql
   │  └─ Contains: CREATE TABLE statements
   │     Creates 3 tables:
   │     - students: Stores student accounts
   │     - courses: Stores available courses
   │     - registrations: Stores student-course relationships
   │
   └─ sample_data.sql
      └─ Contains: INSERT statements
         Adds: 5 sample courses to courses table
         Note: No sample students (register through app)
```

---

## 🔄 DATA FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────┐
│                 USER JOURNEY                             │
└─────────────────────────────────────────────────────────┘

1. NEW USER
   ├─ Opens: http://localhost/ONLINE_REGISTRATION/
   ├─ Lands on: index.php (shows login page)
   ├─ Clicks: "Register here" link
   ├─ Navigates to: register.php
   ├─ Fills form: Reg No, Name, Email, Dept, Password
   ├─ Submits: Form sent to register.php (POST)
   ├─ Process:
   │  ├─ Validate all fields
   │  ├─ Hash password
   │  └─ INSERT into students table
   ├─ Redirect: Back to index.php (login page)
   └─ Now registered!

2. RETURNING USER (LOGIN)
   ├─ Opens: http://localhost/ONLINE_REGISTRATION/
   ├─ Lands on: index.php showing login form
   ├─ Fills: Reg No + Password
   ├─ Submits: Form to login.php (POST)
   ├─ Process:
   │  ├─ Query: SELECT from students WHERE reg_no = ?
   │  ├─ Verify: password_verify()
   │  └─ Session: $_SESSION['student_id'] = ID
   ├─ Redirect: dashboard.php
   └─ Logged in!

3. DASHBOARD (MAIN PAGE)
   ├─ Requires: session_id must be set
   ├─ Loads:
   │  ├─ Student profile info (from students table)
   │  ├─ All available courses (from courses table)
   │  └─ Their registered courses (from registrations table)
   │
   ├─ Register for Course:
   │  ├─ POST: form with action='add_course'
   │  ├─ Check: Student has < 8 registrations
   │  ├─ Check: Not already registered
   │  └─ INSERT: Into registrations table
   │
   ├─ Drop Course:
   │  ├─ POST: form with action='drop_course'
   │  └─ DELETE: From registrations table
   │
   └─ Logout:
      ├─ Redirect to: logout.php
      ├─ Process:
      │  ├─ session_destroy()
      │  └─ Redirect to: index.php
      └─ Back to login

┌─────────────────────────────────────────────────────────┐
│          DATABASE RELATIONSHIPS                          │
└─────────────────────────────────────────────────────────┘

students (1)  ──────┐
                    ├──────── registrations ────────┐
courses (1)   ──────┤                                │ (Many)
                    └────────────────────────────────┘

Example:
- Student ID 1 (John Doe) registered for:
  - Course 1 (CSE101) - registration record 1
  - Course 2 (CSE102) - registration record 2
  - Course 3 (MTH101) - registration record 3

- Student can have: 0 to 8 registrations
- Each registration links: one student + one course
```

---

## 🔐 SECURITY FEATURES EXPLAINED

### 1. SQL Injection Prevention
**Problem**: Attackers type SQL code into forms
**Solution**: Use prepared statements
```php
// ❌ UNSAFE - Don't do this
$stmt = $pdo->query("SELECT * FROM students WHERE reg_no = '".$_POST['reg_no']."'");

// ✅ SAFE - Do this instead
$stmt = $pdo->prepare("SELECT * FROM students WHERE reg_no = ?");
$stmt->execute([$_POST['reg_no']]);
```
All queries in this app use prepared statements!

### 2. Password Security
**Problem**: Storing plain passwords is dangerous
**Solution**: Hash passwords
```php
// Registration - Hash password before storing
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Login - Verify entered password against hash
if (password_verify($_POST['password'], $student['password'])) {
    // Password correct!
}
```

### 3. Session Security
**Problem**: Unauthorized access to dashboard
**Solution**: Check session exists
```php
// Dashboard checks at start:
if (empty($_SESSION['student_id'])) {
    header('Location: ../../index.php');
    exit;
}
// If not logged in, redirects to login!
```

### 4. XSS Prevention
**Problem**: Attackers inject JavaScript into page
**Solution**: Escape output
```php
// ❌ UNSAFE
echo $_POST['name'];

// ✅ SAFE
echo htmlspecialchars($_POST['name']);
```
All output uses `htmlspecialchars()`!

### 5. Data Validation
**Problem**: Bad data breaks app
**Solution**: Validate inputs
```php
// Check email is valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'Invalid email';
}

// Check fields not empty
if ($reg_no === '' || $password === '') {
    $message = 'All fields required';
}
```

---

## 🎯 COMMON WORKFLOWS

### Workflow 1: First-Time User Registration
```
Step 1: User visits http://localhost/ONLINE_REGISTRATION/
        ↓
Step 2: Sees login form (from index.php)
        ↓
Step 3: Clicks "Register here" link
        ↓
Step 4: Fill registration form:
        - Reg No: STU001
        - First Name: John
        - Last Name: Doe
        - Email: john@mzumbe.ac.tz
        - Department: Computer Science
        - Academic Level: Year 1
        - Password: password123
        ↓
Step 5: Click "Create Account"
        ↓
Step 6: PHP validates all fields
        ↓
Step 7: PHP checks if reg_no/email already exists
        ↓
Step 8: PHP hashes password
        ↓
Step 9: PHP stores in students table
        ↓
Step 10: Redirects to login page
        ↓
Step 11: User sees success! Now logs in with credentials
```

### Workflow 2: Student Course Registration
```
Step 1: Student logged in, on dashboard.php
        ↓
Step 2: Sees "Available Courses" section
        ↓
Step 3: Reviews 5 sample courses with details
        ↓
Step 4: Finds course to register for
        ↓
Step 5: Clicks "Register" button
        ↓
Step 6: PHP checks:
        - Student has < 8 registrations? YES ✓
        - Not already registered? YES ✓
        ↓
Step 7: PHP inserts into registrations table
        ↓
Step 8: Shows success message
        ↓
Step 9: Course now appears in "My Registered Courses"
```

### Workflow 3: Dropping a Course
```
Step 1: Student on dashboard.php
        ↓
Step 2: Sees "My Registered Courses" section
        ↓
Step 3: Reviews their enrolled courses
        ↓
Step 4: Finds course to drop
        ↓
Step 5: Clicks "Drop Course" button
        ↓
Step 6: PHP deletes from registrations table
        ↓
Step 7: Shows success message
        ↓
Step 8: Course removed from "My Registered Courses"
```

---

## 🛠️ TROUBLESHOOTING BY SECTION

### CONFIG & DATABASE
**Q: Database connection failed**
- Check: MySQL running in XAMPP
- Check: Database `mzumbe_courses` exists
- Check: Credentials in config.php match XAMPP

**Q: Tables don't exist**
- Run: schema.sql in phpMyAdmin
- Check: All 3 tables created (students, courses, registrations)

**Q: No sample courses**
- Run: sample_data.sql in phpMyAdmin
- Check: 5 courses in courses table

### LOGIN & REGISTRATION
**Q: Can't register account**
- Check: All fields filled
- Check: Passwords match
- Check: Email is valid format
- Check: Reg No not already used

**Q: Can't login**
- Check: Account registered first
- Check: Correct Reg No entered
- Check: Correct password entered
- Check: Capslock not on

### DASHBOARD
**Q: Can't register for course**
- Check: Already have < 8 courses
- Check: Not already registered for it

**Q: Dashboard shows blank**
- Check: Logged in (session active)
- Check: All 5 sample courses loaded

**Q: Can't drop course**
- Check: Actually registered for it
- Check: Course appears in "My Courses"

---

## 📊 WHAT HAPPENS BEHIND THE SCENES

### When User Clicks "Register" Button in Dashboard:
```
1. HTML Form is submitted
   └─ POST request with:
      - course_id (which course)
      - action='add_course'

2. PHP processes the POST
   └─ Gets course_id from $_POST

3. Database Query #1: Count registrations
   └─ SELECT COUNT(*) FROM registrations WHERE student_id = ?
   └─ Check: Is student already at 8 courses?

4. If count >= 8
   └─ Show error: "Cannot register for more than 8 courses"
   └─ Stop

5. Database Query #2: Check for duplicate
   └─ SELECT * FROM registrations 
      WHERE student_id = ? AND course_id = ?
   └─ Check: Already registered for this course?

6. If duplicate found
   └─ Show error: "Already registered for this course"
   └─ Stop

7. Database Query #3: Insert registration
   └─ INSERT INTO registrations 
      (student_id, course_id, status) 
      VALUES (?, ?, 'pending')
   └─ Adds new registration record

8. Show success message
   └─ "Course registration successful. Status: pending."

9. Reload dashboard.php
   └─ New course now appears in "My Registered Courses"
```

---

## ✅ FILE CHECKLIST

Before testing, verify these files exist:

```
□ index.php                    ← Main entry
□ src/config/config.php        ← Database settings
□ src/config/database.php      ← PDO connection
□ src/pages/login.php          ← Login form
□ src/pages/register.php       ← Registration form
□ src/pages/dashboard.php      ← Main page
□ src/pages/logout.php         ← Logout
□ assets/css/style.css         ← Styling
□ database/schema.sql          ← Create tables
□ database/sample_data.sql     ← Sample data
□ README.md                    ← Overview
□ SETUP_GUIDE.md              ← Setup steps
```

All 12 files present? ✅ You're ready to test!

---

**Last Updated**: June 4, 2026
**System**: Mzumbe University Online Course Registration v1.0
