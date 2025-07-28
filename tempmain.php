<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle AJAX request for deleting high priority tasks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_delete_high_task'])) {
    $task_id = $_POST['task_id'] ?? null;
    
    if ($task_id && is_numeric($task_id)) {
        // Delete the task from database
        $delete_sql = "DELETE FROM tasks WHERE task_id = ? AND user_id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        
        if ($delete_stmt->execute([$task_id, $user_id])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete task']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid task ID']);
    }
    exit;
}

// Handle regular form submission for deleting high priority tasks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_high_task'])) {
    $task_id = $_POST['task_id'] ?? null;
    
    if ($task_id && is_numeric($task_id)) {
        $delete_sql = "DELETE FROM tasks WHERE task_id = ? AND user_id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$task_id, $user_id]);
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
    exit;
}

// Rest of your existing PHP code for fetching tasks, pagination, etc.
$filter = $_GET['filter'] ?? 'all';
$current_page = max(1, intval($_GET['page'] ?? 1));
$tasks_per_page = 10;
$offset = ($current_page - 1) * $tasks_per_page;

$categories = [
    1 => 'School',
    2 => 'Work', 
    3 => 'Personal'
];

// Build query based on filter
$where_clause = "WHERE user_id = ?";
$params = [$user_id];

if ($filter !== 'all') {
    $category_id = array_search(ucfirst($filter), $categories);
    if ($category_id !== false) {
        $where_clause .= " AND category_id = ?";
        $params[] = $category_id;
    }
}

// Get total count for pagination
$count_sql = "SELECT COUNT(*) FROM tasks $where_clause";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_tasks = $count_stmt->fetchColumn();
$total_pages = ceil($total_tasks / $tasks_per_page);

