<?php
session_start();
include '../includes/db.php';

if (!isset($_GET['id']) || !isset($_GET['redirect'])) {
    header("Location: all_tasks.php");
    exit();
}

$task_id = intval($_GET['id']);
$redirect_page = $_GET['redirect'];

// Delete the task
$sql = "DELETE FROM tasks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $task_id);

if ($stmt->execute()) {
    header("Location: " . $redirect_page);
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>

