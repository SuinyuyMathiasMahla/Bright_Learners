<?php
require_once 'config.php';
requireRole('teacher');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_materials.php?error=invalid");
    exit;
}

$materialId = (int) $_GET['id'];
$teacherId  = $_SESSION['user_id'];

/* CHANGE table name here if needed */
$stmt = $pdo->prepare("
    SELECT file_path 
    FROM course_materials
    WHERE id = ? AND teacher_id = ?
");
$stmt->execute([$materialId, $teacherId]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$material) {
    header("Location: view_materials.php?error=unauthorized");
    exit;
}

/* Delete file */
$file = 'uploads/' . $material['file_path'];
if (file_exists($file)) {
    unlink($file);
}

/* Delete record */
$deleteStmt = $pdo->prepare("
    DELETE FROM course_materials WHERE id = ?
");
$deleteStmt->execute([$materialId]);

header("Location: view_materials.php?success=deleted");
exit;
?>

