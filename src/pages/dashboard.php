<?php
session_start();
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

if (empty($_SESSION['student_id'])) {
    header('Location: ../../index.php');
    exit;
}

$studentId = $_SESSION['student_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_course' && !empty($_POST['course_id'])) {
        $courseId = (int) $_POST['course_id'];

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM registrations WHERE student_id = ?');
        $stmt->execute([$studentId]);
        $registrationCount = $stmt->fetchColumn();

        if ($registrationCount >= 8) {
            $message = 'You cannot register for more than 8 courses.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM registrations WHERE student_id = ? AND course_id = ?');
            $stmt->execute([$studentId, $courseId]);

            if ($stmt->fetch()) {
                $message = 'You are already registered for this course.';
            } else {
                $insert = $pdo->prepare('INSERT INTO registrations (student_id, course_id, status) VALUES (?, ?, ?)');
                $insert->execute([$studentId, $courseId, 'pending']);
                $message = 'Course registration successful. Status: pending.';
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'drop_course' && !empty($_POST['registration_id'])) {
        $registrationId = (int) $_POST['registration_id'];
        $delete = $pdo->prepare('DELETE FROM registrations WHERE id = ? AND student_id = ?');
        $delete->execute([$registrationId, $studentId]);
        $message = 'Course dropped successfully.';
    }
}

$studentStmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$studentStmt->execute([$studentId]);
$student = $studentStmt->fetch();

$coursesStmt = $pdo->query('SELECT * FROM courses ORDER BY semester, course_code');
$courses = $coursesStmt->fetchAll();

$registrationsStmt = $pdo->prepare('SELECT r.id AS registration_id, c.* FROM registrations r JOIN courses c ON r.course_id = c.id WHERE r.student_id = ? ORDER BY c.course_code');
$registrationsStmt->execute([$studentId]);
$registrations = $registrationsStmt->fetchAll();

