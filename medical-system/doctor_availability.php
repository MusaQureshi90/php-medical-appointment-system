<?php
session_start();
require 'db.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $available_days = isset($_POST['available_days']) ? implode(',', $_POST['available_days']) : '';
    $time_slots = $_POST['time_slots'];

    $stmt = $conn->prepare("UPDATE doctors SET available_days = ?, time_slots = ? WHERE id = ?");
    $stmt->bind_param("ssi", $available_days, $time_slots, $doctor_id);
    $stmt->execute();
    $success = true;
}

// Fetch current values
$query = "SELECT available_days, time_slots FROM doctors WHERE id = $doctor_id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
$current_days = isset($data['available_days']) ? explode(',', $data['available_days']) : [];
$current_slots = $data['time_slots'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Availability</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Set Your Availability</h3>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">Availability updated successfully!</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Available Days:</label><br>
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                $checked = in_array($day, $current_days) ? 'checked' : '';
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' name='available_days[]' value='$day' $checked>
                        <label class='form-check-label'>$day</label>
                      </div>";
            }
            ?>
        </div>

        <div class="mb-3">
            <label for="time_slots" class="form-label">Available Time (e.g., 10:00 AM - 2:00 PM):</label>
            <input type="text" class="form-control" name="time_slots" value="<?= htmlspecialchars($current_slots) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Availability</button>
        <a href="doctor_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </form>
</div>
</body>
</html>
