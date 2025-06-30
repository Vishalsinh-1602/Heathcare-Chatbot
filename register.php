<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "healthmate_db";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a 32-character secret key for AES-256 encryption (replace with your own secure key)
define('ENCRYPTION_KEY', '1qafg562rsf#$2hdkahjkjq!qwjhgk#r'); // Must be exactly 32 characters

// Function to encrypt data using AES-256-CBC
function encryptData($data) {
    $cipher = "AES-256-CBC";
    $key = ENCRYPTION_KEY;
    // Get the required IV length for the cipher method
    $ivlen = openssl_cipher_iv_length($cipher);
    // Generate a secure IV
    $iv = openssl_random_pseudo_bytes($ivlen);
    // Encrypt the data
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    // Return the IV and encrypted data combined and base64-encoded
    return base64_encode($iv . $encrypted);
}

// Get form data (ensure your form uses method="post")
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$phone = $_POST['phone'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];

// Basic validation: Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Encrypt sensitive data
$encryptedEmail = encryptData($email);
$encryptedPhone = encryptData($phone);
$encryptedGender = encryptData($gender);
$encryptedDob = encryptData($dob);

// Insert data into database
$sql = "INSERT INTO users (username, email, password, phone, gender, dob) 
        VALUES ('$username', '$encryptedEmail', '$hashed_password', '$encryptedPhone', '$encryptedGender', '$encryptedDob')";

if ($conn->query($sql) === TRUE) {
    // Redirect to the landing page with a success message
    header("Location: index.html?registration=success");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>