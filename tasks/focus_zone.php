<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// Fetch tasks for the focus zone - high priority or tasks near deadline, and not done
$user_id = $_SESSION['user_id'];

// Set current time and calculate upcoming tasks within a short time (next 24 hours)
$current_time = date('Y-m-d H:i:s');
$focus_time_limit = date('Y-m-d H:i:s', strtotime('+1 day'));

// Modify the query to exclude "Done" tasks
$sql = "SELECT * FROM tasks 
        WHERE user_id = ? 
        AND (priority = 'High' OR deadline BETWEEN ? AND ?) 
        AND status != 'Done' 
        ORDER BY deadline ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $current_time, $focus_time_limit);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

// Handle the "Mark Done" action
if (isset($_GET['mark_done'])) {
    $task_id = $_GET['mark_done'];
    $update_sql = "UPDATE tasks SET status = 'Done' WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $task_id, $user_id);
    if ($update_stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page after updating
        exit();
    } else {
        echo "Error updating task status: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focus Zone</title>
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
    <h1>Focus Zone</h1>
    <table>
        <tr>
            <th>Task</th>
            <th>Priority</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
                <tr style="color: <?= (strtotime($task['deadline']) < time()) ? 'red' : ((strtotime($task['deadline']) - time()) < 86400 ? 'orange' : 'black') ?>">
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['priority']) ?></td>
                    <td><?= htmlspecialchars($task['deadline']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <?php if ($task['status'] !== 'Done'): ?>
                            <a href="?mark_done=<?= $task['id'] ?>" class="mark-done">Mark Done</a>
                        <?php endif; ?>
                        <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-primary">Edit</a>
                        <a href="delete.php?id=<?= $task['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No tasks found for Focus Zone.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
