<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planify Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;500;400&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="sidebar">
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:100px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu">
        <a href="#" class="menu-item"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="#" class="menu-item"><span class="nav-icon">&#128100;</span> User Profile</a>
        <a href="#" class="menu-item"><span class="nav-icon">&#43;</span> Add Task</a>
      </div>
      <a href="#" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="main-content">
      <?php
        // Example PHP usage: dynamic username
        $default_username = 'Ashraya';
        $user = isset($_GET['user']) ? htmlspecialchars($_GET['user']) : $default_username;
      ?>
      <div class="greeting">
        <h1><span style="font-weight:700;">Good Afternoon</span><br><span style="font-weight:700;"><?php echo $user; ?></span></h1>
      </div>
      <div class="dashboard-grid">
        <div class="my-task-card">
          <div class="card-header">
            <span class="card-title">My Task</span>
            <span class="card-add">+</span>
          </div>
          <div class="task-filters">
            <button class="filter-btn active">All</button>
            <button class="filter-btn">Work</button>
            <button class="filter-btn">Study</button>
            <button class="filter-btn">Personal</button>
          </div>
          <div class="task-list">
            <div class="task-item">
              <div class="task-title">CREATE NEW PLAYER GAMES YESESYSE</div>
              <div class="task-desc">English Review</div>
              <div class="task-date">19 March,2025</div>
              <span class="task-priority medium">Medium</span>
            </div>
            <div class="task-item">
              <div class="task-title">CREATE NEW PLAYER GAMES YESESYSE</div>
              <div class="task-desc">English Review</div>
              <div class="task-date">19 March,2025</div>
              <span class="task-priority medium">Medium</span>
            </div>
            <div class="task-item">
              <div class="task-title">CREATE NEW PLAYER GAMES YESESYSE</div>
              <div class="task-desc">English Review</div>
              <div class="task-date">19 March,2025</div>
              <span class="task-priority personal">Personal</span>
            </div>
            <div class="task-item">
              <div class="task-title">CREATE NEW PLAYER GAMES YESESYSE</div>
              <div class="task-desc">English Review</div>
              <div class="task-date">19 March,2025</div>
              <span class="task-priority medium">Medium</span>
            </div>
            <div class="task-item">
              <div class="task-title">CREATE NEW PLAYER GAMES YESESYSE</div>
              <div class="task-desc">English Review</div>
              <div class="task-date">19 March,2025</div>
              <span class="task-priority work">Medium</span>
            </div>
          </div>
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
          <span class="card-title">My Task</span>
        </div>
      </div>
    </div>
  </div>
</body>
</html>