<?php
// Assuming you're using MySQL and have a database connection function
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "s_d"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected contest date from the form
    $contest_date = $_POST['date-picker'];

    // Sanitize the input (if necessary, to prevent SQL injection)
    $contest_date = $conn->real_escape_string($contest_date);

    // Query to get student data based on the contest date
    $sql = "SELECT `Student_ID`, `Username`, `Problems_Solved`, `Contests_Participated` FROM stu WHERE `Date` = '$contest_date'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<h2>Participants on $contest_date</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Problems Solved</th>
                    <th>Contests Participated</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Username']) . "</td>
                    <td>" . htmlspecialchars($row['Student_ID']) . "</td>
                    <td>" . htmlspecialchars($row['Problems_Solved']) . "</td>
                    <td>" . htmlspecialchars($row['Contests_Participated']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No participants found for the selected date.";
    }
}

$conn->close();
?>
