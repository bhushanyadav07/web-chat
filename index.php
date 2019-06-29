<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
}
require_once('database.php');
$error = '';
$success = '';
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete') {
    $sql="SELECT * FROM messages WHERE id = " . $_GET['id'];
    $result = $conn-> query($sql);
    $row = $result->fetch_assoc();
    if ($row['action'] == "" && $row['from_user'] != $row['to_user']) {
        $sql  = "UPDATE messages SET action = '" . $_SESSION['username'] . "' WHERE id = " . $_GET['id'];
    } else {
        $sql = "DELETE FROM messages WHERE id = " . $_GET['id'];
    }
    $conn->query($sql);
    header('location:index.php');
}
if (isset($_POST['send'])) {
    $to=$_POST['username'];
    $sql="SELECT * FROM user WHERE username='$to'";
    $result=$conn->query($sql);
    if ($result->num_rows == 0) {
        $error = "User not found.";
    } else {
        $msg=$_POST['message'];
        $date = new DateTime();
        $sql="INSERT INTO messages (`from_user`, `to_user`, `message`, `time`, `action`) VALUES('" . $_SESSION['username'] . "', '$to','$msg', '" . $date->getTimeStamp() . "', '')";
        $result=$conn ->query($sql);
        $success = "Message delivered.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Let's chat</title>
    <?php require_once('header.php') ?>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><img src="chat_icon.svg" width="30" height="30" alt="Chat" title="Chat"></a>
        <a class="navbar-brand" href="#">Welcome <?php echo $_SESSION['username']?></a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>
<div id="main">

<div class='container'>
    <div class='col-sm-4'>
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
            <div id="username-div" class="form-group" required>
                <label for="username">Receiver:</label>
                <input type='text' id='username' name="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea name="message" row='5' class="form-control" requried></textarea>
            </div>
            <input id='send' class='btn btn-primary' type="submit" name='send' value="Send">
        </form>
    </div>
    <div class="col-sm-4">
        <?php
        $sql="SELECT  * FROM messages WHERE to_user = '" . $_SESSION['username'] . "' AND action != '" . $_SESSION['username'] . "'";
        $result = $conn-> query($sql);

        if ($result->num_rows > 0) {
            //output data of each row
            ?>
            <table class='table'>
                <caption>Received messages</caption>
                <tr><th>Sender</th><th>Message</th><th>Time</th></tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['from_user'] . "</td><td>" . $row['message'] . "</td><td>" . date("d F Y g:i:s A", $row['time']) . "</td><td><a href='index.php?action=delete&id=" . $row['id'] . "'>Delete</a></td></tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "No message received.";
        }
        ?>
    </div>
    <div class="col-sm-4">
        <?php
        $sql="SELECT  * FROM messages WHERE from_user = '" . $_SESSION['username'] . "' AND action != '" . $_SESSION['username'] . "'";
        $result = $conn-> query($sql);

        if ($result->num_rows > 0) {
            //output data of each row
            ?>
            <table class='table'>
                <caption>Sent messages</caption>
                <tr><th>Receiver</th><th>Message</th><th>Time</th><th>Action</th></tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['to_user'] . "</td><td>" . $row['message'] . "</td><td>" . date("d F Y g:i:s A", $row['time']) . "</td><td><a href='index.php?action=delete&id=" . $row['id'] . "'>Delete</a></td></tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "No message sent.";
        }
        $conn->close();
        ?>
    </div>
</div>
</body>
</html>
