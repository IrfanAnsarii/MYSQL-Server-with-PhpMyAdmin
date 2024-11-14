<?php
$host = 'primary.mysql--pcscn2qr8zht.addon.code.run';
$user = 'fd9ae7189cfc00ea';
$password = 'add55cad6006a7aa5282d53eb425ef';
$database = '87990ae11349';
$port = 29631;

function getDbConnection() {
    global $host, $user, $password, $database, $port;
    
    $conn = mysqli_init();
    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    
    if (!$conn->real_connect($host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

$gmailid = ''; // YOUR gmail email
$gmailpassword = ''; // YOUR gmail App password
$gmailusername = ''; // YOUR gmail Username

?>