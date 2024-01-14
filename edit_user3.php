<?php
include 'config.php';
session_start();

// Initialize variables
$car_type = $car_registration = $car_brand = $car_model = '';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch car details from the database
    $car_query = "SELECT car_type, car_registration, car_brand, car_model FROM cars WHERE user_id = ?";
    if ($car_stmt = mysqli_prepare($conn, $car_query)) {
        mysqli_stmt_bind_param($car_stmt, "i", $user_id);
        mysqli_stmt_execute($car_stmt);
        $car_result = mysqli_stmt_get_result($car_stmt);

        if ($car_row = mysqli_fetch_assoc($car_result)) {
            // Assign fetched car values to variables
            $car_type = $car_row['car_type'];
            $car_registration = $car_row['car_registration'];
            $car_brand = $car_row['car_brand'];
            $car_model = $car_row['car_model'];
        }

        mysqli_stmt_close($car_stmt);
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $new_car_type = $_POST['car_type'];
    $new_car_registration = $_POST['car_registration'];
    $new_car_brand = $_POST['car_brand'];
    $new_car_model = $_POST['car_model'];

    // Update car details in the database
    $update_query = "UPDATE cars SET car_type = ?, car_registration = ?, car_brand = ?, car_model = ? WHERE user_id = ?";
    if ($update_stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($update_stmt, "ssssi", $new_car_type, $new_car_registration, $new_car_brand, $new_car_model, $user_id);
        mysqli_stmt_execute($update_stmt);

        // Redirect to the user's profile page after updating
        header("Location: edit_user3.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Infomation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/edit_user.css">
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
                        <a class="nav-link" aria-current="page" href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutpage.php"><i class="fa-solid fa-people-group"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactpage.php"><i class="fa-solid fa-file-contract"></i> Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="searchpage.php" active><i class="fa-solid fa-magnifying-glass"></i> Search</a>
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
                                <a class="nav-link active" href="edit_userphp"><i class="fa-solid fa-circle-user"></i> User</a>
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
    <div class="container-fluid">
        <div class="title mt-5 text-center">
            USER INFORMATION
        </div>
        <<div class="content mt-5 mb-5 pb-5">
            <div class=" mt-5 d-flex justify-content-center align-items-center">
                <?php
                $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
                if ($fetch['image'] == '') {
                    echo '<img class="img-cicle" src="img/default-avatar.png">';
                } else {
                    echo '<img class="img-cicle" src="uploaded_img/' . $fetch['image'] . '">';
                }
                ?>
            </div>

            <div class=" mt-3 d-flex justify-content-center align-items-center">
                <div class="text-username mb-5"><?php echo htmlspecialchars($username); ?></div>
            </div>

            <div class="text-topic mt-4 d-flex justify-content-center align-items-center mb-3">Car Information</div>

            <div class="box-info-user ">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div>
                        <div class="mt-5"><span class="text-topic">Car type:</span>
                            <span class="text-content"><input type="text" class="form-control" name="car_type" value="<?php echo htmlspecialchars($car_type); ?>"></span>
                        </div>
                        <div class="mt-4"><span class="text-topic">Car registration:</span>
                            <span class="text-content"><input type="text" class="form-control" name="car_registration" value="<?php echo htmlspecialchars($car_registration); ?>"></span>
                        </div>
                        <div class="mt-4"><span class="text-topic">Car brand:</span>
                            <span class="text-content"><input type="text" class="form-control" name="car_brand" value="<?php echo htmlspecialchars($car_brand); ?>"></span>
                        </div>
                        <div class="mt-4 mb-5"><span class="text-topic">Car model:</span>
                            <span class="text-content"><input type="text" class="form-control" name="car_model" value="<?php echo htmlspecialchars($car_model); ?>"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn-edit mt-4 mb-4"><i class="fa-solid fa-check"></i> Confirm Edit</button>
                    </div>

                    <div class="d-flex justify-content-center align-items-center">
                        <a href="edit_user.php">
                            <button class="btn-back mt-4 mb-4"><i class="fa-solid fa-chevron-left"></i> Go Back</button>
                        </a>
                    </div>
                </form>
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