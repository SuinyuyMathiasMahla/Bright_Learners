<?php
require_once 'config.php';
requireRole('admin'); // ensure only admin can perform this

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // =========================
    // RESET PASSWORD
    // =========================
    if ($action === 'reset_password') {
        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId > 0) {
            // You can set a default password or generate a random one
            $newPassword = 'Password123!'; // default password
            // OR generate random:
            // $newPassword = bin2hex(random_bytes(4)); // 8-char random password

            // Hash the password before storing
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashedPassword, $userId])) {
                // Redirect back with success message (optional)
                header("Location: admin_dashboard.php?msg=" . urlencode("Password reset successfully. New password: $newPassword"));
                exit;
            } else {
                die("Error: Unable to reset password.");
            }
        } else {
            die("Invalid user ID.");
        }
    }

    // =========================
    // Other admin actions...
    // =========================
}
