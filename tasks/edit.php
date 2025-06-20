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

// Check if task ID is provided for editing
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the task details
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found or access denied.";
        exit();
    }
} else {
    echo "No task ID provided.";
    exit();
}

// Handle form submission to update task (without changing the status)
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    // Update task in database without changing the status
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, priority = ?, deadline = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $title, $priority, $deadline, $task_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>window.location.href='index.php';</script>";
        exit();
    } else {
        echo "Error updating task: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 0 auto;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="text"]:focus, input[type="datetime-local"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Button Styling */
        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Styling for Error and Success Messages */
        .error, .success {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Edit Task</h1>
    <form method="POST" action="edit.php?id=<?= $task_id ?>">
        <label for="title">Task Title:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>

        <label for="priority">Priority:</label>
        <input type="text" name="priority" id="priority" value="<?= htmlspecialchars($task['priority']) ?>" required><br><br>

        <label for="deadline">Deadline:</label>
        <input type="datetime-local" name="deadline" id="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" required><br><br>

        <button type="submit" name="update">Update Task</button>
    </form>
</body>
</html>
