<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'requester') {
    echo "<script>window.location = 'login.php';</script>";
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $deadline = $_POST['deadline'];
    $payment = $_POST['payment'];
    $requester_id = $_SESSION['user_id'];
 
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, category, deadline, payment, requester_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdi", $title, $description, $category, $deadline, $payment, $requester_id);
 
    if ($stmt->execute()) {
        echo "<script>alert('Task posted successfully!'); window.location = 'marketplace.php';</script>";
    } else {
        echo "<script>alert('Error posting task.'); window.location = 'post_task.php';</script>";
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Task</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); margin: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        form input, form textarea, form select { display: block; width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 4px; transition: background 0.3s; }
        button:hover { background: #1e7e34; }
        @media (max-width: 768px) { .container { padding: 15px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Post a New Task</h2>
        <form action="post_task.php" method="POST">
            <input type="text" name="title" placeholder="Task Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="data_entry">Data Entry</option>
                <option value="survey">Survey</option>
                <option value="transcription">Transcription</option>
                <option value="other">Other</option>
            </select>
            <input type="date" name="deadline" required>
            <input type="number" name="payment" placeholder="Payment Amount" step="0.01" required>
            <button type="submit">Post Task</button>
        </form>
    </div>
</body>
</html>
