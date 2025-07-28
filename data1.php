<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$categories = [
    1 => 'School',
    2 => 'Work',
    3 => 'Personal'
];

$statuses = [
    'Pending' => 'In-Progress',
    'Completed' => 'Completed'
];

$message = '';
$errors = [];
$selected_category = $_POST['category_id'] ?? '';
$selected_status = $_POST['status'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority = $_POST['priority'] ?? 'Medium';
    $category_id = $_POST['category_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if (!in_array($priority, ['Low', 'Medium', 'High'])) {
        $errors[] = 'Invalid priority selected.';
    }

    if (!array_key_exists($status, $statuses)) {
        $errors[] = 'Invalid status selected.';
    }

    if (!array_key_exists($category_id, $categories)) {
        $errors[] = 'Invalid category selected.';
    }

    if (empty($errors)) {
        $sql = 'INSERT INTO tasks (user_id, title, description, category_id, priority, status) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([
            $user_id,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $category_id,
            $priority,
            $status
        ])) {
            header('Location: tempmain.php?success=1');
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            $errors[] = 'Error adding task: ' . htmlspecialchars($errorInfo[2]);
        }
    }
}

if (isset($_GET['success'])) {
    $message = 'Task added successfully!';
}
?>