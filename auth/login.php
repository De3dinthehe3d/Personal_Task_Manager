<?php
session_start();
include '../includes/db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../tasks/index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate the input
    if (empty($email) || empty($password)) {
        $error_message = "Both fields are required.";
    } else {
        // Check if user exists with the provided email
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // If user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: ../tasks/index.php"); // Redirect to tasks dashboard
            exit();
        } else {
            // If login fails, show error
            $error_message = "Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        input[type="email"], input[type="password"] {
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

        /* Position "Back to Welcome" link at the top-left corner */
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
    <h2>Login to Your Account</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
    <!-- Display error message if any -->
    <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
</body>
</html>
