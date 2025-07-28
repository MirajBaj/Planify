<?php
require '../db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add a Note</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>" />
</head>
<body>
<div class="container">
  <div class="sidebar">
    <div class="logo">
      <img src="../planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;" />
    </div>
    <div class="menu">
      <a href="../tempmain.php" class="menu-item"><span class="nav-icon">&#9632;</span> Dashboard</a>
      <a href="../alltasks.php" class="menu-item"><span class="nav-icon">&#128196;</span> All Task</a>
      <a href="../allnotes.php" class="menu-item"><span class="nav-icon">&#128221;</span> All Notes</a>
    </div>
    <a href="../logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
  </div>
  <div class="main-content">
    <h1>Add a Note</h1>
    <div style="background: #fff; padding: 32px; border-radius: 16px; width: 600px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
      <form id="noteForm">
        <label style="font-size: 1.2rem; margin-bottom: 8px;">Note Title</label><br />
        <input type="text" id="title" required style="width: 100%; padding: 14px; margin-bottom: 18px; font-size: 1.1rem; border-radius: 8px; border: 1px solid #ccc;" /><br />
        <label style="font-size: 1.2rem; margin-bottom: 8px;">Note Content</label><br/>
        <textarea id="description" rows="7" style="width: 100%; padding: 14px; font-size: 1.05rem; border-radius: 8px; border: 1px solid #ccc; margin-bottom: 18px;"></textarea><br />
        <button type="submit" style="background: #4a90e2; color: white; margin-top: 10px; font-size: 1.1rem; padding: 12px 32px; border-radius: 8px;">Save Note</button>
        <button type="button" onclick="document.getElementById('noteForm').reset();" style="background: gray; color: white; margin-left: 16px; font-size: 1.1rem; padding: 12px 32px; border-radius: 8px;">Cancel</button>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('noteForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    axios.post('http://localhost/planify/Task/note.php', {
      title,
      description
    }).then(res => {
      if (res.data.success) {
        alert('Note added successfully!');
        document.getElementById('noteForm').reset();
      } else {
        alert('Error: ' + res.data.error);
      }
    }).catch(err => {
      alert('Error: ' + err.message);
    });
  });
</script>
</body>
</html>
