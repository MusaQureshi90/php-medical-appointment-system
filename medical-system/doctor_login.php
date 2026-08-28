<?php
session_start();
require 'db.php'; // Make sure this connects to the correct DB

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize
    $email = mysqli_real_escape_string($conn, $email);

    // Fetch doctor by email
    $query = "SELECT * FROM doctors WHERE email='$email'";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $doctor = mysqli_fetch_assoc($result);

        if (password_verify($password, $doctor['password'])) {
            $_SESSION['doctor_id'] = $doctor['id'];
            $_SESSION['doctor_name'] = $doctor['name'];
            header("Location: doctor_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Doctor not found or inactive.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Doctor Login</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
