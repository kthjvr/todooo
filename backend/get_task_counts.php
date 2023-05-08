<?php
        // Connect to the database
        $severname = 'localhost'; // Change to your database host
        $username = 'root'; // Change to your database username
        $password = ''; // Change to your database password
        $database = 'getItDone'; // Change to your database name

        $mysqli = new mysqli($servername, $username, $password, $database);

        if ($mysqli->connect_errno) {
          die('Failed to connect to database: ' . $mysqli->connect_error);
        }

        $id = $_SESSION['id'];

        // Define the user ID to retrieve the counts for
        $user_id = $id; // Change to the user ID you want to retrieve the counts for

        // Define the SQL query to retrieve the counts for the user ID
        $sql = "SELECT priority, COUNT(*) as count FROM tasks WHERE user_id = '$user_id' GROUP BY priority";

        // Execute the SQL query and retrieve the results
        $result = mysqli_query($conn, $sql);

        // Create an empty array to store the counts
        $counts = array("High" => 0, "Medium" => 0, "Low" => 0, "Urgent" => 0, "On Hold" => 0);

        // Loop through the results and update the counts array
        while ($row = mysqli_fetch_assoc($result)) {
          $priority = $row["priority"];
          $count = $row["count"];
          $counts[$priority] = $count;
        }

        // Return the counts as a JSON-encoded string
        echo json_encode($counts);
?>