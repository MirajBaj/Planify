<?php require_once 'data1.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add a new task</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="task1style.css">
</head>
<body>
    <div class="sidebar">
        <div>
        <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:100px; display:block; margin: 0 auto 10px auto;">
      </div>
            <nav>
                <ul>
                    <li><span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/></svg>Dashboard</span></li>
                    <li><span><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>User Profile</span></li>
                    <li><a href="newTask.php" class="add-task-link"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>Add Task</a></li>
                    <li><a href="Task/addnote.php" class="menu-item"><span class="nav-icon">&#128221;</span> Add Notes</a></li>
                </ul>
            </nav>
        </div>
        <div class="logout">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </div>
    </div>
    <div class="main-content">
        <div class="main-title">Add a new task</div>
        <div class="main-flex">
            <div class="form-box">
                <?php if ($message): ?>
                    <div class="message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <?php foreach ($errors as $err): ?>
                        <div class="error"><?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="title">Task Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <input type="hidden" name="category_id" id="hidden_category_id" value="<?= htmlspecialchars($selected_category) ?>">
                    <input type="hidden" name="status" id="hidden_status" value="<?= htmlspecialchars($selected_status) ?>">
                    <div class="form-actions">
                        <button type="submit">Save Task</button>
                        <button type="button" class="cancel" onclick="window.location.href='dashboard.php'">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="right-cards">
                <div class="right-card" id="category-card">
                    <div class="card-title">Category <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div>
                    <div class="radio-group">
                        <?php foreach ($categories as $id => $name): ?>
                            <label><input type="radio" name="category_radio" value="<?= $id ?>" <?= ($selected_category == $id) ? 'checked' : '' ?>> <?= htmlspecialchars($name) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="right-card" id="status-card">
                    <div class="card-title">Status <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></div>
                    <div class="radio-group">
                        <?php foreach ($statuses as $val => $label): ?>
                            <label><input type="radio" name="status_radio" value="<?= $val ?>" <?= ($selected_status == $val) ? 'checked' : '' ?>> <?= htmlspecialchars($label) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    
    document.querySelectorAll('input[name="category_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('hidden_category_id').value = this.value;
        });
    });
    document.querySelectorAll('input[name="status_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.getElementById('hidden_status').value = this.value;
        });
    });
    
    if (!document.querySelector('input[name="category_radio"]:checked')) {
        var firstCat = document.querySelector('input[name="category_radio"]');
        if (firstCat) {
            firstCat.checked = true;
            document.getElementById('hidden_category_id').value = firstCat.value;
        }
    }
    if (!document.querySelector('input[name="status_radio"]:checked')) {
        var firstStat = document.querySelector('input[name="status_radio"]');
        if (firstStat) {
            firstStat.checked = true;
            document.getElementById('hidden_status').value = firstStat.value;
        }
    }
    </script>
</body>
</html>