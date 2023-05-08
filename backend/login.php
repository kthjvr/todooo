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


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the username and password from the form
  $username = $_POST['username'];
  $userpassword = $_POST['userpassword'];

  // Prepare the SQL statement to retrieve the user's credentials
  $query = "SELECT userpassword FROM users WHERE username = '$username'";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // If the username is found in the database
  if (mysqli_num_rows($result) == 1) {
    // Retrieve the password hash from the result
    $row = mysqli_fetch_assoc($result);
    $userpassword_hash = $row['userpassword'];

    // Verify the password hash with the provided password
    if (password_verify($userpassword, $userpassword_hash)) {
      // Redirect the user to the dashboard page
      echo "<script>window.location.href='../public/dashboard.php'</script>";
      exit;
    }
  }

  // If the username or password is incorrect
  echo "<script>alert('Invalid username or password')</script>";
  echo "<script>window.location.href='sign-in.php'</script>";

}
?>
