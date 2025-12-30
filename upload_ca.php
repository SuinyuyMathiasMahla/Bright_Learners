<?php
require_once 'config.php';
requireRole('teacher');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = intval($_POST['course_id']);
    $title     = trim($_POST['title']);
    $comments  = trim($_POST['comments']);

    if ($course_id && $title && isset($_FILES['ca_file'])) {
        $uploadDir = "uploads/ca/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['ca_file']['name']);
        $target   = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['ca_file']['tmp_name'], $target)) {
            // Corrected insert: match columns to values
            $stmt = $pdo->prepare("
                INSERT INTO ca_mark (course_id, title, comments, file_path, uploaded_by) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$course_id, $title, $comments, $target, $_SESSION['user_id']]);
        }
    }
}

header("Location: teacher_dashboard.php");
exit;
