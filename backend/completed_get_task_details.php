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
      <button class='moveToTrash'><img src='../images/trash.png' style='vertical-align: middle;'>&nbsp;Move to trash</button>

    </div>
  </div>";
?>

<script>
  $('.edit').on('click', function() {
  // Get the task ID from the row
  var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();
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
</script>

<!-- THIS IS FOR MOVING TASK TO TRASH -->
<script>
  $('.moveToTrash').on('click', function() {
      // Get the task ID from the row
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();

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