<?php
// Start the session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch tasks for the logged-in user
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC");
$result->bind_param("i", $user_id);
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
    <title>Task Manager</title>
    <style>
        /* General Table Styling */
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
    background-color: #007bff;
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

/* Marking 'Overdue' or 'Approaching Deadline' Tasks */
tr[style="color: red;"] {
    background-color: #f8d7da; /* Light Red for Overdue */
}

tr[style="color: orange;"] {
    background-color: #fff3cd; /* Light Yellow for Approaching Deadline */
}

/* Action Buttons Styling */
.action-btn {
    display: inline-block;
    padding: 6px 12px;
    margin-right: 8px;
    text-decoration: none;
    border-radius: 5px;
    color: white;
    font-size: 14px;
}

/* Mark Done Button - Green */
.btn-success {
    background-color: #28a745; /* Green */
}

.btn-success:hover {
    background-color: #218838; /* Darker green on hover */
}

.btn-success:active {
    background-color: #1e7e34; /* Even darker green on active */
}

/* Delete Button - Red */
.btn-danger {
    background-color: #dc3545; /* Red */
}

.btn-danger:hover {
    background-color: #c82333; /* Darker red on hover */
}

.btn-danger:active {
    background-color: #bd2130; /* Even darker red on active */
}

/* Edit Button - Blue */
.btn-primary {
    background-color: rgb(26, 59, 95); /* Blue */
}

.btn-primary:hover {
    background-color: rgb(26, 59, 95); /* Slightly darker blue on hover */
}

.btn-primary:active {
    background-color: rgb(15, 45, 75); /* Even darker blue on active */
}

/* Centered message when no tasks found */
td[colspan="5"] {
    text-align: center;
    color: #6c757d;
    font-size: 1.1em;
}
    </style>
</head>
<body>
    <h1>Task List</h1>

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
                <tr style="color: <?= (strtotime($task['deadline']) < time()) ? 'red' : ((strtotime($task['deadline']) - time()) < 86400 ? 'orange' : 'black') ?>">
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['priority']) ?></td>
                    <td><?= htmlspecialchars($task['deadline']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <!-- Mark Done - Form Implementation -->
                        <?php if ($task['status'] !== 'Done'): ?>
                            <form method="POST" action="update.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                <input type="hidden" name="status" value="Done">
                                <button type="submit" name="update" class="action-btn btn-success">Mark Done</button>
                            </form>
                        <?php endif; ?>
                        <!-- Delete Button -->
                        <a href="delete.php?id=<?= $task['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="action-btn btn-danger">Delete</a>
                        <!-- Edit Button -->
                        <a href="edit.php?id=<?= $task['id'] ?>" class="action-btn btn-primary">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No tasks found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
