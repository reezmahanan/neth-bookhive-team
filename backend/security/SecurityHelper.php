<?php
/**
 * Security Helper Functions
 * NETH BookHive - PHP Security Module
 * 
 * This file provides essential security functions for the application
 */

class SecurityHelper {
    
    /**
     * Sanitize input data to prevent XSS attacks
     * @param string $data Input data
     * @return string Sanitized data
     */
    public static function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
    
    /**
     * Validate email address
     * @param string $email Email to validate
     * @return bool True if valid
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Generate CSRF token
     * @return string CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     * @param string $token Token to verify
     * @return bool True if valid
     */
    public static function verifyCSRFToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Check if user is logged in
     * @return bool True if logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Check if user is admin
     * @return bool True if admin
     */
    public static function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
    
    /**
     * Require login (redirect if not logged in)
     * @param string $redirect Redirect URL
     */
    public static function requireLogin($redirect = '/frontend/login.html') {
        if (!self::isLoggedIn()) {
            header('Location: ' . $redirect);
            exit();
        }
    }
    
    /**
     * Require admin access
     * @param string $redirect Redirect URL
     */
    public static function requireAdmin($redirect = '/admin_login.php') {
        if (!self::isAdmin()) {
            header('Location: ' . $redirect);
            exit();
        }
    }
    
    /**
     * Validate password strength
     * @param string $password Password to validate
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validatePassword($password) {
        $result = ['valid' => true, 'message' => ''];
        
        if (strlen($password) < 8) {
            $result['valid'] = false;
            $result['message'] = 'Password must be at least 8 characters long';
            return $result;
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must contain at least one uppercase letter';
            return $result;
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must contain at least one lowercase letter';
            return $result;
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $result['valid'] = false;
            $result['message'] = 'Password must contain at least one number';
            return $result;
        }
        
        return $result;
    }
    
    /**
     * Hash password securely
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool True if match
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Prevent SQL injection (use with PDO)
     * @param PDO $pdo PDO instance
     * @param string $query SQL query with placeholders
     * @param array $params Parameters
     * @return PDOStatement
     */
    public static function executeQuery($pdo, $query, $params = []) {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Log security event
     * @param string $event Event description
     * @param string $level Level (info, warning, error)
     */
    public static function logSecurityEvent($event, $level = 'info') {
        $logFile = __DIR__ . '/../../logs/security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user = $_SESSION['user_id'] ?? 'guest';
        
        $logMessage = "[$timestamp] [$level] [IP: $ip] [User: $user] $event\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    /**
     * Rate limiting - check if user exceeded request limit
     * @param string $identifier User identifier (IP or user ID)
     * @param int $limit Max requests
     * @param int $timeWindow Time window in seconds
     * @return bool True if allowed
     */
    public static function checkRateLimit($identifier, $limit = 100, $timeWindow = 60) {
        $cacheFile = __DIR__ . '/../../cache/rate_limit_' . md5($identifier) . '.txt';
        $cacheDir = dirname($cacheFile);
        
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        $now = time();
        $data = [];
        
        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            
            // Remove old entries
            $data = array_filter($data, function($timestamp) use ($now, $timeWindow) {
                return ($now - $timestamp) < $timeWindow;
            });
        }
        
        if (count($data) >= $limit) {
            self::logSecurityEvent("Rate limit exceeded for $identifier", 'warning');
            return false;
        }
        
        $data[] = $now;
        file_put_contents($cacheFile, json_encode($data));
        
        return true;
    }
    
    /**
     * Generate secure random token
     * @param int $length Token length
     * @return string Random token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Validate file upload security
     * @param array $file $_FILES array element
     * @param array $allowedTypes Allowed MIME types
     * @param int $maxSize Max file size in bytes
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateFileUpload($file, $allowedTypes = [], $maxSize = 5242880) {
        $result = ['valid' => true, 'message' => ''];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $result['valid'] = false;
            $result['message'] = 'No file uploaded';
            return $result;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $result['valid'] = false;
            $result['message'] = 'File too large (max ' . ($maxSize / 1048576) . ' MB)';
            return $result;
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            $result['valid'] = false;
            $result['message'] = 'Invalid file type';
            return $result;
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $result['valid'] = false;
            $result['message'] = 'Upload error: ' . $file['error'];
            return $result;
        }
        
        return $result;
    }
}

// Initialize session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
