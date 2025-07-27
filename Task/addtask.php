<?php
require('../Planify/db.php');

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
  <link rel="stylesheet" href="../Planify/style.css" />
</head>
<body>
<div class="container">
  <div class="sidebar">
    <div class="logo">
      <img src="../Planify/planify.png" alt="Planify Logo" style="width:100px; display:block; margin: 0 auto 10px auto;" />
    </div>
    <div class="menu">
      <a href="#" class="menu-item">&#9632; Dashboard</a>
      <a href="#" class="menu-item">&#x1F464; User Profile</a>
      <a href="addtask.php" class="menu-item">&#x2795; Add Task</a>
    </div>
    <a href="#" class="logout">&#x21B6; Logout</a>
  </div>
  <div class="main-content">
    <h1>Add a Note</h1>
    <div style="background: #fff; padding: 20px; border-radius: 10px; width: 400px;">
      <form id="taskForm">
        <label>Task Title</label><br />
        <input type="text" id="title" required style="width: 100%; padding: 8px; margin-bottom: 10px;" /><br />
        <label>Description</label><br />
        <textarea id="description" rows="4" style="width: 100%; padding: 8px;"></textarea><br />
        <button type="submit" style="background: #4a90e2; color: white; margin-top: 10px;">Save Task</button>
        <button type="button" onclick="document.getElementById('taskForm').reset();" style="background: gray; color: white; margin-left: 10px;">Cancel</button>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('taskForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;

    axios.post('http://localhost/GroupProject/Task/task.php', {
      title,
      description
    }).then(res => {
      if (res.data.success) {
        alert('Task added successfully!');
        document.getElementById('taskForm').reset();
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