$registeredCourseIds = array_column($registrations, 'id', 'registration_id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Mzumbe Registration</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <div class="header-brand">
                <img src="../../assets/images/Mzumbe.logo/logo.png" alt="Mzumbe" class="logo" onerror="this.style.display='none'">
                <div class="brand-text">
                    <h1>MU-ARMS 2.0.0</h1>
                    <div class="tag">Welcome, <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> · <?= htmlspecialchars($student['department']) ?> · <?= htmlspecialchars($student['reg_no']) ?></div>
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <div class="notification">
                        <span>Notifications</span>
                        <span class="badge">0</span>
                    </div>
                    <div class="profile-chip">
                        <?php if (!empty($student['photo'])): ?>
                            <img src="../../<?= htmlspecialchars($student['photo']) ?>" alt="Profile">
                        <?php else: ?>
                            <span class="avatar-initial"><?= strtoupper(htmlspecialchars(substr($student['first_name'],0,1) . substr($student['last_name'],0,1))) ?></span>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($student['first_name']) ?></span>
                    </div>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="content-area">
        <?php if ($message): ?>
            <div class="alert success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="layout">
            <aside class="sidebar">
                <div class="card">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
                        <img src="../../assets/images/Mzumbe.logo/logo.png" alt="MU" style="width:34px;height:34px;border-radius:4px;object-fit:contain" onerror="this.style.display='none'">
                        <div>
                            <div style="font-weight:800;color:#fff">MU-ARMS</div>
                            <div style="font-size:12px;color:rgba(255,255,255,0.7)">v2.0.0</div>
                        </div>
                    </div>
                    <p class="profile-name"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                    <p class="profile-meta"><?= htmlspecialchars($student['reg_no']) ?> · <?= htmlspecialchars($student['department']) ?></p>
                    <div class="nav-menu">
                        <div class="nav-section">
                            <div class="nav-section-title">Academic Apps</div>
                            <a href="dashboard.php" class="active"><span class="icon">🏠</span> Dashboard</a>
                            <a href="#registration"><span class="icon">📚</span> Registration</a>
                            <a href="#records"><span class="icon">📝</span> Academic Records</a>
                            <a href="#field"><span class="icon">🔗</span> Field & Projects</a>
                        </div>
                        <div class="nav-section">
                            <div class="nav-section-title">Other Apps</div>
                            <a href="#accommodation"><span class="icon">🛏️</span> Accommodation</a>
                            <a href="#nhif-info"><span class="icon">🏥</span> NHIF</a>
                            <a href="#students-guide"><span class="icon">📘</span> Students' Guide</a>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="main">
                <div class="dashboard-panel">
                    <div class="hero">
                        <h1>Dashboard</h1>
                        <p>Welcome to MU-ARMS. Use the registration section below to select your courses, and navigate the academic apps from the sidebar.</p>
                    </div>

                    <div class="tiles">
            <a class="tile" href="#registration"><span class="t-icon">📚</span>REGISTRATION</a>
            <a class="tile" href="#records"><span class="t-icon">🗂️</span>ACADEMIC RECORDS</a>
            <a class="tile" href="#field"><span class="t-icon">🔗</span>FIELD & PROJECTS</a>
            <a class="tile" href="#accommodation"><span class="t-icon">🛏️</span>ACCOMMODATION</a>
            <a class="tile small" href="#esb"><span class="t-icon">📦</span>ESB</a>
        </div>
        <!-- Registration module quick links and sections -->
        <div class="card section-card" id="registration">
            <h2>Registration</h2>
            <div class="subnav">
                <a href="#reg-dashboard">Dashboard</a>
                <a href="#semester-registration">Semester Registration</a>
                <a href="#programme-structure">Programme Structure</a>
                <a href="#registration-history">Registration History</a>
                <a href="#programme-transfer">Programme Transfer</a>
                <a href="#postpone-studies">Postpone Studies</a>
            </div>
            <div id="reg-dashboard" class="section-card">
                <h3>Registration Dashboard</h3>
                <div class="section-placeholder">Quick summary of registration status and current semester actions.</div>
            </div>
            <div id="semester-registration" class="section-card">
                <h3>Semester Registration</h3>
                <div class="section-placeholder">Use the form below to register for this semester's courses (see Available Courses).</div>
            </div>
            <div id="programme-structure" class="section-card">
                <h3>Programme Structure</h3>
                <div class="section-placeholder">Programme structure and core/optional course lists.</div>
            </div>
            <div id="registration-history" class="section-card">
                <h3>Registration History</h3>
                <div class="section-placeholder">Past semesters and registration records.</div>
            </div>
            <div id="programme-transfer" class="section-card">
                <h3>Programme Transfer</h3>
                <div class="section-placeholder">Apply for programme transfer or view transfer status.</div>
            </div>
            <div id="postpone-studies" class="section-card">
                <h3>Postpone Studies</h3>
                <div class="section-placeholder">Request postponement of studies and view current requests.</div>
            </div>
        </div>

                    <div class="card" id="courses">
                        <h2>Available Courses</h2>
            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Credits</th>
                        <th>Semester</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <?php
                        $registered = false;
                        foreach ($registrations as $reg) {
                            if ($reg['id'] === $course['id']) {
                                $registered = true;
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($course['course_code']) ?></td>
                            <td><?= htmlspecialchars($course['course_title']) ?></td>
                            <td><?= htmlspecialchars($course['credit_hours']) ?></td>
                            <td><?= htmlspecialchars($course['semester']) ?></td>
                            <td><?= htmlspecialchars($course['department']) ?></td>
                            <td>
                                <?php if ($registered): ?>
                                    <span class="small muted">Registered</span>
                                <?php else: ?>
                                    <form method="post" style="margin:0;">
                                        <input type="hidden" name="action" value="add_course">
                                        <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

                    <div class="card">
                        <h2>Your Registered Courses</h2>
                        <?php if (empty($registrations)): ?>
                            <p>No courses registered yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Credits</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?= htmlspecialchars($reg['course_code']) ?></td>
                                <td><?= htmlspecialchars($reg['course_title']) ?></td>
                                <td><?= htmlspecialchars($reg['credit_hours']) ?></td>
                                <td><?= htmlspecialchars($reg['semester']) ?></td>
                                <td><?= htmlspecialchars($reg['status']) ?></td>
                                <td>
                                    <form method="post" style="margin:0;">
                                        <input type="hidden" name="action" value="drop_course">
                                        <input type="hidden" name="registration_id" value="<?= (int)$reg['registration_id'] ?>">
                                        <button type="submit" class="btn btn-danger">Drop</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
                    </div>

                    <div class="card" id="records">
                        <h2>Academic Records</h2>
                        <div class="subnav">
                            <a href="#exam-results">Exam Results</a>
                            <a href="#course-evaluations">Course Evaluations</a>
                            <a href="#postpone-exams">Postpone Examinations</a>
                        </div>
                        <div id="exam-results" class="section-card">
                            <h3>Exam Results</h3>
                            <div class="section-placeholder">Exam results will appear here once released.</div>
                        </div>
                        <div id="course-evaluations" class="section-card">
                            <h3>Course Evaluations</h3>
                            <div class="section-placeholder">Complete course evaluations and view past responses.</div>
                        </div>
                        <div id="postpone-exams" class="section-card">
                            <h3>Postpone Examinations</h3>
                            <div class="section-placeholder">Request exam postponement and view status.</div>
                        </div>
                    </div>

                    <div class="card" id="field">
                        <h2>Field & Projects</h2>
                        <div class="subnav">
                            <a href="#my-placements">My Placements</a>
                        </div>
                        <div id="my-placements" class="section-card">
                            <h3>My Placements</h3>
                            <div class="section-placeholder">Your field placements will be listed here.</div>
                        </div>
                    </div>

                    <div class="card" id="accommodation">
                        <h2>Accommodation</h2>
                        <div class="subnav">
                            <a href="#accom-booking">Booking</a>
                            <a href="#accom-history">History</a>
                        </div>
                        <div id="accom-booking" class="section-card">
                            <h3>Booking</h3>
                            <div class="section-placeholder">Submit accommodation booking requests here.</div>
                        </div>
                        <div id="accom-history" class="section-card">
                            <h3>History</h3>
                            <div class="section-placeholder">View past accommodation bookings and status here.</div>
                        </div>
                    </div>

                    <div class="card" id="nhif">
                        <h2>NHIF</h2>
                        <div id="nhif-info" class="section-card">
                            <h3>NHIF Information</h3>
                            <div class="section-placeholder">
                                <p>NHIF details are displayed below:</p>
                                <ul style="list-style:disc;margin-left:20px;">
                                    <li><strong>Member ID:</strong> 14320096/T.24</li>
                                    <li><strong>Status:</strong> Active</li>
                                    <li><strong>Plan:</strong> Student Health Package</li>
                                    <li><strong>Expiry:</strong> 2026-12-31</li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </section>
        </div>

        <div class="footer">
            <p>Online Course Registration System for Mzumbe University</p>
        </div>
    </main>
    <script>
        // Toggle visibility of section cards on click
        (function(){
            function hideAll(){
                document.querySelectorAll('.section-card').forEach(function(el){ el.classList.remove('active'); });
            }

            function show(target){
                hideAll();
                var el = document.querySelector(target);
                if(el){ el.classList.add('active'); el.scrollIntoView({behavior:'smooth', block:'start'}); }
            }

            // subnav links
            document.querySelectorAll('.subnav a').forEach(function(a){
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    var href = a.getAttribute('href');
                    if(href){ show(href); }
                });
            });

            // sidebar nav links and tiles
            document.querySelectorAll('.nav-menu a, .tile').forEach(function(a){
                a.addEventListener('click', function(e){
                    var href = a.getAttribute('href');
                    if(href && href.indexOf('#') === 0){
                        e.preventDefault();
                        // If top-level registration, show its dashboard subsection by default
                        if(href === '#registration'){
                            show('#reg-dashboard');
                        } else {
                            show(href);
                        }
                    }
                });
            });

            // hide all on initial load (keep hero visible)
            hideAll();
        })();
    </script>
</body>
</html>
