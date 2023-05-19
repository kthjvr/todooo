<?php include '../frontend/sidebar.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Montserrat:wght@600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="../css/dashboard.css">
<script src="../javascript/script.js"></script>
<script src="https://apis.google.com/js/api.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-jscalendar@1.4.4/source/jsCalendar.min.js" integrity="sha384-0LaRLH/U5g8eCAwewLGQRyC/O+g0kXh8P+5pWpzijxwYczD3nKETIqUyhuA8B/UB" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-jscalendar@1.4.4/source/jsCalendar.min.css" integrity="sha384-44GnAqZy9yUojzFPjdcUpP822DGm1ebORKY8pe6TkHuqJ038FANyfBYBpRvw8O9w" crossorigin="anonymous">



<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "getItDone";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Create a new MySQLi instance
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
if ($mysqli->connect_errno) {
    // Handle database connection error
    die("Failed to connect to the database: " . $mysqli->connect_error);
}

// Assuming you have the user ID stored in the $userID variable
$userID = $_SESSION['id'];

// Get the start and end dates of the current week in the format 'Y-m-d'
$startDate = date('Y-m-d', strtotime('monday this week'));
$endDate = date('Y-m-d', strtotime('sunday this week'));


// SQL query to count the tasks due within the current week for the specified user ID
$sql = "SELECT COUNT(*) AS taskCount FROM mytasks WHERE id = $userID AND endDate >= '$startDate' AND endDate <= '$endDate' AND currentStatus != 'Completed' AND trash = 0";

// Execute the query
$result = $mysqli->query($sql);

// Fetch the task count from the query result
$row = $result->fetch_assoc();
$taskCount = $row['taskCount'];

// Close the database connection
// $mysqli->close();
?>

    <div class="container-fluid">

            <div class="banner">
                <h1>Hi, <?php echo $_SESSION['username']; ?></h1>
                <div class="content">
                    <p class="reminder">You still have <span id="taskCount">10</span> tasks due this week</p>
                    <img src="../images/avatar/<?php echo $_SESSION['avatar']; ?>" alt="avatar">
                </div>
            </div>

            <div class="dailyq">
                <h3>Daily Affirmations</h3>
                <p class='quotes'></p>
            </div>

            <div class="datetime"></div>
            <div class="auto-jsCalendar calendar" id="calendar" style='color: black;'></div>

            <div class="analytics">

            <?php
              $id = $_SESSION['id'];

              // Get the total number of tasks
              $query = "SELECT COUNT(*) AS countTotal FROM mytasks WHERE id = $id AND trash = 0";
              $result = $mysqli->query($query);
              $row = $result->fetch_assoc();
              $totalTasks = $row['countTotal'];

              if ($totalTasks > 0) {
                // Get the number of completed tasks
                $query = "SELECT COUNT(*) AS countCompleted FROM mytasks WHERE id = $id AND trash = 0 AND currentStatus='Completed'";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $completedTasks = $brow['countCompleted'];

                // Get the number of tasks in progress
                $query = "SELECT COUNT(*) AS countInprogress FROM mytasks WHERE id = $id AND trash = 0 AND currentStatus='In Progress'";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $inProgressTasks = $brow['countInprogress'];

                // Get the number of pending tasks
                $query = "SELECT COUNT(*) AS countPending FROM mytasks WHERE id = $id AND trash = 0 AND currentStatus='Not Started'";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $notStartedTasks = $brow['countPending'];

                // Calculate the percentages
                $completedPercentage = round(($completedTasks / $totalTasks) * 100, 0);
                $inProgressPercentage = round(($inProgressTasks / $totalTasks) * 100, 0);
                $notStartedPercentage = round(($notStartedTasks / $totalTasks) * 100, 0);
              } else {
                // Set default values when there are no tasks
                $completedTasks = 0;
                $inProgressTasks = 0;
                $notStartedTasks = 0;
                $completedPercentage = 0;
                $inProgressPercentage = 0;
                $notStartedPercentage = 0;
              }
            ?>

                <div class="progress-container">
                  <div role="progressbar inprogress" aria-valuenow="<?= $inProgressPercentage ?>" aria-valuemin="0" aria-valuemax="<?= $totalTasks ?>" style="--value:<?= $inProgressPercentage ?>"></div>
                  <p class='prog-text'>Task In Progress</p>
                  <p class='prog-count'><?= $inProgressTasks ?> out of <?= $totalTasks ?></p>
                </div>


                <div class="progress-container">
                <div role="progressbar notstarted" aria-valuenow="<?= $notStartedPercentage ?>" aria-valuemin="0" aria-valuemax="<?= $totalTasks ?>" style="--value:<?= $notStartedPercentage ?>"></div>
                <p class='prog-text'>Task Pending</p>
                <p class='prog-count'><?= $notStartedTasks ?> out of <?= $totalTasks ?></p>
                </div>

                <div class="progress-container">
                <div role="progressbar completed" aria-valuenow="<?= $completedPercentage ?>" aria-valuemin="0" aria-valuemax="<?= $totalTasks ?>" style="--value:<?= $completedPercentage ?>"></div>
                <p class='prog-text'>Task Completed</p>
                <p class='prog-count'><?= $completedTasks ?> out of <?= $totalTasks ?></p>
                </div>

            </div>




        <div class="events"></div>
        
        <div class="duetoday"></div>
        <div class="overdue"></div>
    </div>


    </section>
