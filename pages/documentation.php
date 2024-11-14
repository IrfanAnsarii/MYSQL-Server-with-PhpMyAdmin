<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>D@TABase- a MySql provider</title>
    <meta content="MySql provider for free" name="description">
    <meta content="DBMS, MySql, free sql, phpmyadmin" name="keywords">

    <!-- Favicons -->
    <link href="../assets1/img/favicon.png" rel="icon">
    <link href="../assets1/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets1/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets1/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets1/vendor/aos/aos.css" rel="stylesheet">
    <link href="../assets1/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets1/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="../assets1/css/main.css" rel="stylesheet">

    <style>
    .copy-btn {
        position: absolute;
        right: 10px;
        top: 10px;
    }

    pre {
        position: relative;
    }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="../index.php" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets1/img/logo.png" alt=""> -->
                <h1 class="sitename">D@TABase</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="../index.php" class="active">Home</a></li>
                    <li><a href="../index.php#about">About</a></li>
                    <li><a href="documentation.php">Documentation</a></li>
                    <li><a href="../index.php#team">Team</a></li>
                    <li><a href="../index.php#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href='auth/signup.php'>Signup/Login</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">
            <div class="container">
                <!-- documentation goes here -->
                <div class="container mt-5 --bs-light-bg-subtle">
                    <h1 class="mb-4">PHP Database Connection Documentation</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            Step 1: Define Connection Variables
                        </div>
                        <div class="card-body">
                            <p style="color:#5D6D7E">First, define the variables needed for the database connection.</p>
                            <pre><code>
                            $host = 'primary.mysql--pcscn2qr8zht.addon.code.run';
                            $user = '';
                            $password = '';
                            $database = '';
                            $port = 29631;
                              </code>
                            </pre>
                            <button class="btn btn-secondary copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            Step 2: Create a Function for the Database Connection
                        </div>
                        <div class="card-body">
                            <p style="color:#5D6D7E">Create a function <code>getDbConnection</code> to establish the
                                connection using the defined variables.</p>
                            <pre><code>function getDbConnection() {
    global $host, $user, $password, $database, $port;
    
    $conn = mysqli_init();
    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    
    if (!$conn->real_connect($host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}</code></pre>
                            <button class="btn btn-secondary copy-btn" onclick="copyToClipboard(this)">Copy</button>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            Step 3: Explanation of the Connection Code
                        </div>
                        <div class="card-body">
                            <p style="color:#5D6D7E">Let's break down the code:</p>
                            <ul>
                                <li><code>global $host, $user, $password, $database, $port;</code>: Import the global
                                    variables into the function.</li>
                                <li><code>$conn = mysqli_init();</code>: Initialize the MySQLi connection.</li>
                                <li><code>$conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);</code>: Set the SSL
                                    verification option.</li>
                                <li><code>$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);</code>: Configure SSL settings.
                                </li>
                                <li><code>if (!$conn->real_connect($host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {</code>:
                                    Attempt to establish a connection using SSL.</li>
                                <li><code>die("Connection failed: " . $conn->connect_error);</code>: If the connection
                                    fails, output an error message.</li>
                                <li><code>return $conn;</code>: Return the established connection.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /Hero Section -->

    </main>

    <footer id="footer" class="footer">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.php" class="d-flex align-items-center">
                        <span class="sitename">D@TABASE</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Kathiatoli</p>
                        <p>Nagaon, 782427</p>
                        <p class="mt-3"><strong>Whatsapp/Phone:</strong> <span>+91 9531398825</span></p>
                        <p><strong>Email:</strong> <span>irfangittech@gmail.com</span></p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h4>Follow Us</h4>
                    <!-- <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p> -->
                    <div class="social-links d-flex">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="../assets1/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets1/vendor/php-email-form/validate.js"></script>
    <script src="../assets1/vendor/aos/aos.js"></script>
    <script src="../assets1/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="../assets1/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="../assets1/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="../assets1/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="../assets1/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="../assets1/js/main.js"></script>

    <!-- Custom JS to copy code -->
    <script>
    function copyToClipboard(button) {
        const codeBlock = button.previousElementSibling;
        const codeText = codeBlock.textContent;

        navigator.clipboard.writeText(codeText).then(function() {
            button.textContent = 'Copied!';
            setTimeout(() => {
                button.textContent = 'Copy';
            }, 2000);
        }, function(err) {
            console.error('Async: Could not copy text: ', err);
        });
    }
    </script>

</body>

</html>