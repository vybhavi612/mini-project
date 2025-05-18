<?php
session_start(); // Start the session

// Check if the user has clicked the "Logout" button
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the welcome.html page
    header("Location: welcome.html");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: stulogin.htm"); // Ensure this points to your login page
    exit();
}

// Function to calculate star rating based on problems solved
function calculateStarRatingBasedOnProblemsSolved($problemsSolved) {
    if ($problemsSolved >= 100) return "⭐⭐⭐⭐⭐";
    else if ($problemsSolved >= 50) return "⭐⭐⭐⭐";
    else if ($problemsSolved >= 25) return "⭐⭐⭐";
    else if ($problemsSolved >= 8) return "⭐⭐";
    else return "⭐";
}

// Retrieve user data from session
$username = $_SESSION['username'];
$studentId = $_SESSION['studentId'];
$problemsSolved = $_SESSION['problemsSolved'];
$contestsParticipated = $_SESSION['contestsParticipated'];

// Calculate star rating based on problems solved
$starRating = calculateStarRatingBasedOnProblemsSolved($problemsSolved);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .profile-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .profile-container h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }
        .profile-item {
            font-size: 1.2em;
            margin-bottom: 15px;
            color: #555;
        }
        .profile-item strong {
            color: #333;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            font-size: 1.1em;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
        <a class="navbar-brand" href="#">Code Horizon</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="practiceproblems.htm">Practice Problems</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contests.htm">Contests</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <div class="profile-item"><strong>Student ID:</strong> <?php echo htmlspecialchars($studentId); ?></div>
        <div class="profile-item"><strong>Problems Solved:</strong> <?php echo htmlspecialchars($problemsSolved); ?></div>
        <div class="profile-item"><strong>Contests Participated:</strong> <?php echo htmlspecialchars($contestsParticipated); ?></div>
        <div class="profile-item"><strong>Star Rating:</strong> <?php echo htmlspecialchars($starRating); ?></div>
        
        <!-- Display the list of contests -->
        <div class="profile-item">
            <strong>Contests:</strong>
            <ul>
                <?php
                for ($i = 1; $i <= $contestsParticipated; $i++) {
                    echo "<li>Vignan Coding Challenge-$i</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Logout form -->
        <form method="post">
            <button type="submit" name="logout" class="btn-logout">Logout</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
