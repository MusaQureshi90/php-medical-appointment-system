<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get doctor_id from query parameter
if (!isset($_GET['doctor_id'])) {
    echo "<h3 class='text-danger text-center mt-5'>Doctor not specified.</h3>";
    exit();
}

$doctor_id = $_GET['doctor_id'];
$patient_id = $_SESSION['user_id'];

// Fetch doctor details
$doctor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE id = $doctor_id"));
if (!$doctor) {
    echo "<h3 class='text-danger text-center mt-5'>Doctor not found.</h3>";
    exit();
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) 
              VALUES ('$patient_id', '$doctor_id', '$date', '$time', '$reason', 'Pending')";

    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success'>Appointment booked successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to book appointment.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Book Appointment with <?= htmlspecialchars($doctor['name']) ?></h3>

    <?= $message ?>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Available Days:</label>
            <input type="text" class="form-control" value="<?= $doctor['available_days'] ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Time Slots:</label>
            <input type="text" class="form-control" value="<?= $doctor['time_slots'] ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Appointment Date:</label>
            <input type="date" name="appointment_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Appointment Time:</label>
            <select name="appointment_time" class="form-select" required>
                <?php
                    $slots = explode(',', $doctor['time_slots']);
                    foreach ($slots as $slot) {
                        echo "<option value='" . trim($slot) . "'>" . trim($slot) . "</option>";
                    }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Reason for Appointment:</label>
            <textarea name="reason" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Book Now</button>
        <a href="view_doctors.php" class="btn btn-secondary">← Back to Doctors</a>
    </form>
</div>
</body>
</html>
