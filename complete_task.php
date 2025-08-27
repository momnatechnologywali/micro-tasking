<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'worker') {
    echo "<script>window.location = 'login.php';</script>";
    exit;
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignment_id = $_POST['assignment_id'];
 
    // Update assignment to completed, add payment to worker balance
    $stmt = $conn->prepare("UPDATE assignments SET status = 'completed', completed_at = NOW() WHERE id = ? AND worker_id = ?");
    $stmt->bind_param("ii", $assignment_id, $_SESSION['user_id']);
    $stmt->execute();
 
    // Get payment and update balance
    $pay_stmt = $conn->prepare("SELECT t.payment FROM tasks t JOIN assignments a ON t.id = a.task_id WHERE a.id = ?");
    $pay_stmt->bind_param("i", $assignment_id);
    $pay_stmt->execute();
    $payment = $pay_stmt->get_result()->fetch_assoc()['payment'];
 
    $balance_stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $balance_stmt->bind_param("di", $payment, $_SESSION['user_id']);
    $balance_stmt->execute();
 
    echo "<script>alert('Task completed! Earnings added.'); window.location = 'dashboard.php';</script>";
}
?>
