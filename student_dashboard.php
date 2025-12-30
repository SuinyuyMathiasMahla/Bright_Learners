<?php
require_once 'config.php';
requireRole('student');

$userId = $_SESSION['user_id'];

/* USER INFO */
$userStmt = $pdo->prepare("SELECT full_name, profile_pic FROM users WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

/* STUDENT INFO */
$studentStmt = $pdo->prepare("SELECT student_account FROM students WHERE id = ?");
$studentStmt->execute([$userId]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC);

/* MAX FEE SET BY ADMIN */
try {
    $maxFeeStmt = $pdo->query("SELECT max_fee_amount FROM fee_settings LIMIT 1");
    $maxFee = $maxFeeStmt->fetchColumn();
    if (!$maxFee) $maxFee = 3000; // default
} catch (PDOException $e) {
    $maxFee = 3000; // default if table missing
}

/* FEES STATUS */
$feesPaidStmt = $pdo->prepare("SELECT SUM(amount) FROM fee_payments WHERE student_id = ? AND status='success'");
$feesPaidStmt->execute([$userId]);
$feesPaid = $feesPaidStmt->fetchColumn() ?? 0;

$remainingFee = max(0, $maxFee - $feesPaid);
$hasCompletedFees = $remainingFee <= 0;

/* INSTALLMENTS */
$installment1 = floor($maxFee / 3);
$installment2 = floor($maxFee / 3);
$installment3 = $maxFee - ($installment1 + $installment2); // third installment rounds up

/* COURSES & MATERIALS */
$courseStmt = $pdo->query("
    SELECT c.id AS course_id, c.department, c.course_name,
           m.id AS material_id, m.title, m.description, m.file_path, m.created_at
    FROM courses c
    LEFT JOIN course_materials m ON c.id = m.course_id
    ORDER BY c.department, c.course_name, m.created_at DESC
");
$courses = $courseStmt->fetchAll(PDO::FETCH_ASSOC);

/* CA MARKS */
$caStmt = $pdo->query("
    SELECT ca.title, ca.comments, ca.file_path,
           c.department, c.course_name
    FROM ca_mark ca
    JOIN courses c ON ca.course_id = c.id
    ORDER BY ca.created_at DESC
");
$caMarks = $caStmt->fetchAll(PDO::FETCH_ASSOC);

/* DEPARTMENTS WITH MATERIALS ONLY */
$deptStmt = $pdo->query("
    SELECT DISTINCT c.department
    FROM courses c
    JOIN course_materials m ON c.id = m.course_id
    ORDER BY c.department
");
$departments = $deptStmt->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = "Student Dashboard - BLOPS";
include 'header.php';
?>

<style>
/* Same CSS as before */
.department-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:1rem; margin-top:1rem;}
.department-card { text-decoration:none; background-color:#f0f7ff; border-radius:12px; padding:20px; text-align:center; color:#1E3A8A; box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;}
.department-card h3 { margin-bottom:10px; font-size:1.2rem;}
.department-card p { font-size:0.95rem; color:#555555; margin:0;}
.department-card:hover { background-color:#e6ffb3; color:#1E3A8A; transform:translateY(-5px); box-shadow:0 6px 12px rgba(0,0,0,0.15);}
@media (max-width:900px) {.department-grid { grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); } }
@media (max-width:650px) { .department-grid { grid-template-columns:repeat(auto-fit,minmax(150px,1fr));} .department-card h3 { font-size:1rem;} .department-card p { font-size:0.8rem; } }
</style>

<link rel="stylesheet" href="style.css">

<!-- Profile Section -->
<div class="profile-small">
    <h2 class="h1s">BLOPS - Student <label>Empowering education with simplicity</label></h2>
    <hr>
    <section class="sprof">
        <div>
            <img src="<?= !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'assets/default.png'; ?>" alt="Profile" class="profile-pic">
        </div>
        <div class="user-info">
            <p class="full-name">Name: <?= htmlspecialchars($_SESSION['full_name']); ?></p>
            <p>Matricule: <?= htmlspecialchars($student['student_account']); ?></p>
        </div>
    </section>
</div>

<!-- Dashboard Layout -->
<section class="dashboard">
    <aside class="sidebar left-sidebar" id="profile">
        <div class="profile-box">
            <img src="<?= !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'assets/default.png'; ?>" alt="Profile" class="profile-pic">
            <div class="user-info">
                <p class="full-name">Name: <?= htmlspecialchars($_SESSION['full_name']); ?></p>
                <p>Matricule: <?= htmlspecialchars($student['student_account']); ?></p>
            </div>
        </div>
        <nav class="side-nav">
            <a href="#fees"><i class="fa fa-wallet"></i> Pay Fees</a>
            <a href="#courses"><i class="fa fa-book"></i> Courses</a>
            <a href="#exam"><i class="fa fa-file-signature"></i> Exam Registration</a>
            <a href="#requirements"><i class="fa fa-list-check"></i> Concour Requirements</a>
            <a href="#ca"><i class="fa fa-file-pdf"></i> CA Marks</a>
        </nav>
    </aside>

    <div class="dashboard-main">
        <!-- Fees Section -->
        <section id="fees" class="card">
            <h2>Fees Payment</h2>
            <?php if ($hasCompletedFees): ?>
                <p class="badge badge-success">All fees completed! Thank you.</p>
            <?php else: ?>
                <p>Maximum fee: <?= number_format($maxFee,2) ?></p>
                <p>Amount paid so far: <?= number_format($feesPaid,2) ?></p>
                <p>Remaining fee: <?= number_format($remainingFee,2) ?></p>
                <p>Installments:</p>
                <ul>
                    <li>Installment 1: <?= number_format($installment1,2) ?></li>
                    <li>Installment 2: <?= number_format($installment2,2) ?></li>
                    <li>Installment 3: <?= number_format($installment3,2) ?></li>
                </ul>
                <a href="pay_fees.php" class="btn btn-primary"><i class="fa fa-money-bill-wave"></i> Pay Remaining Fees (<?= number_format($remainingFee,2) ?>)</a>
            <?php endif; ?>
        </section>

        <!-- Lecture Notes Section -->
        <section id="courses" class="card">
            <h2>Lecture Notes by Department</h2>
            <?php if (!$hasCompletedFees): ?>
                <p class="alert">You must complete all fees to access lecture notes.</p>
            <?php endif; ?>
            <div class="department-grid">
                <?php foreach ($departments as $dept): ?>
                    <a href="department_notes.php?dept=<?= urlencode($dept) ?>" class="department-card">
                        <h3><?= htmlspecialchars($dept) ?></h3>
                        <p>View all courses & notes</p>
                    </a>
                <?php endforeach; ?>
                <?php if (empty($departments)): ?>
                    <p class="muted">No lecture notes available yet.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Exam Registration -->
        <section id="exam" class="card">
            <h2>Exam Registration & Payment</h2>
            <?php if (!$hasCompletedFees): ?>
                <p class="alert">You must complete all fees before registering for exams.</p>
            <?php else: ?>
                <p>Register and pay for your Concour exam. A receipt will be generated on success.</p>
                <a href="exam_register.php" class="btn btn-primary"><i class="fa fa-file-invoice-dollar"></i> Register for Exam</a>
            <?php endif; ?>
        </section>

        <!-- Concour Requirements -->
        <section id="requirements" class="card">
            <h2>Concour Requirements</h2>
            <div class="requirements-grid">
                <div class="req-item">
                    <h3>FET</h3>
                    <ul>
                        <li>Ordinary Level: Mathematics, Physics</li>
                        <li>Advanced Level: Mathematics</li>
                    </ul>
                </div>
                <div class="req-item">
                    <h3>Medicine</h3>
                    <ul>
                        <li>Ordinary Level: Physics/Maths, Chemistry, Biology</li>
                        <li>Advanced Level: Chemistry, Biology</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CA Marks -->
        <section id="ca" class="card">
            <a href="ca_marks.php"><i class="fa fa-file-pdf"></i> CA Marks</a>
        </section>
    </div>
</section>

<?php include 'footer.php'; ?>
