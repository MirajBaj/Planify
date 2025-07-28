<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Handle note deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note_id'])) {
    $delete_id = intval($_POST['delete_note_id']);
    $del_stmt = $pdo->prepare("DELETE FROM notes WHERE note_id = ? AND user_id = ?");
    $del_stmt->execute([$delete_id, $user_id]);
    header('Location: allnotes.php');
    exit;
}

$notes_sql = "SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC";
$notes_stmt = $pdo->prepare($notes_sql);
$notes_stmt->execute([$user_id]);
$notes = $notes_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <style>
    .all-notes-container { max-width: 900px; margin: 40px auto; }
    .all-notes-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 32px; }
    .all-notes-list { display: flex; flex-wrap: wrap; gap: 24px; }
    .note-item { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); min-width: 220px; max-width: 320px; flex: 1 1 220px; }
    .note-title { font-weight: 600; font-size: 1.2rem; color: #333; margin-bottom: 10px; word-break: break-word; }
    .note-content { color: #666; font-size: 1rem; margin-bottom: 12px; line-height: 1.5; word-break: break-word; }
    .note-date { color: #999; font-size: 0.9rem; }
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
        <a href="alltasks.php" class="menu-item"><span class="nav-icon">&#128196;</span> All Task</a>
        <a href="allnotes.php" class="menu-item active"><span class="nav-icon">&#128221;</span> All Notes</a>
      </div>
      <a href="logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="all-notes-container">
      <div class="all-notes-title">All Notes</div>
      <div class="all-notes-list">
        <?php if (empty($notes)): ?>
          <div style="color: #888;">No notes found.</div>
        <?php else: ?>
          <?php foreach ($notes as $note): ?>
            <div class="note-item">
              <div class="note-title"><?php echo htmlspecialchars($note['title']); ?></div>
              <div class="note-content"><?php echo htmlspecialchars($note['content']); ?></div>
              <div class="note-date"><?php echo date('d M, Y', strtotime($note['created_at'])); ?></div>
              <form method="post" style="margin-top: 10px;">
                <input type="hidden" name="delete_note_id" value="<?php echo $note['note_id']; ?>">
                <button type="submit" style="background: #e74c3c; color: #fff; border: none; padding: 6px 18px; border-radius: 8px; cursor: pointer; font-size: 1rem;">Delete</button>
              </form>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      
    </div>
  </div>
</body>
</html> 