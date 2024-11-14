<?php
include '../auth/config.php';
include '../auth/session.php';

// Check if the user is logged in
if (!checkUserSession()) {
    // Redirect to the login page if not logged in
    header("Location: ../auth/login.php");
    exit();
}

// Retrieve user details from session
$user = getUserFromSession();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['newPassword'];
    $username= $_POST['username'];

    // Validate the password
    if (empty($newPassword)) {
        echo "Password is required";
        exit();
    }

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET db_password = ?, db_user_created = 1 WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $user['id']);

    if ($stmt->execute()) {
        // Create new MySQL user
        $sql_create_user = "CREATE USER '$username'@'%' IDENTIFIED BY '$newPassword'";
        if ($conn->query($sql_create_user) === TRUE) {
            echo "New user created successfully<br>";
        } else {
            echo "Error creating user: " . $conn->error;
            $conn->close();
            exit;
        }
        echo "Password updated successfully";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating password: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
?>
