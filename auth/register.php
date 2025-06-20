<?php
session_start();
include '../includes/db.php';

// Handle form submission for registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // First check if username already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error_message = "Username already taken. Try another!";
    } else {
        // Username available, now insert the new user
        $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $username, $email, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            header("Location: ../tasks/index.php"); // Redirect to the tasks page
            exit();
        } else {
            $error_message = "Error during registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <a href="../welcome.php" class="back-link">← Back</a>
    <h2>Create a New Account</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <!-- Set autocomplete="off" and give a random name for the username field to avoid Gmail suggestions -->
        <input type="text" name="username" placeholder="Username" autocomplete="off" required><br>
        <input type="email" name="email" placeholder="Email" autocomplete="email" required><br>
        <input type="password" name="password" placeholder="Password" autocomplete="new-password" required><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
    <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
</body>
</html>
