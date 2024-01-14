<?php
session_start();

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
$delete_admin_id = '';

// Define variables to store error messages
$delete_admin_id_error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate delete admin ID
    if (empty($_POST['delete_admin_id'])) {
        $delete_admin_id_error = 'Admin ID is required';
    } else {
        $delete_admin_id = mysqli_real_escape_string($conn, $_POST['delete_admin_id']);

        // Check if the admin to be deleted is not admin 1
        if ($delete_admin_id == 1) {
            $delete_admin_id_error = 'Cannot delete admin 1';
        }
    }

    // If there are no errors, delete admin from admin_table
    if (empty($delete_admin_id_error)) {
        $delete_query = "DELETE FROM admin_table WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $delete_admin_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            $success_message = 'Admin account deleted successfully';
        } else {
            $error_message = 'Error deleting admin account';
        }

        mysqli_stmt_close($delete_stmt);
    }
}

// Fetch all admins for display
$all_admins_query = "SELECT id, username, title, fname, lname FROM admin_table";
$all_admins_result = mysqli_query($conn, $all_admins_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include your head content -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/home-page.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
    <!-- Include your navbar content -->
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
                        <a class="nav-link" href="add_admin.php"><i class="fa-solid fa-house"></i> Add Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="delete_admin.php"><i class="fa-solid fa-people-group"></i> Delete admin</a>
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
        <h1>Delete Admin</h1>

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

        <!-- Display all admins with delete button -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($admin = mysqli_fetch_assoc($all_admins_result)) : ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><?php echo $admin['username']; ?></td>
                        <td><?php echo $admin['title']; ?></td>
                        <td><?php echo $admin['fname']; ?></td>
                        <td><?php echo $admin['lname']; ?></td>
                        <td>
                            <!-- Display delete button for each admin except admin 1 -->
                            <?php if ($admin['id'] !== 1) : ?>
                                <form method="post" action="">
                                    <input type="hidden" name="delete_admin_id" value="<?php echo $admin['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin_page.php" class="btn btn-secondary mt-3">Back to Admin Page</a>
    </div>

    <!-- Include your JavaScript files here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="js/homepage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>