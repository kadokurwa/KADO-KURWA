# 📋 QUICK REFERENCE CARD

## FILE PURPOSES (One-Line Descriptions)

| File | Purpose |
|------|---------|
| `index.php` | Entry point - checks login, shows login form |
| `src/config/config.php` | Stores database credentials & app constants |
| `src/config/database.php` | Creates PDO database connection |
| `src/pages/login.php` | Login form & authentication |
| `src/pages/register.php` | Registration form for new students |
| `src/pages/dashboard.php` | Main dashboard - shows profile, courses, registration |
| `src/pages/logout.php` | Destroys session & logs out user |
| `assets/css/style.css` | Styling for entire website |
| `database/schema.sql` | Creates 3 database tables |
| `database/sample_data.sql` | Inserts 5 sample courses |

---

## 🚀 QUICK START (5 MINUTES)

### Setup
```
1. Open XAMPP Control Panel
2. Start Apache & MySQL
3. Open http://localhost/phpmyadmin
4. Create database: mzumbe_courses
5. Run schema.sql (creates tables)
6. Run sample_data.sql (adds courses)
```

### Test
```
1. Go to: http://localhost/ONLINE_REGISTRATION/
2. Click: "Register here"
3. Fill: All fields (e.g., STU001, John Doe, etc.)
4. Set Password: password123
5. Click: "Create Account"
6. Login with: STU001 / password123
7. See Dashboard with courses
```

---

## DATABASE TABLES

### students
```
id                INTEGER       primary key
reg_no            VARCHAR(30)   unique
first_name        VARCHAR(100)  
last_name         VARCHAR(100)  
email             VARCHAR(150)  unique
password          VARCHAR(255)  hashed
department        VARCHAR(100)  
academic_level    VARCHAR(50)   
created_at        TIMESTAMP     auto
```

### courses
```
id                INTEGER       primary key
course_code       VARCHAR(20)   unique (e.g., CSE101)
course_title      VARCHAR(255)  
credit_hours      INTEGER       (3 or 4)
semester          VARCHAR(50)   
department        VARCHAR(100)  
max_enrollment    INTEGER       
created_at        TIMESTAMP     auto
```

### registrations
```
id                INTEGER       primary key
student_id        INTEGER       foreign key → students.id
course_id         INTEGER       foreign key → courses.id
registered_at     TIMESTAMP     auto
status            ENUM          'pending','confirmed','cancelled'
unique:           (student_id, course_id) ← can't register twice!
```

---

## KEY PHP CONCEPTS USED

### 1. Session Management
```php
session_start();                          // Start/resume session
$_SESSION['student_id'] = $id;            // Store in session
if (empty($_SESSION['student_id'])) {     // Check if logged in
    header('Location: login.php');        // Redirect if not
    exit;
}
session_destroy();                        // Logout
```

### 2. Prepared Statements (Prevent SQL Injection)
```php
$stmt = $pdo->prepare('SELECT * FROM students WHERE reg_no = ?');
$stmt->execute([$reg_no]);                // ? replaced with actual value
$student = $stmt->fetch();                // Get result
```

### 3. Password Security
```php
$hashed = password_hash($password, PASSWORD_DEFAULT);  // Register
if (password_verify($input, $hashed)) {                 // Login
    // Password correct!
}
```

