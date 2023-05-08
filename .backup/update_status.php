<?php
// Database connection
$db_host = 'localhost'; 
$db_user = 'root'; 
$db_pass = ''; 
$db_name = 'getItDone'; 

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die("Database connection failed: " . mysqli_connect_error());
}

// Get the task ID and progress value from the AJAX request
$taskID = $_POST['taskID'];
$progressValue = isset($_POST['progressValue']) ? $_POST['progressValue'] : null;

// Update the task status in the database
$conn = new PDO("mysql:host=localhost;dbname=$db_name", "$db_user", "$db_pass");
$stmt = $conn->prepare("UPDATE mytasks SET currentStatus = IF(:progressValue = 100, 'Completed', 'In progress') WHERE taskID = :taskID");
$stmt->bindParam(':progressValue', $progressValue);
$stmt->bindParam(':taskID', $taskID);
$stmt->execute();


?>
