<?php
require_once 'config.php';
requireRole('teacher');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id   = intval($_POST['course_id']);
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($course_id && $title && isset($_FILES['material_file'])) {
        $uploadDir = "uploads/materials/";
        if (!is_dir($uploadDir)) mkdir($uploadDir,0777,true);

        $fileName = time()."_".basename($_FILES['material_file']['name']);
        $target   = $uploadDir.$fileName;

        if (move_uploaded_file($_FILES['material_file']['tmp_name'],$target)) {
            $stmt = $pdo->prepare("INSERT INTO course_materials (teacher_id,course_id,title,description,file_path) VALUES (?,?,?,?,?)");
            $stmt->execute([$_SESSION['user_id'],$course_id,$title,$description,$target]);
        }
    }
}
header("Location: teacher_dashboard.php");
exit;