<?php
/**
 * Uptime Monitor - Automated website health checker
 * 
 * SETUP INSTRUCTIONS:
 * 1. Run this script via cron job or Windows Task Scheduler
 * 2. Cron example (every 5 minutes): *\/5 * * * * php /path/to/uptime_monitor.php
 * 3. Windows Task Scheduler: Run every 5 minutes with action: php.exe uptime_monitor.php
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/EmailHelper.php';

class UptimeMonitor {
    private $conn;
    private $check_url;
    private $timeout = 10; // seconds
    private $alert_email = 'admin@nethbookhive.com';
    private $consecutive_failures = 3; // Alert after 3 consecutive failures
    
    public function __construct($db) {
        $this->conn = $db;
        $this->check_url = $this->getBaseUrl();
    }
    
    /**
     * Get base URL from config or auto-detect
     */
    private function getBaseUrl() {
        // In production, set this to your actual domain
        if (isset($_SERVER['HTTP_HOST'])) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            return $protocol . '://' . $_SERVER['HTTP_HOST'];
        }
        return 'http://localhost/NETH%20Bookhive'; // Default for local development
    }
    
    /**
     * Check website availability
     */
    public function checkWebsite() {
        $start_time = microtime(true);
        $status = 'down';
        $response_time = 0;
        $error_message = null;
        
        try {
            $ch = curl_init($this->check_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            
            $end_time = microtime(true);
            $response_time = round(($end_time - $start_time) * 1000); // milliseconds
            
            curl_close($ch);
            
            // Consider 2xx and 3xx as successful
            if ($http_code >= 200 && $http_code < 400) {
                $status = 'up';
            } else {
                $error_message = "HTTP Status Code: $http_code";
            }
            
            if ($curl_error) {
                $status = 'down';
                $error_message = $curl_error;
            }
            
        } catch (Exception $e) {
            $status = 'down';
            $error_message = $e->getMessage();
            $response_time = round((microtime(true) - $start_time) * 1000);
        }
        
        // Log the check
        $this->logCheck($status, $response_time, $error_message);
        
        // Check if we need to send alert
        if ($status === 'down') {
            $this->checkAndSendAlert($error_message);
        }
        
        return [
            'status' => $status,
            'response_time' => $response_time,
            'error' => $error_message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Log uptime check to database
     */
    private function logCheck($status, $response_time, $error_message) {
        $query = "INSERT INTO uptime_logs 
                 (check_time, status, response_time_ms, error_message) 
                 VALUES (NOW(), :status, :response_time, :error_message)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':response_time', $response_time);
        $stmt->bindParam(':error_message', $error_message);
        
        return $stmt->execute();
    }
    
    /**
     * Check consecutive failures and send alert
     */
    private function checkAndSendAlert($error_message) {
        // Get recent checks
        $query = "SELECT status FROM uptime_logs 
                 ORDER BY check_time DESC 
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $this->consecutive_failures, PDO::PARAM_INT);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Check if all recent checks failed
        if (count($results) >= $this->consecutive_failures) {
            $all_down = true;
            foreach ($results as $status) {
                if ($status !== 'down') {
                    $all_down = false;
                    break;
                }
            }
            
            if ($all_down) {
                $this->sendDowntimeAlert($error_message);
            }
        }
    }
    
    /**
     * Send downtime alert email
     */
    private function sendDowntimeAlert($error_message) {
        // Check if we already sent an alert recently (within last hour)
        $query = "SELECT COUNT(*) as count FROM uptime_logs 
                 WHERE check_time >= DATE_SUB(NOW(), INTERVAL 1 HOUR) 
                 AND error_message LIKE '%ALERT SENT%'";
        
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return; // Already sent alert in the last hour
        }
        
        $subject = '🚨 ALERT: NETH Bookhive is DOWN';
        $message = $this->buildAlertEmail($error_message);
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: NETH Bookhive Monitor <noreply@nethbookhive.com>\r\n";
        
        mail($this->alert_email, $subject, $message, $headers);
        
        // Mark that we sent an alert
        $this->logCheck('down', 0, 'ALERT SENT: ' . $error_message);
    }
    
    /**
     * Build alert email HTML
     */
    private function buildAlertEmail($error_message) {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Website Down Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0; font-size: 2em;">🚨 Website Down Alert</h1>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #ecf0f1;">
        <h2 style="color: #e74c3c; margin-top: 0;">NETH Bookhive is currently unreachable!</h2>
        
        <div style="background: #ffebee; padding: 15px; border-left: 4px solid #e74c3c; margin: 20px 0;">
            <p style="margin: 0;"><strong>Error:</strong> ' . htmlspecialchars($error_message) . '</p>
        </div>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>URL:</strong> ' . htmlspecialchars($this->check_url) . '</p>
            <p style="margin: 5px 0;"><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p>
            <p style="margin: 5px 0;"><strong>Consecutive Failures:</strong> ' . $this->consecutive_failures . '</p>
        </div>
        
        <h3 style="color: #2c3e50;">Immediate Actions Required:</h3>
        <ul style="color: #2c3e50;">
            <li>Check server status</li>
            <li>Verify database connectivity</li>
            <li>Check server logs for errors</li>
            <li>Verify DNS settings</li>
            <li>Check SSL certificate (if HTTPS)</li>
        </ul>
        
        <p style="margin-top: 30px;">This is an automated alert. Please investigate immediately.</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
        <p style="margin: 0; color: #7f8c8d; font-size: 0.9em;">NETH Bookhive Uptime Monitor</p>
    </div>
</body>
</html>';
    }
    
    /**
     * Get uptime statistics
     */
    public function getUptimeStats($days = 30) {
        $query = "SELECT 
                    COUNT(*) as total_checks,
                    SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as successful_checks,
                    AVG(response_time_ms) as avg_response_time,
                    MAX(response_time_ms) as max_response_time,
                    MIN(response_time_ms) as min_response_time
                 FROM uptime_logs 
                 WHERE check_time >= DATE_SUB(NOW(), INTERVAL :days DAY)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calculate uptime percentage
        if ($stats['total_checks'] > 0) {
            $stats['uptime_percentage'] = ($stats['successful_checks'] / $stats['total_checks']) * 100;
        } else {
            $stats['uptime_percentage'] = 0;
        }
        
        return $stats;
    }
    
    /**
     * Clean old logs (keep last 90 days)
     */
    public function cleanOldLogs($keep_days = 90) {
        $query = "DELETE FROM uptime_logs 
                 WHERE check_time < DATE_SUB(NOW(), INTERVAL :days DAY)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':days', $keep_days, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}

// Run the monitor if executed directly
if (php_sapi_name() === 'cli' || basename($_SERVER['PHP_SELF']) === 'uptime_monitor.php') {
    echo "=== NETH Bookhive Uptime Monitor ===\n";
    echo "Starting check at " . date('Y-m-d H:i:s') . "\n\n";
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $monitor = new UptimeMonitor($db);
        $result = $monitor->checkWebsite();
        
        echo "Status: " . strtoupper($result['status']) . "\n";
        echo "Response Time: " . $result['response_time'] . " ms\n";
        
        if ($result['error']) {
            echo "Error: " . $result['error'] . "\n";
        }
        
        // Display stats
        echo "\n--- Last 30 Days Statistics ---\n";
        $stats = $monitor->getUptimeStats(30);
        echo "Total Checks: " . $stats['total_checks'] . "\n";
        echo "Uptime: " . number_format($stats['uptime_percentage'], 2) . "%\n";
        echo "Avg Response Time: " . round($stats['avg_response_time']) . " ms\n";
        
        // Clean old logs
        $monitor->cleanOldLogs(90);
        
        echo "\nCheck completed successfully!\n";
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        exit(1);
    }
}
?>
