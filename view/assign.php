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
// echo $id;
// Retrieve the task ID from the AJAX request
$taskID = $_POST['taskID'];


// Retrieve the task details from the database based on the task ID
$info_sql ="SELECT * FROM mytasks JOIN categories WHERE categories.categoryID=mytasks.categoryID AND categories.id=mytasks.id 
AND taskID='$taskID' AND mytasks.trash='0' AND mytasks.currentStatus!='Completed'";

    $info_result = mysqli_query($conn, $info_sql);
    

    if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
        while ($row = mysqli_fetch_assoc($info_result)) {
          $selectedOption = $row['category'];
          // $selectedOption1 = $row['category'];
          echo ' <h2 style="color: black;">Assign Task</h2>';
            echo '<div class="form_container">';
              echo '<form method="POST" action="../backend/assign.php">';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-name">Task Name</label>';
                    echo '<input type="text" id="task-name" name="task-name" value="'.$row["taskName"].'" readonly/>';
                    echo '</div>';
                  echo '<div class="form_item" hidden>';
                    echo '<label for="taskID">Task ID</label>';
                    echo '<input type="text" id="taskID" name="taskID" value="'.$row["taskID"].'" readonly/>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="task-description">Task Description</label>';
                    echo '<textarea readonly id="task-description" name="task-description" maxlength="300" readonly>'.$row["taskDescription"].'</textarea>';
                    echo '</div>';
                  echo '</div>';

                echo '<div class="form_wrap form_grp">';
                  echo '<div class="form_item">';
                    echo '<label for="assignee_id">Assign to:</label>';
                    echo '<input type="number" id="assignee_id" name="assignee_id" value="" placeholder="Input user id" required/>';
                    echo '<input style="display:none;" type="text" id="assignedBy" name="assignedBy" value="'.$row["id"].'" required/>';

                  echo '</div>';
                  echo '</div>';
                  

                echo '<div class="btn">
                      <input value="Submit" type="submit" class="submit-button"  readonly>
                      <input value="Close" type="close" class="close-button close-button1" readonly>
                      </div>';

              echo '</form>';
            echo '</div>';
        }
      }
      // Close the database connection again
      mysqli_close($conn);
?>

<!-- this section is for update pop-up window for adding tasks
<div id="popup-taskcontent" class="popup">
  <div class="popup-content" id="task-details">
  </div>
  </div>
</div> -->

<script>

document.querySelector('form').addEventListener('submit', function (event) {
  // Get the task ID from the row
  var taskID = $(this).closest('div#task-details-container').find('.task-id').text();
  var assignee_id = document.querySelector('#assignee_id').value;
  var assignedBy = document.querySelector('#assignedBy').value;
  console.log(assigneeBy);

        $.ajax({
        url: '../backend/assign.php',
        method: 'POST',
        data: { taskID: taskID, assignee_id: assignee_id, assignedBy: assignedBy },
        success: function(response) {
        }
      });
    });
</script>

<script>

var popup2 = document.getElementById("popup-taskcontent"); // Get the pop-up and all buttons that open it
  var close2 = document.getElementsByClassName("close-button1")[0]; // Get the close button and add a click event listener
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

</script>