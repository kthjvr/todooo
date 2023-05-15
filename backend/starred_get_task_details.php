<?php
  // Get task details from database based on taskID
  $conn = mysqli_connect("localhost", "root", "", "getitdone");
  $taskID = $_POST["taskID"];
  $id = $_POST["id"];

  $sql = "SELECT * FROM mytasks JOIN categories WHERE categories.categoryID=mytasks.categoryID AND categories.id=$id AND mytasks.id=$id AND mytasks.trash='0' AND mytasks.currentStatus!='Completed' AND taskID=$taskID";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  // echo "<p>".$row['id']."</p>";

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
    &nbsp;&nbsp;&nbsp;
    <img src='../images/category.png' alt='cat' style='vertical-align: middle;'/>
    ".$row['category']."
    </p>
  </div>
  ";
  echo "<hr  class='divider2'>";
  echo "<p class='task-description'>".$row['taskDescription']."</p>";
  echo "          </div>
  
  <div class='task-info'>
    <div class='task-info-item'>
      <h3><img src='../images/settings.png' style='vertical-align: middle;'>&nbsp;Task Configuration</h3>
      <hr class='divider'>";

      
      if ($row['currentStatus'] == 'Not Started') {
        echo "<button class='setInprogress'><img src='../images/inprog.png' style='vertical-align: middle;'>&nbsp;Set to in-progress</button>";
      }
      
      echo "
      <button class='setComplete'><img src='../images/complete.png' style='vertical-align: middle;'>&nbsp;Complete</button>
      <button class='moveToTrash'><img src='../images/trash.png' style='vertical-align: middle;'>&nbsp;Move to trash</button>
     <button id='star-button' class='starred ".$row['starred']."'>
     <i id='star-icon' class='fas fa-star' style='vertical-align: middle;'></i>&nbsp;
     <span id='star-text' style='color: #141E61;'>Star</span>
     </button>
      <button class='edit'><img src='../images/edit-task.png' style='vertical-align: middle;'>&nbsp;Edit Task</button>

      

    </div>
  </div>";

?>

<script>
  var starButton = document.getElementById('star-button');
var starText = document.getElementById('star-text');

if (starButton.classList.contains('yes')) {
  starText.textContent = 'Unstar';
} else {
  starText.textContent = 'Star';
}
</script>

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
  $(document).ready(function() {
    $("#star-button").click(function() {
      var button = $(this);
      var taskID = $(this).closest('div#details-placeholder').find('.task-id').text();
      var status = button.hasClass("yes") ? "no" : "yes";
      var title = status == "yes" ? "Star task" : "Unstar task";
      var text = status == "yes" ? "Do you want to star this task?" : "Do you want to unstar this task?";
      

      swal.fire({
        title: title,
        text: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          if (status == "yes") {
            button.addClass("no");
          } else {
            button.removeClass("yes");
          }

        $.ajax({
        url: "../backend/update_starred.php",
        method: "POST",
        data: {
          taskID: taskID,
          status: status
        },
        success: function(response) {
          swal.fire({
              title: "Task status updated!",
              text: "The task status has been updated successfully.",
              icon: "success",
              confirmButtonText: "OK",
              confirmButtonColor: "#F999B7"
            }).then(() => {
              // Reload the page or update the UI here
              location.reload(); // Reload the page
            });
        },
          error: function() {
            swal("Error", "There was an error updating the task status.", "error");
          }
        });
        }
      });
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