</div>




<script>
    document.addEventListener("DOMContentLoaded", function() {
    var datetimeContainer = document.querySelector(".datetime");
    var calendarContainer = document.querySelector(".calendar");

    // Function to format the current time
    function formatTime(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? "pm" : "am";
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        var timeString = hours + ":" + minutes + ampm;
        return timeString;
    }

    // Function to format the current date
    function formatDate(date) {
        var options = { weekday: "long", month: "short", day: "numeric" };
        var dateString = date.toLocaleDateString(undefined, options);
        return dateString;
    }

    // Function to update the datetime
    function updateDateTime() {
        var currentTime = new Date();
        var timeString = formatTime(currentTime);
        var dateString = formatDate(currentTime);
        var dateTimeString = timeString + ", " + dateString;
        datetimeContainer.textContent = dateTimeString;
    }
    // Initial update
    updateDateTime();

    // Update datetime every second
    setInterval(updateDateTime, 1000);
    });

</script>


<script>
  // Update the task count in the HTML
  document.getElementById("taskCount").textContent = "<?php echo $taskCount; ?>";
</script>

<script>
  // Array of quotes
  var quotes = [
    "You are fully capable of accomplishing any task you set your mind to.",
    "Every step you take brings you closer to achieving your goals.",
    "You are motivated, focused, and determined to complete your tasks.",
    "Embrace challenges as opportunities for growth and learning.",
    "You are resilient, and you never give up on your dreams.", 
    "You have the power to overcome any obstacles that come your way.",
    "Each day, you are getting closer to living the life of your dreams.",
    "Be grateful for the progress you have made so far.",
    "You are capable of handling any setbacks that may arise.",
    "You are worthy of success and happiness.",
    "Trust in your abilities to accomplish your goals.",
    "Stay committed to taking consistent action towards your dreams.",
    "You are confident in your skills and talents.",
    "Believe in yourself and your ability to achieve greatness.",
    "Stay disciplined and fully dedicated to your tasks.",
    "Stay open to receiving guidance and support on your journey.",
    "Be motivated by your vision of a fulfilling and joyful life.",
    "Remember that you have all the resources you need to accomplish your goals.",
    "Attract positive opportunities that help you reach your objectives.",
    "Stay organized, focused, and efficient in completing your tasks.",
    "Be proud of the progress you make each day.",
    "You are capable of turning your dreams into reality.",
    "Trust the process and enjoy every step of your journey.",
    "Release all doubts and replace them with unwavering belief in yourself.",
    "You are in control of your actions and make choices that support your goals.",
    "Celebrate your achievements, no matter how small they may seem.",
    "Surround yourself with a supportive and encouraging environment.",
    "Radiate confidence and positivity in all that you do.",
    "You are a magnet for success and abundance.",
    "Remember that you are worthy of reaching your highest potential.",
    "Attract inspiration and creative ideas to help you complete your tasks.",
    "Find joy in the process of achieving your goals.",
    "Be grateful for the lessons you learn along the way.",
    "Visualize yourself successfully completing your tasks and reaching your goals.",
    "Release any fears or doubts and step into your full power.",
    "Stay fully present and focused on the task at hand.",
    "Be patient and trust that everything unfolds at the perfect time.",
    "Let your intuition guide you towards the right actions.",
    "Create a harmonious balance between work and play in your life.",
    "Remember that you are deserving of happiness, fulfillment, and success.",
    "Choose to approach challenges with a positive and solution-oriented mindset.",
    "Be grateful for the progress you make, no matter how small.",
    "Remember that you are constantly growing and evolving into the best version of yourself.",
    "Attract opportunities that align with your passions and purpose.",
    "Know that you are worthy of receiving support and assistance on your journey.",
    "Fill yourself with enthusiasm and excitement as you work towards your goals.",
    "Release any self-limiting beliefs and embrace your limitless potential.",
    "Trust in the divine timing of your accomplishments.",
    "Stay dedicated to self-improvement and personal growth.",
    "Remember that you are a source of inspiration and motivation for others."
  ];

  var quotesContainer = document.querySelector(".quotes");

  function displayRandomQuote() {
    var randomIndex = Math.floor(Math.random() * quotes.length);
    var randomQuote = quotes[randomIndex];

    // Apply fade-out effect
    quotesContainer.style.opacity = 0;

    // Delay updating the quote to allow for the fade-out effect
    setTimeout(function() {
      quotesContainer.textContent = randomQuote;

      // Apply fade-in effect
      quotesContainer.style.opacity = 1;
    }, 500);
  }

  // Initial display
  displayRandomQuote();

  // Display a new quote every 10 seconds
  setInterval(displayRandomQuote, 10000);
</script>>
