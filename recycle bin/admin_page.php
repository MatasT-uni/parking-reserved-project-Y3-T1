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
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="homepage.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutpage.php"><i class="fa-solid fa-people-group"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookingpage.php"><i class="fa-solid fa-calendar-plus"></i> Booking</a>

                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <!-- Show link only when the user is logged in -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ticketpage.php"><i class="fa-solid fa-ticket"></i> Tickects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="paymentpage.php"><i class="fa-solid fa-money-bill-1"></i> Payment</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="contactpage.php"><i class="fa-solid fa-file-contract"></i> Contact</a>
                </li>
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

                                <?php echo htmlspecialchars($title); ?>
                                <?php echo htmlspecialchars($fname); ?>
                                <?php if (!empty($lname)) : ?>
                                    <?php echo substr($lname, 0, 1) . '.'; ?>
                                <?php endif; ?>
                            </div>

                            <li class="nav-item">
                                <a class="nav-link" href="user.php"><i class="fa-solid fa-circle-user"></i> User</a>
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
    <div class="container my-5">
        <h2>List of Admins</h2>
        <a class="btn btn-primary" href="add_admin.php" role="button">New Admin</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Tel</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "root";
                $database = "loginsystem";

                $mysqli = mysqli_connect($servername, $username, $password, $database);
                // Check connection
                if (mysqli_connect_errno()){
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }

                $sql = "SELECT * FROM admins";
                $result = $mysqli->query($sql);

                if(!$result){
                    die("Invalid query: " . $mysqli->error);
                }

                while($row = $result->fetch_assoc()){
                    echo "
                    <tr>
                        <td>$row[id]</td>
                        <td>$row[username]</td>
                        <td>$row[tel]</td>
                        <td>$row[email]</td>
                        <td>$row[create_datetime]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='edit_admin.php?id=$row[id]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='delete_admin.php?id=$row[id]'>Delete</a>
                        </td>
                    </tr>
                    ";
                }
                ?>
                
            </tbody>
        </table>
        </br>
    </div>
    
</body>
</html>