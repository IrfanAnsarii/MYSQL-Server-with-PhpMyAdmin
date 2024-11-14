<?php
session_start(); // Start the session
include_once '../config.php';
require_once "config.php";
$conn = getDbConnection();

// Function to start a session for a user
function startUserSession($userId) {
    global $conn;

    // Invalidate any existing session for this user
    $delete_stmt = $conn->prepare("DELETE FROM sessions WHERE user_id = ?");
    $delete_stmt->bind_param("i", $userId);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Generate a new session token
    $session_token = bin2hex(random_bytes(32));

    // Insert the new session token into the sessions table
    $insert_stmt = $conn->prepare("INSERT INTO sessions (user_id, session_token, created_at) VALUES (?, ?, NOW())");
    $insert_stmt->bind_param("is", $userId, $session_token);
    $insert_stmt->execute();
    $insert_stmt->close();

    // Store session token in session
    $_SESSION['session_token'] = $session_token;

    return $session_token;
}

// Function to check if a user is logged in
function checkUserSession() {
    global $conn;

    if (isset($_SESSION['session_token'])) {
        // Verify if the session token is valid
        $session_token = $_SESSION['session_token'];
        $stmt = $conn->prepare("SELECT * FROM sessions WHERE session_token = ?");
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return true;
        } else {
            // Invalidate the session if the token is not found in the database
            session_unset();
            session_destroy();
            header("Location:". BASE_URL . "/auth/logout.php");
            exit();
        }

        $stmt->close();
    }
    return false;
}

// Function to get user details from session token
function getUserFromSession() {
    global $conn;

    if (isset($_SESSION['session_token'])) {
        $session_token = $_SESSION['session_token'];

        // Prepare and bind
        $stmt = $conn->prepare("SELECT users.id, users.FirstName, users.LastName, users.username, users.email, users.db_user_created, users.phone, users.db_password FROM users JOIN sessions ON users.id = sessions.user_id WHERE sessions.session_token = ?");
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the session is valid
        if ($result->num_rows == 1) {
            // Fetch user details
            return $result->fetch_assoc();
        }

        $stmt->close();
    }
    return null;
}

// Function to destroy a user session
function destroyUserSession() {
    global $conn;

    if (isset($_SESSION['session_token'])) {
        // Delete session token from the database
        $session_token = $_SESSION['session_token'];
        $delete_stmt = $conn->prepare("DELETE FROM sessions WHERE session_token = ?");
        $delete_stmt->bind_param("s", $session_token);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();
}

function getIpAddress() {
    // Check for shared Internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // Check for IP address from proxy or load balancer
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // Check for regular IP address
    if (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        return $_SERVER['REMOTE_ADDR'];
    }

    // Unable to get the IP address
    return null;
}
?>
