<?php
require_once 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CLEAN INPUTS
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = strtolower(trim($_POST['email'] ?? ''));
    $password  = $_POST['password'] ?? '';
    $role      = $_POST['role'] ?? '';

    /* PROFILE IMAGE */
    $profile_pic = null;

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $target   = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $profile_pic = $target;
        }
    }

    // VALIDATION
    if ($full_name && $email && $password && $profile_pic && in_array($role, ['student','teacher'])) {

        try {
            // CHECK IF EMAIL EXISTS
            $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->fetch()) {
                $message = "This email is already registered.";
            } else {

                // HASH PASSWORD
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // INSERT USER
                $stmt = $pdo->prepare(
                    "INSERT INTO users (full_name, email, password_hash, role, profile_pic)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$full_name, $email, $passwordHash, $role, $profile_pic]);

                $userId = $pdo->lastInsertId();

                // ROLE TABLES
                if ($role === 'student') {
                    $student_account = "BLOPSTU-" . str_pad($userId, 3, "0", STR_PAD_LEFT);
                    $pdo->prepare(
                        "INSERT INTO students (id, student_account) VALUES (?, ?)"
                    )->execute([$userId, $student_account]);
                }

                if ($role === 'teacher') {
                    $pdo->prepare(
                        "INSERT INTO teachers (id, department) VALUES (?, NULL)"
                    )->execute([$userId]);
                }

                // LOGIN USER
                $_SESSION['user_id']   = $userId;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['role']      = $role;

                // REDIRECT
                header("Location: " . ($role === 'student'
                    ? "student_dashboard.php"
                    : "teacher_dashboard.php"));
                exit;
            }

        } catch (PDOException $e) {
            // REAL ERROR (for debugging)
            $message = "Database error: " . $e->getMessage();
        }

    } else {
        $message = "Please fill all fields correctly.";
    }
}

$pageTitle = "Register - BLOPS";
include 'header.php';
?>

<script>
function validateForm() {
    const form = document.forms["signupForm"];
    if (
        !form.full_name.value ||
        !form.email.value ||
        !form.password.value
    ) {
        alert("All fields are required!");
        return false;
    }
    return true;
}
</script>

<link rel="stylesheet" href="style.css">

<h1>Welcome to BLOPS</h1>

<section class="auth-section">
    <h2>Create an account</h2>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form name="signupForm"
          method="post"
          class="auth-form"
          enctype="multipart/form-data"
          onsubmit="return validateForm()">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" minlength="6" required>
        </div>

        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="">-- Select role --</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fa fa-user-plus"></i> Register
        </button>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </form>
</section>

<?php include 'footer.php'; ?>
