<?php
include 'config.php';

header('Content-Type: text/plain'); // Set the response type
$conn = getDbConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare and bind for insertion
            $stmt = $conn->prepare("INSERT INTO contact_message (name, email, message, contact_date) VALUES (?, ?, ?, NOW())");
            if ($stmt) {
                $stmt->bind_param("sss", $name, $email, $message);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "OK"; // Return OK for successful submission
                } else {
                    echo "Error executing statement: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Invalid email format";
        }
    } else {
        echo "Name, email, or message is not set in POST request";
    }
}

// Close the connection
$conn->close();
?>
