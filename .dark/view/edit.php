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
    $info_result = mysqli_query($conn, $info_sql);
    

    if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
        while ($row = mysqli_fetch_assoc($info_result)) {
          $selectedOption = $row['category'];
          // $selectedOption1 = $row['category'];
          echo ' <h2>Edit Task Details</h2>';
            echo '<div class="form_container">';
              echo '<form method="POST" action="../backend/save.php">';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-name">Task Name</label>';
                    echo '<input type="text" id="task-name" name="task-name" value="'.$row["taskName"].'"/>';
                    echo '</div>';
                  echo '<div class="form_item" hidden>';
                    echo '<label for="taskID">Task ID</label>';
                    echo '<input type="text" id="taskID" name="taskID" value="'.$row["taskID"].'" readonly/>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-description">Task Description</label>';
                    echo '<textarea id="task-description" name="task-description" maxlength="300">'.$row["taskDescription"].'</textarea>';
                    echo '</div>';
                  echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="starred">Marked as important?</label>';
                    echo '<select id="starred" name="starred">
                          <option value="">Select importance...</option>
                          <option id="yesstarred" for="yesstarred" value="1">Yes</option>
                          <option id="notStarred" for="notStarred" value="0">No</option>
                          </select>';
                  echo '</div>';


                  echo '<div class="form_item">';
                    echo '<label for="priority_stat">Priority</label>';
                    echo '<select id="priority_stat" name="priority_stat">
                          <option value="">Select priority status...</option>
                          <option id="extreme-priority" name="priority_stat" value="extreme">Extreme</option>
                          <option id="high-priority" name="priority_stat" value="high">High</option>
                          <option id="medium-priority" name="priority_stat" value="medium">Medium</option>
                          <option id="low-priority" type="radio" name="priority_stat" value="low">Low</option>
                          </select>';
                  echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="category">Category</label>';
                    echo '<select id="category" name="category">';
                    
                      $id = $_SESSION['id'];
                      // SQL query to retrieve categories from database table
                      $info_sql = "SELECT * FROM categories WHERE id = '$id'";   
                      $info_result = mysqli_query($conn, $info_sql);
                      if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
                          while ($row = mysqli_fetch_assoc($info_result)) {
                              // echo "<option>".$id."</option>";
                              echo "<option value='".$row["categoryID"]."' name='category' id='".$row["category"]."'>".$row["category"]."</option>";
                          }
                      } else {
                          echo "<option>No results</option>";
                        }
                    echo '</select>';
                  echo '</div>';

                  echo '<div class="form_item">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" name="endDate"/>
                      </div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                echo '<div class="form_item"">';
                    echo '<label for="currentStatus">Status</label>
                    <select id="status" name="currentStatus">
                      <option id="notStarted" name="currentStatus" value="Not Started">Not Started</option>                     
                      <option id="inProgress" name="currentStatus" value="In Progress">In Progress</option>                   
                      <option id="Completed" name="currentStatus" value="Completed">Completed</option>              
                    </select>';
                echo '</div>';
                echo '</div>';

                echo '<div class="btn">
                      <input value="Submit" type="submit" class="update-button">
                      <input value="Close" type="close" class="close-button" readonly>
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

var popup2 = document.getElementById("popup-taskcontent"); // Get the pop-up and all buttons that open it
var buttons2 = document.getElementsByClassName("seeTaskContent");

var close2 = document.getElementsByClassName("close-button")[0]; // Get the close button and add a click event listener
close2.addEventListener("click", function() {
  popup2.style.display = "none";
});

$(document).ready(function () {
    var max_length = 300;
    $('textarea').keyup(function () {
        var len = max_length - $(this).val().length;
        $('.GFG').text(len);
    });

});

    // Validate the form before submitting it
document.querySelector('form').addEventListener('submit', function (event) {
  if (!validateForm()) {
    event.preventDefault();
  } else{
    Swal.fire({
            title: "Task updated!",
            text: "The task has been updated successfully.",
            icon: "success",
            showConfirmButton: false
          })
  }
});

function validateForm() {
  let formIsValid = true;

  // Check if task name is empty
  const taskName = document.querySelector('#task-name').value;
  if (taskName.trim() === '') {
    alert('Task name cannot be empty');
    formIsValid = false;
  }

  // Check if task description is empty
  const taskDescription = document.querySelector('#task-description').value;
  if (taskDescription.trim() === '') {
    alert('Task description cannot be empty');
    formIsValid = false;
  }

  // Check if end date is empty
  const endDate = document.querySelector('#endDate').value;
  if (endDate.trim() === '') {
    alert('End date cannot be empty');
    formIsValid = false;
  }

  const starred = document.querySelector('#starred').value;
  if (starred.trim() === '') {
    alert('Marked as important cannot be empty');
    formIsValid = false;
  }

  // const priority_stat = document.querySelector('#priority_stat').value;
  // if (priority_stat.trim() === '') {
  //   alert('Priority Status cannot be empty');
  //   formIsValid = false;
  // }

  return formIsValid;
}
</script>