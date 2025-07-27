<?php
require('../db.php'); // Make sure this creates $pdo as PDO instance

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

header('Content-Type: application/json');

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['title']) || trim($data['title']) === '') {
    echo json_encode(['success' => false, 'error' => 'Task title is required']);
    exit;
}


$title = trim($data['title']);
$description = isset($data['description']) ? trim($data['description']) : '';

try {
    // Prepare statement with placeholders
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (:title, :description)");

    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (:title, :description, :user_id)");


    // Execute query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save task']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
