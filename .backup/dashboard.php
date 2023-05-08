<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$_SESSION['username'];

?>
<!DOCTYPE html>
<html>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">

    <head>
      <title>Purrsonal TM - Dashboard</title>
      <link rel="icon" type="image/x-icon" href="images/webIcon.ico">
      <script src="csi.min.js"></script>
      <!-- <meta http-equiv="refresh" content="0"> -->

    </head>

    <body>
        <nav id="tabs" class='category-tab'>
            <a><span class="username">Hi,  <?=$_SESSION['username']?>!</span><img src="images/logo.png" alt="logo" class="logo"></a>
            <a class="pageLinks" href="dashboard-contents.php"><i class="fa fa-calendar-check-o"></i><span>Dashboard</span></a>
            <a class="pageLinks" href="#first"><i class="fa fa-calendar-check-o"></i><span>Today</span></a>
            <a class="pageLinks" href="#second"><i class="fa fa-star"></i><span>Starred</span></a>
            <a class="pageLinks" href="#third"><i class="fa fa-folder-open"></i><span>All Tasks</span></a>
            <a class="pageLinks" href="#fourth" ><i class="fa fa-book"></i><span>Log Book</span></a>
            <a class="pageLinks" href="#fifth"><i class="fa fa-plus-square"></i><span>Add Task</span></a>
            <a href="index.html"><i class="fa fa-sign-out"></i><span>Sign Out</span></a>
        </nav>

          <div class= 'container'> 
            <section id= 'first'>
              <h1>Today</h1>
              <iframe src="tasks-today.php" id="pageFrame"></iframe>
            </section>
            
            <section id= 'second'> 
              <h1>Starred/Important</h1>
              <iframe src="tasks-starred.php" id="pageFrame"></iframe>
            </section>
            
            <section id= 'third'>
              <h1>All Tasks</h1>
              <iframe src="tasks.php" id="pageFrame"></iframe>
            </section>
            
            <section id= 'fourth'>
              <h1>Log book</h1>
              <iframe src="tasks-completed.php" id="pageFrame"></iframe>
            </section>

            <section id= 'fifth'>
              <h1>Add Task</h1>
              <iframe src="addTask.php" seamless class="addTaskContainer" id="pageFrame"></iframe>
            </section>
          </div>
           


<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script>

$(document).ready(function() {
  $('.category-tab a').on('click', function() {
    var target = $(this).attr('href');
    $(target + ' iframe').attr('src', function(i, val) {
      return val;
    });
  });
});


  $(function(){
    var url = window.location.href;
    
    $("#tabs a").click(function(){
      $('a').removeClass("active")
      $(this).closest("a").removeClass("notActive").addClass("active")
      
    })
  })
</script>

        
 
    </body>
</html>
