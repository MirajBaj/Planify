<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="sidebar">
    <div class="logo">
      <img src="planify.png" alt="Planify Logo" style="width:100px; display:block; margin: 0 auto 10px auto;">
      
    </div>
      <div class="menu">
        <a href="#" class="menu-item"> <span style="margin-right:8px;">&#9632;</span> Dashboard</a>
      </div>
      <a href="#" class="logout"> <span style="margin-right:8px;">&#x21B6;</span> Logout</a>
    </div>
    <div class="main-content">
      <h1>User Management</h1>
      <table id="userTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <script>
        function fetchUsers() {
          axios.get('http://localhost/planify/data.php')
            .then(response => {
              const users = response.data;
              const tbody = document.querySelector('#userTable tbody');
              tbody.innerHTML = '';
              users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                  <td>${user.user_id}</td>
                  <td>${user.username}</td>
                  <td>${user.email}</td>
                  <td><button onclick=\"deleteUser(${user.user_id})\">Delete</button></td>
                `;
                tbody.appendChild(tr);
              });
            });
        }
        function deleteUser(id) {
          if (!confirm('Are you sure you want to delete this user?')) return;
          axios.delete('http://localhost/planify/data.php', { data: { id } })
            .then(response => {
              if (response.data.success) {
                fetchUsers();
              } else {
                alert('Failed to delete user');
              }
            });
        }
        fetchUsers();
      </script>
    </div>
  </div>
</body>
</html>