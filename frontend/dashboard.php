<?php include '../frontend/sidebar2.php'; ?>

<meta http-equiv=''>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

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

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Failed to connect to the database: " . $mysqli->connect_error);
}

$userID = $_SESSION['id'];

$startDate = date('Y-m-d', strtotime('monday this week'));
$endDate = date('Y-m-d', strtotime('sunday this week'));
$current_date = date('Y-m-d');

// SQL query to count the tasks due within the current week for the specified user ID
$sql = "SELECT COUNT(DISTINCT mytasks.taskID) AS taskCount
FROM mytasks
LEFT JOIN assignments ON mytasks.taskID = assignments.taskID
WHERE (mytasks.id = $userID OR assignments.assignee_id = $userID)
  AND mytasks.endDate >= '$startDate' AND mytasks.endDate <= '$endDate'
  AND mytasks.currentStatus != 'Completed' AND mytasks.trash = 0";

$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$taskCount = $row['taskCount'];

// SQL query to count the tasks over due for the specified user ID
$sql1 = "SELECT COUNT(DISTINCT mytasks.taskID) AS taskCountDue
FROM mytasks
LEFT JOIN assignments ON mytasks.taskID = assignments.taskID
WHERE (mytasks.id = $userID OR assignments.assignee_id = $userID)
  AND mytasks.endDate < '$current_date'
  AND mytasks.currentStatus != 'Completed' AND mytasks.trash = 0";

$result1 = $mysqli->query($sql1);
$row1 = $result1->fetch_assoc();
$taskCountDue = $row1['taskCountDue'];



?>

