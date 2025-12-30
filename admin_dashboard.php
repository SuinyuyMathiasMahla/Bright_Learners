<?php
require_once 'config.php';
requireRole('admin');

/* =========================
   STUDENTS
========================= */
$students = $pdo->query("
    SELECT 
        u.id,
        u.full_name,
        u.email,
        s.student_account,
        COALESCE(SUM(f.amount),0) AS paid_amount
    FROM users u
    JOIN students s ON u.id = s.id
    LEFT JOIN fee_payments f ON f.student_id = u.id
    GROUP BY u.id
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   TEACHERS
========================= */
$teachers = $pdo->query("
    SELECT u.id,u.full_name,u.email,t.department
    FROM users u
    JOIN teachers t ON u.id=t.id
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   SCHOOL FEES
========================= */
$maxFee = $pdo->query("SELECT max_fee_amount FROM fee_settings LIMIT 1")->fetchColumn();

/* =========================
   MATERIALS
========================= */
$materials = $pdo->query("
    SELECT 
        m.*,
        u.full_name AS teacher_name,
        c.department,
        c.course_name
    FROM course_materials m
    JOIN users u ON u.id=m.teacher_id
    JOIN courses c ON c.id=m.course_id
    ORDER BY m.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   CA DOCUMENTS
========================= */
$caMarks = $pdo->query("
    SELECT 
        ca.*,
        u.full_name AS student_name,
        c.course_name
    FROM ca_marks ca
    JOIN users u ON u.id=ca.student_id
    JOIN courses c ON c.id=ca.course_id
    ORDER BY ca.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>
<style>
   /* =========================
   GENERAL STYLES
========================= */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f9;
    color: #333;
    margin: 0;
    padding: 0;
}

h1.admin {
    text-align: center;
    margin: 30px 0;
    font-size: 2.5em;
    color: #1e40af; /* Deep Blue */
}

/* =========================
   DASHBOARD LAYOUT
========================= */
.dashboard-main {
    max-width: 1400px; /* larger max width for big screens */
    margin: 0 auto;
    padding: 0 20px;
}

/* =========================
   CARDS
========================= */
.card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    padding: 20px;
    margin: 20px 0;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.card h2 {
    font-size: 1.6em;
    color: #1e3a8a;
    margin-bottom: 15px;
}

/* =========================
   TABLES
========================= */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95em;
    min-width: 800px; /* allow scroll for smaller screens */
}

thead {
    background-color: #1e40af;
    color: #fff;
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

tbody tr {
    background-color: #f9fafb;
    transition: background-color 0.2s;
}

tbody tr:nth-child(even) {
    background-color: #f1f5f9;
}

tbody tr:hover {
    background-color: #e0f2fe;
}

/* =========================
   BUTTONS
========================= */
.btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.9em;
}

.btn-primary {
    background-color: #1e40af;
    color: #fff;
}

.btn-primary:hover {
    background-color: #3b82f6;
}

.btn-outline {
    background-color: transparent;
    color: #1e40af;
    border: 2px solid #1e40af;
}

.btn-outline:hover {
    background-color: #1e40af;
    color: #fff;
}

.btn-warning {
    background-color: #f59e0b;
    color: #fff;
}

.btn-warning:hover {
    background-color: #facc15;
}

.btn-danger {
    background-color: #dc2626;
    color: #fff;
}

.btn-danger:hover {
    background-color: #ef4444;
}

/* =========================
   FORM ELEMENTS
========================= */
input[type="number"], input[type="text"], input[type="email"], select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input[type="number"]:focus,
input[type="text"]:focus,
input[type="email"]:focus,
select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 5px rgba(59,130,246,0.3);
}

/* =========================
   RESPONSIVE DESIGN
========================= */
@media (max-width: 1200px) {
    table {
        min-width: 700px;
    }
}

@media (max-width: 992px) {
    table {
        min-width: 600px;
    }

    .card {
        padding: 15px;
    }
}

@media (max-width: 768px) {
    table {
        min-width: 100%;
    }

    th, td {
        padding: 10px 8px;
    }

    .btn {
        padding: 6px 12px;
        font-size: 0.85em;
        margin-bottom: 5px;
    }

    input[type="number"], input[type="text"], input[type="email"], select {
        width: 100%;
        margin-bottom: 10px;
    }
}

</style>
<link rel="stylesheet" href="style.css">

<h1 class="admin">Admin Control Panel</h1>

<section class="dashboard admin-dashboard">
<div class="dashboard-main full-width">

<!-- =========================
   SET MAX SCHOOL FEE
========================= -->
<section class="card">
<h2>Set Maximum School Fee</h2>
<form action="admin_actions.php" method="post">
    <input type="hidden" name="action" value="set_max_fee">
    <input type="number" name="max_fee" value="<?= htmlspecialchars($maxFee) ?>" required>
    <button class="btn btn-primary">Update Fee</button>
</form>
</section>

<!-- =========================
   STUDENTS
========================= -->
<section class="card">
<h2>Students</h2>
<div class="table-responsive">
<table>
<thead>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Account</th><th>Fee Status</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($students as $s): 
    $status = ($s['paid_amount'] >= $maxFee)
        ? "Completed"
        : "Owing $" . ($maxFee - $s['paid_amount']);
?>
<tr>
<td><?= $s['id'] ?></td>
<td><?= htmlspecialchars($s['full_name']) ?></td>
<td><?= htmlspecialchars($s['email']) ?></td>
<td><?= htmlspecialchars($s['student_account']) ?></td>
<td><?= $status ?></td>
<td>

<!-- Open Student Account -->
<form action="admin_actions.php" method="post" style="display:inline">
<input type="hidden" name="action" value="open_student">
<input type="hidden" name="user_id" value="<?= $s['id'] ?>">
<button class="btn btn-outline">Open</button>
</form>

<!-- Reset Password -->
<form action="admin_actions.php" method="post" style="display:inline">
<input type="hidden" name="action" value="reset_password">
<input type="hidden" name="user_id" value="<?= $s['id'] ?>">
<button class="btn btn-warning">Reset Password</button>
</form>

<!-- Delete -->
<form action="admin_actions.php" method="post" style="display:inline"
onsubmit="return confirm('Delete this student?');">
<input type="hidden" name="action" value="delete_user">
<input type="hidden" name="user_id" value="<?= $s['id'] ?>">
<button class="btn btn-danger">Delete</button>
</form>

</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</section>

<!-- =========================
   TEACHERS
========================= -->
<section class="card">
<h2>Teachers</h2>
<div class="table-responsive">
<table>
<thead>
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Department</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($teachers as $t): ?>
<tr>
<td><?= $t['id'] ?></td>
<td><?= htmlspecialchars($t['full_name']) ?></td>
<td><?= htmlspecialchars($t['email']) ?></td>
<td><?= htmlspecialchars($t['department']) ?></td>
<td>

<form action="admin_actions.php" method="post" style="display:inline">
<input type="hidden" name="action" value="reset_password">
<input type="hidden" name="user_id" value="<?= $t['id'] ?>">
<button class="btn btn-warning">Reset Password</button>
</form>

<form action="admin_actions.php" method="post" style="display:inline"
onsubmit="return confirm('Delete this teacher?');">
<input type="hidden" name="action" value="delete_user">
<input type="hidden" name="user_id" value="<?= $t['id'] ?>">
<button class="btn btn-danger">Delete</button>
</form>

</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</section>

<!-- =========================
   COURSE MATERIALS
========================= -->
<section class="card">
<h2>Course Materials</h2>
<div class="table-responsive">
<table>
<thead>
<tr>
<th>Teacher</th><th>Course</th><th>Title</th><th>File</th><th>Date</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($materials as $m): ?>
<tr>
<td><?= htmlspecialchars($m['teacher_name']) ?></td>
<td><?= htmlspecialchars($m['department']." - ".$m['course_name']) ?></td>
<td><?= htmlspecialchars($m['title']) ?></td>
<td><a href="<?= htmlspecialchars($m['file_path']) ?>" target="_blank">View</a></td>
<td><?= $m['created_at'] ?></td>
<td>
<form action="admin_actions.php" method="post"
onsubmit="return confirm('Delete this material?');">
<input type="hidden" name="action" value="delete_material">
<input type="hidden" name="material_id" value="<?= $m['id'] ?>">
<button class="btn btn-danger">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</section>

<!-- =========================
   CA DOCUMENTS
========================= -->
<section class="card">
<h2>CA Documents</h2>
<div class="table-responsive">
<table>
<thead>
<tr>
<th>Student</th><th>Course</th><th>Title</th><th>File</th><th>Date</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($caMarks as $c): ?>
<tr>
<td><?= htmlspecialchars($c['student_name']) ?></td>
<td><?= htmlspecialchars($c['course_name']) ?></td>
<td><?= htmlspecialchars($c['title']) ?></td>
<td><a href="<?= htmlspecialchars($c['file_path']) ?>" target="_blank">View</a></td>
<td><?= $c['created_at'] ?></td>
<td>
<form action="admin_actions.php" method="post"
onsubmit="return confirm('Delete this CA document?');">
<input type="hidden" name="action" value="delete_ca">
<input type="hidden" name="ca_id" value="<?= $c['id'] ?>">
<button class="btn btn-danger">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</section>

</div>
</section>

<?php include 'footer.php'; ?>
