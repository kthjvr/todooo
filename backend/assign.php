<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "getItDone";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the task ID and assignee ID from the AJAX request
$taskID = $_POST['taskID'];
$assigneeID = $_POST['assignee_id'];
$assignedBy = $_POST['assignedBy'];

// Insert the assigned task into the database
$insert_sql = "INSERT INTO assignments (taskID, assignee_id, assignedBy) VALUES ('$taskID', '$assigneeID', '$assignedBy')";

if (mysqli_query($conn, $insert_sql)) {
    echo '<script>alert("Assigned task successfuly!");</script>';
    header("Location: ../frontend/category.php");
} else {
    echo "Error assigning task: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
