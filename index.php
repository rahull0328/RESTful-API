<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP RESTful API CRUD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #6a11cb;
            border: none;
        }
        .btn-danger {
            background-color: #ff4d4d;
            border: none;
        }
        .table th {
            background: #2575fc;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">PHP RESTful API CRUD</h2>
        <div class="card p-4 shadow-sm">
            <form id="userForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add User</button>
            </form>
        </div>
        <div class="mt-4">
            <h4>User List</h4>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php
                    $users = fetchUsers(); // Call the function and store the results
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            echo "<tr>
                                    <td>{$user['id']}</td>
                                    <td>{$user['name']}</td>
                                    <td>{$user['email']}</td>
                                    <td>
                                        <form method='POST' action='config.php'>
                                            <input type='hidden' name='delete_id' value='{$user['id']}'>
                                            <button type='submit' class='btn btn-danger btn-sm'>
                                                <i class='fas fa-trash-alt'></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#userForm").submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                let name = $("#name").val();
                let email = $("#email").val();

                $.ajax({
                    url: "config.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ name: name, email: email }),
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload page to update user list
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            });
        });

        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: "config.php?id=" + id,
                    type: "DELETE",
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    }
                });
            }
        }
    </script>

</body>
</html>
