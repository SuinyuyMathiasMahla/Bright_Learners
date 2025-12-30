<?php
require_once 'config.php';
requireRole('student');

$userId = $_SESSION['user_id'];

if (!isset($_GET['dept'])) {
    header("Location: student_dashboard.php");
    exit;
}

$department = $_GET['dept'];

/* FEES STATUS */
$feesStmt = $pdo->prepare(
    "SELECT COUNT(*) FROM fee_payments WHERE student_id = ? AND status='success'"
);
$feesStmt->execute([$userId]);
$hasPaidFees = $feesStmt->fetchColumn() > 0;

/* COURSES + MATERIALS FOR DEPARTMENT */
$stmt = $pdo->prepare("
    SELECT 
        c.course_name,
        m.title,
        m.description,
        m.file_path,
        m.created_at
    FROM courses c
    JOIN course_materials m ON c.id = m.course_id
    WHERE c.department = ?
    ORDER BY c.course_name, m.created_at DESC
");
$stmt->execute([$department]);
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $department . " Notes";
include 'header.php';
?>

<style>
   /* ================================
   DEPARTMENT NOTES PAGE STYLING
================================ */

/* Main Card */
.card {
    background: #ffffff;
    border-radius: 18px;
    padding: 26px;
    margin: 25px auto;
    max-width: 1100px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
}

/* Page Title */
.card h2 {
    font-size: 1.8rem;
    color: #0f172a;
    margin-bottom: 18px;
    padding-left: 14px;
    border-left: 6px solid #2563eb;
}

/* Course Title */
.card h3 {
    font-size: 1.35rem;
    margin: 30px 0 14px;
    color: #020617;
    position: relative;
    padding-left: 12px;
}

.card h3::before {
    content: "";
    position: absolute;
    left: 0;
    top: 4px;
    width: 4px;
    height: 100%;
    background: #16a34a;
    border-radius: 4px;
}

/* Materials Grid */
.course-materials {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}

/* Individual Material Card */
.material-item {
    background: linear-gradient(145deg, #f8fafc, #eef2f7);
    border-radius: 14px;
    padding: 18px;
    border: 1px solid #e5e7eb;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.material-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.14);
}

/* Material Title */
.material-item strong {
    display: block;
    font-size: 1.05rem;
    color: #111827;
    margin-bottom: 6px;
}

/* Material Description */
.material-item p {
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.5;
}

/* ================================
   BUTTONS
================================ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline {
    border: 1px solid #2563eb;
    color: #2563eb;
    background-color: dodgerblue;
}

.btn-outline:hover {
    background: #2563eb;
    color: #ffffff;
}

.btn-secondary {
    display: inline-block;
    margin: 25px auto;
    background: #334155;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #1e293b;
}

/* ================================
   ALERT & TEXT
================================ */
.alert {
    background: #fff7ed;
    color: #9a3412;
    border-left: 6px solid #fb923c;
    padding: 12px 14px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.muted {
    color: #6b7280;
    font-style: italic;
    font-size: 0.9rem;
}

/* ================================
   RESPONSIVE DESIGN
================================ */
@media (max-width: 768px) {
    .card {
        padding: 18px;
    }

    .card h2 {
        font-size: 1.5rem;
    }

    .card h3 {
        font-size: 1.2rem;
    }
}

</style>

<section class="card">
    <h2><?= htmlspecialchars($department) ?> Department Notes</h2>

    <?php if (!$hasPaidFees): ?>
        <p class="alert">You must pay fees to download notes.</p>
    <?php endif; ?>

    <?php
    $currentCourse = null;
    foreach ($materials as $row):

        if ($row['course_name'] !== $currentCourse):
            if ($currentCourse !== null) echo "</div>";
            $currentCourse = $row['course_name'];
            echo "<h3>" . htmlspecialchars($currentCourse) . "</h3>";
            echo "<div class='course-materials'>";
        endif;
    ?>
        <div class="material-item">
            <p><strong><?= htmlspecialchars($row['title']) ?></strong></p>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

            <?php if ($hasPaidFees): ?>
                <a href="<?= htmlspecialchars($row['file_path']) ?>"
                   target="_blank"
                   class="btn btn-outline btn-small">
                    <i class="fa fa-download"></i> Download
                </a>
            <?php else: ?>
                <p class="muted">Pay fees to access this note.</p>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

    <?php
    if ($currentCourse !== null) echo "</div>";

    if (empty($materials)):
    ?>
        <p class="muted">No notes available for this department.</p>
    <?php endif; ?>
</section>

<a href="student_dashboard.php" class="btn btn-secondary">
    ← Back to Departments
</a>

<?php include 'footer.php'; ?>
