<?php
require_once 'config.php';
requireRole('teacher');

$teacherId = $_SESSION['user_id'];

/* USER INFO */
$userStmt = $pdo->prepare(
    "SELECT full_name, profile_pic FROM users WHERE id = ?"
);
$userStmt->execute([$teacherId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

/* COURSES */
$courses = $pdo->query(
    "SELECT id, department, course_name FROM courses ORDER BY department, course_name"
)->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Teacher Dashboard - BLOPS";
include 'header.php';
?>

<link rel="stylesheet" href="style.css">

<style>
/* --- DASHBOARD STYLING --- */
.profile-small {
    background-color: #f0f7ff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-pic {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1E3A8A;
}

.user-info p {
    margin: 4px 0;
    font-size: 1rem;
    color: #1E3A8A;
}

.dashboard {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.sidebar {
    flex: 1;
    min-width: 220px;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.dashboard-main {
    flex: 3;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.card {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.card h2 {
    margin-bottom: 15px;
    color: #1E3A8A;
}

.data-form .form-group {
    margin-bottom: 15px;
}

.data-form label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
}

.data-form input[type="text"],
.data-form input[type="file"],
.data-form select,
.data-form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 0.95rem;
}

.btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
    cursor: pointer;
}

.btn-primary {
    background-color: #1E3A8A;
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-outline {
    background-color: transparent;
    color: #1E3A8A;
    border: 2px solid #1E3A8A;
}

.btn-outline:hover {
    background-color: #1E3A8A;
    color: #fff;
}

.list {
    list-style: none;
    padding: 0;
}

.list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.list li:last-child {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 900px) {
    .dashboard {
        flex-direction: column;
    }
}
</style>

<!-- PROFILE -->
<div class="profile-small">
    <img src="<?= !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : 'assets/default.png'; ?>" alt="Profile Picture" class="profile-pic">
    <div class="user-info">
        <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['full_name']); ?></p>
        <p><strong>Role:</strong> Teacher</p>
    </div>
</div>

<section class="dashboard">
    <aside class="sidebar left-sidebar">
        <h3>Navigation</h3>
        <nav class="side-nav">
            <a href="#upload-material"><i class="fa fa-upload"></i> Upload Course Materials</a>
            <a href="#upload-ca"><i class="fa fa-file-pdf"></i> Upload CA Marks</a>
            <a href="view_materials.php"><i class="fa fa-book"></i> View Uploaded Materials</a>
            <a href="view_ca.php"><i class="fa fa-file-pdf"></i> View Uploaded CA Marks</a>
        </nav>
    </aside>

    <div class="dashboard-main">
        <section id="upload-material" class="card">
            <h2>Upload Course Materials</h2>
            <form action="upload_material.php" method="post" enctype="multipart/form-data" class="data-form">
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id']; ?>"><?= htmlspecialchars($c['department'] . " - " . $c['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Description / Comments</label>
                    <textarea name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>File (PDF, DOC, PPT)</label>
                    <input type="file" name="material_file" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Material</button>
            </form>
        </section>

        <section id="upload-ca" class="card">
            <h2>Upload CA Marks (PDF)</h2>
            <form action="upload_ca.php" method="post" enctype="multipart/form-data" class="data-form">
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id']; ?>"><?= htmlspecialchars($c['department'] . " - " . $c['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Title (e.g. CA1 Marks)</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Comments</label>
                    <textarea name="comments" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>CA File (PDF)</label>
                    <input type="file" name="ca_file" accept="application/pdf" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload CA Marks</button>
            </form>
        </section>
    </div>
</section>

<?php include 'footer.php'; ?>
