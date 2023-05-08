<?php include '../frontend/sidebar.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Montserrat:wght@600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="../css/taskBoard.css">
<script src="../javascript/script.js"></script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "getItDone";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Close the database connection
mysqli_close($conn);
?>


  <!-- Add Task button -->
  <button type="button" class="add-btn myButton" onclick='openForm()'>+ Add task</button>

  <div class="container-fluid">
    <!-- FOR SORT BUTTONS -->
    <div class="container2">
      <label for="sort">Sort:</label>
      <button id="sort-date" class="sort-btn" onclick="sortTableAssending()">Sort by Date (Newest)</button>
      <button id="sort-date" class="sort-btn" onclick="sortTableDescending()">Sort by Date (Oldest)</button>
      <button id="sort-status" class="sort-btn" onclick="sortStatus()">Sort by Status</button>
      <button id="sort-status" class="sort-btn" onclick="sortPriority()">Sort by Priority</button>
      <button id="sort-status" class="sort-btn" onclick="sortCategory()">Sort by Category</button>
    </div>

    <!-- FOR TASK LIST TABLE -->
    <div class="table-container">
      <table id="myTable">
        <thead>
          <tr>
          <th>Category</th>
          <th>Task</th>
          <th>End Date</th>
          <th>Priority</th>
          <th>Status</th>
          <th width=200px>Menu</th>
          </tr>
        </thead>

        <tbody id="table-body">
          <?php 
            $current_date = date('Y-m-d'); // get current date
            $conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
            if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
              
            $id = $_SESSION['id'];
              
            $info_sql = "SELECT * FROM mytasks JOIN categories WHERE categories.categoryID=mytasks.categoryID AND categories.id=$id AND mytasks.id=$id AND mytasks.trash='1' AND mytasks.currentStatus='Discarded'"; //retrieve info from db
            $info_result = mysqli_query($conn, $info_sql);
            
            if (mysqli_num_rows($info_result) > 0) { // Display the information in the table
                while ($row = mysqli_fetch_assoc($info_result)) {
                    echo "<tr>";
                      echo "<td class='task-id' hidden>".$row["taskID"]."</td>";
                      $category_sql = "SELECT * FROM categories JOIN mytasks ON categories.categoryID = mytasks.category;";
                      echo "<td class='category'>".$row["category"]."</td>";
                      echo "<td>
                      <h3>".$row["taskName"]."</h3>
                      <p class='description'>".$row["taskDescription"]."</p>
                    </td>";
                      echo "<td>".$row["endDate"]."</td>";
                      echo "<td class='flag'><i class='fas fa-flag' id='flag-priority' data-value='".$row["priority_stat"]."'></i></td>";
                      echo "<td>".$row["currentStatus"]."</td>";
                      echo "<td>
                            <a class='open-menu'><i class='fas fa-info-circle'></i></a>
                            <div class='dropdown'>
                            <a class='dropbtn' style='margin-left: 5vh'><i class='fas fa-ellipsis-h'></i></a>
                            <div class='dropdown-content'>
                            <a class='restore'>Restore</a>
                            <a class='deletePermanently'>Delete permanently</a>
                            </div>
                          </div>
                            </td>";
                    echo "</tr>";
                }
              }else {
                echo "<tr>";
                echo "<td><p>No results</p></td>";
                echo "</tr>";
              }
              // Close the database connection again
              mysqli_close($conn);
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</section>


<!-- THIS IS FOR UPDATE MODAL -->
<div id="popup-taskcontent" class="popup">
  <div class="popup-content" id="task-details">
    </div>
  </div>
</div>

