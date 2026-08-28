<?php
include 'db.php';

// Fetch all doctors
$result = mysqli_query($conn, "SELECT * FROM doctors");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">⬅️ Back to Dashboard</a>
    <a href="add_doctor.php" class="btn btn-primary mb-3">➕ Add New Doctor</a>

    <h2>All Doctors</h2>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Available Days</th>
                <th>Time Slots</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['specialization'] ?></td>
                <td><?= $row['available_days'] ?? '—' ?></td>
                <td><?= $row['time_slots'] ?? '—' ?></td>
                <td><?= $row['password'] ?? '—' ?></td>
                <td>
                    <a href="edit_doctor.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_doctor.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
