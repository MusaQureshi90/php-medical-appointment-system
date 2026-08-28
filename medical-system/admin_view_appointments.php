<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Handle status update if form submitted
if (isset($_POST['update_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = mysqli_real_escape_string($conn, strtolower($_POST['status']));

    $update_sql = "UPDATE appointments SET status='$new_status' WHERE id=$appointment_id";
    mysqli_query($conn, $update_sql);
}

// Fetch appointments with patient name and doctor name using JOIN
$result = mysqli_query($conn, "
    SELECT a.id, p.name as user_name, d.name as doctor_name, d.specialization, 
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
    <title>Admin - View Appointments</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 0; }
        .header { background-color: #0d6efd; color: white; padding: 20px; text-align: center; }
        table { margin: 40px auto; border-collapse: collapse; width: 95%; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #0d6efd; color: white; }
        a.button { display: inline-block; margin: 20px auto; padding: 10px 20px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 8px; }
        a.button:hover { background-color: #084298; }
        select, button { padding: 5px; }
    </style>
</head>
<body>

<div class="header">
    <h1>View All Appointments</h1>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Doctor Name</th>
        <th>Specialization</th>
        <th>Date</th>
        <th>Time</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
        <td><?php echo $row['appointment_date']; ?></td>
        <td><?php echo $row['appointment_time']; ?></td>
        <td><?php echo htmlspecialchars($row['reason']); ?></td>
        <td><?php echo ucfirst($row['status']); ?></td>
        <td>
            <form method="post">
                <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                <select name="status">
                    <option value="pending" <?php if(strtolower($row['status'])=='pending') echo 'selected'; ?>>Pending</option>
                    <option value="completed" <?php if(strtolower($row['status'])=='completed') echo 'selected'; ?>>Completed</option>
                    <option value="cancelled" <?php if(strtolower($row['status'])=='cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
                <button type="submit" name="update_status">Update</button>
            </form>
        </td>
    </tr>
    <?php } ?>

</table>

<div style="text-align: center;">
    <a class="button" href="admin_dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>