<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_appointments.php");
    exit();
}

$id = intval($_GET['id']);
$patient_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT doctor_id, appointment_date, appointment_time, reason FROM appointments WHERE id = ? AND patient_id = ?");
$stmt->bind_param("ii", $id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: my_appointments.php");
    exit();
}

$appointment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = trim($_POST['reason']);

    $stmt = $conn->prepare("UPDATE appointments SET doctor_id=?, appointment_date=?, appointment_time=?, reason=?, status='pending' WHERE id=? AND patient_id=?");
    $stmt->bind_param("isssii", $doctor_id, $appointment_date, $appointment_time, $reason, $id, $patient_id);
    $stmt->execute();

    header("Location: my_appointments.php");
    exit();
}

$doctors = $conn->query("SELECT id, name FROM doctors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
        <h2 class="mb-4 text-center">Edit Appointment</h2>
        <form method="post">
            <div class="mb-3">
                <label for="doctor_id" class="form-label">Select Doctor</label>
                <select name="doctor_id" id="doctor_id" class="form-select" required>
                    <?php while ($doctor = $doctors->fetch_assoc()) { ?>
                        <option value="<?= $doctor['id'] ?>" <?= ($doctor['id'] == $appointment['doctor_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($doctor['name']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="appointment_date" class="form-label">Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['appointment_date']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="appointment_time" class="form-label">Time</label>
                <input type="time" name="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment['appointment_time']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="3" required><?= htmlspecialchars($appointment['reason']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Appointment</button>
            <a href="my_appointments.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>