<?php
session_start();
require '../includes/config.inc.php';

if (isset($_POST['mark_attendance'])) {
    // Loop through each student and mark attendance
    foreach ($_POST['attendance'] as $student_id => $status) {
        $date = date('Y-m-d'); // Current date
        $query = "INSERT INTO attendance (Student_id, Status, Date) VALUES ('$student_id', '$status', '$date') 
                  ON DUPLICATE KEY UPDATE Status = '$status'";
        mysqli_query($conn, $query);
    }
    echo "<script>alert('Attendance has been marked successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mark Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../web_home/css_home/bootstrap.css">
    <link rel="stylesheet" href="../web_home/css_home/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="../web_home/css_home/fontawesome-all.css">
    <link href="//fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>
<div class="inner-page-banner" id="home">
    <header>
        <div class="container agile-banner_nav">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <h1><a class="navbar-brand" href="admin_home.php">VFSTR</a></h1>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="admin_home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="students.php">Students</a>
                        </li>
                        <li class="dropdown nav-item">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <?php echo $_SESSION['username']; ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="admin_profile.php">My Profile</a></li>
                                <li><a href="../includes/logout.inc.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</div>

<section class="contact py-5">
    <div class="container">
        <h2 class="heading text-capitalize mb-sm-5 mb-4">Mark Attendance</h2>

        <form method="POST" action="attendance.php">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Hostel</th>
                        <th>Room Number</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Fetch all students
                $query = "SELECT * FROM Student LIMIT 30";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $student_id = $row['Student_id'];
                        $student_name = $row['Fname'] . " " . $row['Lname'];

                        // Get room number and hostel name
                        $room_id = $row['Room_id'];
                        $room_no = 'None';
                        if ($room_id) {
                            $room_query = "SELECT Room_No FROM Room WHERE Room_id = '$room_id'";
                            $room_result = mysqli_query($conn, $room_query);
                            $room_row = mysqli_fetch_assoc($room_result);
                            $room_no = $room_row['Room_No'] ?? 'None';
                        }

                        $hostel_id = $row['Hostel_id'];
                        $hostel_name = 'None';
                        if ($hostel_id) {
                            $hostel_query = "SELECT Hostel_name FROM Hostel WHERE Hostel_id = '$hostel_id'";
                            $hostel_result = mysqli_query($conn, $hostel_query);
                            $hostel_row = mysqli_fetch_assoc($hostel_result);
                            $hostel_name = $hostel_row['Hostel_name'] ?? 'None';
                        }

                        echo "<tr>
                            <td>{$student_name}</td>
                            <td>{$student_id}</td>
                            <td>{$hostel_name}</td>
                            <td>{$room_no}</td>
                            <td>
                                <input type='radio' name='attendance[$student_id]' value='Present' required> Present
                                <input type='radio' name='attendance[$student_id]' value='Absent'> Absent
                            </td>
                          </tr>";
                    }
                } else {
                    echo '<tr><td colspan="5">No students found.</td></tr>';
                }
                ?>
                </tbody>
            </table>
            <input type="submit" class="btn btn-primary" name="mark_attendance" value="Mark Attendance">
        </form>
    </div>
</section>

<footer class="py-5">
    <div class="container py-xl-5 py-lg-3">
        <div class="row footer-grids pt-lg-3">
            <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
                <h2>Contact Us</h2>
                <p><span class="fa fa-map-marker"></span> Vadlamudi, Guntur, Andhra Pradesh, India.</p>
                <p><span class="fa fa-envelope"></span> info@vignan.ac.in </p>
                <p><span class="fa fa-phone"></span> 0863-2344-700 </p>
            </div>
        </div>
        <div class="copyright mt-sm-5 mt-4 text-center">
            <p>© 2024 VFSTR. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="../web_home/js/jquery-2.2.3.min.js"></script>
<script src="../web_home/js/move-top.js"></script>
<script src="../web_home/js/easing.js"></script>
<script>
    $(document).ready(function () {
        $().UItoTop({ easingType: 'easeOutQuart' });
    });
</script>
<script src="../web_home/js/SmoothScroll.min.js"></script>

</body>
</html>
