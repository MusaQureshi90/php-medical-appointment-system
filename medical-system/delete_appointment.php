<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $patient_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND patient_id = ?");
    $stmt->bind_param("ii", $id, $patient_id);
    $stmt->execute();
}

header("Location: my_appointments.php");
exit();
?>