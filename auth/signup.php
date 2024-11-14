<?php
require_once 'config.php';
include 'session.php';

$conn = getDbConnection();

if (checkUserSession()) {
    header("Location: ../dashboard/dashboard.php");
    exit();
}

// Define variables and initialize with empty values
$useremail = $username =  $firstname = $lastname = $password = $confirm_password = $phone = "";
$useremail_err = $username_err= $firstname_err= $lastname_err = $password_err = $confirm_password_err = $phone_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
            
        }
    }

    //validate first Name
    if (empty(trim($_POST["firstname"]))) {
        $firstname_err = "Please enter First Name.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    //validate Last Name
    if (empty(trim($_POST["lastname"]))) {
        $lastname_err = "Please enter Last Name.";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have atleast 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    //Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please enter a confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate useremail
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter Email.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid Email format";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = trim($_POST["useremail"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $useremail_err = "This Email is already Registered.";
                } else {
                    $useremail = trim($_POST["useremail"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
            
        }
    }

    // Check input errors before inserting in database
    if (empty($useremail_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, FirstName, LastName, email, password, phone, created_at, modified_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_firstname, $param_lastname, $param_email, $param_password, $param_phone);

            // Set parameters
            $param_email = $useremail;
            $param_username = $username;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_phone = $phone;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to Login page
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);

}
?>

<?php include '../layouts/main.php'; ?>

<head>

    <?php includeFileWithVariables('../layouts/title-meta.php', array('title' => 'Sign Up')); ?>

    <?php include '../layouts/head-css.php'; ?>

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.php" class="d-inline-block auth-logo">
                                    <img src="../assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Register for a free database and enjoy its benefits.</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Create New Account</h5>
                                    <p class="text-muted">Get your free D@taBase account now</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form class="needs-validation" novalidate action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                                        <div class="mb-3 <?= !empty($useremail_err) ? 'has-error' : ''; ?>">
                                            <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="useremail" value="<?=$useremail?>" id="useremail" placeholder="Enter email address" required>  
                                            <span class="text-danger"><?=$useremail_err?></span>
                                            <div class="invalid-feedback">
                                                Please enter email
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($username_err) ? 'has-error' : ''; ?>">
                                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="username" value="<?=$username?>" id="username" placeholder="Enter username" required>
                                            <span class="text-danger"><?=$username_err?></span>
                                            <div class="invalid-feedback">
                                                Please enter username
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($firstname_err) ? 'has-error' : ''; ?>">
                                            <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="firstname" value="<?=$firstname?>" id="firstname" placeholder="Enter First Name" required>
                                            <span class="text-danger"><?=$firstname_err?></span>
                                            <div class="invalid-feedback">
                                                Please enter First Name
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($lastname_err) ? 'has-error' : ''; ?>">
                                            <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="lastname" value="<?=$lastname?>" id="lastname" placeholder="Enter Last Name" required>
                                            <span class="text-danger"><?=$lastname_err?></span>
                                            <div class="invalid-feedback">
                                                Please enter Last Name
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($phone_err) ? 'has-error' : ''; ?>">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="phone" class="form-control" name="phone" value="<?=$phone?>" id="phone" placeholder="Enter Phone Number(With Country Code without +)" required>  
                                            <span class="text-danger"><?=$phone_err?></span>
                                            <div class="invalid-feedback">
                                                Please enter Phone Number
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input" name="password" value="<?=$password?>" onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                <span class="text-danger"><?=$password_err?></span>
                                                <div class="invalid-feedback">
                                                    Please enter password
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3 <?= !empty($confirm_password_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="password-input">Confirm Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input" name="confirm_password" value="<?=$confirm_password?>" onpaste="return false" placeholder="Enter Confirm password" id="password-input" aria-describedby="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                <span class="text-danger"><?=$confirm_password_err?></span>
                                                <div class="invalid-feedback">
                                                    Please enter Confirm password
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="mb-0 fs-13 text-muted fst-italic">By registering you agree to the D@taBase. <a href="#" class="text-primary text-decoration-underline fst-normal fw-semibold">Terms of Use</a></p>
                                        </div>

                                        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-14">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-13 mb-2">Minimum <b>8 characters</b></p>
                                            <p id="pass-lower" class="invalid fs-13 mb-2">At <b>lowercase</b> letter (a-z)</p>
                                            <p id="pass-upper" class="invalid fs-13 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-13 mb-0">A least <b>number</b> (0-9)</p>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-info w-100" type="submit">Sign Up</button>
                                        </div>

                                        <!-- <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                            </div>

                                            <div>
                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div> -->
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Already have an account ? <a href="login.php" class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>document.write(new Date().getFullYear())</script> D@taBase. made with <i class="mdi mdi-heart text-danger"></i> by I®SPACE.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <?php include '../layouts/vendor-scripts.php'; ?>

    <!-- particles js -->
    <script src="../assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="../assets/js/pages/particles.app.js"></script>
    <!-- validation init -->
    <script src="../assets/js/pages/form-validation.init.js"></script>
    <!-- password create init -->
    <script src="../assets/js/pages/passowrd-create.init.js"></script>
</body>

</html>