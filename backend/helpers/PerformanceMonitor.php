<?php
/**
 * Performance Monitoring System
 * Tracks page load times, database queries, and system health
 */

class PerformanceMonitor {
    private static $startTime;
    private static $queries = [];
    private static $memoryStart;
    
    /**
     * Start monitoring
     */
    public static function start() {
        self::$startTime = microtime(true);
        self::$memoryStart = memory_get_usage();
    }
    
    /**
     * End monitoring and log results
     */
    public static function end($pageName = 'unknown') {
        $endTime = microtime(true);
        $executionTime = ($endTime - self::$startTime) * 1000; // Convert to milliseconds
        $memoryUsed = memory_get_usage() - self::$memoryStart;
        $memoryPeak = memory_get_peak_usage();
        
        $data = [
            'page' => $pageName,
            'execution_time_ms' => round($executionTime, 2),
            'memory_used_kb' => round($memoryUsed / 1024, 2),
            'memory_peak_kb' => round($memoryPeak / 1024, 2),
            'queries_count' => count(self::$queries),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        self::logPerformance($data);
        
        return $data;
    }
    
    /**
     * Track database query
     */
    public static function trackQuery($query, $executionTime) {
        self::$queries[] = [
            'query' => $query,
            'time' => $executionTime
        ];
    }
    
    /**
     * Log performance metrics
     */
    private static function logPerformance($data) {
        $logFile = __DIR__ . '/../../logs/performance.log';
        $logDir = dirname($logFile);
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logEntry = sprintf(
            "[%s] Page: %s | Time: %sms | Memory: %sKB | Peak: %sKB | Queries: %d\n",
            $data['timestamp'],
            $data['page'],
            $data['execution_time_ms'],
            $data['memory_used_kb'],
            $data['memory_peak_kb'],
            $data['queries_count']
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        // Also log to database if available
        self::logToDatabase($data);
    }
    
    /**
     * Log to database
     */
    private static function logToDatabase($data) {
        try {
            include_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "INSERT INTO performance_logs 
                     (page_name, execution_time_ms, memory_used_kb, memory_peak_kb, queries_count, created_at) 
                     VALUES (:page, :exec_time, :memory_used, :memory_peak, :queries, NOW())";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':page', $data['page']);
            $stmt->bindParam(':exec_time', $data['execution_time_ms']);
            $stmt->bindParam(':memory_used', $data['memory_used_kb']);
            $stmt->bindParam(':memory_peak', $data['memory_peak_kb']);
            $stmt->bindParam(':queries', $data['queries_count']);
            
            $stmt->execute();
        } catch (Exception $e) {
            // Silently fail if database not available
            error_log("Performance logging to DB failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get average performance metrics
     */
    public static function getAverageMetrics($hours = 24) {
        try {
            include_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "SELECT 
                        AVG(execution_time_ms) as avg_time,
                        AVG(memory_used_kb) as avg_memory,
                        AVG(queries_count) as avg_queries,
                        COUNT(*) as total_requests
                     FROM performance_logs 
                     WHERE created_at >= DATE_SUB(NOW(), INTERVAL :hours HOUR)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':hours', $hours);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Check system health
     */
    public static function checkSystemHealth() {
        $health = [
            'status' => 'healthy',
            'issues' => [],
            'metrics' => []
        ];
        
        // Check disk space
        $diskFree = disk_free_space('/');
        $diskTotal = disk_total_space('/');
        $diskUsedPercent = (($diskTotal - $diskFree) / $diskTotal) * 100;
        
        $health['metrics']['disk_used_percent'] = round($diskUsedPercent, 2);
        
        if ($diskUsedPercent > 90) {
            $health['status'] = 'critical';
            $health['issues'][] = 'Disk space critically low';
        } elseif ($diskUsedPercent > 80) {
            $health['status'] = 'warning';
            $health['issues'][] = 'Disk space running low';
        }
        
        // Check memory usage
        $memoryLimit = ini_get('memory_limit');
        $memoryUsed = memory_get_usage(true);
        $health['metrics']['memory_used_mb'] = round($memoryUsed / 1024 / 1024, 2);
        
        // Check database connection
        try {
            include_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            $health['metrics']['database'] = 'connected';
        } catch (Exception $e) {
            $health['status'] = 'critical';
            $health['issues'][] = 'Database connection failed';
            $health['metrics']['database'] = 'disconnected';
        }
        
        // Check average response time
        $metrics = self::getAverageMetrics(1);
        if ($metrics) {
            $avgTime = $metrics['avg_time'];
            $health['metrics']['avg_response_time_ms'] = round($avgTime, 2);
            
            if ($avgTime > 2000) {
                $health['status'] = $health['status'] === 'healthy' ? 'warning' : $health['status'];
                $health['issues'][] = 'Average response time is high';
            }
        }
        
        return $health;
    }
    
    /**
     * Get slow queries report
     */
    public static function getSlowQueries($thresholdMs = 100, $hours = 24) {
        try {
            include_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "SELECT page_name, execution_time_ms, queries_count, created_at
                     FROM performance_logs 
                     WHERE execution_time_ms > :threshold 
                     AND created_at >= DATE_SUB(NOW(), INTERVAL :hours HOUR)
                     ORDER BY execution_time_ms DESC 
                     LIMIT 20";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':threshold', $thresholdMs);
            $stmt->bindParam(':hours', $hours);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
