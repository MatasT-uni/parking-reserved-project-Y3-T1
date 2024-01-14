<?php
include 'config.php';
session_start();

$title = $fname = $lname = ''; // Initialize variables

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details from the database
    $query = "SELECT title, fname, lname FROM user_form WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Assign fetched values to variables
            $fname = $row['fname'];
            $lname = $row['lname'];
            $title = $row['title'];
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/contact_login.css">
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
                        <a class="nav-link " aria-current="page" href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutpage.php"><i class="fa-solid fa-people-group"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contactpage.php"><i class="fa-solid fa-file-contract"></i> Contact</a>
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
                                    <?php echo htmlspecialchars($title); ?>
                                    <?php echo htmlspecialchars($fname); ?>
                                    <?php if (!empty($lname)) : ?>
                                        <?php echo substr($lname, 0, 1) . '.'; ?>
                                </div>
                            <?php endif; ?>
                            </div>

                            <li class="nav-item">
                                <a class="nav-link" href="edit_user.php"><i class="fa-solid fa-circle-user"></i> User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                            </li>

                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php"><i class="fa-solid fa-right-from-bracket"></i> Sign-up</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="login.php"><i class="fa-solid fa-circle-user"></i> Login</a>
                            </li>


                        <?php endif; ?>

                    </ul>

                </div>
            </div>
        </div>
    </nav>

    <!-- content -->
    <div class="container-fluid" style="min-height: 69vh;">
        <div class="title mt-5 text-center">
            Contact
        </div>
        <div class="pt-5 info-text-title">
            <div class=" box2 ">
                Contact INFO
            </div>
        </div>

        <div class="d-flex justify-content-center align-items-center mt-2 mb-5">
            <div class="box">
                <div class="info-text "><span style="font-weight: bold; color: #E88E5D">Address</span> : 99 Moo 18 , Thammasat University Rangsit campus, Khlongluang, Pathumthani 12121</div>
                <div class="info-text mt-4"><span style="font-weight: bold; color: #E88E5D">Phone</span> : 099-9999-999</div>
                <div class="info-text mt-4"><span style="font-weight: bold; color: #E88E5D">Email</span> : parking reserve@gamil.com</div>
            </div>
        </div>

    </div>

    <script src="js/homepage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
<footer>
    <div class="p-0 m-0 footer">
        <div class="footer-content ">
            <div class="row d-flex justify-content-center align-items-center icon-footer">
                <div class="col-auto"><i class="fa-brands fa-facebook"></i></div>
                <div class="col-auto"><i class="fa-brands fa-twitter"></i></div>
                <div class="col-auto"><i class="fa-brands fa-instagram"></i></div>
                <div class="col-auto"><i class="fa-brands fa-youtube"></i></div>
            </div>
            <div class="row d-flex justify-content-center align-items-center text-footer mt-2">
                <div class="col-auto">Info
                </div>
                <div class="col-auto">|</div>
                <div class="col-auto">Support</div>
                <div class="col-auto">|</div>
                <div class="col-auto">Contact</div>
            </div>
            <div class="row d-flex justify-content-center align-items-center text-footer mt-2">
                <div class="col-auto">Term of Use
                </div>
                <div class="col-auto">|</div>
                <div class="col-auto">Privacy Policy</div>
            </div>
            <div class="row d-flex justify-content-center align-items-center text-footer mt-2">
                <div class="col-auto" style="color: white;"><i class="fa-regular fa-copyright"></i>
                    2023 Parking Reserve
                </div>
            </div>
        </div>
    </div>
</footer>

</html>