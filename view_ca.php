<?php
require_once 'config.php';
requireRole('teacher');

$teacherId = $_SESSION['user_id'];

/* FETCH CA MARKS */
$stmt = $pdo->prepare("
    SELECT ca.id, ca.title, ca.comments, ca.file_path, ca.created_at,
           c.department, c.course_name
    FROM ca_marks ca
    JOIN courses c ON ca.course_id = c.id
    WHERE ca.teacher_id = ?
    ORDER BY ca.created_at DESC
");
$stmt->execute([$teacherId]);
$cas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "My CA Marks - BLOPS";
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
th,td{padding:12px;}
th{background:#1E3A8A;color:#fff;}
tr:nth-child(even){background:#f7f9fc;}
tr:hover{background:#e6ffb3;}

.btn{
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:.85rem;
}
.btn-view{background:#dc2626;color:#fff;}
.btn-delete{background:#7f1d1d;color:#fff;}
</style>

<div class="card-box">
    <h2><i class="fa fa-file-pdf"></i> Uploaded CA Marks</h2>

    <?php if (empty($cas)): ?>
        <p class="muted">No CA marks uploaded.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Comments</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cas as $ca): ?>
                    <tr>
                        <td><?= htmlspecialchars($ca['department']." - ".$ca['course_name']) ?></td>
                        <td><?= htmlspecialchars($ca['title']) ?></td>
                        <td><?= htmlspecialchars($ca['comments']) ?></td>
                        <td><?= date("d M Y", strtotime($ca['created_at'])) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($ca['file_path']) ?>" target="_blank" class="btn btn-view">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="delete_ca.php?id=<?= $ca['id'] ?>"
                               class="btn btn-delete"
                               onclick="return confirm('Delete this CA file permanently?');">
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
