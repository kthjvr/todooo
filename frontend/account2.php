<?php include '../frontend/sidebar.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Montserrat:wght@600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="../css/account.css">
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

<div class="acc">
  <div class="profile-bg">
    <div class="profile-container">
      <div class="profile-image">
        <img src="<?php echo $_SESSION['avatar']; ?>" alt="Profile Image">
        <!-- <div class="edit-icon" onclick="openUploadDialog()">
          <i class="fas fa-pencil-alt"></i>
        </div> -->
      </div>
      <div class="profile-details">
        <div class="profile-input">
          <label for="username">Username:</label>
          <input type="text" id="username" value="<?php echo $_SESSION['username']; ?>" disabled>
        </div>
        <div class="profile-input">
          <label for="email">Email:</label>
          <input type="email" id="email" value="<?php echo $_SESSION['email']; ?>" disabled>
        </div>
        <div class="profile-input">
          <label for="userpassword">Password:</label>
          <div class="password-input">
            <input type="password" id="userpassword" value="<?php echo $_SESSION['userpassword']; ?>" disabled>
            <button id="toggle-password" onclick="togglePassword()">Show</button>
          </div>
        </div>
        <div class="profile-input">
          <label for="userid">User ID:</label>
          <div class="userid-container">
            <input type="text" id="userid" value="<?php echo $_SESSION['id']; ?>" readonly>
            <button id="copy-userid" onclick="copyUserID()"><i class="fas fa-copy"></i></button>
          </div>
        </div>
        <div class="profile-buttons">
          <button id="edit-button" onclick="editProfile()">Edit</button>
          <button id="save-button" onclick="saveProfile()" style="display: none;">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Avatar upload dialog -->
<div id="upload-dialog" class="upload-dialog">
  <div class="upload-dialog-content">
    <span class="upload-dialog-close" onclick="closeUploadDialog()">&times;</span>
    <h2>Upload Avatar</h2>
    <form id="avatar-upload-form" enctype="multipart/form-data">
      <input type="file" name="avatar" id="avatar-input" accept="image/*">
      <button type="submit">Upload</button>
    </form>
  </div>
</div>

<script>
function editProfile() {
  var inputs = document.getElementsByTagName("input");
  var editButton = document.getElementById("edit-button");
  var saveButton = document.getElementById("save-button");
  var pencilIcon = document.querySelector(".pencil-icon");

  if (editButton.textContent === "Edit") {
    editButton.textContent = "Cancel";
    saveButton.style.display = "block";

    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].id !== "id") {
        inputs[i].disabled = false;
        inputs[i].classList.add("blue-border");
      }
    }

    if (!pencilIcon) {
      // Create and append the pencil icon to the profile image container
      var profileImageContainer = document.querySelector(".profile-image");
      pencilIcon = document.createElement("i");
      pencilIcon.classList.add("fas", "fa-pencil-alt", "pencil-icon");
      profileImageContainer.appendChild(pencilIcon);

      // Add event listener to the pencil icon for uploading a new avatar
      pencilIcon.addEventListener("click", function() {
        var uploadDialog = document.getElementById("upload-dialog");
        uploadDialog.style.display = "block";
      });
    }
  } else {
    editButton.textContent = "Edit";
    saveButton.style.display = "none";

    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].id !== "id") {
        inputs[i].disabled = true;
        inputs[i].classList.remove("blue-border");
      }
    }

    if (pencilIcon) {
      // Remove the pencil icon from the profile image container
      pencilIcon.remove();
    }
  }
}




  function saveProfile() {
    var inputs = document.getElementsByTagName("input");
    var editButton = document.getElementById("edit-button");
    var saveButton = document.getElementById("save-button");

    // Get the user details and send them to the server
    var userDetails = {
      userid: document.getElementById("userid").value,
      username: document.getElementById("username").value,
      email: document.getElementById("email").value,
      userpassword: document.getElementById("userpassword").value,
    };

    // Send an HTTP request to update the user details
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../backend/update_user_details.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Update the UI to reflect the updated user details

          console.log(xhr.responseText);
          Swal.fire({
            title: "Account details updated",
            text: "Kindly login again, to fully update the changes. Thank you!",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#F999B7"
          }).then(() => {
            window.location.href = "sign-in.html";
          });
        } else {
          console.log("Error updating user details: " + xhr.status);
        }
      }
    };
    xhr.send(JSON.stringify(userDetails));

    editButton.innerText = "Edit";
    saveButton.style.display = "none";

    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].id !== "userid") {
        inputs[i].disabled = true;
        inputs[i].classList.remove("blue-border");
      }
    }
  }

  function togglePassword() {
    var passwordInput = document.getElementById("userpassword");
    var toggleButton = document.getElementById("toggle-password");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleButton.innerText = "Hide";
    } else {
      passwordInput.type = "password";
      toggleButton.innerText = "Show";
    }
  }

  function copyUserID() {
    var useridInput = document.getElementById("userid");
    var copyButton = document.getElementById("copy-userid");
    var copyText = useridInput.value;
    var copySuccess = document.getElementById("copy-success");
    var copyError = document.getElementById("copy-error");

    navigator.clipboard.writeText(copyText).then(function() {
      copyButton.innerHTML = '<i class="fas fa-check"></i>';
      copySuccess.style.display = "inline-block";
      copyError.style.display = "none";
      setTimeout(function() {
        copyButton.innerHTML = '<i class="fas fa-copy"></i>';
        copySuccess.style.display = "none";
        copyError.style.display = "none";
      }, 2000);
    }, function() {
      copyButton.innerHTML = '<i class="fas fa-times"></i>';
      copySuccess.style.display = "none";
      copyError.style.display = "inline-block";
      setTimeout(function() {
        copyButton.innerHTML = '<i class="fas fa-copy"></i>';
        copySuccess.style.display = "none";
        copyError.style.display = "none";
      }, 2000);
    });
  }

  function openUploadDialog() {
    var uploadDialog = document.getElementById("upload-dialog");
    uploadDialog.style.display = "block";
  }

  function closeUploadDialog() {
    var uploadDialog = document.getElementById("upload-dialog");
    uploadDialog.style.display = "none";
  }

  // Handle avatar upload
  document.getElementById("avatar-upload-form").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../backend/upload_avatar.php", true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Avatar uploaded successfully
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            // Update the profile image
            var profileImage = document.querySelector(".profile-image img");
            profileImage.src = response.avatarURL;

            // Close the upload dialog
            closeUploadDialog();
            location.reload(); 
          } else {
            console.log("Error uploading avatar: " + response.error);
          }
        } else {
          console.log("Error uploading avatar: " + xhr.status);
        }
      }
    };
    xhr.send(formData);
  });
</script>
