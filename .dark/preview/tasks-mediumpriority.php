<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="taskBoard.css">

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

  
<div class="table-container">
  
  <table cellspacing="0">
     <tr>
      <th>Task</th>
      <th>End Date</th>
      <th>Status</th>
     </tr>

     <?php
    // Connect to the database again
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection again
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //start session
    session_start();

    // get user id 
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

    //retrieve info from db
   $info_sql = "SELECT * FROM MyTasks WHERE id = '$id' AND currentStatus!='Completed' AND priority='medium'";
   $info_result = mysqli_query($conn, $info_sql);

    // Display the information in the HTML table
    if (mysqli_num_rows($info_result) > 0) {
      while ($row = mysqli_fetch_assoc($info_result)) {
        echo "<tr>";
        echo "<td>
                <h3>".$row["taskName"]."</h3>
                <p class='description'>".$row["taskDescription"]."</p>
             </td>";
        echo "<td>".$row["endDate"]."</td>";
        echo "<td><span class='status'>".$row["currentStatus"]."</span></td>";
        echo "</tr>";
      }
  }

    // Close the database connection again
    mysqli_close($conn);
    ?>

  </table>
</div>





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
<script>


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