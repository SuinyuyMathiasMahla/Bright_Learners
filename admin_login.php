<?php
require_once 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $adminName = trim($_POST['admin_name']);
    $password  = $_POST['password'];

    if ($adminName === "" || $password === "") {
        $error = "All fields are required.";
    } else {

        // Fetch admin by name
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE admin_name = ?");
        $stmt->execute([$adminName]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Plain-text password check
        if ($admin && $admin['password'] === $password) {

            // Set session
            $_SESSION['user_id']   = $admin['id'];
            $_SESSION['role']      = 'admin';
            $_SESSION['full_name'] = $admin['admin_name'];

            header("Location: admin_dashboard.php");
            exit;

        } else {
            $error = "Invalid admin name or password.";
        }
    }
}

$pageTitle = "Admin Login - BLOPS";
include 'header.php';
?>

<link rel="stylesheet" href="style.css">

<style>
.admin-login {
    max-width: 420px;
    margin: 80px auto;
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.admin-login h2 {
    text-align: center;
    color: #1E3A8A;
    margin-bottom: 20px;
}

.admin-login .form-group {
    margin-bottom: 15px;
}

.admin-login label {
    font-weight: 600;
}

.admin-login input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.admin-login .btn {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    background: #1E3A8A;
    color: #fff;
    border: none;
    font-weight: 600;
}

.admin-login .btn:hover {
    background: #2563eb;
}

.error {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
}
</style>

<div class="admin-login">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Admin Name</label>
            <input type="text" name="admin_name" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn">Login</button>
    </form>
</div>

<?php include 'footer.php'; ?>
