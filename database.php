<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=php_assignment", "root", "");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
