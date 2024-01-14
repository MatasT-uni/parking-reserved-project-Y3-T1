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

        <!-- HTML form for search and filtering -->
        <div class="mt-5 row d-flex justify-content-center">
            <div class="mt-5 row d-flex justify-content-center">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-7 col-10">
                <input class="form-control" type="text" name="search_term" placeholder="Search..">
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-7 col-10 filter-margin-top">
                <select class="form-select" name="province">
                    <option selected disabled>Province</option>
                    <option value="Bangkok">Bangkok</option>
                    <option value="Chiang Mai">Chiang Mai</option>
                    <option value="Phuket">Phuket</option>
                </select>
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-7 col-10 filter-margin-top">
                <select class="form-select" name="district">
                    <option selected disabled>District</option>
                    <option value="Pathum Wan">Pathum Wan</option>
                    <option value="Lat Phrao">Lat Phrao</option>
                    <option value="Chatuchak">Chatuchak</option>
                </select>
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-7 col-10 filter-margin-top">
                <select class="form-select" name="sub_district">
                    <option selected disabled>Sub-District</option>
                    <option value="Siam">Siam</option>
                    <option value="Chom Phon">Chom Phon</option>
                    <option value="Chatuchak">Chatuchak</option>
                </select>
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-7 col-10 filter-margin-top">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="open_status" id="openStatusCheckbox">
                    <label class="form-check-label" for="openStatusCheckbox">
                        Open Now
                    </label>
                </div>
            </div>
            <div class="col-xl-auto col-lg-auto col-md-auto col-sm-12 search-center-md">
                <button type="submit" class="btnserach">Search</button>
            </div>
        </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $searchTerm = sanitizeInput($_POST['search_term']);
            $province = isset($_POST['province']) ? sanitizeInput($_POST['province']) : '';
            $district = isset($_POST['district']) ? sanitizeInput($_POST['district']) : '';
            $subDistrict = isset($_POST['sub_district']) ? sanitizeInput($_POST['sub_district']) : '';
            $openStatus = isset($_POST['open_status']) ? true : false;

            // Construct the SQL query based on the provided criteria
            $query = "SELECT * FROM parking_spots WHERE 1=1";

            if (!empty($searchTerm)) {
                $query .= " AND name LIKE '%$searchTerm%'";
            }

            if (!empty($province)) {
                $query .= " AND province = '$province'";
            }

            if (!empty($district)) {
                $query .= " AND district = '$district'";
            }

            if (!empty($subDistrict)) {
                $query .= " AND sub_district = '$subDistrict'";
            }

            // If open_status is true, filter spots that are currently open
            if ($openStatus) {
                $query .= " AND open_time <= NOW() AND close_time >= NOW()";
            }

            $query .= " ORDER BY id";  // Order by ID

            // Execute the query and fetch the results
            $result = mysqli_query($conn, $query);
            echo '<div class="row d-flex justify-content-center align-items-center  mt-4 mb-4">';

            // Display the results
            while ($row = mysqli_fetch_assoc($result)) {
                $spotID = $row['id']; // Get the ID of the parking spot
                $spotName = $row['name'];
                $spotImage = $row['image_path'];
                $spotOpen = formatTime($row['open_time']);
                $spotClose = formatTime($row['close_time']);
                $isSpotOpen = isSpotOpen($row['open_time'], $row['close_time']);
                $status = $isSpotOpen ? 'Open' : 'Closed';

                // Output HTML for each parking spot with status
                echo '<div class="col-lg-auto col-md-12 d-flex justify-content-center align-items-center mt-4">';
                echo '<div>';
                echo '<div class="col-lg-4 col-md-12 mb-4 img-btn"><img class="img" src="' . $spotImage . '" alt=""></div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">' . $spotName . '</div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">Status: ' . $status . '</div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">Parking Slot Remain: ' . $row['slot_no'] . '</div>';
                // Check the status before displaying the button
                if ($isSpotOpen) {
                    // Include spot ID in the form for viewing details
                    echo '<form  method="post" action="view.php">';
                    echo '<input type="hidden" name="selected_spot_id" value="' . $spotID . '">';
                    echo '<div class="col-lg-4 col-md-12 mb-5 d-flex justify-content-center" style="width: 100%;">';
                    echo '<button type="submit" class="view-btn">View</button>';
                    echo '</div>';
                    echo '</form>';
                } else {


                    echo '<div class="col-lg-4 col-md-12 mb-5 d-flex justify-content-center" style="width: 100%;">';
                    echo '<button class="view-btn" disabled>Closed</button>';
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            }
            echo '</div>';

            // Free the result set
            mysqli_free_result($result);
        }
        ?>
    </div>
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