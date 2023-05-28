<?php
  // Get task details from database based on taskID
  $conn = mysqli_connect("localhost", "root", "", "getitdone");
  $catID = $_POST["catID"];
  $id = $_POST["id"];

// SQL query to retrieve categories from database table
  $info_sql = "SELECT * FROM mytasks JOIN categories WHERE categories.categoryID='$catID' AND mytasks.categoryID='$catID' AND categories.id=mytasks.id AND mytasks.trash='0' AND mytasks.currentStatus!='Completed'"; //retrieve info from db
  $info_result = mysqli_query($conn, $info_sql);

  echo '<ul id="task-list" class="task-list">';

  if (mysqli_num_rows($info_result) > 0) {
    while($row = mysqli_fetch_assoc($info_result)) {
      echo "<li class='task' data-priority='".$row['priority_stat']."' data-starred='".$row['starred']."' style='color: black' id='".$row['taskID']."' uid='".$row['id']."'>".$row['taskName']."";
      echo "<p class='description'>".$row["taskDescription"]."</p>
      </li>";
      echo "<p class='task-id' hidden>".$row['taskID']."</p>";
    }
  } 

  echo "</ul>";
  
?>