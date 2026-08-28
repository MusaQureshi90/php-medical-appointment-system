<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch all appointments for the logged-in patient
$query = "
    SELECT a.id, a.appointment_date, a.appointment_time, a.reason, a.status,
           d.name AS doctor_name, d.specialization
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id = $patient_id
    ORDER BY a.appointment_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">My Appointments</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= $row['appointment_date'] ?></td>
                    <td><?= $row['appointment_time'] ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td><strong><?= $row['status'] ?></strong></td>
                    <td>
                        <?php if (strtolower($row['status']) === 'pending'): ?>
                            <form action="update_appointment_status.php" method="post" class="d-inline">
                                <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="status" value="Cancelled">
                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">No action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">You have no appointments.</div>
    <?php endif; ?>

    <a href="user_dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>
</body>
</html>
