<?php


// Start or resume the current session
session_start();


// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    // The user is logged in, so retrieve their user ID from the session variable
    $id = $_SESSION['id'];
    echo $id;

    // Get the data from the form submission (assuming the data is submitted through POST method)
    $taskName = $_POST['task-name'];
    $taskDescription = $_POST['task-description'];
    $endDate = $_POST['end-date'];
    $priority = $_POST['priority'];
    $currentStatus = $_POST['currentStatus'];
    $starred = $_POST['starred'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "getItDone");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert the data into the tasks table with the user ID
    $sql = "INSERT INTO MyTasks (id, taskName, taskDescription, endDate, priority, currentStatus, starred)
    VALUES ('$id', '$taskName', '$taskDescription', '$endDate', '$priority', '$currentStatus', '$starred')";
    if (mysqli_query($conn, $sql)) {
        echo "Data inserted successfully";
        echo $id;
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);


} else {
    // The user is not logged in, so redirect them to the login page
    echo $id;
    header("Location: sign-in.php");
    exit;
}



?>