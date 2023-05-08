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

    // Generate JavaScript code to display a SweetAlert message

    // Insert the data into the tasks table with the user ID
    $sql = "UPDATE mytasks SET taskName='$taskName', taskDescription='$taskDescription', endDate='$endDate', priority_stat='$priority_stat', starred='$starred', categoryID='$category', trash='0' WHERE taskID='$taskID'";

    if ($conn->query($sql) === TRUE) {
      sleep(1);
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

