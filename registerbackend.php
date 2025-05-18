<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "s_d"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$studentId = $_POST['studentId'];
$username = $_POST['username'];
$password = $_POST['password']; // Get the password from the form

// Hash the password for security
$hashedPassword = $password;

// Check if student ID already exists
$sql = "SELECT * FROM stu WHERE Student_ID='$studentId'";
$result = $conn->query($sql);

// HTML and CSS for styling
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Registration Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .message {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .success {
            color: #4CAF50;
        }
        .error {
            color: #D8000C;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button a {
            color: white;
            text-decoration: none;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class='container'>
";

// Handle registration logic
if ($result->num_rows > 0) {
    // Student ID already exists
    echo "<p class='message error'>Student ID already registered.</p>";
} else {
    // Insert new student data
    $sql = "INSERT INTO stu (Student_ID, Username, Password) VALUES ('$studentId', '$username', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='message success'>Registered Successfully</p>";
        echo "<button><a href='stulogin.htm'>Click Here to Login</a></button>";
    } else {
        echo "<p class='message error'>Error: " . $conn->error . "</p>";
    }
}

echo "
    </div>
</body>
</html>
";

$conn->close();
?>
