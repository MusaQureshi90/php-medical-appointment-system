<?php
session_start();
require 'db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Total appointments
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = $doctor_id"))['total'];

// Pending
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = $doctor_id AND status = 'pending'"))['total'];

// Completed
$completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = $doctor_id AND status = 'completed'"))['total'];

// Cancelled
$cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = $doctor_id AND status = 'cancelled'"))['total'];

// Next Appointment
$next_result = mysqli_query($conn, "
    SELECT a.appointment_date, a.appointment_time, p.name AS patient_name 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.id 
    WHERE a.doctor_id = $doctor_id AND a.status = 'pending' AND a.appointment_date >= CURDATE()
    ORDER BY a.appointment_date ASC, a.appointment_time ASC LIMIT 1
");
$next = mysqli_fetch_assoc($next_result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">👨‍⚕️ Welcome, Doctor!</h3>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h4>Total Appointments</h4>
                    <h2><?= $total ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h4>Pending</h4>
                    <h2><?= $pending ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h4>Completed</h4>
                    <h2><?= $completed ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h4>Cancelled</h4>
                    <h2><?= $cancelled ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5>📅 Next Appointment</h5>
        </div>
        <div class="card-body">
            <?php if ($next): ?>
                <p><strong>Patient:</strong> <?= htmlspecialchars($next['patient_name']) ?></p>
                <p><strong>Date:</strong> <?= $next['appointment_date'] ?></p>
                <p><strong>Time:</strong> <?= $next['appointment_time'] ?></p>
            <?php else: ?>
                <p class="text-muted">No upcoming appointment found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="doctor_appointments.php" class="btn btn-outline-primary">View & Manage Appointments</a>
        <a href="doctor_availability.php" class="btn btn-outline-secondary">Set Availability</a>
        <a href="logout.php" class="btn btn-danger float-end">Logout</a>
    </div>
</div>
</body>
</html>
