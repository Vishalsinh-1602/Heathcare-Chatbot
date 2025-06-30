<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthmate_db";  // Update to healthmate_db

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$medicineName = $_POST['medicine-name'];
$medicineTimes = $_POST['medicine-times'];

// Insert medication data into the database
$stmt = $conn->prepare("INSERT INTO medications (medicine_name, times_per_day) VALUES (?, ?)");
$stmt->bind_param("si", $medicineName, $medicineTimes);
$stmt->execute();

// Get last inserted medication ID
$medicationId = $stmt->insert_id;

// Insert alarm times into the alarms table
for ($i = 1; $i <= $medicineTimes; $i++) {
    $alarmTime = $_POST["alarm-time-" . $i];
    
    if ($alarmTime) {
        // Insert alarm times associated with medication
        $stmtAlarm = $conn->prepare("INSERT INTO alarms (medication_id, alarm_time) VALUES (?, ?)");
        $stmtAlarm->bind_param("is", $medicationId, $alarmTime);
        $stmtAlarm->execute();
    }
}

echo "Medication and alarm times saved successfully!";

// Close connections
$stmt->close();
$stmtAlarm->close();
$conn->close();
?>
