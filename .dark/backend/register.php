<?php
// Database connection
$db_host = 'localhost'; // Change to your database hostname
$db_user = 'root'; // Change to your database username
$db_pass = ''; // Change to your database password
$db_name = 'getItDone'; // Change to your database name

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die("Database connection failed: " . mysqli_connect_error());
}

// Form submission processing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$userpassword = $_POST['userpassword'];
	$email = $_POST['email'];

	// Check if username is already in use
	$sql = "SELECT * FROM users WHERE username='$username'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		echo "<script>alert('Username already in use.')</script>";
	} else {
		// Insert user data into database
		$sql = "INSERT INTO users (username, userpassword, email) VALUES ('$username', '$userpassword', '$email')";
		if (mysqli_query($conn, $sql)) {
			echo "<script>alert('Registered successfully!')</script>";
            echo "<script>window.location.href='../frontend/sign-in.php'</script>";
		} else {
			echo "Error: " . mysqli_error($conn);
		}
	}

	mysqli_close($conn);
}
?>