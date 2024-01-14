<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_booking'])) {
    // Retrieve data from the form submission
    $spot_id = isset($_POST['spot_id']) ? sanitizeInput($_POST['spot_id']) : null;
    $booking_time = isset($_POST['booking_time']) ? sanitizeInput($_POST['booking_time']) : null;
    $floor_level = isset($_POST['floor_level']) ? sanitizeInput($_POST['floor_level']) : null;
    $zone = isset($_POST['zone']) ? sanitizeInput($_POST['zone']) : null;
    $slot_no = isset($_POST['slot_no']) ? sanitizeInput($_POST['slot_no']) : null;

    // Validate data (add your own validation logic)

    // Insert data into the bookings table
    $query = "INSERT INTO bookings (spot_id, user_id, booking_time, floor_level, zone, slot_no, created_at) VALUES ('$spot_id', 'user_id_placeholder', '$booking_time', '$floor_level', '$zone', '$slot_no', CURRENT_TIMESTAMP)";

    $result = mysqli_query($conn, $query);

    // Check for errors in the query execution
    if ($result === false) {
        die('Error: ' . mysqli_error($conn) . '<br>Query: ' . $query);
    }

    // Update the total_slots in parking_spots table (assuming total_slots is a column in the parking_spots table)
    $updateQuery = "UPDATE parking_spots SET total_slots = total_slots - 1 WHERE id = '$spot_id'";
    $updateResult = mysqli_query($conn, $updateQuery);

    // Check for errors in the update query execution
    if ($updateResult === false) {
        die('Error updating total slots: ' . mysqli_error($conn) . '<br>Query: ' . $updateQuery);
    }

    // Redirect to a success page or perform any additional actions
    header('Location: success_page.php');
    exit();
} else {
    // Handle the case when the form is not submitted
    echo "Form not submitted.";
}
?>
