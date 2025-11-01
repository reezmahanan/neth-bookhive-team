<?php
/**
 * System Diagnostic Check for NETH BookHive
 * Run this page to check if everything is configured correctly
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - NETH BookHive</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        h1 i { color: #667eea; font-size: 2.5rem; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .check-item {
            padding: 20px;
            margin: 15px 0;
            border-radius: 12px;
            border-left: 5px solid #ddd;
            background: #f8f9fa;
        }
        .check-item.success { border-left-color: #28a745; background: #d4edda; }
        .check-item.warning { border-left-color: #ffc107; background: #fff3cd; }
        .check-item.error { border-left-color: #dc3545; background: #f8d7da; }
        .check-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .check-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        .success .check-icon { background: #28a745; color: white; }
        .warning .check-icon { background: #ffc107; color: #333; }
        .error .check-icon { background: #dc3545; color: white; }
        .check-details {
            margin-left: 42px;
            color: #555;
            line-height: 1.6;
        }
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
        }
        .btn-create-admin {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-create-admin:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }
        .summary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
            text-align: center;
        }
        .summary h2 { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <i class="fas fa-stethoscope"></i>
            System Diagnostic Check
        </h1>
        <p class="subtitle">NETH BookHive - Checking system configuration...</p>

        <?php
        $allGood = true;
        $warnings = 0;
        $errors = 0;

        // Check 1: PHP Version
        $phpVersion = phpversion();
        $phpOk = version_compare($phpVersion, '7.4.0', '>=');
        if (!$phpOk) $errors++;
        ?>
        <div class="check-item <?php echo $phpOk ? 'success' : 'error'; ?>">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-<?php echo $phpOk ? 'check' : 'times'; ?>"></i>
                </span>
                PHP Version
            </div>
            <div class="check-details">
                Current version: <strong>PHP <?php echo $phpVersion; ?></strong>
                <?php if (!$phpOk): ?>
                    <br>⚠️ Requires PHP 7.4 or higher
                <?php endif; ?>
            </div>
        </div>

        <?php
        // Check 2: PDO Extension
        $pdoAvailable = extension_loaded('pdo') && extension_loaded('pdo_mysql');
        if (!$pdoAvailable) $errors++;
        ?>
        <div class="check-item <?php echo $pdoAvailable ? 'success' : 'error'; ?>">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-<?php echo $pdoAvailable ? 'check' : 'times'; ?>"></i>
                </span>
                PDO MySQL Extension
            </div>
            <div class="check-details">
                <?php if ($pdoAvailable): ?>
                    ✓ PDO MySQL extension is loaded and available
                <?php else: ?>
                    ✗ PDO MySQL extension is not available. Please enable it in php.ini
                <?php endif; ?>
            </div>
        </div>

        <?php
        // Check 3: Database Connection
        $dbConnected = false;
        $dbMessage = '';
        $dbExists = false;
        
        try {
            require_once 'backend/config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            if ($db) {
                $dbConnected = true;
                $dbMessage = "Successfully connected to MySQL on localhost:3307";
                
                // Check if bookstore database exists and has users table
                try {
                    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $dbExists = true;
                    $userCount = $result['count'];
                    
                    // Check for admin users
                    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE is_admin = 1");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $adminCount = $result['count'];
                    
                } catch (PDOException $e) {
                    $dbExists = false;
                    $dbMessage .= "<br>⚠️ Users table not found or database schema issue";
                }
            }
        } catch (Exception $e) {
            $dbMessage = "Database connection failed: " . $e->getMessage();
            $errors++;
        }
        
        if (!$dbConnected) $errors++;
        ?>
        <div class="check-item <?php echo $dbConnected ? 'success' : 'error'; ?>">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-<?php echo $dbConnected ? 'check' : 'times'; ?>"></i>
                </span>
                Database Connection
            </div>
            <div class="check-details">
                <?php echo $dbMessage; ?>
                <?php if (!$dbConnected): ?>
                    <br><br><strong>Troubleshooting:</strong>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        <li>Ensure XAMPP MySQL is running</li>
                        <li>Check MySQL is running on port 3307</li>
                        <li>Verify database name is "bookstore"</li>
                        <li>Run the database setup SQL file</li>
                    </ul>
                    <div class="code">
                        # Start MySQL in XAMPP Control Panel<br>
                        # Import: database/bookstore.sql
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($dbConnected && $dbExists): ?>
        <div class="check-item success">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-check"></i>
                </span>
                Database Schema
            </div>
            <div class="check-details">
                ✓ Database "bookstore" exists<br>
                ✓ Users table found with <?php echo $userCount; ?> users<br>
                <?php if ($adminCount > 0): ?>
                    ✓ Found <?php echo $adminCount; ?> admin user(s)
                <?php else: ?>
                    ⚠️ No admin users found
                <?php endif; ?>
            </div>
        </div>

        <?php if ($adminCount == 0): ?>
        <div class="check-item warning">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                Admin Account
            </div>
            <div class="check-details">
                No admin account found. You need to create one to access the admin panel.
                <div class="code">
                    php tools/create_admin.php --email=admin@bookhive.com --name="Admin User"
                </div>
                <p style="margin-top: 10px;">Run this command in PowerShell from the project root directory.</p>
            </div>
        </div>
        <?php $warnings++; ?>
        <?php endif; ?>
        <?php endif; ?>

        <?php
        // Check 4: File Permissions
        $uploadsWritable = is_writable('uploads') || !file_exists('uploads');
        ?>
        <div class="check-item <?php echo $uploadsWritable ? 'success' : 'warning'; ?>">
            <div class="check-header">
                <span class="check-icon">
                    <i class="fas fa-<?php echo $uploadsWritable ? 'check' : 'exclamation-triangle'; ?>"></i>
                </span>
                File Permissions
            </div>
            <div class="check-details">
                <?php if ($uploadsWritable): ?>
                    ✓ Upload directory is writable
                <?php else: ?>
                    ⚠️ Upload directory may not be writable
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary">
            <h2>
                <?php if ($errors == 0 && $warnings == 0): ?>
                    <i class="fas fa-check-circle"></i> All Systems Operational!
                <?php elseif ($errors > 0): ?>
                    <i class="fas fa-exclamation-circle"></i> Critical Issues Found
                <?php else: ?>
                    <i class="fas fa-exclamation-triangle"></i> Minor Issues Found
                <?php endif; ?>
            </h2>
            <p style="font-size: 1.1rem; margin-top: 10px;">
                <?php if ($errors == 0 && $warnings == 0): ?>
                    Your system is properly configured and ready to use!
                <?php elseif ($errors > 0): ?>
                    Please fix the <?php echo $errors; ?> critical error(s) above before using the application.
                <?php else: ?>
                    System is functional but has <?php echo $warnings; ?> warning(s) to address.
                <?php endif; ?>
            </p>
            
            <?php if ($errors == 0): ?>
                <div style="margin-top: 20px;">
                    <a href="frontend/index.html" class="btn-create-admin">
                        <i class="fas fa-home"></i> Go to Homepage
                    </a>
                    <a href="admin_login.php" class="btn-create-admin">
                        <i class="fas fa-shield-alt"></i> Admin Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
