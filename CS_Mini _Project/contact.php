<?php
        // Include the database connection
        include("database.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Sanitize input
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_SPECIAL_CHARS);

        // Prepare the SQL statement
        $sql = "INSERT INTO contact (name, phone, email, message) VALUES (?, ?, ?, ?)";

        try {

                // Enable exception mode for mysqli
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                
                // Initialize a statement and prepare the SQL query
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                        // Bind parameters to the query
                        mysqli_stmt_bind_param($stmt, "siss", $name, $phone, $email, $message);
                        
                        // Execute the statement
                        mysqli_stmt_execute($stmt);
                        echo "You are now registered";
                        
                        // Close the statement
                        mysqli_stmt_close($stmt);
                } else {
                        echo "Error preparing statement: " . mysqli_error($conn);
                }
        }
        catch (mysqli_sql_exception $e) {
                echo "Error: " . $e->getMessage();
        }
        }

        // Close the connection
        mysqli_close($conn);

?>
