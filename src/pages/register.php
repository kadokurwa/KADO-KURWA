<?php
session_start();
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

try {
    $pdo->exec("ALTER TABLE students ADD COLUMN photo VARCHAR(255) DEFAULT NULL");
} catch (PDOException $e) {
    // Column may already exist or DB version may not support IF NOT EXISTS; ignore.
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $reg_no = trim($_POST['reg_no'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $academic_level = trim($_POST['academic_level'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $photoPath = null;

    if ($reg_no === '' || $first_name === '' || $last_name === '' || $email === '' || $department === '' || $academic_level === '' || $password === '') {
        $message = 'Please complete all required fields.';
    } elseif ($password !== $confirm_password) {
        $message = 'Passwords do not match.';
    } else {
        $photoPath = null;
        if (!empty($_FILES['photo']['name'])) {
            $allowedTypes = ['image/jpeg' => '.jpg', 'image/png' => '.png', 'image/gif' => '.gif'];
            $photo = $_FILES['photo'];
            if ($photo['error'] === UPLOAD_ERR_OK && isset($allowedTypes[$photo['type']])) {
                $extension = $allowedTypes[$photo['type']];
                $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $reg_no) . $extension;
                $destination = __DIR__ . '/../../assets/images/profiles/' . $safeFileName;
                if (!is_dir(dirname($destination))) {
                    mkdir(dirname($destination), 0755, true);
                }
                if (move_uploaded_file($photo['tmp_name'], $destination)) {
                    $photoPath = 'assets/images/profiles/' . $safeFileName;
                } else {
                    $message = 'Unable to save the uploaded photo. Please try again.';
                }
            } elseif ($photo['error'] !== UPLOAD_ERR_NO_FILE) {
                $message = 'Please upload a valid JPG, PNG, or GIF image.';
            }
        }

        if ($message === '') {
            $stmt = $pdo->prepare('SELECT id FROM students WHERE reg_no = ? OR email = ?');
            $stmt->execute([$reg_no, $email]);
            if ($stmt->fetch()) {
                $message = 'A student with this registration number or email already exists.';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO students (reg_no, first_name, last_name, email, password, department, academic_level, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $insert->execute([$reg_no, $first_name, $last_name, $email, $password_hash, $department, $academic_level, $photoPath]);
                header('Location: ../../index.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | Mzumbe Registration</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Student Registration</h1>
            </div>
            <?php if ($message): ?>
                <div class="alert error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="register">
                <label>Registration Number</label>
                <input type="text" name="reg_no" required>
                <label>Profile Photo</label>
                <input type="file" name="photo" accept="image/*">
                <label>First Name</label>
                <input type="text" name="first_name" required>
                <label>Last Name</label>
                <input type="text" name="last_name" required>
                <label>Email Address</label>
                <input type="email" name="email" required>
                <label>Department</label>
                <input type="text" name="department" required>
                <label>Academic Level</label>
                <input type="text" name="academic_level" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
                <input type="submit" value="Create Account">
            </form>
            <p>Already registered? <a href="../../index.php" class="login-link">Login here</a></p>
        </div>
    </div>
</body>
</html>
