<?php
session_start();
require 'db.php';

// Only admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM doctors WHERE id = $id");
}

header("Location: manage_doctors.php");
exit();