<div class="dashboard-container">
    <div class="container-fluid">

      <div class="banner">
          <h1>Hi, <?php echo $_SESSION['username']; ?></h1>
          <div class="content">
              <p class="reminder">You have <span id="taskCount">0</span> tasks due this week.</p>
              <p class="reminderDue">You have <span id="taskCountDue">0</span> tasks over due!</p>
              <!-- <p class="reminderAssigned">You have <span id="taskCountAssigned">0</span> assigned tasks due this week!</p> -->

              <img src="<?php echo $_SESSION['avatar']; ?>" alt="avatar">
          </div>
      </div>

            <div class="dailyq">
                <h3 class='title'>Daily Affirmations</h3>
                <p class='quotes'></p>
            </div>

            <div class="datetime">
              <span class="clock-icon">&#128339;</span>
              <span class="time"></span>
              <span class="calendar-icon">&#128197;</span>
              <span class="date"></span>
            </div>

            <div class="auto-jsCalendar calendar" id="calendar" style='color: black;'></div>

            <div class="analytics">

            <?php
              $id = $_SESSION['id'];

              // Get the total number of tasks
              $query = "SELECT SUM(taskCount) AS countTotal
              FROM (
                  SELECT COUNT(*) AS taskCount
                  FROM mytasks
                  WHERE id = $id AND trash = 0
                  
                  UNION ALL
                  
                  SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                  FROM assignments
                  JOIN mytasks ON mytasks.taskID = assignments.taskID
                  WHERE assignments.assignee_id = $id AND mytasks.trash = 0
              ) AS counts";
              $result = $mysqli->query($query);
              $row = $result->fetch_assoc();
              $totalTasks = $row['countTotal'];

              if ($totalTasks > 0) {
                // Get the number of completed tasks
                $query = "SELECT SUM(taskCount) AS countCompleted
                FROM (
                    SELECT COUNT(*) AS taskCount
                    FROM mytasks
                    WHERE id = $id AND trash = 0 AND currentStatus = 'Completed'
                    
                    UNION ALL
                    
                    SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                    FROM assignments
                    JOIN mytasks ON mytasks.taskID = assignments.taskID
                    WHERE assignments.assignee_id = $id AND mytasks.currentStatus = 'Completed' AND mytasks.trash = 0
                ) AS counts";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $completedTasks = $brow['countCompleted'];

                // Get the number of tasks in progress
                $query = "SELECT SUM(taskCount) AS countInprogress
                FROM (
                    SELECT COUNT(*) AS taskCount
                    FROM mytasks
                    WHERE id = $id AND trash = 0 AND currentStatus = 'In Progress'
                    
                    UNION ALL
                    
                    SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                    FROM assignments
                    JOIN mytasks ON mytasks.taskID = assignments.taskID
                    WHERE assignments.assignee_id = $id AND mytasks.currentStatus = 'In Progress' AND mytasks.trash = 0
                ) AS counts";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $inProgressTasks = $brow['countInprogress'];

                // Get the number of pending tasks
                $query = "SELECT SUM(taskCount) AS countPending
                FROM (
                    SELECT COUNT(*) AS taskCount
                    FROM mytasks
                    WHERE id = $id AND trash = 0 AND currentStatus = 'Not Started'
                    
                    UNION ALL
                    
                    SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                    FROM assignments
                    JOIN mytasks ON mytasks.taskID = assignments.taskID
                    WHERE assignments.assignee_id = $id AND mytasks.currentStatus = 'Not Started' AND mytasks.trash = 0
                ) AS counts";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $notStartedTasks = $brow['countPending'];

                // Get the number of assigned tasks
                $query = "SELECT COUNT(DISTINCT mytasks.taskID) AS countAssigned
                          FROM mytasks
                          JOIN assignments ON mytasks.taskID = assignments.taskID
                          WHERE assignments.assignee_id = $id AND mytasks.currentStatus != 'Completed' AND mytasks.trash = 0";
                $bresult = $mysqli->query($query);
                $brow = $bresult->fetch_assoc();
                $assignedTasks = $brow['countAssigned'];

                // Calculate the percentages
                $completedPercentage = round(($completedTasks / $totalTasks) * 100, 0);
                $inProgressPercentage = round(($inProgressTasks / $totalTasks) * 100, 0);
                $notStartedPercentage = round(($notStartedTasks / $totalTasks) * 100, 0);
                $assignedPercentage = round(($assignedTasks / $totalTasks) * 100, 0);
              } else {
                // Set default values when there are no tasks
                $completedTasks = 0;
                $inProgressTasks = 0;
                $notStartedTasks = 0;
                $assignedTasks = 0;
                $completedPercentage = 0;
                $inProgressPercentage = 0;
                $notStartedPercentage = 0;
                $assignedPercentage = 0;
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

            <div class="overview">
              <div class="overview-container">
              <p>Weekly Task Overview</p>
              <?php
                  $id = $_SESSION['id'];

                  // Get the current week's start and end dates
                  $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                  $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
                
                  // Query to get the number of tasks added for the week
                  $queryAdded = "SELECT SUM(taskCount) AS addedTasks
                  FROM (
                      SELECT COUNT(*) AS taskCount
                      FROM mytasks
                      WHERE id = $id AND DATE(createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek' AND trash = '0' AND currentStatus != 'Completed'
                      
                      UNION ALL
                      
                      SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                      FROM assignments
                      JOIN mytasks ON mytasks.taskID = assignments.taskID
                      WHERE assignments.assignee_id = $id AND DATE(mytasks.createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek' AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed'
                  ) AS counts";
                  $resultAdded = mysqli_query($conn, $queryAdded);
                  $addedTasks = mysqli_fetch_assoc($resultAdded)['addedTasks'];
                
                  // Query to get the number of completed tasks for the week
                  $queryCompleted = "SELECT SUM(taskCount) AS completedTasks
                  FROM (
                      SELECT COUNT(*) AS taskCount
                      FROM mytasks
                      WHERE id = $id  AND currentStatus = 'Completed' AND DATE(createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek'
                      
                      UNION ALL
                      
                      SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                      FROM assignments
                      JOIN mytasks ON mytasks.taskID = assignments.taskID
                      WHERE assignments.assignee_id = $id AND mytasks.currentStatus = 'Completed' AND DATE(mytasks.createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek'
                  ) AS counts";
                  $resultCompleted = mysqli_query($conn, $queryCompleted);
                  $completedTasks = mysqli_fetch_assoc($resultCompleted)['completedTasks'];
                
                  // Query to get the number of discarded tasks for the week
                  $queryDiscarded = "SELECT SUM(taskCount) AS discardedTasks
                  FROM (
                      SELECT COUNT(*) AS taskCount
                      FROM mytasks
                      WHERE id = $id AND currentStatus = 'Discarded' AND DATE(createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek'
                      
                      UNION ALL
                      
                      SELECT COUNT(DISTINCT assignments.taskID) AS taskCount
                      FROM assignments
                      JOIN mytasks ON mytasks.taskID = assignments.taskID
                      WHERE assignments.assignee_id = $id AND mytasks.currentStatus = 'Discarded' AND DATE(mytasks.createdAt) BETWEEN '$startOfWeek' AND '$endOfWeek' 
                  ) AS counts";
                  $resultDiscarded = mysqli_query($conn, $queryDiscarded);
                  $discardedTasks = mysqli_fetch_assoc($resultDiscarded)['discardedTasks'];
                ?>
                
                <div class="overview-item">
                  <span class="item-label">New Tasks Added:</span>
                  <span class="item-value"><?php echo $addedTasks; ?></span>
                </div>
                
                <div class="overview-item">
                  <span class="item-label">Tasks Completed:</span>
                  <span class="item-value"><?php echo $completedTasks; ?></span>
                </div>
                
                <div class="overview-item">
                  <span class="item-label">Tasks Discarded:</span>
                  <span class="item-value"><?php echo $discardedTasks; ?></span>
                </div>
              </div>
              


            </div>
        
            <div class="duetoday">
              <p class='title'>Tasks due today</p>
              <a href="tasks-today.php">View All ></a>
              <div class="duetoday-container">
                <ul id="task-list">
                  <?php
                    $id = $_SESSION['id'];
                    $current_date = date('Y-m-d');
                    // echo $current_date;
                    $queryToday = "SELECT mytasks.taskName
                    FROM mytasks
                    WHERE (mytasks.id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.endDate='$current_date') 
                    
                    UNION
    
                    SELECT mytasks.taskName
                    FROM mytasks
                    JOIN assignments ON assignments.taskID = mytasks.taskID
                    WHERE (assignments.assignee_id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.endDate='$current_date')";
                    $resultToday = mysqli_query($conn, $queryToday);
                    
                    // Display the information in the HTML table
                  if (mysqli_num_rows($resultToday) > 0) {
                    while($row = mysqli_fetch_assoc($resultToday)) {
                      echo "<li class='task' style='color: black'>".$row['taskName']."</li>";
                    }
                  } else {
                    echo "<li class='task' style='color: black'>No results</li>";
                  }
                  ?>
                </ul>
              </div>


            </div>

            <div class="overdue">
              <p class='title'>Overdue Tasks</p>
              <a href="task-due.php">View All ></a>
              <div class="due-container">
                <ul id="task-list">
                  <?php
                    $id = $_SESSION['id'];
                    $current_date = date('Y-m-d');
                    $queryToday = "SELECT mytasks.taskName
                    FROM mytasks
                    WHERE (mytasks.id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.endDate < '$current_date')
    
                    UNION
    
                    SELECT mytasks.taskName
                    FROM mytasks
                    JOIN assignments ON assignments.taskID = mytasks.taskID
                    WHERE (assignments.assignee_id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.endDate < '$current_date')";
                    $resultToday = mysqli_query($conn, $queryToday);
                    
                    // Display the information in the HTML table
                  if (mysqli_num_rows($resultToday) > 0) {
                    while($row = mysqli_fetch_assoc($resultToday)) {
                      echo "<li class='task' style='color: black'>".$row['taskName']."</li>";
                    }
                  } else {
                    echo "<li class='task' style='color: black'>No results</li>";
                  }
                  ?>
                </ul>
              </div>
            </div>

            <div class="importanttask">
              <p class='title'>Important Tasks</p>
              <a href="tasks-starred.php">View All ></a>
              <div class="important-container">
                <ul id="task-list">
                  <?php
                    $id = $_SESSION['id'];
                    $queryStar = "SELECT mytasks.taskName
                    FROM mytasks
                    WHERE (mytasks.id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.starred='yes')
                    
                    UNION
                    
                    SELECT mytasks.taskName
                    FROM mytasks
                    JOIN assignments ON assignments.taskID = mytasks.taskID
                    WHERE (assignments.assignee_id = $id AND mytasks.trash = '0' AND mytasks.currentStatus != 'Completed' AND mytasks.starred='yes')";
                    $resultStar = mysqli_query($conn, $queryStar);
                    
                    // Display the information in the HTML table
                  if (mysqli_num_rows($resultStar) > 0) {
                    while($row = mysqli_fetch_assoc($resultStar)) {
                      echo "<li class='task' style='color: black'>".$row['taskName']."</li>";
                    }
                  } else {
                    echo "<li class='task' style='color: black'>No results</li>";
                  }
                  ?>
                </ul>
              </div>
            </div>

            <div class="notes">
              <p class='title'>Notepad</p>
              <div class="notes-container">
                <?php
                  $id = $_SESSION['id'];
                  $queryNotes = "SELECT noteContent FROM mynotes WHERE id = $id";
                  $resultNotes = mysqli_query($conn, $queryNotes);
                  $initialNotes = mysqli_fetch_assoc($resultNotes)['noteContent'];
                ?>
                <textarea id="notes-box" placeholder="Write your notes here"><?php echo $initialNotes?></textarea>
              </div>
            </div>
    </div>
    </div>


    </section>
</div>


<script>
  // Get the notes box element
  const notesBox = document.getElementById('notes-box');


  // Save the notes after a certain delay
  let timeoutId;
  notesBox.addEventListener('input', function() {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(saveNotes, 1000);
  });

  // Function to save the notes
  function saveNotes() {
    const noteContent = notesBox.value;
    const userID = <?php echo $_SESSION['id']; ?>;
    console.log(userID)

    // Send an AJAX request to the server to save the notes
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../backend/save_notes.php');
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          // showAutoSavedMessage();
        } else {
          console.error('Error saving notes');
        }
      } else {
        console.error('Request failed. Status:', xhr.status);
      }
    };
    const params = 'noteContent=' + encodeURIComponent(noteContent) + '&id=' + encodeURIComponent(userID);
    xhr.send(params);
  }
</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    var timeContainer = document.querySelector(".time");
    var dateContainer = document.querySelector(".date");

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
      timeContainer.textContent = timeString;
      dateContainer.textContent = dateString;
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
  document.getElementById("taskCountDue").textContent = "<?php echo $taskCountDue; ?>";

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
