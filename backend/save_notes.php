<?php
// Retrieve the note content and userID from the AJAX request
$noteContent = $_POST['noteContent'];
$id = $_POST['id'];

// Database connection details
$host = 'localhost';
$dbName = 'getitdone';
$username = 'root';
$password = '';

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to update the notes in the database
$sql = "UPDATE mynotes SET noteContent = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $noteContent, $id);
$stmt->execute();

// Check for errors during query execution
if ($stmt->error) {
    die("Error updating notes: " . $stmt->error);
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// Send a success response to the client-side
$response = array('status' => 'success');
echo json_encode($response);
exit; // Make sure to exit after sending the response
?>
