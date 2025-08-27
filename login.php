<?php
session_start();
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
 
    $stmt = $conn->prepare("SELECT id, password, type FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
 
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user['type'];
        echo "<script>window.location = 'index.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials.'); window.location = 'login.php';</script>";
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Similar kamal CSS */
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); margin: 0; }
        .container { max-width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        form input { display: block; width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 4px; transition: background 0.3s; }
        button:hover { background: #0056b3; }
        @media (max-width: 768px) { .container { margin: 50px auto; padding: 15px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Not registered? <a href="index.php">Sign Up</a></p>
    </div>
</body>
</html>
