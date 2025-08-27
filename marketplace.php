<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location = 'login.php';</script>";
    exit;
}
 
// Fetch all open tasks
$stmt = $conn->prepare("SELECT t.*, u.username AS requester FROM tasks t JOIN users u ON t.requester_id = u.id WHERE t.status = 'open'");
$stmt->execute();
$tasks = $stmt->get_result();
 
// Categories for filter (static for simplicity)
$categories = ['data_entry', 'survey', 'transcription', 'other'];
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Marketplace</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); margin: 0; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .header { text-align: center; background: linear-gradient(to right, #28a745, #1e7e34); color: white; padding: 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .tasks { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .task-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .task-card:hover { transform: scale(1.02); }
        .task-card button { background: #ffc107; color: #333; border: none; padding: 8px; cursor: pointer; border-radius: 4px; }
        .categories { margin: 20px 0; display: flex; justify-content: center; flex-wrap: wrap; }
        .categories button { margin: 5px; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        @media (max-width: 768px) { .tasks { grid-template-columns: 1fr; } .header { padding: 30px 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Marketplace</h1>
        <p>Browse and apply for tasks</p>
        <?php if ($_SESSION['user_type'] == 'requester'): ?>
            <a href="post_task.php" style="color: white; text-decoration: underline;">Post a New Task</a>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="categories">
            <h3>Categories:</h3>
            <?php foreach ($categories as $cat): ?>
                <button onclick="filterCategory('<?php echo $cat; ?>')"><?php echo ucfirst($cat); ?></button>
            <?php endforeach; ?>
        </div>
        <div class="tasks">
            <?php while ($task = $tasks->fetch_assoc()): ?>
                <div class="task-card" data-category="<?php echo $task['category']; ?>">
                    <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                    <p><?php echo htmlspecialchars($task['description']); ?></p>
                    <p>Requester: <?php echo $task['requester']; ?></p>
                    <p>Category: <?php echo $task['category']; ?></p>
                    <p>Payment: $<?php echo $task['payment']; ?></p>
                    <p>Deadline: <?php echo $task['deadline']; ?></p>
                    <?php if ($_SESSION['user_type'] == 'worker'): ?>
                        <form action="apply_task.php" method="POST">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit">Apply</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        function filterCategory(cat) {
            const cards = document.querySelectorAll('.task-card');
            cards.forEach(card => {
                card.style.display = (card.dataset.category === cat || cat === '') ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
