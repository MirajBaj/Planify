<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Category mapping
$categories = [
    1 => 'School',
    2 => 'Work',
    3 => 'Personal'
];

// Handle task deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task_id'])) {
    $delete_id = intval($_POST['delete_task_id']);
    $del_stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
    $del_stmt->execute([$delete_id, $user_id]);
    header('Location: alltasks.php');
    exit;
}

// Fetch all tasks
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Tasks</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <style>
    .all-tasks-container { max-width: 1100px; margin: 40px auto; }
    .all-tasks-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 32px; }
    .tasks-table { width: 100%; border-collapse: collapse; background: #f3f5f2; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 12px #0001; }
    .tasks-table th, .tasks-table td { padding: 18px 16px; text-align: left; }
    .tasks-table th { background: #e0e7de; font-size: 1.1rem; }
    .tasks-table tr:not(:last-child) { border-bottom: 1px solid #d1d5db; }
    .task-title-cell { font-weight: 600; color: #222; }
    .task-category { font-weight: 500; color: #256029; }
    .task-status { font-weight: 500; border-radius: 8px; padding: 4px 16px; color: #fff; display: inline-block; }
    .task-status.High { background: #e74c3c; }
    .task-status.Medium { background: #bfa46f; }
    .task-status.Low { background: #95a5a6; }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu">
        <a href="tempmain.php" class="menu-item"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="alltasks.php" class="menu-item active"><span class="nav-icon">&#128196;</span> All Tasks</a>
        <a href="allnotes.php" class="menu-item"><span class="nav-icon">&#128221;</span> All Notes</a>
        
      </div>
      <a href="logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="all-tasks-container">
      <div class="all-tasks-title">All Tasks</div>
      <table class="tasks-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tasks)): ?>
            <tr><td colspan="5" style="text-align:center; color:#888;">No tasks found.</td></tr>
          <?php else: ?>
            <?php foreach ($tasks as $task): ?>
              <tr>
                <td class="task-title-cell"><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo htmlspecialchars($task['description']); ?></td>
                <td class="task-category"><?php echo htmlspecialchars($categories[$task['category_id']] ?? 'Uncategorized'); ?></td>
                <td><span class="task-status <?php echo htmlspecialchars($task['priority']); ?>"><?php echo htmlspecialchars($task['status']); ?></span></td>
                <td><?php echo date('d M, Y', strtotime($task['created_at'])); ?></td>
                <td>
                  <form method="post" style="display:inline;">
                    <input type="hidden" name="delete_task_id" value="<?php echo $task['task_id']; ?>">
                    <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                      <img src="dustbin.png" alt="Delete" style="width: 26px; height: 26px; vertical-align: middle;" />
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html> 