### 4. Form Handling
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {           // Check POST
    $value = trim($_POST['field'] ?? '');              // Get input
    if ($value === '') {                               // Validate
        $error = 'Field required';
    }
}
```

### 5. Output Escaping (Prevent XSS)
```php
echo htmlspecialchars($value);            // Safe output
```

---

## COMMON QUERIES

### Login Query
```sql
SELECT * FROM students WHERE reg_no = ?
-- Used in: login.php
-- Purpose: Find student account by registration number
```

### Register Query
```sql
INSERT INTO students (reg_no, first_name, last_name, email, password, department, academic_level)
VALUES (?, ?, ?, ?, ?, ?, ?)
-- Used in: register.php
-- Purpose: Create new student account
```

### Get Courses Query
```sql
SELECT * FROM courses ORDER BY semester, course_code
-- Used in: dashboard.php
-- Purpose: List all available courses
```

### Register for Course Query
```sql
INSERT INTO registrations (student_id, course_id, status)
VALUES (?, ?, 'pending')
-- Used in: dashboard.php
-- Purpose: Enroll student in course
```

### Drop Course Query
```sql
DELETE FROM registrations WHERE id = ? AND student_id = ?
-- Used in: dashboard.php
-- Purpose: Remove student from course
```

### Get Student's Courses Query
```sql
SELECT r.id, c.* FROM registrations r 
JOIN courses c ON r.course_id = c.id 
WHERE r.student_id = ? 
ORDER BY c.course_code
-- Used in: dashboard.php
-- Purpose: Show courses student is registered for
```

---

## STATUS FLOW

### User Registration Status
```
Not registered → Register form → Submit → Account created → Can login
```

### User Login Status
```
Not logged in (index.php) → Login form → Valid creds → Logged in (dashboard.php)
```

### Course Registration Status
```
Not registered → Click register → Check limits → Registered (status: pending)
```

---

## LIMITS & RULES

- **Students**: Can register for **max 8 courses**
- **Courses**: 5 sample courses provided
- **Password**: Hashed with `PASSWORD_DEFAULT`
- **Duplicate**: Can't register for same course twice
- **Unique**: Reg No and Email must be unique per student

---

## ERROR MESSAGES & MEANINGS

### Login Page Errors
| Error | Cause |
|-------|-------|
| "Invalid registration number or password" | Account doesn't exist OR wrong password |
| "Please provide both..." | Reg No or Password field empty |

### Register Page Errors
| Error | Cause |
|-------|-------|
| "Please complete all required fields" | At least one field is empty |
| "Passwords do not match" | Password and Confirm Password don't match |
| "already exists" | Reg No or Email already registered |

### Dashboard Errors
| Error | Cause |
|-------|-------|
| "cannot register for more than 8 courses" | Already enrolled in 8 courses |
| "already registered for this course" | Duplicate registration attempt |

---

## TESTING CHECKLIST

- [ ] XAMPP Apache running
- [ ] XAMPP MySQL running  
- [ ] Database `mzumbe_courses` created
- [ ] Tables created (schema.sql run)
- [ ] Sample data loaded (sample_data.sql run)
- [ ] Can visit http://localhost/ONLINE_REGISTRATION/
- [ ] Can register new account
- [ ] Can login with new account
- [ ] Can see dashboard
- [ ] Can see 5 sample courses
- [ ] Can register for course
- [ ] Can drop course
- [ ] Can logout

All checked? ✅ System working!

---

## TIPS FOR DEVELOPERS

1. **Always use prepared statements** for database queries
2. **Always check sessions** before showing protected pages
3. **Always escape output** with `htmlspecialchars()`
4. **Always validate input** on the server side (not just client)
5. **Always hash passwords** before storing
6. **Always use `header()` redirects after POST** to prevent re-submission
7. **Always test with sample data** before going live

---

## FOLDER HIERARCHY

```
ONLINE_REGISTRATION/
├── PUBLIC FILES (Accessible via browser)
│   └── index.php
│
├── SOURCE CODE (PHP logic)
│   └── src/
│       ├── config/ (Settings)
│       └── pages/ (Page logic)
│
├── ASSETS (CSS, images, media)
│   └── assets/css/
│
├── DATABASE (SQL scripts)
│   └── database/
│
└── DOCUMENTATION (Guides)
    ├── README.md
    ├── SETUP_GUIDE.md
    ├── PROJECT_STRUCTURE.md
    └── QUICK_REFERENCE.md (this file)
```

---

## TYPICAL USER JOURNEY (VISUAL)

```
START
  ↓
Visit http://localhost/ONLINE_REGISTRATION/
  ↓
See index.php → Login page
  ↓
New User? ──YES→ Click "Register here" → register.php → Fill form → Submit
             │
             └─ php validates
             └─ Hash password
             └─ Insert student
             └─ Redirect to login
             ↓
        Login with credentials
             ↓
Existing? ──YES→ Login → Validate → Create session
  ↓
dashboard.php (Main page)
  ├─ See Profile
  ├─ See All Courses
  ├─ Register for courses
  ├─ See My Courses  
  ├─ Drop courses
  └─ Logout button
  ↓
Click Logout
  ↓
logout.php → Destroy session
  ↓
Redirect to login
  ↓
END
```

---

**Last Updated**: June 4, 2026
**For**: Mzumbe University Online Course Registration System
