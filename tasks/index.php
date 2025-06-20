<?php
session_start();
include '../includes/db.php';
include '../includes/session.php';

// Fetch the logged-in user's full name
$full_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Task Manager - Home</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5em;
            margin: 0;
        }

        nav {
            display: flex;
            justify-content: center;
            background: #0056b3;
            padding: 15px 10px;
            gap: 30px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            padding: 40px 20px;
            max-width: 1000px;
            margin: auto;
        }

        .add-task-btn {
            font-size: 1.5em; 
            display: block;
            width: 250px;
            padding: 20px;
            background-color: #28a745;
            color: white;
            text-align: center;
            margin: 30px auto;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }

        .add-task-btn:hover {
            background-color: #218838;
        }

        .section-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 40px;
        }

        .section-card {
            flex: 1 1 calc(40% - 20px);
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            font-size: 1.3em;
            color: #333;
            text-decoration: none;
            transition: transform 0.3s, background 0.3s;
        }

        .section-card:hover {
            background-color: #e0f0ff;
            transform: translateY(-5px);
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>

<header>
    <h1><?php echo htmlspecialchars($full_name); ?>, Welcome to Personal Task Manager</h1>
</header>

<nav>
    <a href="../users/profile.php">Edit Profile</a>
    <a href="../auth/logout.php" class="logout-btn">Logout</a>
</nav>

<div class="container">
    <a href="add.php" class="add-task-btn">➕ Add New Task</a>

    <h2 style="text-align: center; color: #333; margin-top: 40px;">Manage Your Tasks Easily</h2>

    <div class="section-grid">
        <a href="all_tasks.php" class="section-card">📋 All Tasks</a>
        <a href="todo_tasks.php" class="section-card">📝 To Do Tasks</a>
        <a href="focus_zone.php" class="section-card">🎯 Focus Tasks</a>
        <a href="completed_tasks.php" class="section-card">✅ Completed Tasks</a>
        <a href="due_tasks.php" class="section-card">⏰ Overdue </a>
    </div>
</div>

</body>
</html>
