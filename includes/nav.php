<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<div class="top-header">
    <div class="welcome-msg">
        👋 Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!
    </div>
    <div class="header-actions">
        <a href="../users/profile.php">Edit Profile</a> |
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<nav>
    <ul>
        <li><a href="../tasks/index.php">All Tasks</a></li>
        <li><a href="../tasks/due.php">Due Tasks</a></li>
        <li><a href="../tasks/completed.php">Completed Tasks</a></li>
        <li><a href="../tasks/focus_zone.php">Focus Tasks</a></li>
    </ul>
</nav>
