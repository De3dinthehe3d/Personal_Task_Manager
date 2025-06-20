<?php
// Start the session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch overdue tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$current_time = date('Y-m-d H:i:s');

$result = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND deadline < ? AND status != 'Done' ORDER BY deadline ASC");
$result->bind_param("is", $user_id, $current_time);
$result->execute();
$result = $result->get_result();

$tasks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Due Tasks</title>
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color:  #007bff; /* Blue header for due tasks */
            color: white;
            font-size: 1.1em;
        }

        td {
            font-size: 1.0em;
            color: #333;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Action Buttons */
        td a {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            margin-right: 8px;
        }

        td a:first-child {
            background-color: #ffc107; /* Yellow for 'Edit' */
        }

        td a:first-child:hover {
            background-color: #e0a800;
        }

        td a:nth-child(2) {
            background-color: #28a745; /* Green for 'Mark Done' */
        }

        td a:nth-child(2):hover {
            background-color: #218838;
        }

        td a:last-child {
            background-color: #dc3545; /* Red for 'Delete' */
        }

        td a:last-child:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Due Tasks</h1>

    <table>
        <tr>
            <th>Task</th>
            <th>Priority</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if (!empty($tasks)) : ?>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['priority']) ?></td>
                    <td><?= htmlspecialchars($task['deadline']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <!-- Edit Link -->
                        <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-warning">Edit</a>
                        
                        <!-- Mark Done Link -->
                        <a href="update.php?id=<?= $task['id'] ?>" class="btn btn-success">Mark Done</a>
                        
                        <!-- Delete Link -->
                        <a href="delete.php?id=<?= $task['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No due tasks found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
