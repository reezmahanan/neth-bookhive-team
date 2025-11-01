<?php
/**
 * System Health Check
 * NETH BookHive - System Monitoring
 * 
 * Checks database, file permissions, and system status
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/backend/config/database.php';

class SystemHealthCheck {
    
    private $results = [];
    private $errors = [];
    private $warnings = [];
    
    /**
     * Run all health checks
     * @return array Results
     */
    public function runAllChecks() {
        $this->checkPHPVersion();
        $this->checkDatabaseConnection();
        $this->checkRequiredExtensions();
        $this->checkFilePermissions();
        $this->checkDiskSpace();
        $this->checkMemoryLimit();
        $this->checkSecuritySettings();
        $this->checkLogFiles();
        
        return [
            'status' => empty($this->errors) ? 'healthy' : 'issues_found',
            'results' => $this->results,
            'errors' => $this->errors,
            'warnings' => $this->warnings
        ];
    }
    
    /**
     * Check PHP version
     */
    private function checkPHPVersion() {
        $version = PHP_VERSION;
        $minVersion = '7.4.0';
        
        if (version_compare($version, $minVersion, '>=')) {
            $this->results[] = "✓ PHP Version: $version (OK)";
        } else {
            $this->errors[] = "✗ PHP Version: $version (Requires $minVersion or higher)";
        }
    }
    
    /**
     * Check database connection
     */
    private function checkDatabaseConnection() {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            if ($db) {
                $this->results[] = "✓ Database Connection: OK";
                
                // Check if tables exist
                $tables = ['users', 'books', 'orders', 'cart'];
                foreach ($tables as $table) {
                    $stmt = $db->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() > 0) {
                        $this->results[] = "  ✓ Table '$table' exists";
                    } else {
                        $this->warnings[] = "  ⚠ Table '$table' not found";
                    }
                }
            } else {
                $this->errors[] = "✗ Database Connection: Failed";
            }
        } catch (Exception $e) {
            $this->errors[] = "✗ Database Error: " . $e->getMessage();
        }
    }
    
    /**
     * Check required PHP extensions
     */
    private function checkRequiredExtensions() {
        $required = ['pdo', 'pdo_mysql', 'mysqli', 'json', 'mbstring', 'curl'];
        
        foreach ($required as $ext) {
            if (extension_loaded($ext)) {
                $this->results[] = "✓ Extension '$ext': Loaded";
            } else {
                $this->errors[] = "✗ Extension '$ext': Not loaded";
            }
        }
    }
    
    /**
     * Check file permissions
     */
    private function checkFilePermissions() {
        $paths = [
            __DIR__ . '/../../frontend/uploads' => 'Uploads directory',
            __DIR__ . '/../../logs' => 'Logs directory',
            __DIR__ . '/../../cache' => 'Cache directory'
        ];
        
        foreach ($paths as $path => $name) {
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                $this->results[] = "✓ Created $name";
            }
            
            if (is_writable($path)) {
                $this->results[] = "✓ $name: Writable";
            } else {
                $this->errors[] = "✗ $name: Not writable";
            }
        }
    }
    
    /**
     * Check disk space
     */
    private function checkDiskSpace() {
        $free = disk_free_space(".");
        $total = disk_total_space(".");
        $used = $total - $free;
        $percent = round(($used / $total) * 100, 2);
        
        if ($percent < 90) {
            $this->results[] = "✓ Disk Space: " . $this->formatBytes($free) . " free ($percent% used)";
        } else {
            $this->warnings[] = "⚠ Disk Space: Low (" . $this->formatBytes($free) . " free, $percent% used)";
        }
    }
    
    /**
     * Check memory limit
     */
    private function checkMemoryLimit() {
        $limit = ini_get('memory_limit');
        $this->results[] = "✓ Memory Limit: $limit";
        
        $used = memory_get_usage();
        $peak = memory_get_peak_usage();
        
        $this->results[] = "  Current: " . $this->formatBytes($used);
        $this->results[] = "  Peak: " . $this->formatBytes($peak);
    }
    
    /**
     * Check security settings
     */
    private function checkSecuritySettings() {
        $settings = [
            'display_errors' => 'Off',
            'expose_php' => 'Off',
            'allow_url_fopen' => 'On',
            'session.use_strict_mode' => 'On'
        ];
        
        foreach ($settings as $setting => $recommended) {
            $value = ini_get($setting);
            $status = ($value == $recommended || 
                      ($recommended == 'Off' && empty($value)) ||
                      ($recommended == 'On' && !empty($value))) ? '✓' : '⚠';
            
            if ($status == '✓') {
                $this->results[] = "$status $setting: $value (Recommended: $recommended)";
            } else {
                $this->warnings[] = "$status $setting: $value (Recommended: $recommended)";
            }
        }
    }
    
    /**
     * Check log files
     */
    private function checkLogFiles() {
        $logDir = __DIR__ . '/../../logs';
        
        if (is_dir($logDir)) {
            $logs = glob($logDir . '/*.log');
            $totalSize = 0;
            
            foreach ($logs as $log) {
                $totalSize += filesize($log);
            }
            
            $this->results[] = "✓ Log Files: " . count($logs) . " files (" . $this->formatBytes($totalSize) . ")";
            
            if ($totalSize > 10485760) { // 10 MB
                $this->warnings[] = "⚠ Log files are large. Consider rotating logs.";
            }
        }
    }
    
    /**
     * Format bytes
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Display results as HTML
     */
    public function displayHTML() {
        $data = $this->runAllChecks();
        
        $statusColor = $data['status'] == 'healthy' ? '#2ecc71' : '#e74c3c';
        $statusText = $data['status'] == 'healthy' ? 'System Healthy' : 'Issues Found';
        
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>System Health Check - NETH BookHive</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 40px 20px;
                    min-height: 100vh;
                }
                .container {
                    max-width: 900px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    overflow: hidden;
                }
                .header {
                    background: <?php echo $statusColor; ?>;
                    color: white;
                    padding: 30px;
                    text-align: center;
                }
                .header h1 { font-size: 2rem; margin-bottom: 10px; }
                .header p { opacity: 0.9; }
                .content { padding: 30px; }
                .section {
                    margin-bottom: 25px;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 10px;
                    border-left: 4px solid #667eea;
                }
                .section h2 {
                    color: #667eea;
                    margin-bottom: 15px;
                    font-size: 1.3rem;
                }
                .item {
                    padding: 8px 0;
                    font-family: 'Courier New', monospace;
                    font-size: 0.9rem;
                }
                .item.error { color: #e74c3c; }
                .item.warning { color: #f39c12; }
                .item.success { color: #2ecc71; }
                .timestamp {
                    text-align: center;
                    color: #7f8c8d;
                    margin-top: 20px;
                    font-size: 0.9rem;
                }
                .btn {
                    display: inline-block;
                    padding: 12px 30px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 25px;
                    margin: 10px 5px;
                    transition: transform 0.3s;
                }
                .btn:hover { transform: translateY(-2px); }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🏥 System Health Check</h1>
                    <p><?php echo $statusText; ?></p>
                </div>
                
                <div class="content">
                    <?php if (!empty($data['results'])): ?>
                    <div class="section">
                        <h2>✓ System Status</h2>
                        <?php foreach ($data['results'] as $result): ?>
                            <div class="item success"><?php echo htmlspecialchars($result); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['warnings'])): ?>
                    <div class="section">
                        <h2>⚠ Warnings</h2>
                        <?php foreach ($data['warnings'] as $warning): ?>
                            <div class="item warning"><?php echo htmlspecialchars($warning); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['errors'])): ?>
                    <div class="section">
                        <h2>✗ Errors</h2>
                        <?php foreach ($data['errors'] as $error): ?>
                            <div class="item error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="timestamp">
                        Last checked: <?php echo date('Y-m-d H:i:s'); ?>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="javascript:location.reload()" class="btn">🔄 Refresh</a>
                        <a href="../frontend/index.php" class="btn">🏠 Back to Home</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}

// Run health check
$healthCheck = new SystemHealthCheck();
$healthCheck->displayHTML();
?>
