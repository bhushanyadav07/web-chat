<?php
session_start();
$error = '';
$success = '';
if (isset($_POST['signup'])) {
    require_once('database.php');
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $sql="SELECT * FROM user WHERE username='$username'";
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Duplicate username.";
    } else {
        $sql="INSERT INTO user(username, password, email, name) VALUES('$username', '$password', '$email', '$name')";
        $result=$conn->query($sql);
        $success = 'Account successfully created.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Web chat</title>
    <?php require_once('header.php') ?>
</head>
<body>
<nav class="navbar navbar-inverse"> 
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><img src="chat_icon.svg" width="30" height="30" alt="Chat" title="Chat"></a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="login.php">Login</a></li>
    </ul>
  </div>
</nav>
<div class='col-sm-4'>
    <h2>Create account here</h2>
    <form method="post">
        <?php
        if ($error) {
            echo '<div id="alert" class="alert alert-danger">';
            echo '<strong>Attention! </strong>' . $error;
            echo '</div>';
        } else if ($success) {
            echo '<div id="alert" class="alert alert-success">';
            echo '<strong>Wohoo! </strong>' . $success;
            echo '</div>';
        }
        ?>
        <div id='username-div' class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="new-username" name="username" class='form-control' required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class='form-control' required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class='form-control' required>
        </div>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class='form-control' required>
        </div>
        <input class='btn btn-primiary' type="submit" name='signup' value="Create Account">
    </form>
</div>
</body>
</html>
