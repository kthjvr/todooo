<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.html');
	exit;
}
$_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://www.cssscript.com/demo/sticky.css" rel="stylesheet" type="text/css">
    
    <script src="https://kit.fontawesome.com/9682f190fa.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap"rel="stylesheet"/>

    <link rel="stylesheet" href="../css/style.css" type="text/css"/>
    
    <title>Todooo</title>
      <link rel="icon" type="image/x-icon" href="../images/icon.ico">
  </head>

  <body>

    <div class="container">
      <aside class="sidebar">
        <ul class="menu-list">
          <li>
            <div class="menu-container">
              <button class="icon" id="menu">
                <i class="fas fa-bars"></i>
              </button>
            </div>
          </li>
          <li>
            <button class="icon" id="dashboard">
              <i class="fa fa-home"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="search">
              <i class="fas fa-file-alt"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="today">
            <i class="fas fa-calendar"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="important">
            <i class="fas fa-star"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="category">
            <i class="fas fa-th-large"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="booklog">
            <i class="fas fa-archive"></i>
            </button>
          </li>
          <li>
            <button class="icon" id="trash">
            <i class="fas fa-trash"></i>
            </button>
          </li>
          <li hidden>
            <button class="icon" id="add">
            <i class="fas fa-plus-square"></i>
            </button>
          </li>
        </ul>
        <div class="logout-container">
          <button class="icon-logout" href="../index.html">
          <i class="fas fa-sign-out-alt" href="../index.html"></i>
          </button>
        </div>
      </aside>

      <section class="main">
      <img src="../images/love.png" alt="greetings" class='love-cat'>
      <h3 class="greetings">Hi,  <?=$_SESSION['username']?>!</h3>


  <script src="../javascript/script.js"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-LLWL5N9CSM');
  </script>
