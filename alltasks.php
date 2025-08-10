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
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
  <style>
    .all-tasks-container { max-width: min(1200px, 100%); margin: 16px auto; padding: 0 16px; }
    .all-tasks-title { font-size: var(--font-size-2xl); font-weight: 700; margin: 8px 0 16px; }
    .table-wrapper { background: var(--card); border-radius: 18px; box-shadow: var(--shadow-md); overflow: hidden; }
    .tasks-table { width: 100%; border-collapse: collapse; min-width: 720px; }
    .tasks-table th, .tasks-table td { padding: 16px 14px; text-align: left; }
    .tasks-table th { background: #e0e7de; font-size: var(--font-size-sm); }
    .tasks-table tr:not(:last-child) { border-bottom: 1px solid #d1d5db; }
    .task-title-cell { font-weight: 600; color: #222; }
    .task-category { font-weight: 500; color: #256029; }
    .task-status { font-weight: 500; border-radius: 8px; padding: 4px 12px; color: #fff; display: inline-block; font-size: var(--font-size-xs); }
    .task-status.High { background: #e74c3c; }
    .task-status.Medium { background: #bfa46f; }
    .task-status.Low { background: #95a5a6; }
    .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    @media (max-width: 768px) {
      .all-tasks-title { font-size: var(--font-size-xl); text-align:center; }
      /* Convert table to stacked cards */
      .table-wrapper { background: transparent; box-shadow: none; border-radius: 0; overflow: visible; }
      .tasks-table { min-width: 0; border-collapse: separate; border-spacing: 0 12px; }
      .tasks-table thead { display: none; }
      .tasks-table, .tasks-table tbody, .tasks-table tr, .tasks-table td { display: block; width: 100%; }
      .tasks-table tr { background: var(--card); border-radius: 16px; box-shadow: var(--shadow-sm); overflow: hidden; }
      .tasks-table td { padding: 12px 16px; font-size: var(--font-size-sm); display: grid; grid-template-columns: 120px 1fr; align-items: center; border-bottom: 1px solid #e9ece7; }
      .tasks-table td:last-child { border-bottom: none; }
      .tasks-table td::before { content: attr(data-label); font-weight: 600; color: #666; }
      .task-title-cell { font-weight: 700; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="sidebar" id="sidebar">
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="menu">☰ Menu</button>
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu" id="menu">
        <a href="tempmain.php" class="menu-item"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="alltasks.php" class="menu-item active"><span class="nav-icon">&#128196;</span> All Tasks</a>
        <a href="allnotes.php" class="menu-item"><span class="nav-icon">&#128221;</span> All Notes</a>
        
      </div>
      <a href="logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="all-tasks-container">
      <div class="all-tasks-title">All Tasks</div>
      <div class="table-scroll"><div class="table-wrapper">
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
                <td class="task-title-cell" data-label="Title"><?php echo htmlspecialchars($task['title']); ?></td>
                <td data-label="Description"><?php echo htmlspecialchars($task['description']); ?></td>
                <td class="task-category" data-label="Category"><?php echo htmlspecialchars($categories[$task['category_id']] ?? 'Uncategorized'); ?></td>
                <td data-label="Status">
  <?php if (isset($task['is_completed']) && $task['is_completed'] == 1): ?>
    <span class="task-status" style="background:#256029;">Completed</span>
  <?php else: ?>
    <span class="task-status <?php echo htmlspecialchars($task['priority']); ?>"><?php echo htmlspecialchars($task['status']); ?></span>
  <?php endif; ?>
</td>
                <td data-label="Created At"><?php echo date('d M, Y', strtotime($task['created_at'])); ?></td>
                <td data-label="Action">
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
      </table></div></div>
    </div>
  </div>
  <script>
    (function(){
      var toggle = document.getElementById('navToggle');
      var sidebar = document.getElementById('sidebar');
      if (toggle && sidebar) {
        toggle.addEventListener('click', function(){
          var isOpen = sidebar.classList.toggle('is-open');
          toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
      }
    })();
  </script>
</body>
</html> 