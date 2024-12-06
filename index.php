<?php
$apiUrl = "http://localhost/restApi/config.php/phpdemo";

$curl = curl_init($apiUrl);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPGET, true);

// Execute the request
$response = curl_exec($curl);

if ($response === false) {
    die("cURL Error: " . curl_error($curl));
}

curl_close($curl);

// Decode the JSON response
$users = json_decode($response, true);

// Check if decoding was successful
if ($users === null) {
    die("JSON Decode Error: " . json_last_error_msg() . " | Raw Response: " . $response);
}

// Access the data key
if (isset($users['data'])) {
    $users = $users['data'];
} else {
    die("No data found in the response.");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
</head>
<body align="center">
<h1>Users List</h1>
<?php if (empty($users)): ?>
    <p>No users found.</p>
<?php else: ?>
    <table border="1" align="center">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo $user['email']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
