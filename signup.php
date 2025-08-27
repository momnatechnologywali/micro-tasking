<?php
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type'];
 
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $type);
 
    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Redirecting to login.'); window.location = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: Username or email already exists.'); window.location = 'index.php';</script>";
    }
}
?>
