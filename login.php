<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthmate_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];

// Check if user exists
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start session and store user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        // Set session variable for login success
        $_SESSION['login_success'] = true;
        header("Location: profile.php");
        exit();
    } else {
        echo "Invalid password";
    }
} else {
    echo "User not found";
}

$conn->close();
?>