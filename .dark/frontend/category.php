<?php include '../frontend/sidebar.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="../css/category.css">
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
// mysqli_close($conn);
?>



<button type="button" class="add-btn myButton" onclick='openForm()'>+</button>

  <div class="container-fluid">
    <div class="category-container">

        <?php
        $conn = mysqli_connect($servername, $username, $password, $dbname);  // Connect to the database again
        if (!$conn) { die("Connection failed: " . mysqli_connect_error()); } // Check connection again
            
        $id = $_SESSION['id'];

        // SQL query to retrieve categories from database table
        $info_sql = "SELECT * FROM categories WHERE trash='0' AND id = '$id'";   
        $info_result = mysqli_query($conn, $info_sql);
        
        if (mysqli_num_rows($info_result) > 0) { // Display the information in the HTML table
            while ($row = mysqli_fetch_assoc($info_result)) {
                echo '<div class="category-subcontainer">';
                  echo '<h2>'.$row["category"].'</h2>';
                  echo '<p class="category-description">'.$row["categoryDetails"].'</p>';
                  echo '<input class="catID" type="hidden" value="'.$row["categoryID"].'">';
                  echo '<div class="view-button-container">
                          <button class="view-button">View</button>
                          <button class="view-button-delete">Delete</button>
                        </div>';
                echo '</div>';
            }
        } else {
            echo "<option>No results</option>";
          }
          // Close the database connection again
          mysqli_close($conn);
      ?>
    </div>
  </div>

</div>
</section>


<!-- this section is for adding category pop-up window -->
<div id="popup" class="popup">
  <div class="popup-content">
    
    <!-- <h2>Add new category</h2> -->
    <!-- <span class="close">&times;</span> -->
    <form method="POST" action="../backend/addCategory.php">
        <div class="container-category">
            <h1 style="text-align: center;">Add new category</h1>
            <p style="text-align: center;">Please fill in this form to create a new category.</p>
            <hr>

            <label for="category"><b>Title</b></label>
            <input type="text" placeholder="Enter category title" name="category" id="category" required>

            <label for="categoryDetails"><b>Description</b></label>
            <input type="text" placeholder="Enter description" name="categoryDetails" id="categoryDetails" maxlength="60" required>
            <p><span class="GFG">60</span> Characters Remaining</p>
            <hr>

          <div class="btn">
            <input value="Submit" type="submit" class="update-button" readonly>
            <input value="Close" type="close" class="close-button" readonly>
          </div>
        </div>
    </form>
  </div>
</div>

  <!-- THIS IS FOR UPDATE MODAL -->
  <div id="popup-taskcontent" class="popup">
    <div class="popup-content-folder" id="task-details">

    </div>
  </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  const viewButtons = document.querySelectorAll('.view-button');
  viewButtons.forEach(button => {
    button.addEventListener('click', (event) => {
      const subcontainer = event.target.closest('.category-subcontainer');
      const catIDInput = subcontainer.querySelector('.catID');
      const catID = catIDInput.value;
      console.log(catID); // or do whatever you want with the retrieved category ID

      // Send an AJAX request to retrieve the details of the task
      $.ajax({
        url: '../view/taskByCategory.php',
        method: 'POST',
        data: { catID: catID },
        success: function(response) {
          // Display the task details in the pop-up window
          $('#task-details').html(response);
          $('#popup-taskcontent').show();
        }
      });
    });
  });
</script>

<script>
  const deleteButtons = document.querySelectorAll('.view-button-delete');
  deleteButtons.forEach(button => {
    button.addEventListener('click', (event) => {
      const subcontainer = event.target.closest('.category-subcontainer');
      const catIDInput = subcontainer.querySelector('.catID');
      const catID = catIDInput.value;
      console.log(catID); // or do whatever you want with the retrieved category ID

  // Show the confirmation dialog box
  Swal.fire({
    title: "Are you sure you want to delete this folder?",
    text: "Deleting a folder will not delete its tasks or its notes.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, move it to trash",
    cancelButtonText: "No, cancel",
    confirmButtonColor: "#F999B7",
    reverseButtons: true
  }).then((result) => {
    // If the user confirms the action, delete the data
    if (result.isConfirmed) {
      // Send an AJAX request to delete the task
      $.ajax({
        url: '../backend/delete-category.php',
        method: 'POST',
        data: { catID: catID },
        success: function(response) {
          Swal.fire({
            title: "Category moved to trash",
            text: "The category folder has been moved to the trash successfully.",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            // Reload the page or update the UI here
            location.reload(); // Reload the page
            // Or update the UI here
          });
        },
        error: function() {
          // Handle the error if the AJAX request fails
          Swal.fire({
            title: "Error",
            text: "An error occurred while moving the task to the trash.",
            icon: "error",
            confirmButtonText: "OK"
          });
        }
      });
    }
  });

    });
  });
</script>


<script>

  $(document).ready(function () {
          var max_length = 60;
          $('#categoryDetails').keyup(function () {
              var len = max_length - $(this).val().length;
              $('.GFG').text(len);
          });

      });

      // Validate the form before submitting it
  document.querySelector('form').addEventListener('submit', function (event) {
    if (!validateForm()) {
      event.preventDefault();
    }

  });

  function validateForm() {
    let formIsValid = true;

    // Check if category name is empty
    const category = document.querySelector('#category').value;
    if (taskName.trim() === '') {
      alert('Category name cannot be empty');
      formIsValid = false;
    }

    // Check if category description is empty
    const categoryDetails = document.querySelector('#categoryDetails').value;
    if (categoryDetails.trim() === '') {
      alert('Category description cannot be empty');
      formIsValid = false;
    }

    return formIsValid;

  }
  // <!-- THIS IS FOR SHOWING THE UPDATE POPUP WINDOW---------------------------------------------------------------------------------------------------------------- -->
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
var close2 = document.getElementsByClassName("close-button-1")[0]; // Get the close button and add a click event listener
close2.addEventListener("click", function() {
  popup2.style.display = "none";
});
</script>