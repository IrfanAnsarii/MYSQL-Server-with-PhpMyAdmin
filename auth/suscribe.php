<?php
include 'config.php';

header('Content-Type: text/plain'); // Set the response type
$conn = getDbConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if the email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM subscribers WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();

                if ($count > 0) {
                    echo "Email already exists";
                    exit();
                }
            } else {
                echo "Error preparing statement: " . $conn->error;
                exit();
            }

            // Prepare and bind for insertion
            $stmt = $conn->prepare("INSERT INTO subscribers(email, subscription_date) VALUES (?, NOW())");
            if ($stmt) {
                $stmt->bind_param("s", $email);

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
        echo "Email is not set in POST request";
    }
}

// Close the connection
$conn->close();
?>
