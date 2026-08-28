<?php
session_start();
require 'db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Handle status filter
$status_filter = isset($_GET['status']) ? strtolower($_GET['status']) : 'all';
$where_clause = "WHERE a.doctor_id = $doctor_id";

if ($status_filter !== 'all') {
    $where_clause .= " AND LOWER(a.status) = '$status_filter'";
}

// Fetch appointments with patient name
$query = "
    SELECT a.id, a.appointment_date, a.appointment_time, a.status, p.name AS patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    $where_clause
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

    <!-- 🔽 Filter Form -->
    <form method="get" class="mb-3 d-flex align-items-center gap-2">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status" class="form-select w-auto">
            <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>All</option>
            <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $status_filter == 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= $status_filter == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-primary">Apply</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                        <td><strong><?= ucfirst($row['status']) ?></strong></td>
                        <td>
    <?php if (strtolower($row['status']) == 'pending'): ?>
        <form method="post" action="update_appointment_status.php" class="d-inline">
            <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
            <select name="status" class="form-select form-select-sm d-inline w-auto">
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <button type="submit" class="btn btn-sm btn-success">Update</button>
        </form>
    <?php else: ?>
        <span class="text-muted">No action</span>
    <?php endif; ?>
    <br>
    <a href="appointment_notes.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info mt-1">📝 Notes</a>
</td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No appointments found.</div>
    <?php endif; ?>

    <a href="doctor_dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</div>
</body>
</html>
