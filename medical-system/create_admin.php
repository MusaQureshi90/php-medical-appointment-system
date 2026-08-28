<?php
include 'db.php';

// Admin credentials
$username = 'admin';
$password_plain = 'admin123';

// Hash the password
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// First delete if any old record exists
$sql_delete = "DELETE FROM admin WHERE username = '$username'";
mysqli_query($conn, $sql_delete);

// Insert fresh admin
$sql_insert = "INSERT INTO admin (username, password) VALUES ('$username', '$password_hashed')";
if (mysqli_query($conn, $sql_insert)) {
    echo "✅ Admin account created successfully.<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
