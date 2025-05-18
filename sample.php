<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    $file = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];
    $fileSize = $_FILES['excelFile']['size'];
    $fileError = $_FILES['excelFile']['error'];
    
    // Validate file type (ensure it's a CSV file)
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if ($fileExt !== 'csv') {
        echo "<p class='error'>Error: Only CSV files are allowed.</p>";
        exit;
    }

    // Check for file upload errors
    if ($fileError !== UPLOAD_ERR_OK) {
        echo "<p class='error'>Error: There was an issue uploading the file.</p>";
        exit;
    }

    // Check if file size is reasonable (e.g., 10MB limit)
    if ($fileSize > 10485760) { // 10MB
        echo "<p class='error'>Error: File is too large.</p>";
        exit;
    }

    // Open and read the CSV file
    if (($handle = fopen($file, "r")) !== FALSE) {
        echo "<h2>Sample Data from Uploaded CSV File</h2>";
        echo "<table class='data-table'>";
        
        // Read and display each row of the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            echo "<tr>";
            foreach ($data as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
        fclose($handle);
    echo "<h2>###### Student Personal Data</h2>";
    echo"<h2>REMANING DATA SHOULD BE ENTERED</h2>";
    echo "<div style='margin-top: 20px;'>";

    } else {
        echo "<p class='error'>Error: Unable to open the file.</p>";
    }
} else {
    // Redirect to the upload form if no file is uploaded
    header('Location: sample.html');
    exit;
}
?>
