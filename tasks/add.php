<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../welcome.php");
    exit();
}

// Include DB connection
include_once("../includes/db.php");

$success_message = ''; // For success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST["title"]);
    $priority = $_POST["priority"];
    $date = $_POST["date"];        // Separate date
    $time = $_POST["time"];        // Separate time
    $deadline = $date . ' ' . $time; // Combine them into one datetime value
    $status = 'Pending';

    if (!empty($title) && !empty($priority) && !empty($date) && !empty($time)) {
        $query = "INSERT INTO tasks (user_id, title, priority, deadline, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issss", $user_id, $title, $priority, $deadline, $status);

        if ($stmt->execute()) {
            $success_message = "Task added successfully! You can add another task below.";
        } else {
            echo "Error adding task: " . $stmt->error;
        }
    } else {
        echo "Please fill all fields.";
    }

    $stmt->close();
}

mysqli_close($conn);
?>

<!-- HTML Form for Adding Task -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background-color: #f4f6f9;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .back-link {
            font-size: 1.1em;
            margin-bottom: 20px;
            display: block;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Back link to index -->
<a href="index.php" class="back-link">← Back to Dashboard</a>

<h2>Add New Task</h2>

<?php if ($success_message): ?>
    <p class="success-message"><?= $success_message ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label for="title">Task Title:</label>
    <input type="text" name="title" required>

    <label for="priority">Priority:</label>
    <select name="priority" required>
        <option value="">--Select--</option>
        <option value="High">High</option>
        <option value="Medium">Medium</option>
        <option value="Low">Low</option>
    </select>

    <label for="date">Deadline Date:</label>
    <input type="date" name="date" required>

    <label for="time">Deadline Time:</label>
    <input type="time" name="time" required>

    <button type="submit">➕ Add Task</button>
</form>

</body>
</html>
