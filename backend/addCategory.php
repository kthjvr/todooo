<?php

// Start or resume the current session
session_start();


// Check if the user is logged in
if (isset($_SESSION['loggedin'])) {
    // The user is logged in, so retrieve their user ID from the session variable
    $id = $_SESSION['id'];

    // Get the data from the form submission (assuming the data is submitted through POST method)
    $category = $_POST['category'];
    $categoryDetails = $_POST['categoryDetails'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "getItDone");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert the data into the tasks table with the user ID
    $sql = "INSERT INTO categories (id, category, categoryDetails)
    VALUES ('$id', '$category', '$categoryDetails')";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../frontend/category.php");
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);


} else {
    // The user is not logged in, so redirect them to the login page
    header("Location: ../frontend/sign-in.html");
    exit;
}

?>





