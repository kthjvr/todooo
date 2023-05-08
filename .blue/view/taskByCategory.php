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

// Close the database connection
mysqli_close($conn);
?>

<?php

$conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
            
session_start();
$id = $_SESSION['id'];

// Retrieve the task ID from the AJAX request
$catID = $_POST['catID'];


// Retrieve the task details from the database based on the task ID
$info_sql = "SELECT * FROM mytasks JOIN categories WHERE categories.categoryID='$catID' AND mytasks.categoryID='$catID' AND categories.id=mytasks.id AND mytasks.trash='0' AND mytasks.currentStatus!='Completed'"; //retrieve info from db
// $info_sql = "SELECT * FROM MyTasks WHERE currentStatus!='Completed' AND categoryID='$catID'"; //retrieve info from db
    $info_result = mysqli_query($conn, $info_sql);

    echo '<table id="myTable">
    <thead>
      <tr>
      <th>Task</th>
      <th>End Date</th>
      <th>Priority</th>
      <th>Status</th>
      <th>Menu</th>
      </tr>
    </thead>';
    

    if (mysqli_num_rows($info_result) > 0) { // Display the information in the table
      while ($row = mysqli_fetch_assoc($info_result)) {
          echo "<tr>";
            echo "<td class='task-id' hidden>".$row["taskID"]."</td>";
            echo "<td>
            <h3>".$row["taskName"]."</h3>
            <p class='description'>".$row["taskDescription"]."</p>
          </td>";
            echo "<td>".$row["endDate"]."</td>";
            echo "<td class='flag'><i class='fas fa-flag' id='flag-priority' data-value='".$row["priority_stat"]."'></i></td>";
            echo "<td>".$row["currentStatus"]."</td>";
            echo "<td><a class='open-menu' ><i class='fas fa-ellipsis-h''></i></a></td>";
          echo "</tr>";
      }
    }else {
      echo "<tr>";
      echo "<td><p>No results</p></td>";
      echo "</tr>";
    }
      // Close the database connection again
      mysqli_close($conn);

      echo '<div class="btn">
      <input value="Close" type="close" class="close-button-1" readonly>
      </div>';
?>

<!-- this section is for update pop-up window for adding tasks-->
<div id="popup-taskcontent" class="popup">
  <div class="popup-content" id="task-details">
  </div>
  </div>
</div>

<script>


$('.action-menu').on('click', function() {
  // Get the task ID from the row
  let inputElement = document.getElementById("task-id");
  let taskID = inputElement.value;
  console.log(taskID);

  // Show the confirmation dialog box
  Swal.fire({
    title: "Edit task?",
    text: "Are you sure you want to edit this task?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, edit the data.",
    cancelButtonText: "No, cancel.",
    confirmButtonColor: "#F999B7",
    reverseButtons: true
  }).then((result) => {
    // If the user confirms the action, delete the data
    if (result.isConfirmed) {
      // Send an AJAX request to delete the task
      $.ajax({
        url: '../view/edit.php',
        method: 'POST',
        data: { taskID: taskID },
        success: function(response) {
        // Display the task details in the pop-up window
        $('#task-details').html(response);
        $('#popup-taskcontent').show();
    }
      });
    }
  });
});

// Add a click event listener to the action menu
$('.delete-button').on('click', function() {
  // Get the task ID from the row
  let inputElement = document.getElementById("task-id");
  let taskID = inputElement.value;
  console.log(taskID);

  // Show the confirmation dialog box
  Swal.fire({
    title: "Move task to trash?",
    text: "Are you sure you want to move this task to the trash?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, move it to trash",
    cancelButtonText: "No, cancel",
    confirmButtonColor: "#F999B7",
    reverseButtons: true
  }).then((result) => {
    // If the user confirms the action, delete the data
    if (result.isConfirmed) {
      // Send an AJAX request to delete the task
      $.ajax({
        url: '../backend/trash.php',
        method: 'POST',
        data: { taskID: taskID },
        success: function(response) {
          Swal.fire({
            title: "Task moved to trash",
            text: "The task has been moved to the trash successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
        },
        error: function() {
          // Handle the error if the AJAX request fails
          Swal.fire({
            title: "Error",
            text: "An error occurred while moving the task to the trash.",
            icon: "error",
            confirmButtonText: "OK"
          });
        }
      });
    }
  });
});


var popup2 = document.getElementById("popup-taskcontent"); // Get the pop-up and all buttons that open it
var buttons2 = document.getElementsByClassName("seeTaskContent");

var close2 = document.getElementsByClassName("close-button-1")[0]; // Get the close button and add a click event listener
close2.addEventListener("click", function() {
  popup2.style.display = "none";
});
</script>