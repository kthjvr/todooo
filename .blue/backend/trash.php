<?php

// Start or resume the current session
session_start();


// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    // The user is logged in, so retrieve their user ID from the session variable
    $id = $_SESSION['id'];

    // Get the data from the form submission (assuming the data is submitted through POST method)
    $taskID = $_POST['taskID'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "getItDone");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert the data into the tasks table with the user ID
    $sql = "UPDATE mytasks SET trash='1', currentStatus='Discarded' WHERE taskID='$taskID'";

    if ($conn->query($sql) === TRUE) {
      header("Location: ../frontend/tasks.php");
    } else {
      echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    mysqli_close($conn);


} else {
    // The user is not logged in, so redirect them to the login page
    header("Location: ../frontend/sign-in.php");
    exit;
}

?>