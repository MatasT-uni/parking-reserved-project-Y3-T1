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
    // Handle the case where admin data is not found
    // Redirect to login or display an error message
    header("Location: login.php");
    exit();
}

// Your HTML code for the admin page can go here
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
                        <a class="nav-link active" aria-current="page" href="admin_page.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_admin.php"><i class="fa-solid fa-house"></i> Add Admin</a>
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

    <h1>Welcome to the Admin Page, <?php echo htmlspecialchars($admin_fname); ?>!</h1>

    <!-- Include your admin-specific content here -->

    <!-- Include your JavaScript files here -->

</body>

</html>