<?php
// Include the database connection
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_SPECIAL_CHARS);
    $experience = filter_input(INPUT_POST, "experience", FILTER_SANITIZE_SPECIAL_CHARS);
    $details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS);

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a actual file
    if (isset($_POST["submit"])) {
        $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not a valid file.";
            $uploadOk = 0;
        }
    }

    // Check file size (limit to 5MB)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($fileType != "doc" && $fileType != "docx" && $fileType != "pdf") {
        echo "Sorry, only DOC, DOCX & PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO career (name, phone, email, status, experience, details, fileToUpload) VALUES (?, ?, ?, ?, ?, ?, ?)";

    try {
        // Enable exception mode for mysqli
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Initialize a statement and prepare the SQL query
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            // Bind parameters to the query
            mysqli_stmt_bind_param($stmt, "ssssiss", $name, $phone, $email, $status, $experience, $details, $target_file);

            // Execute the statement
            mysqli_stmt_execute($stmt);
            echo "You are now registered";

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    mysqli_close($conn);
}
?>

