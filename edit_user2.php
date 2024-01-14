<?php
include 'config.php';
session_start();

$title = $fname = $lname = ''; // Initialize variables

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details from the database
    $query = "SELECT title, fname, lname, username, gender, phone, date_of_birth FROM user_form WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Assign fetched values to variables
            $fname = $row['fname'];
            $lname = $row['lname'];
            $title = $row['title'];
            $username = $row['username']; // Add this line to retrieve the username
            $gender = $row['gender'];
            $phone = $row['phone'];
            $dob = $row['date_of_birth'];
        }

        mysqli_stmt_close($stmt);
    }
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    // Retrieve the values from the form
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $phone = mysqli_real_escape_string($conn, $_POST['update_phone']);
    $dob = mysqli_real_escape_string($conn, $_POST['update_dob']);

    // Update the user information in the database
    mysqli_query($conn, "UPDATE `user_form` SET email = '$update_email', phone = '$phone', date_of_birth = '$dob' WHERE id = '$user_id'") or die('query failed');

    $old_pass = $_POST['old_pass'];
    $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
    $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($update_pass != $old_pass) {
            $message[] = 'old password not matched!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'confirm password not matched!';
        } else {
            mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'password updated successfully!';
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'image is too large';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $message[] = 'image updated succssfully!';
        }
    }
    header("Location: edit_user.php?success");
    exit();
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
        <div class="content mt-5 mb-5 pb-5">
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

            <div class="text-topic mt-4 d-flex justify-content-center align-items-center mb-3">Edit Infomation</div>

            <?php
            $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select) > 0) {
                $fetch = mysqli_fetch_assoc($select);
            }
            ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="box-info-user ">

                    <div>
                        <div class="mt-5 ">
                            <label for="formFile" class="form-label text-topic mt-2">Profile Picture</label>
                            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="form-control">
                        </div>

                        <div class="mt-4"><span class="text-topic">Title :</span>
                            <span class="text-content"><?php echo htmlspecialchars($title); ?></span>
                        </div>

                        <div class="mt-4"><span class="text-topic">Name :</span>
                            <span class="text-content"><?php echo htmlspecialchars($fname); ?></span>
                        </div>

                        <div class="mt-4"><span class="text-topic">Lastname :</span>
                            <span class="text-content"><?php echo htmlspecialchars($lname); ?></span>
                        </div>

                        <div class="mt-4"><span class="text-topic">Gender :</span>
                            <span class="text-content"><?php echo htmlspecialchars($gender); ?></span>
                        </div>

                        <div class="mt-4">
                            <span class="text-topic">Date of birth :</span>
                            <span class="text-content">
                                <input type="date" name="update_dob" class="form-control" value="<?php echo $dob; ?>">
                            </span>
                        </div>

                        <div class="mt-4">
                            <span class="text-topic">Tel :</span>
                            <span class="text-content">
                                <input type="text" name="update_phone" class="form-control" value="<?php echo $phone; ?>">
                            </span>
                        </div>

                        <div class="mt-4">
                            <span class="text-topic">your email :</span>
                            <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box form-control">
                        </div>
                        <div class="mt-4">
                            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                            <span class="text-topic">old password :</span>
                            <input type="password" name="update_pass" placeholder="enter previous password" class="box form-control">
                        </div>
                        <div class="mt-4">
                            <span class="text-topic">new password :</span>
                            <input type="password" name="new_pass" placeholder="enter new password" class="box form-control">
                        </div>
                        <div class="mt-4 mb-5">
                            <span class="text-topic">confirm password :</span>
                            <input type="password" name="confirm_pass" placeholder="confirm new password" class="box form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="sumbit" value="update profile" name="update_profile" class="btn-edit mt-4 mb-4"><i class="fa-solid fa-check"></i> Confirm Edit</button>
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="edit_user.php">
                            <button class="btn-back mt-4 mb-4"><i class="fa-solid fa-chevron-left"></i> Go Back</button>
                        </a>
                    </div>
                </div>
            </form>


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