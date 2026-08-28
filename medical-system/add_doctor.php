<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $available_days = $_POST['available_days'];
    $time_slots = $_POST['time_slots'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing

    $sql = "INSERT INTO doctors (name, email, specialization, available_days, time_slots, password)
            VALUES ('$name', '$email', '$specialization', '$available_days', '$time_slots', '$password')";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_doctors.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <a href="manage_doctors.php" class="btn btn-secondary mb-3">⬅️ Back to Manage Doctors</a>
    <h2>Add New Doctor</h2>
    <form method="post" action="add_doctor.php">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label>Specialization:</label>
            <input type="text" name="specialization" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Available Days (e.g. Mon,Wed,Fri):</label>
            <input type="text" name="available_days" class="form-control">
        </div>
        <div class="form-group">
            <label>Time Slots (e.g. 10:00-12:00,14:00-16:00):</label>
            <input type="text" name="time_slots" class="form-control">
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Add Doctor</button>
    </form>
</div>
</body>
</html>
