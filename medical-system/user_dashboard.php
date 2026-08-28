<?php
session_start();

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            max-width: 500px;
            margin: auto;
            margin-top: 60px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 12px;
            background: white;
        }
        .btn-block {
            width: 100%;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h3 class="text-center mb-4">Welcome, <?= htmlspecialchars($name) ?> 👋</h3>

    <a href="view_doctors.php" class="btn btn-primary btn-block">👨‍⚕️ View Doctors</a>
    <a href="book_appointment.php" class="btn btn-success btn-block">📅 Book Appointment</a>
    <a href="my_appointments.php" class="btn btn-info btn-block">🗂 My Appointments</a>
    <a href="logout.php" class="btn btn-danger btn-block">🚪 Logout</a>
</div>

</body>
</html>
