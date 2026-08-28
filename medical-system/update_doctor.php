<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_doctors.php");
    exit();
}

$doctor_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $stmt = $conn->prepare("UPDATE doctors SET name = ?, specialization = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $name, $specialization, $email, $phone, $address, $doctor_id);
    $stmt->execute();

    header("Location: manage_doctors.php");
    exit();
}

// Fetch doctor data
$stmt = $conn->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: manage_doctors.php");
    exit();
}

$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4b6cb7, #182848);
            color: white;
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            color: #333;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center">Edit Doctor</h2>

    <form method="POST">
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Specialization:</label>
            <input type="text" name="specialization" class="form-control" value="<?= htmlspecialchars($doctor['specialization']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($doctor['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Address:</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($doctor['address']) ?></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Doctor</button>
            <a href="manage_doctors.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
