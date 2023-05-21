<?php
session_start();

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

$response = array(); // Initialize the response array

// Check if the avatar form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
    $avatarFile = $_FILES['avatar']['tmp_name'];
    $targetDir = '../images/avatar/';
    $targetFile = $targetDir . basename($_FILES['avatar']['name']);
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Check if the file extension is allowed
    if (in_array($fileExtension, $allowedExtensions)) {
        // Move the uploaded file to the target location
        if (move_uploaded_file($avatarFile, $targetFile)) {
            // Update the session variable with the new avatar path
            $_SESSION['avatar'] = $targetFile;
            // Update the avatar field in the database for the current user
            $userId = $_SESSION['id'];
            $updateSql = "UPDATE users SET avatar = '$targetFile' WHERE id = '$userId'";
            mysqli_query($conn, $updateSql);

            // Set success message in the response
            $response['success'] = true;
            $response['message'] = 'Avatar updated successfully.';
        } else {
            // Set error message in the response
            $response['success'] = false;
            $response['message'] = 'Failed to upload the avatar.';
        }
    } else {
        // Set error message in the response
        $response['success'] = false;
        $response['message'] = 'Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.';
    }
} else {
    // Set error message in the response
    $response['success'] = false;
    $response['message'] = 'Invalid request.';
}

// Close the database connection
mysqli_close($conn);

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
