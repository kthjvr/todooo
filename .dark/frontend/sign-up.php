<!DOCTYPE html>
<html>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/signupin.css">

    <head>
        <title>Todooo - Sign Up</title>
        <link rel="icon" type="image/x-icon" href="../images/icon.ico">
    </head>

    <body>

        <nav>
            <img src="../images/logo.png" alt="Todooo">
        </nav>

        <div class="center-content">
            <div class="box-1">
            <img src="../images/icon.png" alt="todooo" class="logo2">
                <form method="post" action="backend/register.php">
                    <h2>Register</h2>
                    <p>Username</p>
                    <input name="username" type="text" placeholder="ex.: anonymous27" required>
                    <p>Email Address</p>
                    <input name="email" type="email" placeholder="ex.: example@gmail.com" required>
                    <p>Password</p>
                    <input name="userpassword" type="password" placeholder="Type password" required>
                    <p>Re-type Password</p>
                    <input name="userpassword" type="password" placeholder="Type the password again" required>
    
                    <a>
                        <button class="submit" type="submit">Sign Up</button>
                    </a>

                    <div class="question">
                        <p>Registered? Click <a href="sign-in.php" style="text-decoration:none; font-weight: bolder; color:#0074D9;">here</a> to log in!</p>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>