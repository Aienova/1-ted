<?php

/**
 * Load environment variables from .env file
 */
function loadEnv($envFile) {
    if (!file_exists($envFile)) {
        throw new RuntimeException("Environment file not found: $envFile");
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Set environment variable
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Detect if we are in local or production environment
 */
function getEnvironment() {
    // Check if we're on localhost
    $isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']);
    
    // Or check by custom environment variable
    if (isset($_ENV['APP_ENV'])) {
        return $_ENV['APP_ENV'];
    }
    
    return $isLocal ? 'local' : 'prod';
}

/**
 * Get database connection with environment-based configuration
 */
function getDatabaseConnection() {
    // Toujours charger local.env qui contient MODE_LOCAL
    $envFile = __DIR__ . "/../local.env";
    loadEnv($envFile);
    
    // Déterminer le mode (local ou production)
    $isLocal = isset($_ENV['MODE_LOCAL']) && $_ENV['MODE_LOCAL'] === 'true';
    
    // Get database configuration from environment variables selon le mode
    if ($isLocal) {
        // Configuration locale
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? '1ted';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';
    } else {
        // Configuration production
        $host = $_ENV['PROD_DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['PROD_DB_NAME'] ?? 'ted_backoffice_prod';
        $username = $_ENV['PROD_DB_USER'] ?? 'root';
        $password = $_ENV['PROD_DB_PASSWORD'] ?? '';
    }
    
    $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
    
    $mode = $isLocal ? 'LOCAL' : 'PRODUCTION';
    
    try {
        // Create PDO connection
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $username, $password, $options);
        
        return $pdo;
        
    } catch (PDOException $e) {
        // Log error
        error_log("Database connection error [$mode]: " . $e->getMessage());
        throw new RuntimeException("Database connection failed [$mode]: " . $e->getMessage());
    }
}
