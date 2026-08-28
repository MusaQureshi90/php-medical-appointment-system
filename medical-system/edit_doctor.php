<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Doctor ID not specified.";
    exit();
}

$doctor_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $available_days = mysqli_real_escape_string($conn, $_POST['available_days']);
    $time_slots = mysqli_real_escape_string($conn, $_POST['time_slots']);
    $password = $_POST['password'];

    $update_query = "UPDATE doctors SET 
        name='$name', 
        email='$email', 
        specialization='$specialization', 
        available_days='$available_days', 
        time_slots='$time_slots'";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password='$hashed_password'";
    }

    $update_query .= " WHERE id=$doctor_id";

    if (mysqli_query($conn, $update_query)) {
        header("Location: manage_doctors.php");
        exit();
    } else {
        echo "Failed to update doctor.";
    }
}

// Fetch doctor info
$doctor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE id=$doctor_id"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Doctor</h3>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Specialization</label>
            <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($doctor['specialization']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Available Days</label>
            <input type="text" name="available_days" class="form-control" value="<?= htmlspecialchars($doctor['available_days']) ?>">
        </div>
        <div class="mb-3">
            <label>Time Slots</label>
            <input type="text" name="time_slots" class="form-control" value="<?= htmlspecialchars($doctor['time_slots']) ?>">
        </div>
        <div class="mb-3">
            <label>New Password (leave blank to keep unchanged)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Update Doctor</button>
        <a href="manage_doctors.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
