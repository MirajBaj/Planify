<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'database.php';

// --- TEMP: No login required, always use user_id = 1 for testing ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
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
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([
            $user_id,
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $category_id,
            $priority,
            $status
        ])) {
            header('Location: newTask.php?success=1');
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a new task</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #d3dbce;
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #ecf1e7;
            height: 96vh;
            border-radius: 32px;
            margin: 2vh 0 2vh 2vw;
            padding: 32px 0 32px 0;
            box-sizing: border-box;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        .sidebar .logo img {
            width: 60px;
            margin-bottom: 8px;
        }
        .sidebar .logo .brand {
            font-weight: bold;
            font-size: 22px;
            letter-spacing: 1px;
        }
        .sidebar .logo .tagline {
            font-size: 12px;
            color: #444;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar nav ul li {
            margin: 18px 0;
            display: flex;
            align-items: center;
        }
        .sidebar nav ul li a, .sidebar nav ul li span {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 36px;
            font-size: 20px;
            color: #222;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.2s, color 0.2s;
            font-weight: 500;
        }
        .sidebar nav ul li a.active, .sidebar nav ul li a:hover {
            background: #d3dbce;
            color: #2563eb;
            font-weight: 700;
        }
        .sidebar .logout {
            padding-left: 36px;
            font-size: 20px;
            color: #444;
            cursor: pointer;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .main-content {
            margin-left: 340px;
            min-height: 100vh;
            padding: 0 0 0 0;
        }
        .main-title {
            font-size: 2.6rem;
            font-weight: 700;
            color: #111;
            margin-top: 60px;
            margin-left: 60px;
            margin-bottom: 24px;
            letter-spacing: -1px;
        }
        .main-flex {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 48px;
        }
        .form-box {
            background: #f6f8f4;
            border-radius: 24px;
            padding: 48px 40px 40px 40px;
            max-width: 540px;
            width: 100%;
            box-shadow: 0 2px 16px #0001;
        }
        .form-group {
            margin-bottom: 28px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 1.15rem;
            color: #222;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 14px;
            border: 1.5px solid #bfc8b2;
            border-radius: 10px;
            font-size: 1.1rem;
            background: #fff;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 70px;
        }
        .form-actions {
            display: flex;
            gap: 32px;
            justify-content: center;
            margin-top: 40px;
        }
        .form-actions button {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 16px 48px;
            border-radius: 14px;
            font-size: 1.15rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px #2563eb22;
        }
        .form-actions button.cancel {
            background: #e5e7eb;
            color: #222;
        }
        .form-actions button:hover:not(.cancel) {
            background: #1741a6;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #2563eb;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .error {
            color: #b91c1c;
            background: #fee2e2;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .sidebar svg, .logout svg {
            width: 22px;
            height: 22px;
            vertical-align: middle;
        }
        .right-cards {
            display: flex;
            flex-direction: column;
            gap: 32px;
            margin-top: 32px;
        }
        .right-card {
            background: #f6f8f4;
            border-radius: 20px;
            box-shadow: 0 2px 12px #0001;
            padding: 28px 32px 24px 32px;
            min-width: 220px;
            min-height: 180px;
        }
        .right-card label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 12px;
            display: block;
        }
        .right-card .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #444;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .right-card .card-title svg {
            width: 18px;
            height: 18px;
        }
        .right-card .radio-group {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .right-card input[type="radio"] {
            accent-color: #2563eb;
            margin-right: 10px;
        }
        @media (max-width: 1100px) {
            .main-flex { flex-direction: column; gap: 0; }
            .right-cards { flex-direction: row; gap: 16px; margin-top: 24px; }
            .main-content { margin-left: 0; }
        }
        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="logo">
                <img src="https://i.imgur.com/0y8Ftya.png" alt="Planify Logo">
                <div class="brand">PLANIFY</div>
                <div class="tagline">Plan. Focus. Win.</div>
            </div>
            <nav>
                <ul>
                    <li><span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/></svg>Dashboard</span></li>
                    <li><span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>User Profile</span></li>
                    <li><a href="newTask.php" class="active"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>+ Add Task</a></li>
                </ul>
            </nav>
        </div>
        <div class="logout">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </div>
    </div>
    <div class="main-content">
        <div class="main-title">Add a new task</div>
        <div class="main-flex">
            <div class="form-box">
                <?php if ($message): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="error"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="title">Task Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <input type="hidden" name="category_id" id="hidden_category_id" value="<?= htmlspecialchars($selected_category) ?>">
                    <input type="hidden" name="status" id="hidden_status" value="<?= htmlspecialchars($selected_status) ?>">
                    <div class="form-actions">
                        <button type="submit">Save Task</button>
                        <button type="button" class="cancel" onclick="window.location.href='dashboard.php'">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="right-cards">
                <div class="right-card" id="category-card">
                    <div class="card-title">Category <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div>
                    <div class="radio-group">
                        <?php foreach ($categories as $id => $name): ?>
                            <label><input type="radio" name="category_radio" value="<?= $id ?>" <?= ($selected_category == $id) ? 'checked' : '' ?>> <?= htmlspecialchars($name) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="right-card" id="status-card">
                    <div class="card-title">Status <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div>
                    <div class="radio-group">
                        <?php foreach ($statuses as $val => $label): ?>
                            <label><input type="radio" name="status_radio" value="<?= $val ?>" <?= ($selected_status == $val) ? 'checked' : '' ?>> <?= htmlspecialchars($label) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Sync radio selection to hidden fields for form submission
    document.querySelectorAll('input[name="category_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('hidden_category_id').value = this.value;
        });
    });
    document.querySelectorAll('input[name="status_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('hidden_status').value = this.value;
        });
    });
    // Set default if not set
    if (!document.querySelector('input[name="category_radio"]:checked')) {
        var firstCat = document.querySelector('input[name="category_radio"]');
        if (firstCat) {
            firstCat.checked = true;
            document.getElementById('hidden_category_id').value = firstCat.value;
        }
    }
    if (!document.querySelector('input[name="status_radio"]:checked')) {
        var firstStat = document.querySelector('input[name="status_radio"]');
        if (firstStat) {
            firstStat.checked = true;
            document.getElementById('hidden_status').value = firstStat.value;
        }
    }
    </script>
</body>
</html>
