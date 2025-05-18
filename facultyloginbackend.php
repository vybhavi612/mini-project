<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['name']) ? $_POST['name'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Validate the credentials
    if ($username === 'vignan' && $password === 'vignan') {
        echo "<h2>Login successful. Welcome, $username!</h2>";
        header("Location:studentdetails.htm");
        exit();
    } else {
        echo "<h2>Invalid username or password. Please try again.</h2>";
    }
}
?>
