<?php require_once 'data1.php'; ?>
<?php
$message = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (strlen($title) > 100) {
        $errors[] = "Task title must not exceed 100 characters.";
    }
    if (strlen($description) > 500) {
        $errors[] = "Task description must not exceed 500 characters.";
    }

}
?>
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
      <div class="logo">
        <img src="planify.png" alt="Planify Logo" style="width:150px; display:block; margin: 0 auto 10px auto;">
      </div>
      <div class="menu">
        <a href="tempmain.php" class="menu-item"><span class="nav-icon">&#9632</span> Dashboard</a>
        <a href="alltasks" class="menu-item"><span class="nav-icon">&#128100;</span> All Task</a>
        <a href="allnotes.php" class="menu-item"><span class="nav-icon">&#43;</span> All Notes</a>
      </div>
      <a href="logout.php" class="logout"><span style="margin-right:8px;">&#x21B6;</span> Logout</a>
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
                        <input type="text" id="title" name="title" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" maxlength="500"></textarea>
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