<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $reg_no = trim($_POST['reg_no'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($reg_no === '' || $password === '') {
        $message = 'Please provide both Registration Number and password.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM students WHERE reg_no = ?');
        $stmt->execute([$reg_no]);
        $student = $stmt->fetch();

        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
            header('Location: src/pages/dashboard.php');
            exit;
        }

        $message = 'Invalid registration number or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | Mzumbe Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="theme-color" content="#6a1020">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <div class="brand">
                <img src="assets/images/mzumbe_logo.svg" alt="Mzumbe University" class="logo">
                <div class="brand-text">
                    <h1>Mzumbe University</h1>
                    <p class="tag">Online Registration Portal</p>
                </div>
            </div>
            <nav class="navbar">
                <a href="#">Home</a>
                <a href="src/pages/register.php">Register</a>
            </nav>
        </div>
    </header>

    <main>
    <div class="login-page">
        <div class="login-info">
            <img src="https://arms.mzumbe.ac.tz/static/assets/img/logo/mulogo.png" alt="Mzumbe University" class="logo-lg">
            <h2>MU-ARMS — Mzumbe University Academic Records Management System</h2>
            <p>MU-ARMS keeps records and facilitates academic functionalities including:</p>
            <div class="services">
                <div class="service">Students' Registration</div>
                <div class="service">Students' Bills Payments</div>
                <div class="service">Examinations Results</div>
                <div class="service">Students' files tracking</div>
                <div class="service">Accommodation</div>
            </div>
            <p class="terms">By logging in you accept the system <a href="https://arms.mzumbe.ac.tz/user-accounts/login/#" target="_blank" rel="noopener">terms and conditions</a>. Mzumbe University may use provided data as permitted by law.</p>
            <p class="small">© <?= date('Y') ?> <a href="http://site.mzumbe.ac.tz/">Mzumbe University</a></p>
        </div>

        <div class="login-form">
            <div class="card">
                <div class="card-header">
                    <div class="form-logo"><img src="assets/images/Mzumbe.logo/logo.png" alt="Mzumbe logo" onerror="this.style.display='none'"></div>
                </div>
                <div class="card-body">
                <div class="card-title">
                    <h2>Sign in with credentials</h2>
                </div>
                <?php if ($message): ?>
                    <div class="alert error"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zM4 20c0-3.866 3.582-7 8-7s8 3.134 8 7v1H4v-1z" fill="#9aa7b0"/></svg>
                        </span>
                        <input type="text" name="reg_no" placeholder="e.g. MU/1234/2020" required>
                    </div>
                    <div class="form-group">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 8V7a5 5 0 10-10 0v1" stroke="#9aa7b0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="11" width="18" height="10" rx="2" stroke="#9aa7b0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <input type="submit" value="Sign in" class="btn btn-primary">
                </form>
                <p class="small">Don't have an account? <a href="src/pages/register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
    </main>

    <footer class="footer">
        <div class="container">&copy; <?= date('Y') ?> Mzumbe University — All rights reserved.</div>
    </footer>
</body>
</html>
