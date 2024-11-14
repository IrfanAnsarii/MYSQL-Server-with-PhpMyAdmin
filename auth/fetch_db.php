<?php
require_once "config.php";
$conn = getDbConnection();


// Count the number of databases
function count_database($user_id) {
    global $conn;
    $database_count = 0;
        
    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM db WHERE userid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
        
    // Count the number of databases
    $database_count = $result->num_rows;
    $stmt->close();

    return $database_count;
}

// Fetch Database Details
function fetch_database($user_id) {
    global $conn;
    $database_details = array();
     // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM db WHERE userid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch details of each database
    while ($row = $result->fetch_assoc()) {
        $database_details[] = $row;
    }
    $stmt->close();

    return $database_details;
}

function fetch_lastlogin(){
    global $conn;
    if (isset($_SESSION['session_token'])) {
        $session_token = $_SESSION['session_token'];
        $lastlogin="";

        $stmt = $conn->prepare("SELECT * FROM sessions WHERE session_token = ?");
        $stmt->bind_param("s", $session_token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $lastlogin= $row['created_at'];
    }
    return $lastlogin;
}