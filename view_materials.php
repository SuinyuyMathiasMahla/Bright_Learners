<?php
require_once 'config.php';
requireRole('teacher');

$teacherId = $_SESSION['user_id'];

/* FETCH MATERIALS */
$stmt = $pdo->prepare("
    SELECT m.id, m.title, m.description, m.file_path, m.created_at,
           c.department, c.course_name
    FROM course_materials m
    JOIN courses c ON m.course_id = c.id
    WHERE m.teacher_id = ?
    ORDER BY m.created_at DESC
");
$stmt->execute([$teacherId]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "My Course Materials - BLOPS";
include 'header.php';
?>

<link rel="stylesheet" href="style.css">

<style>
.card-box{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 6px 15px rgba(0,0,0,.12);
}
.card-box h2{color:#1E3A8A;margin-bottom:15px;}
.table-wrap{overflow-x:auto;}

table{width:100%;border-collapse:collapse;}
th,td{padding:12px;text-align:left;}
th{background:#1E3A8A;color:#fff;}
tr:nth-child(even){background:#f7f9fc;}
tr:hover{background:#e6ffb3;}

.btn{
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:.85rem;
}
.btn-view{background:#2563eb;color:#fff;}
.btn-delete{background:#dc2626;color:#fff;}
.btn-delete:hover{background:#b91c1c;}
.btn-view:hover{background:#1d4ed8;}
</style>

<div class="card-box">
    <h2><i class="fa fa-book"></i> Uploaded Course Materials</h2>

    <?php if (empty($materials)): ?>
        <p class="muted">No materials uploaded yet.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($materials as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['department']." - ".$m['course_name']) ?></td>
                        <td><?= htmlspecialchars($m['title']) ?></td>
                        <td><?= htmlspecialchars($m['description']) ?></td>
                        <td><?= date("d M Y", strtotime($m['created_at'])) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($m['file_path']) ?>" target="_blank" class="btn btn-view">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="delete_material.php?id=<?= $m['id'] ?>"
                               class="btn btn-delete"
                               onclick="return confirm('Delete this material permanently?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<br>
<a href="teacher_dashboard.php"> Back to dashboard </a>


<?php include 'footer.php'; ?>
