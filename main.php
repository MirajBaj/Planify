<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
  <h1>User Management</h1>
  <table border="1" id="userTable">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Age</th><th>Action</th></tr>
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
              <td>${user.id}</td>
              <td>${user.name}</td>
              <td>${user.age}</td>
              <td><button onclick="deleteUser(${user.id})">Delete</button></td>
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

    // Initial load
    fetchUsers();
  </script>
</body>
</html>