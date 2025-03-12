<?php

$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST" && isset($_POST['name']) && isset($_POST['email'])) {
    addUser($_POST['name'], $_POST['email']);
} elseif ($method == "POST" && isset($_POST['delete_id'])) {
    deleteUser($_POST['delete_id']);
}

function fetchUsers()
{
    global $conn;
    $result = $conn->query("SELECT * FROM users");
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users; // Return as an array instead of echoing JSON
}

function addUser($name, $email)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding user";
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