// Get tasks for current page
$sql = "SELECT * FROM tasks $where_clause ORDER BY created_at DESC LIMIT $tasks_per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get username
$user_sql = "SELECT username FROM users WHERE user_id = ?";
$user_stmt = $pdo->prepare($user_sql);
$user_stmt->execute([$user_id]);
$username = $user_stmt->fetchColumn();
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
    <style>
        .delete-button {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            margin-left: 8px;
            opacity: 1;
        }
        
        .high-task-bullet {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            padding: 8px 0;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            justify-content: space-between;
        }
        
        .task-content {
            display: flex;
            align-items: center;
            flex: 1;
            cursor: pointer;
        }
        
        .task-actions {
            display: flex;
            align-items: center;
        }
        
        .high-priority-list-divs {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .high-priority-list-divs::-webkit-scrollbar {
            width: 0;
            background: transparent;
        }
        
        .high-priority-list-divs {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
    </style>
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:100px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu">
        <a href="tempmain.php" class="menu-item <?php echo $filter === 'all' ? 'active' : ''; ?>"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="#" class="menu-item"><span class="nav-icon">&#128100;</span> User Profile</a>
        <a href="newTask.php" class="menu-item"><span class="nav-icon">&#43;</span> Add Task</a>
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
                <div class="task-item" data-title="<?php echo htmlspecialchars($task['title']); ?>" data-desc="<?php echo htmlspecialchars($task['description']); ?>" data-date="<?php echo date('d M, Y', strtotime($task['created_at'])); ?>" data-priority="<?php echo htmlspecialchars($task['priority'] ?? 'Medium'); ?>" data-category="<?php echo isset($categories[$task['category_id']]) ? htmlspecialchars($categories[$task['category_id']]) : 'Unknown'; ?>">
                  <div class="task-title"><?php echo htmlspecialchars($task['title']); ?></div>
                  <div class="task-desc"><?php echo htmlspecialchars($task['description']); ?></div>
                  <div class="fixed-date"><?php echo date('d M, Y', strtotime($task['created_at'])); ?></div>
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
            <span class="card-add">+</span>
          </div>
          <div class="notes-list">
            <div class="note-placeholder"></div>
            <div class="note-placeholder"></div>
          </div>
        </div>
        <div class="my-task-summary-card">
          <div class="high-priority-header">
            <span class="card-title">High Priority Tasks:</span>
            <div class="high-priority-list-divs" id="highPriorityList">
              <?php
                // Fetch high priority tasks for this user
                $high_sql = "SELECT task_id, title, status FROM tasks WHERE user_id = ? AND priority = 'High' ORDER BY created_at DESC LIMIT 20";
                $high_stmt = $pdo->prepare($high_sql);
                $high_stmt->execute([$user_id]);
                $high_tasks = $high_stmt->fetchAll(PDO::FETCH_ASSOC);
                if (empty($high_tasks)) {
                  echo '<div class="no-high-task">No high priority tasks</div>';
                } else {
                  foreach ($high_tasks as $htask) {
                    echo '<div class="high-task-bullet" data-task-id="' . $htask['task_id'] . '">';
                    echo '<div class="task-content" onclick="deleteHighPriorityTask(' . $htask['task_id'] . ')">';
                    echo '<span class="bullet-icon">•</span>';
                    echo '<span class="task-text">' . htmlspecialchars($htask['title']) . '</span>';
                    echo '</div>';
                    echo '<div class="task-actions">';
                    echo '<form method="POST" style="display: inline-block; margin: 0;">';
                    echo '<input type="hidden" name="delete_high_task" value="1">';
                    echo '<input type="hidden" name="task_id" value="' . $htask['task_id'] . '">';
                    echo '<button type="submit" class="delete-button">×</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                  }
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Task Details Modal -->
<div id="taskModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.25); align-items:center; justify-content:center;">
  <div class="modal-content" style="background:#fff; border-radius:18px; padding:32px 28px; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
    <span id="closeModal" style="position:absolute; top:12px; right:18px; font-size:1.6rem; color:#b00020; cursor:pointer;">&times;</span>
    <h2 id="modalTitle" style="margin-top:0; color:#256029;"></h2>
    <div style="margin-bottom:10px;"><strong>Description:</strong> <span id="modalDesc"></span></div>
    <div style="margin-bottom:10px;"><strong>Date:</strong> <span id="modalDate"></span></div>
    <div style="margin-bottom:10px;"><strong>Priority:</strong> <span id="modalPriority"></span></div>
    <div style="margin-bottom:10px;"><strong>Category:</strong> <span id="modalCategory"></span></div>
  </div>
</div>

<script>
  // Function to delete high priority task
  function deleteHighPriorityTask(taskId) {
    const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
    
    // Send AJAX request to delete the task
    fetch(window.location.href, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `ajax_delete_high_task=1&task_id=${taskId}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Remove the task element from DOM
        if (taskElement) {
          taskElement.remove();
          
          // Check if there are any remaining tasks
          const remainingTasks = document.querySelectorAll('.high-task-bullet');
          if (remainingTasks.length === 0) {
            document.getElementById('highPriorityList').innerHTML = '<div class="no-high-task">No high priority tasks</div>';
          }
        }
      } else {
        alert('Error deleting task: ' + (data.error || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error deleting task. Please try again.');
    });
  }
  
  // Existing modal functionality
  document.querySelectorAll('.task-item').forEach(function(item) {
    item.addEventListener('click', function() {
      document.getElementById('modalTitle').textContent = item.getAttribute('data-title');
      document.getElementById('modalDesc').textContent = item.getAttribute('data-desc');
      document.getElementById('modalDate').textContent = item.getAttribute('data-date');
      document.getElementById('modalPriority').textContent = item.getAttribute('data-priority');
      document.getElementById('modalCategory').textContent = item.getAttribute('data-category');
      document.getElementById('taskModal').style.display = 'flex';
    });
  });
  
  document.getElementById('closeModal').onclick = function() {
    document.getElementById('taskModal').style.display = 'none';
  };
  
  window.onclick = function(event) {
    if (event.target == document.getElementById('taskModal')) {
      document.getElementById('taskModal').style.display = 'none';
    }
  };
</script>
</body>
</html>