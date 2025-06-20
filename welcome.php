<?php
// welcome.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | Personal Task Manager</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 50px;
      background: #f4f7fc;  /* Light background for modern look */
    }
    h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #333;
    }
    p {
      max-width: 600px;
      margin: auto;
      font-size: 1.1rem;
      color: #666;
    }
    .buttons a {
      display: inline-block;
      margin: 20px;
      padding: 12px 24px;
      background: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-size: 1rem;
    }
    .buttons a:hover {
      background: #0056b3;
    }
    /* Responsive Styles */
    @media (max-width: 768px) {
      h1 {
        font-size: 2rem;
      }
      .buttons a {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <h1>Welcome to Personal Task Manager ✅</h1>
  <p>Manage your daily tasks efficiently! Organize, prioritize, and track your progress with our simple, web-based task manager.</p>
  <div class="buttons">
    <a href="auth/register.php">Register</a>
    <a href="auth/login.php">Login</a>
  </div>
</body>
</html>
