<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Database connection details
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

// Define the same 32-character secret key used for encryption
define('ENCRYPTION_KEY', '1qafg562rsf#$2hdkahjkjq!qwjhgk#r'); // Replace with your secure key

// Decryption function using AES-256-CBC
function decryptData($data) {
    $cipher = "AES-256-CBC";
    $key = ENCRYPTION_KEY;
    // Base64 decode the data
    $data = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher);
    // Extract the IV which is prepended to the encrypted data
    $iv = substr($data, 0, $ivlen);
    $encrypted = substr($data, $ivlen);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Retrieve user information using the session user_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, phone, gender, dob FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// If user not found, destroy session and redirect to login
if (!$user) {
    session_destroy();
    header("Location: login.html");
    exit();
}

// Decrypt the sensitive data retrieved from the database
$decryptedEmail  = decryptData($user['email']);
$decryptedPhone  = decryptData($user['phone']);
$decryptedGender = decryptData($user['gender']);
$decryptedDob    = decryptData($user['dob']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            background: white;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($decryptedEmail); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($decryptedPhone); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($decryptedGender); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($decryptedDob); ?></p>
    <a href="logout.php">Logout</a>
    <a href="index.html">Home</a>
</div>

</body>
</html>