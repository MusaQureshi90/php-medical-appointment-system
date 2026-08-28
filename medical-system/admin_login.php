<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Corrected query (no 'name' column issue)
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $db_username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_username'] = $db_username;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin found with this username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Office Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gradient bg-primary bg-opacity-75 d-flex align-items-center justify-content-center" style="height:100vh;">

<div class="card p-4 shadow-lg animate__animated animate__fadeIn">
    <h2 class="text-center mb-4">Medical Office Manager</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
