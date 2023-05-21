<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>

<?php

// Start or resume the current session
session_start();


// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    // The user is logged in, so retrieve their user ID from the session variable
    $id = $_SESSION['id'];

    // Get the data from the form submission (assuming the data is submitted through POST method)
    $taskID = $_POST['taskID'];
    $taskName = $_POST['task-name'];
    $taskDescription = $_POST['task-description'];
    $endDate = $_POST['endDate'];
    $priority_stat = $_POST['priority_stat'];
    $starred = $_POST['starred'];
    $category = $_POST['category'];


    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "getItDone");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE mytasks SET taskName=?, taskDescription=?, endDate=?, priority_stat=?, starred=?, categoryID=?, trash='0' WHERE taskID=?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssssssi", $taskName, $taskDescription, $endDate, $priority_stat, $starred, $category, $taskID);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        sleep(1);
        header("Location: ../frontend/tasks_v2.php");
    } else {
        echo "Error updating record: " . mysqli_stmt_error($stmt);
    }

    // Close the statement and the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);


} else {
    // The user is not logged in, so redirect them to the login page
    header("Location: ../frontend/sign-in.html");
    exit;
}

?>