<!-- THIS IS FOR ADD TASK MODAL -->
<div id="popup" class="popup">
  <div class="popup-content">
    <h2>Add Tasks</h2>
    <div class="form_container">
      <form method="POST" action="../backend/submit.php">
        <div class="form_wrap form_grp">
          <div class="form_item">
            <label for="task-name">Task Name</label>
            <input type="text" placeholder="Title" id="task-name" name="task-name"/>
          </div>
        </div>

        <div class="form_wrap form_grp">
          <div class="form_item">
            <label for="task-description">Task Description</label>
            <textarea id="task-description" name="task-description" placeholder="Add description" maxlength="300"></textarea>
            <p><span class="GFG">300</span> Characters Remaining</p>
          </div>
        </div>

        <div class="form_wrap form_grp">
          <div class="form_item">
            <label for="starred">Marked as important?</label>
              <select id="starred" name="starred">
                  <option selected disabled>Select importance...</option>
                  <option id="yesstarred" for="yesstarred" value="yes">Yes</option>
                  <option id="notStarred" for="notStarred" value="no">No</option>
              </select>
          </div>

          <div class="form_item">
            <label for="priority_stat">Priority</label>
                <select id="priority_stat" name="priority_stat">
                <option selected disabled>Select priority status...</option>
                    <option id="extreme-priority" name="priority_stat" value="extreme">Extreme</option>
                    <option id="high-priority" name="priority_stat" value="high">High</option>
                    <option id="medium-priority" name="priority_stat" value="medium">Medium</option>
                    <option id="low-priority" type="radio" name="priority_stat" value="low">Low</option>
                </select>
          </div>
        </div>

        <div class="form_wrap form_grp">
          <div class="form_item">
            <label>Category</label>
            <select id="category" name="category">
            <option selected disabled>Select category...</option>
            <?php
              $conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
              if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
                  
              $id = $_SESSION['id'];

              // SQL query to retrieve categories from database table
              $info_sql = "SELECT * FROM categories WHERE id = '$id' AND trash='0'";   
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

          <div class="form_item">
            <label for="endDate">End Date</label>
            <?php
              $current_date = date('Y-m-d'); // get current date
              echo '<input type="date" id="endDate" name="endDate" min="'.$current_date.'"/>';
            ?>
          </div>
        </div>

        <div class="btn">
          <input value="Submit" type="submit" class="update-button" readonly>
          <input value="Close" type="close" class="close-button" readonly>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- THIS SECTION IS FOR JAVASCRIPT -->

<!-- THIS IS FOR MOVING TASK TO TRASH -->
<script>
  $('.moveToTrash').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      Swal.fire({
      title: "Move this task to trash?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes.",
      cancelButtonText: "No.",
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
            title: "Task has been moved to trash",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
      }
        });
      }
    });
  });
</script>

<!-- THIS IS SETTING TASK TO INPROGRESS -->
<script>
  $('.setInprogress').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      Swal.fire({
      title: "Set this task to inprogress?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes.",
      cancelButtonText: "No.",
      confirmButtonColor: "#F999B7",
      reverseButtons: true
    }).then((result) => {
      // If the user confirms the action, delete the data
      if (result.isConfirmed) {
        // Send an AJAX request to delete the task
        $.ajax({
          url: '../backend/setInprogress.php',
          method: 'POST',
          data: { taskID: taskID },
          success: function(response) {
            Swal.fire({
            title: "Task updated to in-progress!",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
      }
        });
      }
    });
  });
</script>

<!-- THIS IS FOR SETTING TASK TO STARRED -->
<script>
  $('.setStar').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      Swal.fire({
      title: "Marked as important?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes.",
      cancelButtonText: "No.",
      confirmButtonColor: "#F999B7",
      reverseButtons: true
    }).then((result) => {
      // If the user confirms the action, delete the data
      if (result.isConfirmed) {
        // Send an AJAX request to delete the task
        $.ajax({
          url: '../backend/setStarred.php',
          method: 'POST',
          data: { taskID: taskID },
          success: function(response) {
            Swal.fire({
            title: "Task updated!",
            text: "The task has been marked as important.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
      }
        });
      }
    });
  });
</script>

