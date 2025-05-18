<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "s_d"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input values
$studentId = $_POST['studentId'];
$newPassword = $_POST['newPassword'];

// HTML and CSS for styling
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset Status</title>
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
        .success {
            color: #4CAF50;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .error {
            color: #D8000C;
            font-size: 18px;
            margin-bottom: 15px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class='container'>
";

// Check if the student ID exists in the database
$sql = "SELECT * FROM stu WHERE Student_ID='$studentId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Student ID found; update the password
    $hashedPassword = $newPassword; // You should hash this with password_hash() for security

    $updateSql = "UPDATE stu SET password='$hashedPassword' WHERE Student_ID='$studentId'";
    if ($conn->query($updateSql) === TRUE) {
        echo "<p class='success'>Password has been successfully reset.</p>";
        echo "<button><a href='stulogin.htm'>Login Here</a></button>";
    } else {
        echo "<p class='error'>Error updating password: " . $conn->error . "</p>";
    }
} else {
    // Student ID not found
    echo "<p class='error'>Student ID not found.</p>";
}

echo "
    </div>
</body>
</html>
";

$conn->close();
?>
