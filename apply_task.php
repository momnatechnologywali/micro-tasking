<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'worker') {
    echo "<script>window.location = 'login.php';</script>";
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $worker_id = $_SESSION['user_id'];
 
    // Check if already applied
    $check_stmt = $conn->prepare("SELECT id FROM assignments WHERE task_id = ? AND worker_id = ?");
    $check_stmt->bind_param("ii", $task_id, $worker_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        echo "<script>alert('You already applied to this task.'); window.location = 'marketplace.php';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO assignments (task_id, worker_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $task_id, $worker_id);
        if ($stmt->execute()) {
            echo "<script>alert('Applied successfully!'); window.location = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Error applying.'); window.location = 'marketplace.php';</script>";
        }
    }
}
?>
