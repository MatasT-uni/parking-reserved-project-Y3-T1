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
    <title>Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/booking.css">
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
                        <a class="nav-link active" href="bookingpage.php"><i class="fa-solid fa-calendar-plus"></i> Booking</a>

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
    <div class="container-fluid">
        <div class="title mt-5 text-center">
            BOOKING
        </div>

        <?php
        // Function to format datetime
        function formatDateTime($datetime)
        {
            return date('d/m/Y H:i A', strtotime($datetime));
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            // Fetch available tickets for the current day and not expired
            $currentDate = date('Y-m-d');
            $currentDateTime = date('Y-m-d H:i:s');
            $expiryDateTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

            $availableTicketsQuery = "SELECT b.*, p.name AS place_area
                              FROM bookings b
                              JOIN parking_spots p ON b.parking_spot_id = p.id
                              WHERE b.user_id = $userId 
                              AND DATE(b.booking_time) = '$currentDate'
                              AND b.booking_time > '$currentDateTime'
                              AND b.booking_time < '$expiryDateTime'
                              ORDER BY b.booking_time ASC";

            $availableResult = mysqli_query($conn, $availableTicketsQuery);

            // Fetch booking history
            $historyBookingsQuery = "SELECT b.*, p.name AS place_area
                             FROM bookings b
                             JOIN parking_spots p ON b.parking_spot_id = p.id
                             WHERE b.user_id = $userId 
                             AND (DATE(b.booking_time) < '$currentDate' OR b.booking_time < '$expiryDateTime')
                             ORDER BY b.booking_time DESC";

            $historyResult = mysqli_query($conn, $historyBookingsQuery);

            // // Display available tickets
            // echo '<div class="content mt-5 mb-5 pb-5">';
            // echo '<div class=" mt-5 mb-3">';
            // echo '<div class="text-topic-1 d-flex justify-content-center align-items-center">Ticket Available</div>';
            // echo '</div>';

            // Loop through available tickets
            while ($row = mysqli_fetch_assoc($availableResult)) {
                // Display available tickets
                echo '<div class="content mt-5 mb-5 pb-5">';
                echo '<div class=" mt-5 mb-3">';
                echo '<div class="text-topic-1 d-flex justify-content-center align-items-center">Ticket Available</div>';
                echo '</div>';

                // Fetch all available tickets and store them in an array
                $availableTickets = [];
                while ($row = mysqli_fetch_assoc($availableResult)) {
                    $availableTickets[] = $row;
                }

                // Loop through available tickets
                foreach ($availableTickets as $row) {
                    // Display information for each available ticket
                    echo '<div class="box-info-ticket mt-4">';
                    echo '<div>';
                    echo '<div class="mt-5"><span class="text-topic">Place Area :</span>';
                    echo '<span class="text-content"> ' . $row['place_area'] . '</span>';
                    echo '</div>';
                    echo '<div class="mt-4"><span class="text-topic">Zone Area :</span>';
                    echo '<span class="text-content"> ' . $row['zone'] . '</span>';
                    echo '</div>';
                    echo '<div class="mt-4 mb-4"><span class="text-topic">Floor : </span>';
                    echo '<span class="text-content"> ' . $row['floor_level'] . '</span>';
                    echo '</div>';
                    echo '<div class="mt-2 mb-4"><span class="text-topic">Slot : </span>';
                    echo '<span class="text-content"> ' . $row['slot_no'] . '</span>';
                    echo '</div>';
                    // ... other details
                    echo '<div class="mt-4 mb-5"><span class="text-topic">Time :</span>';
                    echo '<span class="text-content">' . formatDateTime($row['booking_time']) . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Button to open QR code modal
                    echo '<div class="d-flex justify-content-center align-items-center mb-4">';
                    echo '<button class="btn-qr" data-bs-target="#exampleModalToggle' . $row['id'] . '" data-bs-toggle="modal">Open QR Code ' . $row['id'] . '</button>';
                    echo '</div>';

                    // Modal for each ticket
                    echo '<div class="modal fade" id="exampleModalToggle' . $row['id'] . '" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">';
                    echo '<div class="modal-dialog modal-dialog-centered">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h1 class="modal-title fs-5" id="exampleModalToggleLabel">QR Code ' . $row['id'] . '</h1>';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<div class="d-flex justify-content-center align-items-center">';
                    echo '<img class="img-qr" src="img/qr.png" alt="">'; // Replace with actual QR code image
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="modal-footer"></div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }

            echo '</div>';


            // Display booking history
            echo '<div class=" mt-5 pt-5 mb-3">';
            echo '<div class="text-topic-1 d-flex justify-content-center align-items-center">History Booking</div>';
            echo '</div>';

            // Loop through booking history
            while ($row = mysqli_fetch_assoc($historyResult)) {
                // Display information for each booking in history
                echo '<div class="box-info-ticket mt-4">';
                echo '<div>';
                echo '<div class="mt-5"><span class="text-topic">Place Area :</span>';
                echo '<span class="text-content"> ' . $row['place_area'] . '</span>';
                echo '</div>';
                echo '<div class="mt-4"><span class="text-topic">Zone Area :</span>';
                echo '<span class="text-content"> ' . $row['zone'] . '</span>';
                echo '</div>';
                echo '<div class="mt-4 mb-4"><span class="text-topic">Floor : </span>';
                echo '<span class="text-content"> ' . $row['floor_level'] . '</span>';
                echo '</div>';
                echo '<div class="mt-2 mb-4"><span class="text-topic">Slot : </span>';
                echo '<span class="text-content"> ' . $row['slot_no'] . '</span>';
                echo '</div>';
                // ... other details
                echo '<div class="mt-4 mb-5"><span class="text-topic">Time :</span>';
                echo '<span class="text-content">' . formatDateTime($row['booking_time']) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '</div>';
        } else {
            echo 'User not logged in.';
        }
        ?>


        <!-- <div class="content mt-5 mb-5 pb-5">
            <div class=" mt-5 mb-3">
                <div class="text-topic-1 d-flex justify-content-center align-items-center">Ticket Available</div>
            </div>

            <div class="box-info-ticket ">
                <div>
                    <div class="mt-5"><span class="text-topic">Place Area :</span>
                        <span class="text-content">Futeure</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Building :</span>
                        <span class="text-content">A</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Level :</span>
                        <span class="text-content">G</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Slot :</span>
                        <span class="text-content">P5</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Date :</span>
                        <span class="text-content">26/10/2023</span>
                    </div>
                    <div class="mt-4 mb-5"><span class="text-topic">Time :</span>
                        <span class="text-content">1.00PM - 2.00PM</span>
                    </div>
                </div>

                <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalToggleLabel">QR Code</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex justify-content-center align-items-center ">
                                    <img class="img-qr" src="img/qr.png" alt="">
                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mb-4">
                    <button class="btn-qr" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Open QR Code</button>
                </div>

            </div>

            <div class=" mt-5 pt-5 mb-3">
                <div class="text-topic-1 d-flex justify-content-center align-items-center">History Booking</div>
            </div>
            <div class="box-info-ticket ">
                <div>
                    <div class="mt-5"><span class="text-topic">Place Area :</span>
                        <span class="text-content">Futeure</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Building :</span>
                        <span class="text-content">A</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Level :</span>
                        <span class="text-content">G</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Slot :</span>
                        <span class="text-content">P5</span>
                    </div>
                    <div class="mt-4"><span class="text-topic">Date :</span>
                        <span class="text-content">26/10/2023</span>
                    </div>
                    <div class="mt-4 mb-5"><span class="text-topic">Time :</span>
                        <span class="text-content">1.00PM - 2.00PM</span>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center mb-4">
                    <button class="btn-hidden">Open QR Code</button>
                </div>

            </div>

        </div>
    </div> -->


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