<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'worker') {
    echo "<script>window.location = 'login.php';</script>";
    exit;
}
 
// Fetch assignments
$stmt = $conn->prepare("SELECT a.*, t.title, t.payment FROM assignments a JOIN tasks t ON a.task_id = t.id WHERE a.worker_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$assignments = $stmt->get_result();
 
// Fetch balance
$balance_stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$balance_stmt->bind_param("i", $_SESSION['user_id']);
$balance_stmt->execute();
$balance = $balance_stmt->get_result()->fetch_assoc()['balance'];
 
// Fetch reviews (for completed tasks)
$reviews_stmt = $conn->prepare("SELECT r.* FROM reviews r JOIN assignments a ON r.assignment_id = a.id WHERE a.worker_id = ?");
$reviews_stmt->bind_param("i", $_SESSION['user_id']);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); margin: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .header { text-align: center; background: linear-gradient(to right, #ffc107, #e0a800); color: #333; padding: 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .summary { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tasks { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .task-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .task-card button { background: #28a745; color: white; border: none; padding: 8px; cursor: pointer; border-radius: 4px; }
        .reviews { margin-top: 40px; }
        .review { background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 4px; }
        button.withdraw { background: #dc3545; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 4px; }
        @media (max-width: 768px) { .tasks { grid-template-columns: 1fr; } .header { padding: 30px 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Worker Dashboard</h1>
        <p>Track your tasks and earnings</p>
        <a href="marketplace.php" style="color: #333; text-decoration: underline;">Back to Marketplace</a>
    </div>
    <div class="container">
        <div class="summary">
            <h2>Earnings Summary</h2>
            <p>Balance: $<?php echo $balance; ?></p>
            <button class="withdraw" onclick="withdraw()">Withdraw Earnings</button>
        </div>
        <div class="tasks">
            <h2>Your Tasks</h2>
            <?php while ($assign = $assignments->fetch_assoc()): ?>
                <div class="task-card">
                    <h3><?php echo htmlspecialchars($assign['title']); ?></h3>
                    <p>Status: <?php echo $assign['status']; ?></p>
                    <p>Payment: $<?php echo $assign['payment']; ?></p>
                    <?php if ($assign['status'] == 'applied' || $assign['status'] == 'accepted'): ?>
                        <form action="complete_task.php" method="POST">
                            <input type="hidden" name="assignment_id" value="<?php echo $assign['id']; ?>">
                            <button type="submit">Complete Task</button>
                        </form>
                    <?php endif; ?>
                    <?php if ($assign['status'] == 'completed'): ?>
                        <!-- Review system: For simplicity, requester can add review via separate tool, but show here -->
                        <p>Completed on: <?php echo $assign['completed_at']; ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="reviews">
            <h2>Reviews</h2>
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review">
                    <p>Rating: <?php echo $review['rating']; ?>/5</p>
                    <p>Comment: <?php echo htmlspecialchars($review['comment']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function withdraw() {
            alert('Withdrawal requested! (Simulated - funds would be transferred in a real system.)');
            // In real, integrate payment API
        }
    </script>
</body>
</html>
