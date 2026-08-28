<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$doctors = mysqli_query($conn, "SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Doctors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Available Doctors</h3>

    <?php if (mysqli_num_rows($doctors) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Available Days</th>
                    <th>Time Slots</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($doctors)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['specialization']) ?></td>
                    <td><?= htmlspecialchars($row['available_days']) ?></td>
                    <td><?= htmlspecialchars($row['time_slots']) ?></td>
                    <td>
                        <a href="book_appointment.php?doctor_id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Book Appointment</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No doctors available at the moment.</div>
    <?php endif; ?>

    <a href="user_dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>
</body>
</html>
