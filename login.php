<?php
require_once 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        if ($user['role'] === 'student') {
            header("Location: student_dashboard.php");
        } elseif ($user['role'] === 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: admin_dashboard.php");
        }
        exit;
    } else {
        $message = "Invalid credentials.";
    }
}

$pageTitle = "Login - BLOPS";
include 'header.php';
?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Login</h2>

        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" class="auth-form">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-sign-in-alt"></i> Login
            </button>

            <p class="auth-switch">No account yet? <a href="register.php">Register</a></p>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
