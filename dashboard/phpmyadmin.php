// login_to_phpmyadmin.php
<?php

$username = 'rij1';
$password = 'Rijwan+11';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging in to phpMyAdmin</title>
</head>
<body>
    <form id="loginForm" action="http://localhost/sql_panel/index.php" method="post">
        <input type="hidden" name="pma_username" value="<?php echo htmlspecialchars($username); ?>">
        <input type="hidden" name="pma_password" value="<?php echo htmlspecialchars($password); ?>">
    </form>
    <script type="text/javascript">
        document.getElementById('loginForm').submit();
    </script>
</body>
</html>
