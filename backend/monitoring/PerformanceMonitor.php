<?php
/**
 * Performance Monitor
 * NETH BookHive - Performance Monitoring System
 * 
 * Tracks page load times, database queries, and memory usage
 */

class PerformanceMonitor {
    
    private static $startTime;
    private static $startMemory;
    private static $queries = [];
    private static $events = [];
    
    /**
     * Start monitoring
     */
    public static function start() {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage();
    }
    
    /**
     * Log database query
     * @param string $query SQL query
     * @param float $executionTime Execution time in seconds
     */
    public static function logQuery($query, $executionTime) {
        self::$queries[] = [
            'query' => $query,
            'time' => $executionTime,
            'timestamp' => microtime(true)
        ];
    }
    
    /**
     * Log custom event
     * @param string $name Event name
     * @param string $description Event description
     */
    public static function logEvent($name, $description = '') {
        self::$events[] = [
            'name' => $name,
            'description' => $description,
            'time' => microtime(true) - self::$startTime,
            'memory' => memory_get_usage() - self::$startMemory
        ];
    }
    
    /**
     * Get performance report
     * @return array Performance statistics
     */
    public static function getReport() {
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $totalTime = $endTime - self::$startTime;
        $totalMemory = $endMemory - self::$startMemory;
        
        $queryTime = 0;
        foreach (self::$queries as $query) {
            $queryTime += $query['time'];
        }
        
        return [
            'page_load_time' => round($totalTime * 1000, 2) . ' ms',
            'memory_used' => self::formatBytes($totalMemory),
            'peak_memory' => self::formatBytes(memory_get_peak_usage()),
            'query_count' => count(self::$queries),
            'query_time' => round($queryTime * 1000, 2) . ' ms',
            'queries' => self::$queries,
            'events' => self::$events,
            'php_version' => PHP_VERSION,
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
        ];
    }
    
    /**
     * Display performance report (for debugging)
     */
    public static function displayReport() {
        $report = self::getReport();
        
        echo '<div style="background: #2c3e50; color: #ecf0f1; padding: 20px; margin: 20px; border-radius: 10px; font-family: monospace;">';
        echo '<h3 style="color: #3498db; margin-top: 0;">⚡ Performance Report</h3>';
        
        echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
        echo '<div><strong>Page Load:</strong> ' . $report['page_load_time'] . '</div>';
        echo '<div><strong>Memory Used:</strong> ' . $report['memory_used'] . '</div>';
        echo '<div><strong>Peak Memory:</strong> ' . $report['peak_memory'] . '</div>';
        echo '<div><strong>Queries:</strong> ' . $report['query_count'] . ' (' . $report['query_time'] . ')</div>';
        echo '<div><strong>PHP Version:</strong> ' . $report['php_version'] . '</div>';
        echo '<div><strong>Server:</strong> ' . $report['server'] . '</div>';
        echo '</div>';
        
        if (!empty($report['queries'])) {
            echo '<h4 style="color: #e74c3c; margin-top: 20px;">Database Queries:</h4>';
            echo '<div style="max-height: 200px; overflow-y: auto;">';
            foreach ($report['queries'] as $i => $query) {
                $time = round($query['time'] * 1000, 2);
                echo '<div style="margin: 5px 0; padding: 5px; background: rgba(255,255,255,0.1); border-radius: 5px;">';
                echo '<small>' . ($i + 1) . '. ' . htmlspecialchars(substr($query['query'], 0, 100)) . '...</small>';
                echo ' <span style="color: #f39c12;">(' . $time . ' ms)</span>';
                echo '</div>';
            }
            echo '</div>';
        }
        
        if (!empty($report['events'])) {
            echo '<h4 style="color: #2ecc71; margin-top: 20px;">Events:</h4>';
            foreach ($report['events'] as $event) {
                $time = round($event['time'] * 1000, 2);
                echo '<div style="margin: 5px 0;">';
                echo '<strong>' . htmlspecialchars($event['name']) . '</strong>: ';
                echo htmlspecialchars($event['description']) . ' ';
                echo '<span style="color: #3498db;">(' . $time . ' ms)</span>';
                echo '</div>';
            }
        }
        
        echo '</div>';
    }
    
    /**
     * Log report to file
     * @param string $page Page name
     */
    public static function logToFile($page = 'unknown') {
        $report = self::getReport();
        $logFile = __DIR__ . '/../../logs/performance.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = sprintf(
            "[%s] Page: %s | Load: %s | Memory: %s | Queries: %d (%s)\n",
            $timestamp,
            $page,
            $report['page_load_time'],
            $report['memory_used'],
            $report['query_count'],
            $report['query_time']
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
    
    /**
     * Get slow query report (queries > threshold)
     * @param float $threshold Threshold in seconds
     * @return array Slow queries
     */
    public static function getSlowQueries($threshold = 0.1) {
        $slowQueries = [];
        
        foreach (self::$queries as $query) {
            if ($query['time'] > $threshold) {
                $slowQueries[] = $query;
            }
        }
        
        return $slowQueries;
    }
    
    /**
     * Format bytes to human readable
     * @param int $bytes Bytes
     * @return string Formatted string
     */
    private static function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Check if page load is slow
     * @param float $threshold Threshold in seconds
     * @return bool True if slow
     */
    public static function isSlow($threshold = 1.0) {
        $endTime = microtime(true);
        $totalTime = $endTime - self::$startTime;
        return $totalTime > $threshold;
    }
    
    /**
     * Get memory usage percentage
     * @return float Memory usage percentage
     */
    public static function getMemoryUsagePercent() {
        $limit = ini_get('memory_limit');
        $limitBytes = self::convertToBytes($limit);
        $used = memory_get_usage();
        
        if ($limitBytes == -1) {
            return 0; // No limit
        }
        
        return round(($used / $limitBytes) * 100, 2);
    }
    
    /**
     * Convert PHP memory limit to bytes
     * @param string $value Memory limit string
     * @return int Bytes
     */
    private static function convertToBytes($value) {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int)$value;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }
}

// Auto-start monitoring if enabled
if (!defined('DISABLE_PERFORMANCE_MONITOR')) {
    PerformanceMonitor::start();
}
?>
