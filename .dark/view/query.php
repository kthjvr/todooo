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
$taskID = $_POST['taskID'];


// Retrieve the task details from the database based on the task ID
$info_sql ="SELECT * FROM mytasks JOIN categories WHERE categories.categoryID=mytasks.categoryID AND categories.id=mytasks.id AND taskID='$taskID' AND mytasks.trash='0' AND mytasks.currentStatus!='Completed'";
// $info_sql = "SELECT * FROM MyTasks WHERE currentStatus!='Completed' AND taskID='$taskID'"; //retrieve info from db
    $info_result = mysqli_query($conn, $info_sql);
    

    if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
        while ($row = mysqli_fetch_assoc($info_result)) {
          $selectedOption = $row['category'];
          // $selectedOption1 = $row['category'];
          echo ' <h2>Task Details</h2>';
            echo '<div class="form_container">';
            
                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-name">Task Name</label>';
                    echo '<input  type="text" id="task-name" name="task-name" value="'.$row["taskName"].'" readonly/>';
                    echo '</div>';
                  echo '<div class="form_item" hidden>';
                    echo '<label for="taskID">Task ID</label>';
                    echo '<input class="task-id" type="text" id="task-id" name="taskID" value="'.$row["taskID"].'" readonly/>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-description">Task Description</label>';
                    echo '<textarea id="task-description" name="task-description" maxlength="300" readonly>'.$row["taskDescription"].'</textarea>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="starred">Marked as important?</label>';
                    echo '<input type="text" id="task-name" name="task-name" value="'.$row["starred"].'" readonly/>';
                    echo '</div>';
                  echo '<div class="form_item">';
                    echo '<label for="priority_stat">Priority</label>';
                    echo '<input type="text" id="task-name" name="task-name" value="'.$row["priority_stat"].'" readonly/>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="category">Category</label>';
                    echo '<input type="text" value="'.$row["category"].'" readonly/>';
                    echo '</div>';

                  echo '<div class="form_item">
                        <label for="end-date">End Date</label>
                        <input type="date" id="end-date" name="end-date" value="'.$row["endDate"].'" readonly />
                        </div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                echo '<div class="form_item"">';
                    echo '<label for="currentStatus">Status</label> <input type="text" value="'.$row["currentStatus"].'" readonly />';
                echo '</div>';
                echo '</div>';

                echo '<div class="btn">
                      <input value="Edit" type="submit" class="action-menu">
                      <input value="Close" type="close" class="close-button" readonly>
                      <input value="Delete" type="submit" class="delete-button">
                      </div>';

              echo '</form>';
            echo '</div>';
        }
      }
      // Close the database connection again
      mysqli_close($conn);
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

var close2 = document.getElementsByClassName("close-button")[0]; // Get the close button and add a click event listener
close2.addEventListener("click", function() {
  popup2.style.display = "none";
});
</script>