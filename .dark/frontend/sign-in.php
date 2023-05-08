<!DOCTYPE html>
<html>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/signupin.css">

    <head>
        <title>Todooo- Sign In</title>
        <link rel="icon" type="image/x-icon" href="../images/icon.ico">
    </head>

    <body>

        <nav>
            <img src="../images/logo.png" alt="todooo">
        </nav>

        <div class="center-content">
            <div class="box-2">
            <img src="../images/icon.png" alt="todooo" class="logo2">
                <form action="../backend/authenticate.php"  method="post">
                
                    <h2>Login</h2>
                    <p>Username</p>
                    <input name="username" type="text" placeholder="Enter username" required>
                    <p>Password</p>
                    <input name="userpassword" type="password" placeholder="Enter password" required>
    
                    <a>
                        <button class="submit" type="submit" value="Login">Sign In</button>
                    </a>

                    <div class="question">
                        <p>Not Registered? Click <a href="sign-up.php" style="text-decoration:none; font-weight: bolder; color:#F2789F;">here</a> to log in!</p>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>