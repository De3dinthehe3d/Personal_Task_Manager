<?php
session_start();
include '../includes/db.php';

$result = $conn->query("SELECT * FROM tasks ORDER BY deadline ASC");

$tasks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$pageTitle = "All Tasks";
include 'task_table.php';
?>
