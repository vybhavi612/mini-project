<?php
$servername = "localhost";
$username = "root";  // default username in XAMPP
$password = "";  // default password in XAMPP
$dbname = "s_d";  // your database name

// Establish connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$login_success = false;
$profile_data = null;

// Handle login request
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['studentId']) && isset($_GET['password'])) {
    $studentId = $_GET['studentId'];
    $password = $_GET['password'];

    // Sanitize input to prevent SQL injection
    $studentId = $conn->real_escape_string($studentId);
    $password = $conn->real_escape_string($password);

    // Verify credentials with updated column names and table
    $sql = "SELECT `Student_ID`, `Username`, `Problems_Solved`, `Contests_Participated` FROM stu WHERE Student_ID = '$studentId' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $row = $result->fetch_assoc();
        $login_success = true;
        $profile_data = [
            'username' => $row['Username'] ?? 'N/A',
            'studentId' => $row['Student_ID'] ?? 'N/A',
            'problemsSolved' => $row['Problems_Solved'] ?? 'N/A',
            'contestsParticipated' => $row['Contests_Participated'] ?? 'N/A',
            'starRating' => isset($row['Contests_Participated']) ? calculateStarRating($row['Contests_Participated']) : 'N/A',
        ];
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Horizon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        nav {
            margin-bottom: 20px;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
            height: auto;
        }
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
        #discussion-messages div {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .star-rating {
            color: gold;
            font-size: 1.5em;
        }
        .event-details {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-container">
        <img src="logo.png" class="logo" alt="Code Horizon Logo">
    </div>
    <h1 class="text-center">Code Horizon</h1>
    
    <nav class="nav justify-content-center">
        <a href="#" class="nav-link" onclick="showSection('login')">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="#" class="nav-link" onclick="showSection('profile')">
            <i class="fas fa-user"></i> User Profile
        </a>
    </nav>

    <!-- Login Page -->
    <div id="login" class="section active">
        <h2 class="text-center">Login</h2>
        <div class="form-group">
            <label for="student-id-input">Student ID</label>
            <input type="text" id="student-id-input" class="form-control" placeholder="Enter Student ID">
        </div>
        <div class="form-group">
            <label for="password-input">Password</label>
            <input type="password" id="password-input" class="form-control" placeholder="Enter Password">
        </div>
        <button class="btn btn-primary" onclick="login()">Login</button>
        <div id="login-message" class="text-danger mt-2">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$login_success && isset($studentId) && isset($password)) {
                echo 'Invalid Student ID or Password.';
            }
            ?>
        </div>
    </div>

    <!-- Profile Section -->
    <div id="profile" class="section">
        <h2 class="text-center">Profile</h2>
        <?php if ($login_success && $profile_data): ?>
            <p><strong>Username:</strong> <span id="profile-username"><?php echo $profile_data['username']; ?></span></p>
            <p><strong>Student ID:</strong> <span id="profile-student-id"><?php echo $profile_data['studentId']; ?></span></p>
            <p><strong>Problems Solved:</strong> <span id="profile-problems-solved"><?php echo $profile_data['problemsSolved']; ?></span></p>
            <p><strong>Contests Participated:</strong> <span id="profile-contests-participated"><?php echo $profile_data['contestsParticipated']; ?></span></p>
            <p><strong>Star Rating:</strong> <span class="star-rating" id="profile-star-rating"><?php echo $profile_data['starRating']; ?></span></p>
        <?php else: ?>
            <p>Please log in to see your profile.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        &copy; 2024 Code Horizon. All rights reserved.
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
    // Show specific section
    function showSection(section) {
        document.querySelectorAll('.section').forEach((sec) => {
            sec.classList.remove('active');
        });
        document.getElementById(section).classList.add('active');
    }

    // Handle login form submission
    async function login() {
        const studentId = document.getElementById('student-id-input').value.trim();
        const password = document.getElementById('password-input').value.trim();

        if (!studentId || !password) {
            document.getElementById('login-message').innerText = 'Please fill in both fields.';
            return;
        }

        try {
            // Make a request to this PHP page for login
            window.location.href = `?studentId=${studentId}&password=${password}`;
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('login-message').innerText = 'An error occurred during login.';
        }
    }
</script>
</body>
</html>
