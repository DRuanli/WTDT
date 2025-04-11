<?php
// Database configuration

// Database credentials
define('DB_HOST', 'localhost'); // Usually localhost for XAMPP
define('DB_USER', 'root');      // Default XAMPP user is root
define('DB_PASS', '');          // Default XAMPP password is empty
define('DB_NAME', 'note_management');

// Database connection helper function
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to ensure proper character encoding
    $conn->set_charset("utf8mb4");
    
    return $conn;
}


// Get database connection
function getDB() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = connectDB();
    }
    
    return $conn;
}

// Close database connection
function closeDB($conn) {
    if ($conn) {
        $conn->close();
    }
}