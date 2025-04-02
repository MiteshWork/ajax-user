<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>User Management</h1>
    <div class="" style="margin-bottom:50px">
        <button id="logoutButton">Logout</button>
        <button id="Register">Register</button>
    </div>

    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Edit User Modal -->
    <div id="userModal" style="display:none;">
        <h2>Edit User</h2>
        <input type="hidden" id="userId">
        <input type="text" id="editName" placeholder="Name">
        <input type="email" id="editEmail" placeholder="Email">
        <button id="saveUser">Save</button>
    </div>

    <!-- View User Modal -->
    <div id="viewUserModal" style="display:none;">
        <h2>User Details</h2>
        <p>ID: <span id="viewUserId"></span></p>
        <p>Name: <span id="viewUserName"></span></p>
        <p>Email: <span id="viewUserEmail"></span></p>
        <button onclick="$('#viewUserModal').hide();">Close</button>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Check login status
        function checkLogin() {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Unauthorized access! Please log in.');
                window.location.href = "/login";
            }
        }

        checkLogin();

        // Logout Function
        $('#logoutButton').on('click', function() {
            localStorage.removeItem('token');
            window.location.href = "/login";
        });

        function fetchUsers() {
            $.ajax({
                url: '/api/users',
                method: 'GET',
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                success: function(response) {
                    $('#userTable').DataTable().destroy();
                    $('#userTable tbody').empty();
                    response.forEach(user => {
                        $('#userTable tbody').append(`
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <button onclick="viewUser(${user.id})">View</button>
                                    <button onclick="editUser(${user.id})">Edit</button>
                                    <button onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                    $('#userTable').DataTable();
                }
            });
        }

        function viewUser(id) {
            $.ajax({
                url: `/api/users/${id}`,
                method: 'GET',
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                success: function(user) {
                    $('#viewUserId').text(user.id);
                    $('#viewUserName').text(user.name);
                    $('#viewUserEmail').text(user.email);
                    $('#viewUserModal').show();
                }
            });
        }

        function editUser(id) {
            $.ajax({
                url: `/api/users/${id}`,
                method: 'GET',
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                success: function(user) {
                    $('#userId').val(user.id);
                    $('#editName').val(user.name);
                    $('#editEmail').val(user.email);
                    $('#userModal').show();
                }
            });
        }

        $('#saveUser').on('click', function() {
            const id = $('#userId').val();
            $.ajax({
                url: `/api/users/${id}`,
                method: 'PUT',
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                data: {
                    name: $('#editName').val(),
                    email: $('#editEmail').val(),
                },
                success: function() {
                    alert('User updated successfully');
                    $('#userModal').hide();
                    fetchUsers();
                }
            });
        });

        function deleteUser(id) {
            $.ajax({
                url: `/api/users/${id}`,
                method: 'DELETE',
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
                success: fetchUsers
            });
        }

        fetchUsers();
    </script>
</body>
</html>
