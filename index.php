<?php
session_start();
include 'db.php';
 
// Fetch featured tasks (open tasks, limit 5)
$stmt = $conn->prepare("SELECT * FROM tasks WHERE status = 'open' LIMIT 5");
$stmt->execute();
$featured_tasks = $stmt->get_result();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroTask Platform - Home</title>
    <style>
        /* Kamal ka CSS: Professional, real-looking with gradients, shadows, flexbox */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); margin: 0; padding: 0; color: #333; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .header { text-align: center; background: linear-gradient(to right, #007bff, #0056b3); color: white; padding: 60px 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header h1 { font-size: 2.5em; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
        .header p { font-size: 1.2em; }
        .section { margin: 40px 0; }
        .signup-form { display: flex; flex-direction: column; max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .signup-form input, .signup-form select { margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .signup-form button { background: #28a745; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 4px; transition: background 0.3s; }
        .signup-form button:hover { background: #218838; }
        .tasks { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .task-card { background: white; width: 300px; margin: 10px; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .task-card:hover { transform: scale(1.05); }
        .task-card h3 { margin: 0 0 10px; color: #007bff; }
        /* Responsive */
        @media (max-width: 768px) { .header { padding: 40px 10px; } .header h1 { font-size: 2em; } .tasks { flex-direction: column; align-items: center; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to MicroTask Platform</h1>
        <p>Complete small tasks like data entry, surveys, and transcription to earn real money!</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Hello, <?php echo $_SESSION['username']; ?>! <a href="logout.php" style="color: white;">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php" style="color: white; text-decoration: underline;">Login</a></p>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="section">
            <h2>How It Works</h2>
            <p>Sign up as a requester to post tasks or as a worker to complete them and earn payments.</p>
        </div>
        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="section">
            <h2>Sign Up</h2>
            <form class="signup-form" action="signup.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="requester">Requester (Post Tasks)</option>
                    <option value="worker">Worker (Complete Tasks)</option>
                </select>
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="section">
            <h2>Featured Tasks</h2>
            <div class="tasks">
                <?php while ($task = $featured_tasks->fetch_assoc()): ?>
                    <div class="task-card">
                        <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($task['description'], 0, 100)) . '...'; ?></p>
                        <p>Category: <?php echo $task['category']; ?></p>
                        <p>Payment: $<?php echo $task['payment']; ?></p>
                        <p>Deadline: <?php echo $task['deadline']; ?></p>
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'worker'): ?>
                            <a href="marketplace.php">Apply</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <script>
        // Internal JS for any client-side needs (e.g., validation if added)
    </script>
</body>
</html>
