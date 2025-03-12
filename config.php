<?php

$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    if (isset($_POST['name']) && isset($_POST['email']) && !isset($_POST['update_id'])) 
    {
        addUser($_POST['name'], $_POST['email']);
    } elseif (isset($_POST['update_id']) && isset($_POST['name']) && isset($_POST['email'])) 
    {
        updateUser($_POST['update_id'], $_POST['name'], $_POST['email']);
    } elseif (isset($_POST['delete_id'])) 
    {
        deleteUser($_POST['delete_id']);
    }
}

function fetchUsers()
{
    global $conn;
    $result = $conn->query("SELECT * FROM users");
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users; // Return array
}

function addUser($name, $email)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding user"]);
    }
    $stmt->close();
    exit(); // Ensure no extra HTML is returned
}


function updateUser($id, $name, $email)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating user";
    }
    $stmt->close();
}

function deleteUser($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting user";
    }
    $stmt->close();
}
?>
