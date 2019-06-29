<?php
$error = "";
session_start();
if (isset($_SESSION['username'])) {
    header('Location:index.php');
}
if (isset($_POST['login'])) {
    include 'database.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql="SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location:index.php");
    } else {
        $error = "Wrong username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Web chat</title>
    <?php require_once('header.php'); ?>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><img src="chat_icon.svg" width="30" height="30" alt="Chat" title="Chat"></a>
    </div>
    <ul class="nav navbar-nav">
        <li class="active"><a href="signup.php">Signup</a></li>
    </ul>
  </div>
</nav>
<div class='col-sm-4'>
    <h2>Login to account</h2>
    <form method="post">
        <?php
        if ($error) {
            echo '<div class="alert alert-danger">';
            echo '<strong>Attention! </strong>' . $error;
            echo '</div>';
        }
        ?>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class='form-control'>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class='form-control'>
        </div>
        <input class='btn btn-primary' type="submit" name='login' value="Login">
    </form>
</div>
</body>
</html>
