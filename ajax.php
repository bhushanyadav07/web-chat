<?php
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    session_start();
    if (!isset($_SESSION['username']) && isset($_GET['action']) && $_GET['action'] != 'new user') {
        echo "Login first";
        die;
    }
    include 'database.php';
    $username = $_GET['username'];
    $sql="SELECT * FROM user WHERE username='$username'";
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Found";
    } else {
        echo "Not Found";
    }
}
