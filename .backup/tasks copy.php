<?php include '../public/sidebar.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="taskBoard.css">
<script src="script.js"></script>

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
    <div class="container2">
      <label for="sort">Sort:</label>
      <button id="sort-date" class="sort-btn" onclick="sortTableAssending()">Sort by Date (Newest)</button>
      <button id="sort-date" class="sort-btn" onclick="sortTableDescending()">Sort by Date (Oldest)</button>
      <button id="sort-status" class="sort-btn" onclick="sortStatus()">Sort by Status</button>
    </div>
  <!-- </div> -->
  
  <div class="table-container">
    <table id="myTable">
      <thead>
        <tr>
        <th>Completed</th>
        <th>Task</th>
        <th>End Date</th>
        <th>Priority</th>
        <th>Status</th>
        <th width="230">Update</th>
        </tr>
      </thead>

      <tbody id="table-body">
        <?php 
          $current_date = date('Y-m-d'); // get current date
          $conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
          if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
            
          $id = $_SESSION['id'];

          //this method updates the current status of a task by clicking the radio button on the first row
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $taskID = $_POST["taskID"];
            $currentStatus = $_POST["currentStatus"];
            $sql = "UPDATE mytasks SET currentStatus='$currentStatus' WHERE taskID=$taskID";
            if ($conn->query($sql) === TRUE) {
                echo "Task updated successfully";
            } else {
                echo "Error updating task: " . $conn->error;
            }
          } 
            
          $info_sql = "SELECT * FROM MyTasks WHERE id = '$id' AND currentStatus!='Completed'"; //retrieve info from db
          $info_result = mysqli_query($conn, $info_sql);
          
          if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
              while ($row = mysqli_fetch_assoc($info_result)) {
                  echo "<tr>";
                    echo "<td><input type='checkbox' name='Completed' value='Completed'onchange='updateTaskStatus(".$row["taskID"].",this.checked)' onclick='refresh()'></td>";
                    echo "<td>
                    <h3>".$row["taskName"]."</h3>
                    <p class='description'>".$row["taskDescription"]."</p>
                  </td>";
                    echo "<td>".$row["endDate"]."</td>";
                    echo "<td class='flag'><i class='fas fa-flag' id='flag-priority' data-value='".$row["priority"]."'></i></td>";
                    echo "<td>".$row["currentStatus"]."</td>";
                    echo "<td><a  id='myButton' class='update-button myButton' href='#' onclick='openForm()'><i class='fa fa-pencil-square' style='margin-right: 10px;'></i>Update</a></td>";
                  echo "</tr>";
              }
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


<!-- this section is for update pop-up window -->
<div id="popup" class="popup">
  <div class="popup-content">
    <span class="close">&times;</span>
    <h2>Pop-up Window</h2>
    <p>This is the content of the pop-up window.</p>
  </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script>

// <! --  add functionality to the sort button
    function sortTableAssending() {
			var table = document.getElementById("myTable");
			var rows = table.rows;
			var sortedRows = [];
			for (var i = 1; i < rows.length; i++) {
				sortedRows.push(rows[i]);
			}
			sortedRows.sort(function(a, b) {
				var dateA = new Date(a.cells[2].textContent);
				var dateB = new Date(b.cells[2].textContent);
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
        var dateA = new Date(a.cells[2].textContent);
        var dateB = new Date(b.cells[2].textContent);
        return dateB - dateA; // sort in reverse order
    });
    for (var i = 0; i < sortedRows.length; i++) {
        table.appendChild(sortedRows[i]);
    }
  }


var sortAscending = true;

function sortStatus() {
  var table, rows, i, x, y, shouldSwitch;
  table = document.getElementById("myTable");
  shouldSwitch = true;
  
  while (shouldSwitch) {
    shouldSwitch = false;
    rows = table.rows;
    
    for (i = 1; i < (rows.length - 1); i++) {
      x = rows[i].getElementsByTagName("TD")[4];
      y = rows[i + 1].getElementsByTagName("TD")[4];
      
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







// <!-- THIS IS FOR UPDATING CURRENT STATUS TO COMPLETED---------------------------------------------------------------------------------------------------------------- -->
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

function refresh(){
  window.location.href("tasks.php")
}


// <!-- THIS IS FOR SHOWING THE UPDATE POPUP WINDOW---------------------------------------------------------------------------------------------------------------- -->
var popup = document.getElementById("popup"); // Get the pop-up and all buttons that open it
var buttons = document.getElementsByClassName("myButton");

var close = document.getElementsByClassName("close")[0]; // Get the close button and add a click event listener
close.addEventListener("click", function() {
  popup.style.display = "none";
});

for (var i = 0; i < buttons.length; i++) { // Loop through all buttons and add a click event listener to each one
  buttons[i].addEventListener("click", function() {
    popup.style.display = "block";
  });
}

// <!-- THIS IS FOR ASSIGNING COLOR FOR THE PRIORITY FLAGS---------------------------------------------------------------------------------------------------------------- -->
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

// <!-- THIS IS FOR STICKY TABLE HEADER---------------------------------------------------------------------------------------------------------------- -->
var tableHeader = document.querySelector('.table-container table thead'); // Get the table header element

document.querySelector('.table-container').addEventListener('scroll', function() { // Add a scroll event listener to the table container
  var distanceFromTop = this.getBoundingClientRect().top; // Get the distance of the table container from the top of the document
  tableHeader.style.top = distanceFromTop + 'px'; // Set the top position of the table header to the distance from the top of the document
});


</script>