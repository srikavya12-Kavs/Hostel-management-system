<?php
session_start();
if (isset($_POST['attendance_submit'])) {

    require 'config.inc.php';

    // Check if attendance data is received
    if (!isset($_POST['attendance']) || empty($_POST['attendance'])) {
        echo "<script>alert('No attendance data submitted.'); window.location='students_attend.php';</script>";
        exit();
    }

    $attendanceData = $_POST['attendance'];

    foreach ($attendanceData as $student_id => $status) {
        // Debugging: Check what data is being processed
        echo "<script>console.log('Processing: Student ID: $student_id, Status: $status');</script>";

        // Check if the record already exists
        $sql = "SELECT * FROM attendance WHERE Student_id=? AND Date=?";
        $stmt = mysqli_stmt_init($conn);
        
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script>alert('Database error. Please try again.'); window.location='students_attend.php';</script>";
            exit();
        } 
        
        mysqli_stmt_bind_param($stmt, "ss", $student_id, date('Y-m-d'));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultCheck = mysqli_stmt_num_rows($stmt);

        if ($resultCheck == 0) {
            // Insert new attendance record
            $insertSql = "INSERT INTO attendance (Student_id, Status, Date) VALUES (?, ?, ?)";
            if (!mysqli_stmt_prepare($stmt, $insertSql)) {
                echo "<script>alert('Database error while inserting. Please try again.'); window.location='students_attend.php';</script>";
                exit();
            } 

            $date = date('Y-m-d');
            mysqli_stmt_bind_param($stmt, "sss", $student_id, $status, $date);
            mysqli_stmt_execute($stmt);
        }
    }

    echo "<script>alert('Attendance has been submitted successfully.'); window.location='students_attend.php';</script>";
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "<script>alert(' data submitted.'); window.location='students_attend.php';</script>";
    exit();
}
?>
