<?php
include '../auth/config.php';
include '../auth/session.php';
include '../auth/fetch_db.php';

// Check if the user is logged in
if (!checkUserSession()) {
    // Redirect to the login page if not logged in
    header("Location: ../auth/login.php");
    exit();
}

// Retrieve user details from session
$user = getUserFromSession();
$ip_address = getIpAddress();

//Retrive Database info
$current_db= count_database($user['id']);
$total_db= 2;

$lastlogin=fetch_lastlogin();
?>
<?php include '../layouts/main.php'; ?>

<head>

    <?php includeFileWithVariables('../layouts/title-meta.php', array('title' => 'Dashboard')); ?>

    <?php include '../layouts/head-css.php'; ?>
    <!-- <link rel="stylesheet" href='dashboard.css'> -->
    <link rel="stylesheet" href="../assets/libs/apexcharts/apexcharts.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Darker semi-transparent background */
        }

        .modal-content {
            background-color: #444; /* Darker background color for the modal */
            margin: auto;
            padding: 40px; /* Increased padding for content */
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            color: #fff; /* White text color */
            border-radius: 10px; /* Rounded corners */
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Box shadow for depth */
            text-align: center; /* Center align text */
        }

        .modal-content h2 {
            margin-bottom: 20px; /* Space below the heading */
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>



</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include '../layouts/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <?php includeFileWithVariables('../layouts/page-title.php', array('pagetitle' => 'Dashboard', 'title' => 'Account Summary')); ?>

                    <div class="row">

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4 class="card-title mb-4">Database</h4></center>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div id="database-circle-progress" class="apex-charts"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card small-card fixed-height">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <h4 class="card-title mb-3">Plan</h4>
                                    <p class="text-muted mb-4">FREE Plan is active</p>
                                    <button class="btn btn-primary">Upgrade Now</button>
                                </div>
                            </div>
                            <div class="card small-card fixed-height">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <h4 class="card-title mb-3">Your IP</h4>
                                        <p class="text-muted mb-4"><?php echo htmlspecialchars($ip_address); ?></p>
                                </div>
                            </div>   
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card small-card fixed-height">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <h4 class="card-title mb-3">Server Status</h4>
                                    <div class="mb-4">
                                        <span class="badge badge-success" style="background-color: green;">Online</span>
                                    </div>
                                    <p class="text-muted">Last checked: <span id="last-checked"></span></p>
                                </div>
                            </div>
                            <div class="card small-card fixed-height">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <h4 class="card-title mb-3">Session Details</h4>
                                    <p class="text-muted mb-4">Last login: <span id="last-login"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- User Details Card -->
                     <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4 class="card-title mb-4">User Details</h4></center>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Username</th>
                                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Email</th>
                                                    <td> <?php echo htmlspecialchars($user['email']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">First Name</th>
                                                    <td> <?php echo htmlspecialchars($user['FirstName']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Last Name</th>
                                                    <td> <?php echo htmlspecialchars($user['LastName']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Phone</th>
                                                    <td> <?php echo htmlspecialchars($user['phone']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Database User Password</th>
                                                    <td> <?php echo htmlspecialchars($user['db_password']); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End User Details Card -->
                    
                    <!-- Modal for setting password -->
                    <div id="passwordModal" class="modal">
                        <div class="modal-content">
                            <h2>Set Database Password</h2>
                            <form action="set_password.php" method="post" onsubmit="return submitPasswordForm();">
                                <div class="form-group">
                                    <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                    <label for="newPassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../layouts/footer.php'; ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <?php include '../layouts/vendor-scripts.php'; ?>

    <!-- apexcharts -->
    <script src="../assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- projects js -->
    <script src="../assets/js/pages/dashboard-projects.init.js"></script>

    <!-- App js -->
    <script src="../assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            //check if database user is creared or not
            <?php if ($user['db_user_created'] == 0): ?>
                var modal = document.getElementById("passwordModal");
                modal.style.display = "block";

                window.onclick = function(event) {
                    if (event.target == modal) {
                        // Prevent closing modal by clicking outside of it
                        modal.style.display = "block";
                    }
                }
            <?php endif; ?>


            // Function to format the time difference
            function timeAgo(time) {
                const now = new Date();
                const secondsPast = Math.floor((now - time) / 1000);

                if (secondsPast < 60) {
                    return secondsPast + ' seconds ago';
                } else if (secondsPast < 3600) {
                    const minutesPast = Math.floor(secondsPast / 60);
                    return minutesPast + ' minutes ago';
                } else if (secondsPast < 86400) {
                    const hoursPast = Math.floor(secondsPast / 3600);
                    return hoursPast + ' hours ago';
                } else {
                    const daysPast = Math.floor(secondsPast / 86400);
                    return daysPast + ' days ago';
                }
            }

            // Get the initial date and time when the page loads
            const lastCheckedTime = new Date();
            // Convert the PHP last login time (IST) to a JavaScript Date object in UTC
            var gmtTime = "<?php echo date('c', strtotime($lastlogin)); ?>";
            var localTime = new Date(gmtTime);
                // Output the results to console for verification
            console.log("GMT Time: " + gmtTime);
            console.log("Local Time: " + localTime.toString());
            localTime.setTime(localTime.getTime() + (2 * 60 * 60 * 1000));


            // Update the last-checked element with the relative time initially
            document.getElementById('last-checked').textContent = timeAgo(lastCheckedTime);
            document.getElementById('last-login').textContent = localTime;

            // Function to update the "Last checked" time every second
            setInterval(() => {
                document.getElementById('last-checked').textContent = timeAgo(lastCheckedTime);
            }, 1000); // 1000 milliseconds = 1 second

            setInterval(() => {
                document.getElementById('last-login').textContent = localTime;
            }, 1000); // 1000 milliseconds = 1 second

            var totalDatabases = <?php echo $total_db; ?>;
            var currentDatabases = <?php echo $current_db; ?>;

            // Calculate the percentage
            var percentage = (currentDatabases / totalDatabases) * 100;

            var options = {
                series: [percentage], // Percentage value
                chart: {
                    height: 232,
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%',
                        },
                        dataLabels: {
                            showOn: 'always',
                            name: {
                                show: false,
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                color: '#111',
                                offsetY: 8,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            },
                            total: {
                                show: true,
                                label: 'Database',
                                formatter: function (w) {
                                    return currentDatabases + ' / ' + totalDatabases;
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#ABE5A1'],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: ['Progress']
            };

            var chart = new ApexCharts(document.querySelector("#database-circle-progress"), options);
            chart.render();

            // Updating the database count text dynamically
            document.getElementById('database-count').innerText = `${currentDatabases} / ${totalDatabases}`;
        });

        function submitPasswordForm() {
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (!newPassword || !confirmPassword) {
                alert("Both password fields are required");
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            // Allow form submission only if both passwords match and are set
            return true;
        }
</script>
<?php $conn->close();?>
</body>

</html>