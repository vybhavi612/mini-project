<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";  // Default username in XAMPP
$password = "";  // Default password in XAMPP
$dbname = "s_d";  // Your database name

// Establish connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student-id-input']) && isset($_POST['password-input'])) {
    $studentId = $_POST['student-id-input'];
    $password = $_POST['password-input'];

    // Sanitize input to prevent SQL injection
    $studentId = $conn->real_escape_string($studentId);
    $password = $conn->real_escape_string($password);

    // Verify credentials
    $sql = "SELECT `Student_ID`, `Username`, `Problems_Solved`, `Contests_Participated` FROM stu WHERE Student_ID = '$studentId' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $row = $result->fetch_assoc();

        // Store user data in session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $row['Username'];
        $_SESSION['studentId'] = $row['Student_ID'];
        $_SESSION['problemsSolved'] = $row['Problems_Solved'];
        $_SESSION['contestsParticipated'] = $row['Contests_Participated'];
        $_SESSION['starRating'] = calculateStarRating($row['Contests_Participated']);

        // Redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        // Handle invalid login
        echo "<div class='text-danger'>Login failed: Invalid Student ID or Password.</div>";
    }
}

// Function to calculate star rating based on contests participated
function calculateStarRating($participationCount) {
    if ($participationCount >= 12) return "⭐⭐⭐⭐⭐";
    else if ($participationCount >= 9) return "⭐⭐⭐⭐";
    else if ($participationCount >= 6) return "⭐⭐⭐";
    else if ($participationCount >= 3) return "⭐⭐";
    else return "⭐";
}
?>
