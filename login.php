<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'homepage.php';

    // Check if the user is an admin and redirect to the admin page
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header("Location: admin_page.php");
        exit();
    }

    header("Location: $redirect_url");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

    // Check if the login input is an email or a username
    $column = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Prepare and execute a SELECT query using a prepared statement for regular users
    $query_user = "SELECT id, username, email, password FROM user_form WHERE $column = ?";

    // Prepare and execute a SELECT query using a prepared statement for admin users
    $query_admin = "SELECT id, username, email, password FROM admin_table WHERE $column = ?";

    $result = null;

    // Check if the user exists in the regular user table
    if ($stmt_user = mysqli_prepare($conn, $query_user)) {
        mysqli_stmt_bind_param($stmt_user, "s", $login);
        mysqli_stmt_execute($stmt_user);
        $result = mysqli_stmt_get_result($stmt_user);
        mysqli_stmt_close($stmt_user);
    } else {
        die("Query preparation failed: " . mysqli_error($conn));
    }

    // If the user is not found in the regular user table, check the admin user table
    if (mysqli_num_rows($result) == 0) {
        if ($stmt_admin = mysqli_prepare($conn, $query_admin)) {
            mysqli_stmt_bind_param($stmt_admin, "s", $login);
            mysqli_stmt_execute($stmt_admin);
            $result = mysqli_stmt_get_result($stmt_admin);
            mysqli_stmt_close($stmt_admin);
        } else {
            die("Query preparation failed: " . mysqli_error($conn));
        }
    }

    // After authenticating the user in login.php
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        if ($password == $hashed_password) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Check if the user is also an admin
            $admin_check_query = "SELECT id FROM admin_table WHERE id = ?";
            $admin_stmt = mysqli_prepare($conn, $admin_check_query);
            mysqli_stmt_bind_param($admin_stmt, "i", $row['id']);
            mysqli_stmt_execute($admin_stmt);
            $admin_result = mysqli_stmt_get_result($admin_stmt);

            // Set the role immediately when checking
            $_SESSION['role'] = mysqli_num_rows($admin_result) == 1 ? 'admin' : 'user';

            // Redirect the user to the intended page or home if not set
            $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'homepage.php';

            // Debug: Output the role to see if it's being set correctly
            echo 'Role: ' . $_SESSION['role'] . '<br>';

            // Clear the redirect URL from the session
            unset($_SESSION['redirect_url']);

            // Redirect based on the role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: homepage.php");
            }
            exit();
        } else {
            $message = 'Incorrect password!';
        }
    } else {
        $message = 'User not found!';
    }

    mysqli_close($conn);
}

$_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER'] ?? 'homepage.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/login.css">

</head>

<style>
    body {
        font-family: 'FontAwesome';
    }
</style>

<body>

    <div class="form-container">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Login now</h3>

            <?php
            if (isset($message)) {
                echo '<div class="message">' . $message . '</div>';
            }
            ?>
            <!-- Input for Email or Username -->
            <div class="col-lg-12 d-flex justify-content-start">
                <label class="col-form-label">Email or Username</label>
            </div>
            <input type="text" name="login" placeholder="Enter email or username" class="box form-control" required>

            <!-- Input for Password -->
            <div class="col-lg-12 d-flex justify-content-start">
                <label class="col-form-label">Password</label>
            </div>
            <input type="password" name="password" placeholder="Enter password" class="box form-control" required>

            <!-- Submit button -->
            <input type="submit" name="submit" value="Login now" class="btn">

            <hr style="border: 2px solid gray;">

            <!-- Go to the registration page -->
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>

    </div>

</body>

</html>