<!-- THIS IS FOR SETTING TASK TO COMPLETED -->
<script>
  $('.setComplete').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      Swal.fire({
      title: "Task Completed?",
      text: "Set this task status to complete?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes.",
      cancelButtonText: "No.",
      confirmButtonColor: "#F999B7",
      reverseButtons: true
    }).then((result) => {
      // If the user confirms the action, delete the data
      if (result.isConfirmed) {
        // Send an AJAX request to delete the task
        $.ajax({
          url: '../backend/setComplete.php',
          method: 'POST',
          data: { taskID: taskID },
          success: function(response) {
            Swal.fire({
            title: "Task updated!",
            text: "The task has been updated successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
      }
        });
      }
    });
  });
</script>

<!-- THIS IS FOR DROPDOWN MENU -->
<script>
  // Get the dropdown button element
  var dropdownBtn = document.querySelector(".dropbtn");

  // Add a hover event listener to the button
  dropdownBtn.addEventListener("mouseover", function() {
    // Get the dropdown content element
    var dropdownContent = document.querySelector(".dropdown-content");
    
    // Toggle the display of the dropdown content
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });

</script>

<!-- THIS IS FOR DISPLAYING TASK DETAILS -->
<script>
  $('.open-menu').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('tr').find('.task-id').text();

      // Send an AJAX request to retrieve the details of the task
      $.ajax({
        url: '../view/trash-details.php',
        method: 'POST',
        data: { taskID: taskID },
        success: function(response) {
          // Display the task details in the pop-up window
          $('#task-details').html(response);
          $('#popup-taskcontent').show();
        }
      });
  });
</script>

<!-- THIS IS FOR COUNTING INPUT CHARACTER IN TEXT AREA -->
<script>
  $(document).ready(function () {
      var max_length = 300;
      $('textarea').keyup(function () {
          var len = max_length - $(this).val().length;
          $('.GFG').text(len);
      });
  });
</script>

