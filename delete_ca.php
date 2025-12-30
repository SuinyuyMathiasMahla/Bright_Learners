<?php
require_once 'config.php';
requireRole('teacher');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_ca.php?error=invalid");
    exit;
}

$caId = (int) $_GET['id'];
$teacherId = $_SESSION['user_id'];

/* Verify CA belongs to logged-in teacher */
$checkStmt = $pdo->prepare("
    SELECT id FROM ca_marks 
    WHERE id = ? AND teacher_id = ?
");
$checkStmt->execute([$caId, $teacherId]);

if (!$checkStmt->fetch()) {
    header("Location: view_ca.php?error=unauthorized");
    exit;
}

/* Delete CA */
$deleteStmt = $pdo->prepare("
    DELETE FROM ca_marks WHERE id = ?
");
$deleteStmt->execute([$caId]);

header("Location: view_ca.php?success=deleted");
exit;?>

