<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_patients.php");
    exit();
}

$patient_id = intval($_GET['id']);
$message = "";

// Fetch current patient data
$result = mysqli_query($conn, "SELECT * FROM patients WHERE id = $patient_id");
$patient = mysqli_fetch_assoc($result);

// Handle update
if (isset($_POST['update_patient'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    mysqli_query($conn, "UPDATE patients SET name = '$name', email = '$email' WHERE id = $patient_id");
    $message = "Patient updated successfully!";
    // Refresh data
    $result = mysqli_query($conn, "SELECT * FROM patients WHERE id = $patient_id");
    $patient = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Patient</h3>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($patient['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>" class="form-control" required>
        </div>
        <button type="submit" name="update_patient" class="btn btn-primary">Update Patient</button>
        <a href="manage_patients.php" class="btn btn-secondary">← Back</a>
    </form>
</div>
</body>
</html>
