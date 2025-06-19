<?php
session_start();

// Database configuration
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'care';

// Function to get the database connection
function getDbConnection() {
    static $db = null;
    if ($db === null) {
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$db) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }
    return $db;
}

?>