<?php
require_once 'config.php';
requireRole('student');

$userId = $_SESSION['user_id'];

/* FETCH CA MARKS */
$caStmt = $pdo->query("
    SELECT 
        ca.title,
        ca.comments,
        ca.file_path,
        ca.created_at,
        c.department,
        c.course_name
    FROM ca_mark ca
    JOIN courses c ON ca.course_id = c.id
    ORDER BY c.department, c.course_name, ca.created_at DESC
");

$caMarks = $caStmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Continuous Assessment (CA) Results";
include 'header.php';
?>

<style>
   /* =========================
   CA MARKS PAGE STYLING
   ========================= */

.card {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

/* Page Title */
.card h2 {
    font-size: 24px;
    color: #1f2937;
    margin-bottom: 10px;
    font-weight: 700;
}

.card > p {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 25px;
    max-width: 900px;
}

/* =========================
   DEPARTMENT HEADINGS
   ========================= */
.card h3 {
    margin-top: 30px;
    margin-bottom: 15px;
    font-size: 22px;
    color: #0f766e;
    border-left: 6px solid #0f766e;
    padding-left: 12px;
}

/* =========================
   COURSE HEADINGS
   ========================= */
.card h4 {
    font-size: 18px;
    margin: 20px 0 10px;
    color: #374151;
    font-weight: 600;
}

/* =========================
   COURSE MATERIAL CONTAINER
   ========================= */
.course-materials {
    margin-bottom: 30px;
}

.material-item-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

/* =========================
   CA ITEM CARD
   ========================= */
.material-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 15px;
    transition: all 0.3s ease;
}

.material-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    border-color: #0f766e;
}

/* CA Title */
.material-item p strong {
    color: #111827;
    font-size: 15px;
    display: block;
    margin-bottom: 6px;
}

/* CA Description */
.material-item p {
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 10px;
    line-height: 1.5;
}

/* =========================
   VIEW BUTTON
   ========================= */
.btn-outline.btn-small {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #0f766e;
    color: #0f766e;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline.btn-small:hover {
    background: #0f766e;
    color: #ffffff;
}

/* =========================
   EMPTY STATE MESSAGE
   ========================= */
.muted {
    font-style: italic;
    color: #6b7280;
    background: #f3f4f6;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}

/* =========================
   BACK BUTTON
   ========================= */
.btn-secondary {
    display: inline-block;
    margin-top: 15px;
    background: #e5e7eb;
    color: #111827;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
}

.btn-secondary:hover {
    background: #d1d5db;
}

/* =========================
   RESPONSIVENESS
   ========================= */
@media (max-width: 768px) {
    .card {
        padding: 18px;
    }

    .card h2 {
        font-size: 20px;
    }

    .card h3 {
        font-size: 18px;
    }

    .card h4 {
        font-size: 16px;
    }

    .material-item-group {
        grid-template-columns: 1fr;
    }
}

</style>

<section class="card">
    <h2>Continuous Assessment (CA) Results</h2>

    <p>
        This page contains officially released Continuous Assessment (CA) documents
        organized by department and course. These results are provided to help students
        monitor their academic performance throughout the session.
    </p>

    <?php
    $currentDept = null;
    $currentCourse = null;

    foreach ($caMarks as $row):

        if ($row['department'] !== $currentDept):
            if ($currentDept !== null) echo "</div></div>";
            $currentDept = $row['department'];
            echo "<h3>" . htmlspecialchars($currentDept) . "</h3>";
            echo "<div class='course-materials'>";
            $currentCourse = null;
        endif;

        if ($row['course_name'] !== $currentCourse):
            if ($currentCourse !== null) echo "</div>";
            $currentCourse = $row['course_name'];
            echo "<h4>" . htmlspecialchars($currentCourse) . "</h4>";
            echo "<div class='material-item-group'>";
        endif;
    ?>
        <div class="material-item">
            <p><strong><?= htmlspecialchars($row['title']) ?></strong></p>
            <p><?= nl2br(htmlspecialchars($row['comments'])) ?></p>

            <a href="<?= htmlspecialchars($row['file_path']) ?>"
               target="_blank"
               class="btn btn-outline btn-small">
                <i class="fa fa-file-pdf"></i> View CA Document
            </a>
        </div>

    <?php endforeach; ?>

    <?php
    if ($currentDept !== null) echo "</div></div>";

    if (empty($caMarks)):
    ?>
        <p class="muted">
            Continuous Assessment results have not yet been published.
            Please check back later or follow official academic announcements.
        </p>
    <?php endif; ?>
</section>

<a href="student_dashboard.php" class="btn btn-secondary">
    ← Back to Dashboard
</a>

<?php include 'footer.php'; ?>
