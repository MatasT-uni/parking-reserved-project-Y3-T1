<?php
include 'config.php';
session_start();

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


function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

function formatTime($time)
{
    return date('H:i', strtotime($time));
}

function isSpotOpen($openTime, $closeTime)
{
    $currentTime = date('H:i');
    $openTime = formatTime($openTime);
    $closeTime = formatTime($closeTime);

    return ($currentTime >= $openTime && $currentTime <= $closeTime);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/search.css">
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
                        <a class="nav-link" href="contactpage.php"><i class="fa-solid fa-file-contract"></i> Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="searchpage.php"><i class="fa-solid fa-magnifying-glass"></i> Search</a>
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

    <div class="container-fluid">
        <div class="title mt-5 text-center">
            Search
        </div>

        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['confirm_booking'])) {
                $selected_spot_id = isset($_POST['selected_spot_id']) ? intval($_POST['selected_spot_id']) : 0;
                $user_id = $_SESSION['user_id'];
                $booking_time = date('Y-m-d H:i:s', strtotime($_POST['booking_time']));
                $floor_level = $_POST['floor_level'];
                $zone = $_POST['zone'];
                $slot_no = $_POST['slot_no'];

                $insertBookingQuery = "INSERT INTO bookings (parking_spot_id, user_id, booking_time, floor_level, zone, slot_no) 
                                      VALUES ($selected_spot_id, $user_id, '$booking_time', '$floor_level', '$zone', $slot_no)";
                $result = mysqli_query($conn, $insertBookingQuery);

                if ($result) {
                    $updateSpotQuery = "UPDATE parking_spots SET slot_no = slot_no - 1 WHERE id = $selected_spot_id";
                    $updateResult = mysqli_query($conn, $updateSpotQuery);

                    if ($updateResult) {
                        echo 'Booking confirmed successfully.';
                    } else {
                        echo 'Error updating slots: ' . mysqli_error($conn);
                    }
                } else {
                    echo 'Error confirming booking: ' . mysqli_error($conn);
                }
            } else {
                // Display details of the selected parking spot
                $selected_spot_id = isset($_POST['selected_spot_id']) ? intval($_POST['selected_spot_id']) : 0;

                // Fetch available floor levels based on the selected parking spot
                $floorQuery = "SELECT DISTINCT floor_level FROM parking_spot_floors WHERE parking_spot_id = $selected_spot_id";
                $floorResult = mysqli_query($conn, $floorQuery);

                // Fetch available zones based on the selected parking spot
                $zoneQuery = "SELECT DISTINCT zone FROM parking_spot_zones WHERE floor_id IN (SELECT id FROM parking_spot_floors WHERE parking_spot_id = $selected_spot_id)";
                $zoneResult = mysqli_query($conn, $zoneQuery);

                // Check if the queries were successful
                if ($floorResult && $zoneResult) {
                    // Fetch and store the available floor levels in an array
                    $availableFloorLevels = [];
                    while ($floorRow = mysqli_fetch_assoc($floorResult)) {
                        $availableFloorLevels[] = $floorRow['floor_level'];
                    }

                    // Fetch and store the available zones in an array
                    $availableZones = [];
                    while ($zoneRow = mysqli_fetch_assoc($zoneResult)) {
                        $availableZones[] = $zoneRow['zone'];
                    }
                } else {
                    echo 'Error fetching floor levels or zones.';
                }
                
                $query = "SELECT * FROM parking_spots WHERE id = $selected_spot_id";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    // Display the details of the selected parking spot
                    $spotName = $row['name'];
                    $spotImage = $row['image_path'];
                    $province = $row['province'];
                    $district = $row['district'];
                    $subDistrict = $row['sub_district'];
                    $openTime = $row['open_time'];
                    $closeTime = $row['close_time'];
                    $isSpotOpen = isSpotOpen($openTime, $closeTime);
                    $status = $isSpotOpen ? 'Open' : 'Closed';

                    // HTML output for the parking spot details
                    echo '<div>';
                    echo '<div class="d-flex justify-content-center mt-5" >';
                    echo "<img class='img-view' src='$spotImage' alt='Spot Image'>";
                    echo '</div>';
                    echo '<div class="box-info-view mt-5">';
                    echo "<p><span class='text-topic'>Name:</span> <sapn class='text-content'>$spotName</sapn></p>";
                    echo "<p><span class='text-topic'>Location:</span> <sapn class='text-content'>$province, $district, $subDistrict</sapn></p>";
                    echo "<p><span class='text-topic'>Status:</span> <sapn class='text-content'>$status</sapn></p>";
                    echo "<p><span class='text-topic'>Open Time:</span> <sapn class='text-content'>$openTime</sapn></p>";
                    echo "<p><span class='text-topic'>Close Time:</span> <sapn class='text-content'>$closeTime</sapn></p>";
                    echo '</div>';
                    // Calculate the minimum and maximum values for the time input
                    $minBookingTime = date('H:i', strtotime('+1 minute')); // Minimum time is set to 1 minute from the current time
                    $maxBookingTime = date('H:i', strtotime('+3 hours'));   // Maximum time is set to 5 hours from the current time

                    // Provide a form to confirm booking
                    echo '<div class="d-flex justify-content-center mt-5 pt-4 text-booking" >';
                    echo 'Booking';
                    echo '</div>';
                    echo '<div class="box-info-view mt-2 mb-5 pb-5 ">';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="selected_spot_id" value="' . $selected_spot_id . '">';

                    echo '<div class="mt-3">';
                    // Add other form fields (e.g., floor level, zone, slot no, etc.)
                    echo '<label for="booking_time" class="text-topic">Booking Time: </label>';

                    echo '<input type="time" class="form-control" name="booking_time" min="' . $minBookingTime . '" max="' . $maxBookingTime . '" required>';
                    echo '</div>';

                    echo '<div class="mt-3">';
                    echo '<label for="floor_level" class="text-topic">Floor Level: </label>';
                    echo '<select class="form-select" name="floor_level" required>';
                    foreach ($availableFloorLevels as $floorLevel) {
                        echo '<option value="' . $floorLevel . '">Floor ' . $floorLevel . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';

                    echo '<div class="mt-3">';
                    echo '<label for="zone" class="text-topic">Zone: </label>';
                    echo '<select class="form-select" name="zone" required>';
                    foreach ($availableZones as $zone) {
                        echo '<option value="' . $zone . '">' . $zone . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';

                    echo '<div class="mt-3 mb-4">';
                    echo '<label for="slot_no" class="text-topic">Slot No: </label>';
                    echo '<input type="number" class="form-control" name="slot_no" required>';
                    echo '</div>';

                    echo '<div class="mt-5 pt-3 mb-4 col-xl-auto col-lg-auto col-md-auto col-sm-12 d-flex justify-content-center">';
                    echo '<button type="submit" class="btn-confirm" name="confirm_booking">Confirm Booking</button>';
                    echo '</div>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo 'Error retrieving parking spot details.';
                }

                // Free the result set
                mysqli_free_result($result);
            }
        } else {
            echo 'Invalid request.';
        }

        ?>

</body>
<footer>
    <div class="p-0 mt-3 footer">
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