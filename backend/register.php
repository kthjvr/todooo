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
	$avatar = $_POST['avatar'];

	// Check if username is already in use
	$sql = "SELECT * FROM users WHERE username='$username'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		echo "<script>alert('Username already in use.')</script>";
	} else {
		// Insert user data into database
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

            echo "<script>window.location.href='../frontend/sign-in.html'</script>";
		} else {
			echo "Error: " . mysqli_error($conn);
		}
	}

	mysqli_close($conn);
}
?>