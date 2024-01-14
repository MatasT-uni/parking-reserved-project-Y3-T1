<?php
session_start();

// Check if the user is an admin, redirect to login if not
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include your config.php file
include 'config.php';



// Fetch admin data from the admin table using the admin's user_id
$admin_id = $_SESSION['user_id'];
$admin_query = "SELECT * FROM admin_table WHERE id = ?";
$admin_stmt = mysqli_prepare($conn, $admin_query);
mysqli_stmt_bind_param($admin_stmt, "i", $admin_id);
mysqli_stmt_execute($admin_stmt);
$admin_result = mysqli_stmt_get_result($admin_stmt);

if ($admin_row = mysqli_fetch_assoc($admin_result)) {
    // Get admin-specific information
    $admin_title = $admin_row['title'];
    $admin_fname = $admin_row['fname'];
    $admin_lname = $admin_row['lname'];
    
    // Add other admin-specific fields as needed
} else {
    // Handle the case where admin data is not found or the user is not an admin
    // Redirect to login or display an error message
    header("Location: login.php");
    exit();
}

// Check if the logged-in admin is admin 1, if not, redirect
if ($admin_id !== 1) {
    header("Location: admin_page.php");
    exit();
}

// Define variables to store form input
$username = $email = $password = $title = $fname = $lname = '';

// Define variables to store error messages
$username_error = $email_error = $password_error = $title_error = $fname_error = $lname_error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    if (empty($_POST['username'])) {
        $username_error = 'Username is required';
    } else {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
    }

    // Validate email
    if (empty($_POST['email'])) {
        $email_error = 'Email is required';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email_error = 'Invalid email format';
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }

    // Validate password
    if (empty($_POST['password'])) {
        $password_error = 'Password is required';
    } else {
        $password = md5(mysqli_real_escape_string($conn, $_POST['password'])); // Use appropriate hashing method
    }

    // Validate title
    if (empty($_POST['title'])) {
        $title_error = 'Title is required';
    } else {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
    }

    // Validate fname
    if (empty($_POST['fname'])) {
        $fname_error = 'First name is required';
    } else {
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    }

    // Validate lname
    if (empty($_POST['lname'])) {
        $lname_error = 'Last name is required';
    } else {
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    }

    // Check for Duplicate Username
    $duplicate_check_query = "SELECT id FROM admin_table WHERE username = ?";
    $duplicate_check_stmt = mysqli_prepare($conn, $duplicate_check_query);
    mysqli_stmt_bind_param($duplicate_check_stmt, "s", $username);
    mysqli_stmt_execute($duplicate_check_stmt);
    $duplicate_check_result = mysqli_stmt_get_result($duplicate_check_stmt);

    if (mysqli_num_rows($duplicate_check_result) > 0) {
        $error_message = 'Username already exists. Please choose a different username.';
    } elseif ($username === $admin_row['username']) {
        // Check if Logged-in Admin is Adding Themselves
        $error_message = 'You cannot add yourself as an admin.';
    } else {
        // If there are no errors, proceed with the insertion
        $insert_query = "INSERT INTO admin_table (username, email, password, title, fname, lname) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ssssss", $username, $email, $password, $title, $fname, $lname);

        if (mysqli_stmt_execute($insert_stmt)) {
            $success_message = 'Admin account added successfully';
        } else {
            $error_message = 'Error adding admin account';
        }

        mysqli_stmt_close($insert_stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/home-page.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="img/logo-transparent.png" class="navbar-brand img-logo">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_page.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="add_admin.php"><i class="fa-solid fa-house"></i> Add Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="delete_admin.php"><i class="fa-solid fa-people-group"></i> Delete admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="searchpage.php"><i class="fa-solid fa-magnifying-glass"></i> Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookingpage.php"><i class="fa-solid fa-calendar-plus"></i> Booking</a>

                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <!-- Show link only when the user is logged in -->
                    </li>

                <?php endif; ?>

                </ul>

                <div class="d-flex justify-content-center">

                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <label></label>
                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <div>

                                <?php if (!isset($_SESSION['welcome_back'])) {
                                    // Display the "Welcome Back!" message
                                    echo "<label>Welcome Back!</label>";

                                    // Set the session variable to indicate that the message has been shown
                                    $_SESSION['welcome_back'] = true;
                                }
                                ?>
                                <div class="mt-2">
                                    <?php echo htmlspecialchars($$admin_title); ?>
                                    <?php echo htmlspecialchars($$admin_fname); ?>
                                    <?php if (!empty($$admin_lname)) : ?>
                                        <?php echo substr($admin_lname, 0, 1) . '.'; ?>
                                </div>
                            <?php endif; ?>
                            </div>

                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                            </li>

                        <?php else : ?>

                            <li class="nav-item">
                                <a class="nav-link" href="login.php"><i class="fa-solid fa-circle-user"></i> Login</a>
                            </li>


                        <?php endif; ?>

                    </ul>

                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Add Admin</h1>

        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <div class="text-danger"><?php echo $username_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <div class="text-danger"><?php echo $email_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="text-danger"><?php echo $password_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <div class="text-danger"><?php echo $title_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>">
                <div class="text-danger"><?php echo $fname_error; ?></div>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>">
                <div class="text-danger"><?php echo $lname_error; ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Add Admin</button>
        </form>

        <a href="admin_page.php" class="btn btn-secondary mt-3">Back to Admin Page</a>
    </div>

    <!-- Include your JavaScript files here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="js/homepage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>