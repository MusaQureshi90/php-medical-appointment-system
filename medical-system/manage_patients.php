<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete
if (isset($_POST['delete_patient'])) {
    $patient_id = intval($_POST['patient_id']);

    // First delete related appointments
    mysqli_query($conn, "DELETE FROM appointments WHERE patient_id = $patient_id");

    // Now delete patient
    mysqli_query($conn, "DELETE FROM patients WHERE id = $patient_id");

    header("Location: manage_patients.php");
    exit();
}


// Fetch all patients
$result = mysqli_query($conn, "SELECT * FROM patients");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Patients</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Manage Patients</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a href="edit_patient.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure to delete this patient?');">
                        <input type="hidden" name="patient_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete_patient" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>
</body>
</html>
