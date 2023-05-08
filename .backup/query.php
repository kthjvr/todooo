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
echo $taskID;

// Retrieve the task details from the database based on the task ID
$info_sql = "SELECT * FROM MyTasks WHERE currentStatus!='Completed' AND taskID='$taskID'"; //retrieve info from db
    $info_result = mysqli_query($conn, $info_sql);
    

    if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
        while ($row = mysqli_fetch_assoc($info_result)) {
          $selectedOption = $row['category'];
          // $selectedOption1 = $row['category'];
            echo '<div class="form_container">';
              echo '<div class="form_wrap form_grp">';
                echo '<div class="form_item">';
                  echo '<label for="task-name">Task Name</label>';
                  echo '<input type="text" id="task-name" name="task-name" value="'.$row["taskName"].'"/>';
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
                        <option id="extreme-priority" name="priority" value="extreme">Extreme</option>
                        <option id="high-priority" name="priority" value="high">High</option>
                        <option id="medium-priority" name="priority" value="medium">Medium</option>
                        <option id="low-priority" type="radio" name="priority" value="low">low</option>
                        </select>';
                echo '</div>';
              echo '</div>';

              echo '<div class="form_wrap form_grp">';
                echo '<div class="form_item">';
                  echo '<label for="category">Category</label>';
                  echo '<select id="category" name="category">';
                    $id = $_SESSION['id'];
                    // SQL query to retrieve categories from database table
                    $info_sql = "SELECT category FROM categories WHERE id = '$id'";   
                    $info_result = mysqli_query($conn, $info_sql);
                    if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
                        while ($row = mysqli_fetch_assoc($info_result)) {
                            // echo "<option>".$id."</option>";
                            echo "<option value='".$row["category"]."' name='".$row["category"]."' id='".$row["category"]."'>".$row["category"]."</option>";
                        }
                    } else {
                        echo "<option>No results</option>";
                      }
                  echo '</select>';
                echo '</div>';

                echo '<div class="form_item">
                <label for="end-date">End Date</label>
                <input type="date" id="end-date" name="end-date"/>
              </div>';
              echo '</div>';


            echo '</div>';
        }
      }
      // Close the database connection again
      mysqli_close($conn);
?>

<script>

const selectElement = document.getElementById('category');
const optionToSelect = selectElement.querySelector(`option[value="${<?php echo $selectedOption; ?>}"]`);

if (optionToSelect) {
  optionToSelect.selected = true;
}

</script>