<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM patients WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $patient = mysqli_fetch_assoc($result);

        if (password_verify($password, $patient['password'])) {
            $_SESSION['user_id'] = $patient['id'];
            $_SESSION['user_name'] = $patient['name'];
            header("Location: user_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3 class="text-center mb-4">Patient Login</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="card p-4 shadow-sm mx-auto" style="max-width: 400px;">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
