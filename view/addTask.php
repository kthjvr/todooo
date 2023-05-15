<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>


<link rel="stylesheet" href="../css/addTask.css">
<script src="../javascript/script.js"></script>

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


  <div class="container-fluid">
  <form method="POST" action="../backend/submit.php">
        <div class="row">
            <h4 for="task-name">Task Name</h4>
            <div class="input-group input-group-icon">
                <input type="text" placeholder="Title" id="task-name" name="task-name"/>
            </div>

            <h4 for="task-description">Task Description</h4>
            <div class="input-group input-group-icon">
                <textarea id="task-description" name="task-description" placeholder="Add description" maxlength="150"></textarea>
                <p>
                    <span class="GFG">150</span> Characters Remaining
                </p>
            </div>

        </div>

        <div class="row">
            <div class="col-half">
                <h4>End Date</h4>
                <div class="input-group">
                    <div class="col" for="end-date">
                        <input type="date" id="end-date" name="end-date"/>
                    </div>
                </div>

            </div>

            <div class="col-half">
                <h4 for="starred">Marked as important?</h4>
                <div class="input-group" id="starred" name="starred">
                    <input id="yesstarred" type="radio" name="starred" value="1" />
                    <label for="yesstarred">Yes</label>
                    
                    <input id="notStarred" type="radio" name="starred" value="0" />
                    <label for="notStarred">No</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-half">
                <h4 for="priority_stat">Priority</h4>
                <div class="input-group" id="priority_stat" name="priority_stat">
                    <input id="extreme-priority" type="radio" name="priority_stat" value="extreme" />
                    <label for="extreme-priority">Extreme</label>

                    <input id="high-priority" type="radio" name="priority_stat" value="high" />
                    <label for="high-priority">High</label>

                    <input id="medium-priority" type="radio" name="priority_stat" value="medium" />
                    <label for="medium-priority">Medium</label>

                    <input id="low-priority" type="radio" name="priority_stat" value="low" />
                    <label for="low-priority">Low</label>
                </div>
            </div>

            <div class="col-half">
                <h4 for="category">Category</h4>
                <div class="custom-select" style="width:300px;" id="category" name="category">
                    <select>
                    <option value="0" disable>Select category:</option>
                        <?php
                        $conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
                        if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
                            
                        $id = $_SESSION['id'];

                        // SQL query to retrieve categories from database table
                        $info_sql = "SELECT * FROM 'categories' WHERE id = '$id'";   
                        $info_result = mysqli_query($conn, $info_sql);
                        
                        if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
                            while ($row = mysqli_fetch_assoc($info_result)) {
                                // echo "<option>".$id."</option>";
                                echo "<option value='".$row["categoryID"]."' name='category' id='".$row["category"]."'>".$row["category"]."</option>";
                            }
                        } else {
                            echo "<option>No results</option>";
                          }
                          // Close the database connection again
                          mysqli_close($conn);
                        ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            <button value="Submit" type="submit" class="update-button" onclick="alert('Added successfuly!')">Submit</button>
            <!-- <a class="update-button" type="submit">Submit</a> -->
        </div>
        
    </form>

  </div>
  </section>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        var max_length = 150;
        $('textarea').keyup(function () {
            var len = max_length - $(this).val().length;
            $('.GFG').text(len);
        });

    });
</script>

<script>
// Validate the form before submitting it
document.querySelector('form').addEventListener('submit', function (event) {
  if (!validateForm()) {
    event.preventDefault();
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
  const endDate = document.querySelector('#end-date').value;
  if (endDate.trim() === '') {
    alert('End date cannot be empty');
    formIsValid = false;
  }

  return formIsValid;
}
</script>