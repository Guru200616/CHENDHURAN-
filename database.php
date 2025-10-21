<?php
/**
 * ===============================================
 * Project: Student Registration with Validation
 * College: Chendhuran College of Engineering and Technology
 * Course: B.E CSE
 * Description: Database Connection File using PostgreSQL PDO
 * ===============================================
 */

// Database configuration using environment variables
$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');

// Create connection string for PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Create PDO instance with error mode set to exception
    $conn = new PDO($dsn, $user, $password);
    
    // Set the PDO error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Disable emulated prepared statements for better security
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    // Log error and display user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Connection failed. Please contact the administrator.");
}

/**
 * Function to create the students table if it doesn't exist
 */
function createStudentsTable($conn) {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id SERIAL PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(15) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $conn->exec($sql);
        return true;
    } catch(PDOException $e) {
        error_log("Table Creation Error: " . $e->getMessage());
        return false;
    }
}

// Create the students table automatically
createStudentsTable($conn);

?>
