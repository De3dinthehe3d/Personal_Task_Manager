<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if the update request is sent
if (isset($_POST['update'])) {
    $task_id = $_POST['id'];
    $status = $_POST['status']; // This will be 'Done'
    $user_id = $_SESSION['user_id'];

    // Update the task's status
    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $task_id, $user_id);

    if ($stmt->execute()) {
        // No redirection, just reload the page to show the updated status
        echo "<script>window.location.href='index.php';</script>";
        exit();
    } else {
        echo "Error updating task: " . $stmt->error;
    }
} else {
    echo "Invalid Request!";
}
?>
