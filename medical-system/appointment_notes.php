<?php
session_start();
require 'db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$appointment_id = $_GET['id'] ?? 0;
$doctor_id = $_SESSION['doctor_id'];
$success = false;

// Fetch appointment
$result = mysqli_query($conn, "
    SELECT a.*, p.name AS patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    WHERE a.id = $appointment_id AND a.doctor_id = $doctor_id
");

if (!$result || mysqli_num_rows($result) == 0) {
    die("Invalid appointment.");
}

$appointment = mysqli_fetch_assoc($result);

// Handle note submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    mysqli_query($conn, "UPDATE appointments SET notes = '$notes' WHERE id = $appointment_id");
    $success = true;
    $appointment['notes'] = $notes;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h4 class="mb-3">Notes for Appointment with <strong><?= htmlspecialchars($appointment['patient_name']) ?></strong></h4>

    <?php if ($success): ?>
        <div class="alert alert-success">Notes updated successfully!</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="notes" class="form-label">Enter Notes:</label>
            <textarea name="notes" id="notes" rows="6" class="form-control"><?= htmlspecialchars($appointment['notes']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">💾 Save Notes</button>
        <a href="doctor_appointments.php" class="btn btn-secondary">← Back to Appointments</a>
    </form>
</div>
</body>
</html>