<!-- THIS IS FOR VALIDATING THE ADD TASK FORM -->
<script>
  document.querySelector('form').addEventListener('submit', function (event) {
    if (!validateForm()) {
      event.preventDefault();
    } else {
      Swal.fire({
            title: "task Added",
            text: "The task has been added successfully.",
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

    return formIsValid;
  }
</script>

<!-- THIS IS FOR SORTING CATEGORY IN THE TABLE -->
<script>
  var sortCategoryAscending = true;
    function sortCategory() {
      var table, rows, i, x, y, shouldSwitch;
      table = document.getElementById("myTable");
      shouldSwitch = true;
      
      while (shouldSwitch) {
        shouldSwitch = false;
        rows = table.rows;
        
        for (i = 1; i < (rows.length - 1); i++) {
          x = rows[i].getElementsByTagName("TD")[1];
          y = rows[i + 1].getElementsByTagName("TD")[1];
          
          if (sortCategoryAscending) {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          } else {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
        
        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        }
      }
      
      sortCategoryAscending = !sortCategoryAscending;
    }
</script>

<!-- THIS IS FOR SORTING DATE IN THE TABLE -->
<script>
  function sortTableAssending() {
    var table = document.getElementById("myTable");
    var rows = table.rows;
    var sortedRows = [];
    for (var i = 1; i < rows.length; i++) {
      sortedRows.push(rows[i]);
    }
    sortedRows.sort(function(a, b) {
      var dateA = new Date(a.cells[3].textContent);
      var dateB = new Date(b.cells[3].textContent);
      return dateA - dateB;
    });
    for (var i = 0; i < sortedRows.length; i++) {
      table.appendChild(sortedRows[i]);
    }
  }
  function sortTableDescending() {
    var table = document.getElementById("myTable");
    var rows = table.rows;
    var sortedRows = [];
    for (var i = 1; i < rows.length; i++) {
        sortedRows.push(rows[i]);
    }
    sortedRows.sort(function(a, b) {
        var dateA = new Date(a.cells[3].textContent);
        var dateB = new Date(b.cells[3].textContent);
        return dateB - dateA; // sort in reverse order
    });
    for (var i = 0; i < sortedRows.length; i++) {
        table.appendChild(sortedRows[i]);
    }
  }
</script>

<!-- THIS IS FOR SORTING STATUS IN THE TABLE -->
<script>
  var sortAscending = true;
  function sortStatus() {
    var table, rows, i, x, y, shouldSwitch;
    table = document.getElementById("myTable");
    shouldSwitch = true;
    
    while (shouldSwitch) {
      shouldSwitch = false;
      rows = table.rows;
      
      for (i = 1; i < (rows.length - 1); i++) {
        x = rows[i].getElementsByTagName("TD")[5];
        y = rows[i + 1].getElementsByTagName("TD")[5];
        
        if (sortAscending) {
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        } else {
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
          }
        }
      }
      
      if (shouldSwitch) {
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      }
    }
    
    sortAscending = !sortAscending;
  }
</script>

<!-- THIS IS FOR SORTING STATUS IN THE TABLE -->
<script>
  var sortPriorityAscending = true;
    function sortPriority() {
      var table, rows, i, x, y, shouldSwitch;
      table = document.getElementById("myTable");
      shouldSwitch = true;
      
      while (shouldSwitch) {
        shouldSwitch = false;
        rows = table.rows;
        
        for (i = 1; i < (rows.length - 1); i++) {
          x = rows[i].getElementsByTagName("TD")[4];
          y = rows[i + 1].getElementsByTagName("TD")[4];
          
          if (sortPriorityAscending) {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          } else {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
        
        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        }
      }
      
      sortPriorityAscending = !sortPriorityAscending;
    }
</script>

<!-- THIS IS FOR UPDATING CURRENT STATUS TO COMPLETED -->
<script>
    function updateTaskStatus(taskID, completed) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        }
    };
    xhttp.open("POST", "", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("taskID=" + taskID + "&currentStatus=" + (completed ? "Completed" : "In progress"));
  }
</script>

<!-- THIS IS FOR DISPLAYING, CLOSING THE MODAL/POPUP WINDOW -->
<script>
  var popup = document.getElementById("popup"); // Get the pop-up and all buttons that open it
  var buttons = document.getElementsByClassName("myButton");
  var close = document.getElementsByClassName("close-button")[0]; // Get the close button and add a click event listener
  
  close.addEventListener("click", function() {
    popup.style.display = "none";
  });
  
  for (var i = 0; i < buttons.length; i++) { // Loop through all buttons and add a click event listener to each one
    buttons[i].addEventListener("click", function() {
      popup.style.display = "block";
    });
  }

  var popup2 = document.getElementById("popup-taskcontent"); // Get the pop-up and all buttons that open it
  var buttons2 = document.getElementsByClassName("seeTaskContent");
  
  for (var i = 0; i < buttons2.length; i++) { // Loop through all buttons and add a click event listener to each one
    buttons2[i].addEventListener("click", function() {
      popup2.style.display = "block";
    });
  }
</script>

<!-- THIS IS FOR ASSIGNING COLOR FOR PRIORITY FLAGS -->
<script>
  const icons = document.querySelectorAll('.fa-flag');
  console.log(icons )

  icons.forEach(icon => {
    var value = icon.dataset.value;
    if(value == "extreme"){
        value = "101"
    }
    else if(value == "high"){
        value = "71"
    } else if(value == "medium"){
        value = "41"
    } else if (value == "low"){
        value = "39"
    }

    if(value > 100){
        icon.classList.add('extreme')
    }
    else if (value > 70) {
      icon.classList.add('high');
    }
    else if (value > 40) {
      icon.classList.add('medium');
    }
    else {
      icon.classList.add('low');
    }
  });
</script>

<!-- THIS IS FOR STICKY TABLE HEADER AND ALLOW SCROLL BAR -->
<script>
  var tableHeader = document.querySelector('.table-container table thead'); // Get the table header element
  document.querySelector('.table-container').addEventListener('scroll', function() { // Add a scroll event listener to the table container
    var distanceFromTop = this.getBoundingClientRect().top; // Get the distance of the table container from the top of the document
    tableHeader.style.top = distanceFromTop + 'px'; // Set the top position of the table header to the distance from the top of the document
  });
</script>