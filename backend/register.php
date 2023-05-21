<?php
$db_host = 'localhost'; 
$db_user = 'root'; 
$db_pass = ''; 
$db_name = 'getItDone'; 

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Form submission processing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $userpassword = $_POST['userpassword'];
    $email = $_POST['email'];
    $avatar = '';

    // Check if username is already in use
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username already in use.')</script>";
    } else {
        // Handle avatar upload
        if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarFile = $_FILES['avatar']['tmp_name'];
            $targetDir = '../images/avatar/';
            $targetFile = $targetDir . basename($_FILES['avatar']['name']);
            $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Check if the file extension is allowed
            if (in_array($fileExtension, $allowedExtensions)) {
                // Move the uploaded file to the target location
                if (move_uploaded_file($avatarFile, $targetFile)) {
                    $avatar = $targetFile;
                } else {
                    echo "<script>alert('Failed to upload the avatar.')</script>";
                    // Handle the error case
                }
            } else {
                echo "<script>alert('Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.')</script>";
                // Handle the error case
            }
        }
        // Insert user data into the database
        $sql = "INSERT INTO users (username, userpassword, email, avatar) VALUES ('$username', '$userpassword', '$email', '$avatar')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registered successfully!')</script>";

            $query = "SELECT id FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $id = $row['id'];

            $query = "INSERT INTO categories (category, categoryDetails) VALUES ('General', 'This is the default category')";
            mysqli_query($conn, $query);
            $categoryID = mysqli_insert_id($conn);
            $query = "UPDATE categories SET id = $id WHERE categoryID = $categoryID";
            mysqli_query($conn, $query);

            $initialNote = "INSERT INTO mynotes (noteContent) VALUES ('Write something here...')";
            mysqli_query($conn, $initialNote);
            $noteID = mysqli_insert_id($conn);
            $query = "UPDATE mynotes SET id = $id WHERE noteID = $noteID";
            mysqli_query($conn, $query);

            echo "<script>window.location.href='../frontend/sign-in.html'</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }


    mysqli_close($conn);
}
?>
