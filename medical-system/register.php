<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO patients (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    $_SESSION['success'] = "Registration successful. Please login.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="style.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>

<div class="container animate__animated animate__fadeInDown">
    <h2 class="mb-4">Patient Registration</h2>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <input type="submit" class="btn btn-success w-100" value="Register">
    </form>

    <div class="mt-3 text-center">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>

</body>
</html>
