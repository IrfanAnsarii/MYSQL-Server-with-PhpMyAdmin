<?php
include '../auth/config.php';
include '../auth/session.php';

// Check if the user is logged in
if (!checkUserSession()) {
    // Redirect to the login page if not logged in
    header("Location: ../auth/login.php");
    exit();
}

// Check if the form is submitted and the database ID is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['db_id'])) {
    $db_id = $_POST['db_id'];
    $dbname = $_POST['db_name'];
    $user = getUserFromSession();

    if ($user) {
        $user_id = $user['id'];
        
        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM db WHERE id = ? AND userid = ?");
        $stmt->bind_param("ii", $db_id, $user_id);

        if ($stmt->execute()) {
            // Check if any row was actually deleted
            if ($stmt->affected_rows > 0) {
                // SQL to delete database
                $sql = "DROP DATABASE $dbname";
                if ($conn->query($sql) === TRUE) {
                    echo "Database deleted successfully";
                } else {
                    echo "Error deleting database: " . $conn->error;
                }
        
                // Set success message
                $_SESSION['message'] = "Database deleted successfully";
                $_SESSION['message_type'] = "success";
            } else {
                // Set error message
                $_SESSION['message'] = "Error: Database not found or you do not have permission";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            // Set error message
            $_SESSION['message'] = "Error: Could not execute query";
            $_SESSION['message_type'] = "danger";
        }
        
        $stmt->close();
    } else {
        // Set error message
        $_SESSION['message'] = "Error: User not found";
        $_SESSION['message_type'] = "danger";
    }
} else {
    // Set error message
    $_SESSION['message'] = "Error: Invalid request";
    $_SESSION['message_type'] = "danger";
}

$conn->close();

// Redirect to databaseinfo
header("Location: databases.php");
exit();
?>
