<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle status update
if (isset($_POST['update_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = mysqli_real_escape_string($conn, strtolower($_POST['status'])); // lowercase for DB
    mysqli_query($conn, "UPDATE appointments SET status='$new_status' WHERE id=$appointment_id");
}

// ✅ Handle delete
if (isset($_POST['delete_appointment'])) {
    $appointment_id = intval($_POST['appointment_id']);

    // Delete the appointment safely
    mysqli_query($conn, "DELETE FROM appointments WHERE id=$appointment_id");
}

// ✅ Fetch all appointments with patient + doctor info
$result = mysqli_query($conn, "
    SELECT a.id, p.name AS patient_name, d.name AS doctor_name, d.specialization,
           a.appointment_date, a.appointment_time, a.reason, a.status
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.appointment_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Appointments - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Manage All Appointments</h3>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['specialization']) ?></td>
                <td><?= $row['appointment_date'] ?></td>
                <td><?= $row['appointment_time'] ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td><strong><?= ucfirst($row['status']) ?></strong></td>

                <!-- ✅ Update Status Dropdown -->
                <td>
                    <form method="post" class="d-flex">
                        <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                        <select name="status" class="form-select form-select-sm me-1">
                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-sm btn-success">Update</button>
                    </form>
                </td>

                <!-- ❌ Delete Appointment -->
                <td>
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                        <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete_appointment" class="btn btn-sm btn-danger">Delete</button>
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
