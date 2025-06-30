<?php
session_start();
$conn = new mysqli("localhost", "root", "", "healthmate_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, doctor_id, appointment_time, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("sis", $patient_name, $doctor_id, $appointment_time);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='appointments.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>