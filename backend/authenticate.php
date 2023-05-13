<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'getItDone';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['userpassword']) ) {
	exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT userpassword FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userpassword);
        $stmt->fetch();
        // Verify the password.
        if ($_POST['userpassword'] === $userpassword) {
            // Create sessions
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            // Query the database for the primary key data you want to retrieve
            $query = "SELECT id FROM users WHERE username = '".$_SESSION['username']."'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['id'] = $row['id'];

            // Query the database for the primary key data you want to retrieve
            $query = "SELECT email FROM users WHERE username = '".$_SESSION['username']."'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $row['email'];

            // Query the database for the primary key data you want to retrieve
            $query = "SELECT avatar FROM users WHERE username = '".$_SESSION['username']."'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['avatar'] = $row['avatar'];

            // Query the database for the primary key data you want to retrieve
            $query = "SELECT userpassword FROM users WHERE username = '".$_SESSION['username']."'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['userpassword'] = $row['userpassword'];
            
            echo "<script>window.location.href='../frontend/dashboard.php'</script>";
        } else {
            // Incorrect password
            echo 'Incorrect password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username!';
    }


	$stmt->close();
}
?>

