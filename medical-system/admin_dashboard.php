<?php
session_start();
require 'db.php';

// Security check for admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch counts for dashboard cards
$total_patients = $conn->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'];
$total_doctors = $conn->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc()['count'];
$total_appointments = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            min-height: 100vh;
            padding: 50px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        .dashboard-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
            color: #333;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .btn-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <h2 class="text-center mb-4">Welcome, Medical Office Manager!</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white text-center p-4">
                <h4>Total Patients</h4>
                <h2><?= $total_patients ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white text-center p-4">
                <h4>Total Doctors</h4>
                <h2><?= $total_doctors ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark text-center p-4">
                <h4>Total Appointments</h4>
                <h2><?= $total_appointments ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <a href="manage_patients.php" class="btn btn-outline-primary w-100 p-3 fs-5">Manage Patients</a>
        </div>
        <div class="col-md-4">
        
    
	<a href="manage_doctors.php" class="btn btn-outline-success w-100 p-3 fs-5">Manage Doctors</a>
        </div>
        <div class="col-md-4">
            <a href="manage_appointments.php" class="btn btn-outline-warning w-100 p-3 fs-5">Manage Appointments</a>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="admin_logout.php" class="btn btn-danger px-5 py-2 fs-5">Logout</a>
	
    </div>
</div>

</body>
</html>
