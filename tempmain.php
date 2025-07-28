<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get filter parameter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Pagination setup
$tasks_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $tasks_per_page;

// Category mapping for filtering
$category_map = [
    'all' => null,
    'work' => 2,
    'school' => 1,
    'personal' => 3
];

$selected_category = $category_map[$filter] ?? null;

// Build WHERE clause for filtering
$where_clause = "WHERE user_id = ?";
$params = [$user_id];

if ($selected_category !== null) {
    $where_clause .= " AND category_id = ?";
    $params[] = $selected_category;
}

// Get total number of tasks for pagination
$count_sql = "SELECT COUNT(*) FROM tasks $where_clause";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_tasks = $count_stmt->fetchColumn();
$total_pages = ceil($total_tasks / $tasks_per_page);

// Fetch user's tasks from database with pagination and filtering
$sql = "SELECT * FROM tasks $where_clause ORDER BY created_at DESC LIMIT $offset, $tasks_per_page";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's notes
$notes_sql = "SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC LIMIT 4";
$notes_stmt = $pdo->prepare($notes_sql);
$notes_stmt->execute([$user_id]);
$notes = $notes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get username for greeting
$user_sql = "SELECT username FROM users WHERE user_id = ?";
$user_stmt = $pdo->prepare($user_sql);
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
$username = $user_data['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planify Dashboard</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;500;400&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu">
        <a href="tempmain.php" class="menu-item active"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="#" class="menu-item"><span class="nav-icon">&#128100;</span> User Profile</a>
        <a href="newTask.php" class="menu-item"><span class="nav-icon">&#43;</span> All Task</a>
        <a href="allnotes.php" class="menu-item"><span class="nav-icon">&#128221;</span> All Notes</a>
      </div>
      <a href="logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="main-content">
      <?php if (isset($_GET['success'])): ?>
        <div class="success-message" style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
          Task added successfully!
        </div>
      <?php endif; ?>
      
      <div class="greeting">
        <h1><span style="font-weight:700;">Good Afternoon</span><br><span style="font-weight:700;"><?php echo htmlspecialchars($username); ?></span></h1>
      </div>
      <div class="dashboard-grid">
        <div class="my-task-card">
          <div class="card-header">
            <span class="card-title">My Task</span>
            <span class="card-add">+</span>
          </div>
          <div class="task-filters">
            <a href="?filter=all<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">All</a>
            <a href="?filter=work<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="filter-btn <?php echo $filter === 'work' ? 'active' : ''; ?>">Work</a>
            <a href="?filter=school<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="filter-btn <?php echo $filter === 'school' ? 'active' : ''; ?>">School</a>
            <a href="?filter=personal<?php echo $current_page > 1 ? '&page=' . $current_page : ''; ?>" class="filter-btn <?php echo $filter === 'personal' ? 'active' : ''; ?>">Personal</a>
          </div>
          <div class="task-list">
            <?php if (empty($tasks)): ?>
              <div class="task-item" style="text-align: center; color: #666; padding: 40px 20px;">
                <div>No tasks yet. Click the + button to add your first task!</div>
              </div>
            <?php else: ?>
              <?php foreach ($tasks as $task): ?>
                <div class="task-item" data-category="<?php echo $task['category_id']; ?>">
                  <div class="task-title"><?php echo htmlspecialchars($task['title']); ?></div>
                  <div class="task-desc"><?php echo htmlspecialchars($task['description']); ?></div>
                  <div class="task-date"><?php echo date('d M, Y', strtotime($task['created_at'])); ?></div>
                  <span class="task-priority <?php echo strtolower($task['priority'] ?? 'medium'); ?>"><?php echo htmlspecialchars($task['priority'] ?? 'Medium'); ?></span>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          
          <!-- Pagination Controls -->
          <?php if ($total_pages > 1): ?>
            <div class="pagination" style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px; padding: 20px;">
              <?php if ($current_page > 1): ?>
                <a href="?filter=<?php echo $filter; ?>&page=<?php echo $current_page - 1; ?>" class="page-btn" style="padding: 8px 16px; background: #f0f0f0; border-radius: 8px; text-decoration: none; color: #333;">← Previous</a>
              <?php endif; ?>
              
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                  <span class="page-btn active" style="padding: 8px 16px; background: #4CAF50; color: white; border-radius: 8px; font-weight: bold;"><?php echo $i; ?></span>
                <?php else: ?>
                  <a href="?filter=<?php echo $filter; ?>&page=<?php echo $i; ?>" class="page-btn" style="padding: 8px 16px; background: #f0f0f0; border-radius: 8px; text-decoration: none; color: #333;"><?php echo $i; ?></a>
                <?php endif; ?>
              <?php endfor; ?>
              
              <?php if ($current_page < $total_pages): ?>
                <a href="?filter=<?php echo $filter; ?>&page=<?php echo $current_page + 1; ?>" class="page-btn" style="padding: 8px 16px; background: #f0f0f0; border-radius: 8px; text-decoration: none; color: #333;">Next →</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="notes-card">
          <div class="card-header">
            <span class="card-title">My Notes</span>
            <a href="Task/addnote.php" class="card-add">+</a>
          </div>
          <div class="notes-list">
            <?php if (empty($notes)): ?>
              <div class="note-placeholder" style="text-align: center; color: #666; padding: 40px 20px;">
                <div>No notes yet. Click the + button to add your first note!</div>
              </div>
            <?php else: ?>
              <?php foreach (array_slice($notes, 0, 2) as $note): ?>
                <div class="note-item" style="background: #fff; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); min-width: 180px; max-width: 220px;">
                  <div class="note-title" style="font-weight: 600; font-size: 1.1rem; color: #333; margin-bottom: 8px; word-break: break-word; "><?php echo htmlspecialchars($note['title']); ?></div>
                  <div class="note-content" style="color: #666; font-size: 0.9rem; margin-bottom: 8px; line-height: 1.4; word-break: break-word; "><?php echo htmlspecialchars($note['content']); ?></div>
                  <div class="note-date" style="color: #999; font-size: 0.8rem;"> <?php echo date('d M, Y', strtotime($note['created_at'])); ?></div>
                </div>
              <?php endforeach; ?>
              <?php if (count($notes) > 2): ?>
                <a href="allnotes.php" style="display: flex; align-items: center; justify-content: center; min-width: 80px; height: 100px; background: #f3f5f2; border-radius: 12px; color: #2563eb; font-weight: 600; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-left: 8px;">More &rarr;</a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="my-task-summary-card">
          <span class="card-title">My Task</span>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // No JavaScript filtering needed - using server-side filtering
  </script>
</body>
</html>