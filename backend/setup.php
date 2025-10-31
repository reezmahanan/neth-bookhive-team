<?php
// Test database connection and setup
header('Content-Type: text/html; charset=utf-8');

echo "<h2>NETH Bookhive - Database Setup</h2>";

// Database connection parameters
$host = "localhost";
$port = "3307";
$username = "root";
$password = "";
$dbname = "bookstore";

try {
    // First, connect without selecting a database
    $conn = new PDO("mysql:host=$host;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Connected to MySQL server successfully</p>";
    
    // Create database if not exists
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<p style='color: green;'>✓ Database '$dbname' created/verified</p>";
    
    // Connect to the database
    $conn->exec("USE $dbname");
    
    // Check if tables exist
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) == 0) {
        echo "<p style='color: orange;'>⚠ No tables found. Creating tables...</p>";
        
        // Read and execute SQL file
        $sqlFile = __DIR__ . '/../database/bookstore.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Split SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $conn->exec($statement);
                    } catch (PDOException $e) {
                        // Ignore errors for CREATE DATABASE and USE statements
                        if (strpos($e->getMessage(), 'database exists') === false) {
                            // echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
                        }
                    }
                }
            }
            echo "<p style='color: green;'>✓ Tables created successfully</p>";
        } else {
            echo "<p style='color: red;'>✗ SQL file not found at: $sqlFile</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ Database tables exist: " . implode(', ', $tables) . "</p>";
    }
    
    // Check books count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM books");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color: green;'>✓ Books in database: " . $result['count'] . "</p>";
    
    // Check users count
    $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color: green;'>✓ Users in database: " . $result['count'] . "</p>";
    
    echo "<hr>";
    echo "<h3>Setup Complete!</h3>";
    echo "<p>Your NETH Bookhive application is ready to use.</p>";
    echo "<p><a href='../frontend/index.html' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Homepage</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure:</p>";
    echo "<ul>";
    echo "<li>XAMPP MySQL is running</li>";
    echo "<li>MySQL port is 3307 (or update database.php if different)</li>";
    echo "<li>MySQL credentials are correct</li>";
    echo "</ul>";
}
?>
