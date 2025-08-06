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
  <link rel="stylesheet" href="../addnote.css?v=<?php echo time(); ?>" />

</head>
<body>
<div class="container">
  <div class="sidebar">
    <div class="logo">
      <img src="../planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;" />
    </div>
    <div class="menu">
      <a href="../tempmain.php" class="menu-item"><span class="nav-icon">&#9632;</span> Dashboard</a>
      <a href="../alltasks.php" class="menu-item"><span class="nav-icon">&#128196;</span> All Tasks</a>
      <a href="../allnotes.php" class="menu-item active"><span class="nav-icon">&#128221;</span> All Notes</a>
    </div>
    <a href="../logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
  </div>
  
  <div class="main-content">
    <div class="add-note-container">
      <h1 class="page-title">Add a Note</h1>
      <div class="note-form-card">
        <form id="noteForm">
          <div class="form-group">
            <label for="title" class="form-label">Note Title</label>
            <input type="text" id="title" class="form-input" required placeholder="Enter note title..." />
          </div>
          
          <div class="form-group">
            <label for="description" class="form-label">Note Content</label>
            <textarea id="description" class="form-textarea" rows="7" placeholder="Write your note content here..."></textarea>
          </div>
          
          <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Save Note</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('noteForm').reset();">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('noteForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    if (!title.trim()) {
      alert('Please enter a note title');
      return;
    }

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