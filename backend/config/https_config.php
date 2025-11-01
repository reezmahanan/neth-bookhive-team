<?php
/**
 * HTTPS and Security Configuration
 * Include this file at the top of sensitive pages (admin, checkout, etc.)
 */

class HTTPSConfig {
    /**
     * Force HTTPS redirect
     */
    public static function forceHTTPS() {
        // Skip for localhost
        if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
            return;
        }
        
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $redirect_url);
            exit();
        }
    }
    
    /**
     * Set secure headers
     */
    public static function setSecureHeaders() {
        // Prevent clickjacking
        header("X-Frame-Options: SAMEORIGIN");
        
        // Prevent MIME type sniffing
        header("X-Content-Type-Options: nosniff");
        
        // XSS Protection
        header("X-XSS-Protection: 1; mode=block");
        
        // Referrer Policy
        header("Referrer-Policy: strict-origin-when-cross-origin");
        
        // Content Security Policy
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com; img-src 'self' data: https:; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com;");
        
        // HTTPS Strict Transport Security (HSTS) - Only if using HTTPS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
        }
        
        // Permissions Policy
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }
    
    /**
     * Configure secure session settings
     */
    public static function configureSecureSession() {
        // Prevent session fixation
        if (session_status() === PHP_SESSION_NONE) {
            // Session cookie parameters
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            
            session_set_cookie_params([
                'lifetime' => 3600, // 1 hour
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => $secure, // HTTPS only
                'httponly' => true, // JavaScript cannot access
                'samesite' => 'Strict' // CSRF protection
            ]);
            
            // Additional session security
            ini_set('session.use_strict_mode', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_httponly', 1);
            
            if ($secure) {
                ini_set('session.cookie_secure', 1);
            }
            
            session_start();
            
            // Regenerate session ID periodically
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }
    
    /**
     * Initialize all security configurations
     */
    public static function init($force_https = false) {
        if ($force_https) {
            self::forceHTTPS();
        }
        
        self::setSecureHeaders();
        self::configureSecureSession();
    }
}

// Auto-initialize if this file is included
HTTPSConfig::init();
?>
