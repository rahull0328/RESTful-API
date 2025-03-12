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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./assets/css/styles.css">
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
                    $users = fetchUsers();
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            echo "<tr>
                                    <td>{$user['id']}</td>
                                    <td>{$user['name']}</td>
                                    <td>{$user['email']}</td>
                                    <td>
                                        <form method='POST' action='config.php' style='display:inline-block;'>
                                            <input type='hidden' name='delete_id' value='{$user['id']}'>
                                            <button type='submit' class='btn btn-danger btn-sm'>
                                                <i class='fas fa-trash-alt'></i> Delete
                                            </button>
                                        </form>
                                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal{$user['id']}'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                    </td>
                                </tr>";

                            // Edit Modal for each user
                            echo "<div class='modal fade' id='editModal{$user['id']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='editModalLabel'>Edit User</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <form method='POST' action='config.php'>
                                                    <input type='hidden' name='update_id' value='{$user['id']}'>
                                                    <div class='mb-3'>
                                                        <label for='name' class='form-label'>Name</label>
                                                        <input type='text' class='form-control' name='name' value='{$user['name']}' required>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='email' class='form-label'>Email</label>
                                                        <input type='email' class='form-control' name='email' value='{$user['email']}' required>
                                                    </div>
                                                    <button type='submit' class='btn btn-primary w-100'>Update User</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
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
                event.preventDefault();

                let name = $("#name").val();
                let email = $("#email").val();

                $.ajax({
                    url: "config.php",
                    type: "POST",
                    data: { name: name, email: email }, // Send as regular form data
                    success: function(response) {
                        try {
                            let res = JSON.parse(response); // Ensure valid JSON response
                            alert(res.message); // Show success message
                            location.reload(); // Reload page to update user list
                        } catch (e) {
                            console.log("Invalid JSON response:", response);
                            alert("An error occurred, please check the server response.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                        alert("Something went wrong!");
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
