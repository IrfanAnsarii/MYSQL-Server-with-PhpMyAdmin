<?php
include '../auth/config.php';
include '../auth/session.php';

// Check if the user is logged in
if (!checkUserSession()) {
    // Redirect to the login page if not logged in
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = getUserFromSession();
    
    if ($user) {
        $user_id = $user['id'];
        $user_name= $user['username'];
        $db_name = $_POST['databaseName'];
        $db_created_at = date('Y-m-d H:i:s');

        // Check if a database with the same name already exists
        $stmt = $conn->prepare("SELECT * FROM db WHERE name = ? AND userid = ?");
        $stmt->bind_param("si", $db_name, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt2 = $conn->prepare("SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?");
        $stmt2->bind_param("s", $db_name);
        $stmt2->execute();
        $dbresult = $stmt2->get_result();

        $host_pattern = "%";
        $sql = "SELECT COUNT(*) as count FROM mysql.user WHERE User = ? AND Host = ?";
        $stmt3 = $conn->prepare($sql);
        $stmt3->bind_param("ss", $user_name, $host_pattern);
        $stmt3->execute();
        $userresult = $stmt3->get_result();
        $row = $userresult->fetch_assoc();

        if ($result->num_rows > 0 || $dbresult->num_rows > 0) {
            // Database with the same name already exists
            $_SESSION['message'] = "Error: A database with this name already exists.";
            $_SESSION['message_type'] = "danger";
        }elseif($row['count'] < 1){
            header("Location: dashboard.php");
            exit();
        } else {
            // Insert the new database
            $stmt = $conn->prepare("INSERT INTO db (userid, name, created_at) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $db_name, $db_created_at);

            if ($stmt->execute()) {
                // Create Database
                $sql_create_db = "CREATE DATABASE $db_name";
                if ($conn->query($sql_create_db) === TRUE) {
                    echo "Database created successfully<br>";
                } else {
                    echo "Error creating database: " . $conn->error;
                    $conn->close();
                    exit;
                }

                // Grant all privileges on the new database to the new user
                $sql_grant_privileges = "GRANT ALL PRIVILEGES ON $db_name.* TO '$user_name'@'%'";
                if ($conn->query($sql_grant_privileges) === TRUE) {
                    echo "Privileges granted successfully<br>";
                } else {
                    echo "Error granting privileges: " . $conn->error;
                    $conn->close();
                    exit;
                }
                    // Set success message
                    $_SESSION['message'] = "Database created successfully";
                    $_SESSION['message_type'] = "success";
            } else {
                // Set error message
                $_SESSION['message'] = "Error: Could not execute query";
                $_SESSION['message_type'] = "danger";
            }
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
