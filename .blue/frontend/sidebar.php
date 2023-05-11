<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.html');
	exit;
}
$_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://www.cssscript.com/demo/sticky.css" rel="stylesheet" type="text/css">
    
    <script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"rel="stylesheet"/>

    <link rel="stylesheet" href="../css/style.css" type="text/css"/>
    
    <title>Todooo</title>
      <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  </head>

  <body>

    <div class="container">
      <aside class="sidebar">
        <ul class="menu-list">
          <li>
            <div class="menu-container">
              <button class="icon" id="menu">
                <i class="fas fa-bars"></i>
              </button>
            </div>
          </li>
          <li>
            <button class="icon" id="dashboard">
              <i class="fa fa-home"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="search">
              <i class="fas fa-file-alt"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="today">
            <i class="fas fa-calendar"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="important">
            <i class="fas fa-star"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="category">
            <i class="fas fa-th-large"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="booklog">
            <i class="fas fa-archive"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="trash">
            <i class="fas fa-trash"></i>
            </button>
          </li>
          <li hidden>
            <button class="icon" id="add">
            <i class="fas fa-plus-square"></i>
            </button>
          </li>
        </ul>
        <div class="logout-container">
          <button class="icon-logout" href="../index.html">
          <i class="fas fa-sign-out-alt" href="../index.html"></i>
          </button>
        </div>
      </aside>

      <section class="main">
      <img src="../images/love.png" alt="greetings" class='love-cat'>
      <h3 class="greetings">Hi,  <?=$_SESSION['username']?>!</h3>

      <?php
        // Connect to the database
        $servername = "localhost"; // server name
        $username = "root"; // username
        $password = ""; // password
        $dbname = "getItDone"; // database name
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

      ?>

      <?php
        $id = $_SESSION['id'];

          $sql = "SELECT priority_stat, COUNT(*) AS count FROM mytasks WHERE id = $id AND trash='0' AND currentStatus!='Completed' AND currentStatus!='Discarded' GROUP BY priority_stat";
          $result = $conn->query($sql);

        
          // initialize counts for each priority level to 0
          $extreme_count = 0;
          $high_count = 0;
          $medium_count = 0;
          $low_count = 0;
          
          // loop through the result set and update the counts
          while($row = $result->fetch_assoc()) {
            switch($row["priority_stat"]) {
              case "extreme":
                $extreme_count = $row["count"];
                break;
              case "high":
                $high_count = $row["count"];
                break;
              case "medium":
                $medium_count = $row["count"];
                break;
              case "low":
                $low_count = $row["count"];
                break;
            }
          }
          
          // display the counts for each priority_stat level
          echo "<div class='container-1'>";
            echo "<div class='box'>";
            echo "<span class='label'>Extreme Priority</span>";
            echo "<span class='count'>$extreme_count</span>";
            echo "</div>";

            echo "<div class='box'>";
            echo "<span class='label'>High Priority</span>";
            echo "<span class='count'>$high_count</span>";
            echo "</div>";

            echo "<div class='box'>";
            echo "<span class='label'>Medium Priority</span>";
            echo "<span class='count'>$medium_count</span>";
            echo "</div>";

            echo "<div class='box'>";
            echo "<span class='label'>Low Priority</span>";
            echo "<span class='count'>$low_count</span>";
            echo "</div>";

            $current_date = date('Y-m-d'); // get current date
            $sql2 = "SELECT COUNT(*) AS count FROM mytasks WHERE id = $id AND currentStatus!='Discarded' AND currentStatus!='Completed' AND endDate = '$current_date'";
            $result = $conn->query($sql2);

            $row = $result->fetch_assoc();
            $count = $row['count'];

            echo "<div class='box'>";
            echo "<span class='label'>Today</span>";
            echo "<span class='count'>$count</span>";
            echo "</div>";
          echo "</div>";
          
      ?>

      <!-- THIS IS FOR PROFILE MODAL -->
    <div id="account" class="popup">
      <div class="popup-content" id="account-details">
        </div>
      </div>
    </div>


  <script src="../javascript/script.js"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-LLWL5N9CSM');
  </script>

<!-- THIS IS FOR DISPLAYING TASK DETAILS -->
<script>
  $('.open-menu').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      // Send an AJAX request to retrieve the details of the task
      $.ajax({
        url: '../view/account.php',
        method: 'POST',
        data: { taskID: taskID },
        success: function(response) {
          // Display the task details in the pop-up window
          $('#account-details').html(response);
          $('#account').show();
        }
      });
  });
</script>

<script>
  var popup = document.getElementById("account"); // Get the pop-up and all buttons that open it
  var buttons = document.getElementsId("logout-photo");

  for (var i = 0; i < buttons.length; i++) { // Loop through all buttons and add a click event listener to each one
    buttons[i].addEventListener("click", function() {
      popup.style.display = "block";
    });
  }
</script>