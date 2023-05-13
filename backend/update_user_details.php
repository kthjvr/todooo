<?php

// Get the user details from the request payload
$request_body = file_get_contents('php://input');
$user_details = json_decode($request_body, true);

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "getitdone";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare the SQL statement
$sql = "UPDATE users SET username=?, email=?, userpassword=? WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $user_details['username'], $user_details['email'], $user_details['userpassword'], $user_details['userid']);

// Execute the SQL statement
if (mysqli_stmt_execute($stmt)) {
    $response = array("status" => "success");
} else {
    $response = array("status" => "error");
}



// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Send the response back to the client
echo json_encode($response);
?>
