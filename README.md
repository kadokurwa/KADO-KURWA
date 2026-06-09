# Mzumbe University Online Course Registration System

## Overview
This is a PHP/MySQL web application for student course registration. It allows students to register, log in, view available courses, register for courses, and drop registered courses.

## Project Files
- `index.php` — main entry point
- `src/config/config.php` — application constants
- `src/config/database.php` — database connection
- `src/pages/login.php` — login screen and authentication
- `src/pages/register.php` — student registration page
- `src/pages/dashboard.php` — student dashboard and course registration
- `src/pages/logout.php` — logout handler
- `assets/css/style.css` — styling
- `database/schema.sql` — database schema
- `database/sample_data.sql` — sample course data

## Requirements
- XAMPP with Apache and MySQL
- PHP 7.4 or newer
- MySQL 5.7 or newer
- Web browser

## Setup and Run
1. Copy the project folder into the XAMPP web root:
   - `C:\xampp\htdocs\ONLINE_REGISTRATION`
   - If you keep spaces in the folder name, use `http://localhost/ONLINE%20REGISTRATION/`

2. Start XAMPP and enable:
   - `Apache`
   - `MySQL`

3. Open phpMyAdmin:
   - `http://localhost/phpmyadmin`

4. Create the database and import SQL files:
   - Create database `mzumbe_courses`
   - Import `database/schema.sql`
   - Import `database/sample_data.sql`

5. Configure database credentials:
   - Open `src/config/config.php`
   - Update `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` if needed

6. Open the application in your browser:
   - `http://localhost/ONLINE_REGISTRATION/`
   - Or `http://localhost/ONLINE%20REGISTRATION/` if the folder name contains spaces

## How to Use
- Register a new student account.
- Log in with registration number and password.
- Browse the available courses on the dashboard.
- Click **Register** to enroll in a course.
- Click **Drop** to remove a registered course.
- Use **Logout** to end the session.

## Database Tables
- `students` — student accounts
- `courses` — available courses
- `registrations` — student course registrations

## Notes
- Passwords use PHP `password_hash()`.
- Students can register for up to 8 courses.
- Sample courses are loaded from `database/sample_data.sql`.
- Rename the project folder to `ONLINE_REGISTRATION` if possible for simpler URLs.

## Example Run URL
- `http://localhost/ONLINE_REGISTRATION/`

---

If you want, I can also create a fresh clean project folder in the workspace with the same course registration app.
