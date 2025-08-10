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
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
  <style>
    .all-notes-container { max-width: min(1200px, 100%); margin: 16px auto; padding: 0 16px; }
    .all-notes-title { font-size: var(--font-size-2xl); font-weight: 700; margin: 8px 0 20px; letter-spacing: -0.5px; }
    .all-notes-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
    .note-item { background: #fff; border-radius: 16px; padding: 20px; box-shadow: var(--shadow-sm); word-break: break-word; transition: transform .15s ease, box-shadow .2s ease; display: flex; flex-direction: column; }
    .note-item:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .note-title { font-weight: 700; font-size: clamp(1rem, .9rem + .5vw, 1.15rem); color: #222; margin-bottom: 6px; }
    .note-content { color: #555; font-size: var(--font-size-sm); margin-bottom: 6px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
    .note-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 10px; }
    .note-date { color: #888; font-size: var(--font-size-xs); }
    .btn { padding: 10px 16px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; min-height: 40px; }
    .btn-danger { background: #e74c3c; color: #fff; }
    .btn-danger:hover { background: #d6453b; }
    @media (max-width: 768px) {
      .all-notes-title { font-size: var(--font-size-xl); text-align: center; }
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
              <div class="note-footer">
                <div class="note-date"><?php echo date('d M, Y', strtotime($note['created_at'])); ?></div>
                <form method="post">
                  <input type="hidden" name="delete_note_id" value="<?php echo $note['note_id']; ?>">
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      
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