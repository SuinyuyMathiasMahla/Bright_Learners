<?php
require_once 'config.php';
requireRole('admin');

/* Fetch Data */
// Students
$students = $pdo->query("
    SELECT u.id,u.full_name,u.email,s.student_account
    FROM users u
    JOIN students s ON u.id=s.id
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Teachers
$teachers = $pdo->query("
    SELECT u.id,u.full_name,u.email,t.department
    FROM users u
    JOIN teachers t ON u.id=t.id
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Fees Payments
$fees = $pdo->query("
    SELECT f.*, u.full_name
    FROM fee_payments f
    JOIN users u ON u.id=f.student_id
    ORDER BY f.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Exam Registrations
$exams = $pdo->query("
    SELECT e.*, u.full_name
    FROM exam_registrations e
    JOIN users u ON u.id=e.student_id
    ORDER BY e.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Course Materials
$materials = $pdo->query("
    SELECT m.*, u.full_name as teacher_name, c.department,c.course_name
    FROM course_materials m
    JOIN users u ON u.id=m.teacher_id
    JOIN courses c ON c.id=m.course_id
    ORDER BY m.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// CA Marks
$caMarks = $pdo->query("
    SELECT ca.*, u.full_name as student_name, c.course_name
    FROM ca_marks ca
    JOIN users u ON u.id=ca.student_id
    JOIN courses c ON c.id=ca.course_id
    ORDER BY ca.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Current Max Fee
$maxFee = $pdo->query("SELECT amount FROM school_fees LIMIT 1")->fetchColumn();

include 'header.php';
?>

<link rel="stylesheet" href="style.css">
<body>
<h1 class="admin">Admin Panel</h1>

<section class="dashboard admin-dashboard">
    <div class="dashboard-main full-width">

        <!-- Set Maximum School Fee -->
        <section class="card">
            <h2>Set Maximum School Fee</h2>
            <form action="admin_actions.php" method="post">
                <input type="hidden" name="action" value="set_max_fee">
                <label>Maximum Fee (USD):</label>
                <input type="number" name="max_fee" value="<?= htmlspecialchars($maxFee) ?>" min="0" required>
                <button type="submit" class="btn btn-primary">Update Fee</button>
            </form>
        </section>

        <!-- Students -->
        <section class="card">
            <h2>Students</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Account</th><th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td><?= htmlspecialchars($s['full_name']) ?></td>
                            <td><?= htmlspecialchars($s['email']) ?></td>
                            <td><?= htmlspecialchars($s['student_account']) ?></td>
                            <td>
                                <form action="admin_actions.php" method="post" onsubmit="return confirm('Delete this student?');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= $s['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Teachers -->
        <section class="card">
            <h2>Teachers</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Department</th><th>Action</th>
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
                                <form action="admin_actions.php" method="post" onsubmit="return confirm('Delete this teacher?');">
                                    <input type="hidden" name="action" value="delete_user">
                                    <input type="hidden" name="user_id" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Course Materials -->
        <section class="card">
            <h2>Course Materials</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th><th>Teacher</th><th>Course</th><th>Title</th><th>File</th><th>Date</th><th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($materials as $m): ?>
                        <tr>
                            <td><?= $m['id'] ?></td>
                            <td><?= htmlspecialchars($m['teacher_name']) ?></td>
                            <td><?= htmlspecialchars($m['department']." - ".$m['course_name']) ?></td>
                            <td><?= htmlspecialchars($m['title']) ?></td>
                            <td><a href="<?= htmlspecialchars($m['file_path']) ?>" target="_blank">View</a></td>
                            <td><?= $m['created_at'] ?></td>
                            <td>
                                <form action="admin_actions.php" method="post" onsubmit="return confirm('Delete this material?');">
                                    <input type="hidden" name="action" value="delete_material">
                                    <input type="hidden" name="material_id" value="<?= $m['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CA Marks -->
        <section class="card">
            <h2>CA Documents</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th><th>Student</th><th>Course</th><th>Title</th><th>File</th><th>Date</th><th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($caMarks as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['student_name']) ?></td>
                            <td><?= htmlspecialchars($c['course_name']) ?></td>
                            <td><?= htmlspecialchars($c['title']) ?></td>
                            <td><a href="<?= htmlspecialchars($c['file_path']) ?>" target="_blank">View</a></td>
                            <td><?= $c['created_at'] ?></td>
                            <td>
                                <form action="admin_actions.php" method="post" onsubmit="return confirm('Delete this CA document?');">
                                    <input type="hidden" name="action" value="delete_ca">
                                    <input type="hidden" name="ca_id" value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
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
