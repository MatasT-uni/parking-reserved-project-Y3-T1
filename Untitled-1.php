// Check if spot ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted!";

    // Retrieve the selected_spot_id from the form submission
    $selected_spot_id = isset($_POST['selected_spot_id']) ? sanitizeInput($_POST['selected_spot_id']) : null;

    // Ensure $selected_spot_id is not null before using it in the query
    if ($selected_spot_id !== null) {
        // Construct the SQL query based on the selected spot ID
        $query = "SELECT * FROM parking_spots WHERE id = $selected_spot_id";

        // Execute the query and fetch the result
        $result = mysqli_query($conn, $query);

        // Check for errors in the query execution
        if ($result === false) {
            die('Error: ' . mysqli_error($conn) . '<br>Query: ' . $query);
        }

        // Check if there are any rows in the result
        if (mysqli_num_rows($result) > 0) {
            // Display the results
            // ... (rest of your code)
            while ($row = mysqli_fetch_assoc($result)) {
                // ... (your existing code)
                $spotName = $row['name'];
                $spotImage = $row['image_path'];
                $spotOpen = formatTime($row['open_time']);
                $spotClose = formatTime($row['close_time']);
                $isSpotOpen = isSpotOpen($row['open_time'], $row['close_time']);
                $status = $isSpotOpen ? 'Open' : 'Closed';
                $selected_spot_id = $row['id'];

                // Check the status before displaying the button
                if (isSpotOpen($row['open_time'], $row['close_time'])) {
                    // Pass the spot ID to the view page
                    echo '<form method="post" action="view.php">';
                    echo '<!-- Other form fields -->';
                    echo '<input type="hidden" name="selected_spot_id" value="' . $selected_spot_id . '">';
                    echo '<button type="submit" name="confirm_booking">View</button>';
                    echo '</form>';
                } else {
                    echo '<button class="view-btn" disabled>Closed</button>';
                }

                // ... (continue with the rest of your code)
                echo '<div class="col-lg-auto col-md-12 d-flex justify-content-center align-items-center mt-4">';
                echo '<div>';
                echo '<div class="col-lg-4 col-md-12 mb-4 img-btn"><img class="img" src="' . $spotImage . '" alt=""></div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">' . $spotName . '</div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">' . $status . '</div>';
                echo '<div class="col-lg-4 col-md-12 mb-3 text-center text-place ">' . $row['total_slots'] . '</div>';  // Display total_slots
                echo '<div class="col-lg-4 col-md-12 mb-5 d-flex justify-content-center" style="width: 100%;">';
                echo '</div>';
                echo '</div>';
                echo '</div>';
        } else {
            echo "No results found for the selected spot ID.";
        }

        // Free the result set
        mysqli_free_result($result);
    } else {
        echo "No spot ID selected.";
    }
}
