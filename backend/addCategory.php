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

    // Prepare the SQL statement with placeholders
    $sql = "INSERT INTO categories (id, category, categoryDetails) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "iss", $id, $category, $categoryDetails);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../frontend/category.php");
    } else {
        echo "Error inserting data: " . mysqli_stmt_error($stmt);
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
