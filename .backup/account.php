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

	<!-- <div class="profile-bg"> -->
	<div class="profile-container">
		<div class="edit-btn"><button onclick="toggleEdit()">Edit</button></div>
		<div class="profile-pic"><img src="../images/avatar/<?php echo $_SESSION['avatar']; ?>"></div>
		<div class="details-container">
			<div class="detail-row">
				<label for="user-id">User ID:</label>
				<input type="text" id="user-id" disabled value="<?php echo $_SESSION['id']; ?>">
			</div>
			<div class="detail-row">
				<label for="username">Username:</label>
				<input type="text" id="username" disabled value="<?php echo $_SESSION['username']; ?>">
			</div>
			<div class="detail-row">
				<label for="email">Email:</label>
				<input type="email" id="email" disabled value="<?php echo $_SESSION['email']; ?>">
			</div>
			<div class="detail-row">
				<label for="password">Password:</label>
				<div class="password-container">
					<input type="password" id="password" disabled value="<?php echo $_SESSION['userpassword']; ?>">
					<button onclick="togglePasswordVisibility()">Show</button>
				</div>
			</div>
			<div class="detail-row hidden" id="old-password-container">
				<label for="old-password">Old Password:</label>
				<input type="password" id="old-password">
			</div>
			<div class="detail-row hidden" id="new-password-container">
				<label for="new-password">New Password:</label>
				<input type="password" id="new-password">
			</div>
		</div>
	</div>

	<!-- </div> -->




  </div>
  </section>

  <!-- <script>
		const saveBtn = document.querySelector('#save-btn');

		saveBtn.addEventListener('click', () => {
			// Get the data
			const username = document.querySelector('input[name="username"]').value;
			const email = document.querySelector('input[name="email"]').value;
			const password = document.querySelector('input[name="password"]').value;
			const oldPassword = document.querySelector('input[name="old-password"]').value;
			const newPassword = document.querySelector('input[name="new-password"]').value;

			const url = '../backend/update_profile.php';
			const xhr = new XMLHttpRequest();
			xhr.open('POST', url);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function() {
				if (xhr.status === 200) {
					alert('Profile updated successfully!');
				}
				else {
					alert('Error updating profile!');
				}
			};
			xhr.send(`username=${username}&email=${email}&password=${password}&oldPassword=${oldPassword}&newPassword=${newPassword}`);
		});

		const editIcons = document.querySelectorAll('.edit-icon');

		editIcons.forEach(icon => {
			icon.addEventListener('click', () => {
				const input = icon.parentElement.querySelector('input');
				input.disabled = !input.disabled;

        if (input.name === 'password' && !input.disabled) {
            document.querySelector('.password-container').style.display = 'flex';
            input.value = '';
        }
        else if (input.name === 'password' && input.disabled) {
            document.querySelector('.password-container').style.display = 'none';
            input.value = '********';
        }

        // Disable input when clicked outside of the input field
				document.addEventListener('click', (event) => {
					if (event.target !== input && event.target !== icon) {
						input.disabled = true;
					}
				});
        });
        });
</script> -->

<script>
	function toggleEdit() {
	const inputs = document.querySelectorAll('input');
	const passwordContainer = document.querySelector('.password-container');
	const oldPasswordContainer = document.querySelector('#old-password-container');
	const newPasswordContainer = document.querySelector('#new-password-container');
	const container = document.querySelector('.container');

	if (inputs[0].disabled) {
		inputs.forEach(input => {
			input.disabled = false;
		});

		passwordContainer.innerHTML = `
			<input type="password" id="password">
			<button onclick="togglePasswordVisibility()">Show</button>
		`;

		container.classList.add('blue-border');
	} else {
		inputs.forEach(input => {
			input.disabled = true;
		});

		passwordContainer.innerHTML = `
			<input type="password" id="password" disabled value="<?php echo $_SESSION['userpassword']; ?>">
			<button onclick="togglePasswordVisibility()">Show</button>
		`;

		container.classList.remove('blue-border');

		// hide old password and new password inputs
		oldPasswordContainer.classList.add('hidden');
		newPasswordContainer.classList.add('hidden');
	}
}

function togglePasswordVisibility() {
	const passwordInput = document.querySelector('#password');
	const button = document.querySelector('.password-container button');

	if (passwordInput.type === 'password') {
		passwordInput.type = 'text';
		button.textContent = 'Hide';
	} else {
		passwordInput.type = 'password';
		button.textContent = 'Show';
	}
}

document.querySelector('#password').addEventListener('focus', () => {
	// show old password and new password inputs
	const oldPasswordContainer = document.querySelector('#old-password-container');
	const newPasswordContainer = document.querySelector('#new-password-container');
	oldPasswordContainer.classList.remove('hidden');
	newPasswordContainer.classList.remove('hidden');
});

</script>