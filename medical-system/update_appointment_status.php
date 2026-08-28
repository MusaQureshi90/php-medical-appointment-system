<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['appointment_id']) || !isset($_POST['status'])) {
        die("Missing data.");
    }

    $appointment_id = (int) $_POST['appointment_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // Optional: Validate allowed status values
    $valid_statuses = ['pending', 'completed', 'cancelled'];
    if (!in_array(strtolower($new_status), $valid_statuses)) {
        die("Invalid status value.");
    }

    $query = "UPDATE appointments SET status = '$new_status' WHERE id = $appointment_id";

    if (mysqli_query($conn, $query)) {
        // ✅ Dynamic redirect based on role
        if (isset($_SESSION['doctor_id'])) {
            header("Location: doctor_appointments.php");
        } elseif (isset($_SESSION['admin_id'])) {
            header("Location: manage_appointments.php");
        } elseif (isset($_SESSION['user_id'])) {
            header("Location: my_appointments.php");
        } else {
            header("Location: login.php");
        }
        exit();
    } else {
        echo "❌ Error updating status: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}
