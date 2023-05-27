<?php
  // Get task details from database based on taskID
  $conn = mysqli_connect("localhost", "root", "", "getitdone");
  $taskID = $_POST["taskID"];
  $sql = "SELECT * FROM mytasks WHERE taskID='".$taskID."'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  // Echo task details HTML
  echo "<h1 class='task-title'>".$row['taskName']."</h1>
  <p class='task-id' hidden>".$row['taskID']."</p>
  <div class='details'>
    <p style='text-transform: capitalize;'>
    <img width='24' height='24' src='https://img.icons8.com/material/24/spinner-frame-5.png' alt='spinner-frame-5' style='vertical-align: middle;'/>
    ".$row['currentStatus']."
    &nbsp;&nbsp;&nbsp;
    <img width='24' height='24' src='https://img.icons8.com/metro/26/high-importance.png' alt='high-importance' style='vertical-align: middle;'/>
    ".$row['priority_stat']."
    &nbsp;&nbsp;&nbsp;
    <img width='24' height='24' src='https://img.icons8.com/material/24/timeline-week.png' alt='timeline-week' style='vertical-align: middle;'/>
    ".$row['endDate']."
    </p>
  </div>
  ";
  echo "<hr  class='divider2'>";
  echo "<p class='task-description'>".$row['taskDescription']."</p>";
  echo "          </div>

  
  
  <div class='task-info'>
    <div class='task-info-item'>
      <h3><img src='../images/settings.png' style='vertical-align: middle;'>&nbsp;Task Configuration</h3>
      <hr class='divider'>
      <button class='moveToTrash'><img src='../images/trash.png' style='vertical-align: middle;'>&nbsp;Delete permanently</button>
      <button class='restoreTask'><img src='../images/restore.png' style='vertical-align: middle;'>&nbsp;Restore</button>

    </div>
  </div>";
?>

<!-- THIS IS FOR RESTORING TASK TO TRASH -->
<script>
  $('.restoreTask').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();

      Swal.fire({
      title: "Restore this task?",
      text: "Restoring task will set the status to not started while the other details can be edited in the task section.",
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
          url: '../backend/restore.php',
          method: 'POST',
          data: { taskID: taskID },
          success: function(response) {
            Swal.fire({
            title: "Task has been restored.",
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


<!-- THIS IS FOR MOVING TASK TO TRASH -->
<script>
  $('.moveToTrash').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();

      Swal.fire({
      title: "Delete this task permanently?",
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
          url: '../backend/delete_task.php',
          method: 'POST',
          data: { taskID: taskID },
          success: function(response) {
            Swal.fire({
            title: "Task has been deleted permanently.",
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
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();
      console.log(taskID)

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
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();

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
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();

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