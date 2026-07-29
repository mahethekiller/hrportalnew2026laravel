<?php

declare(strict_types=1);

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=i2u2_db_laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT id, employee_id, first_name, user_id FROM employees LIMIT 5');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Existing Employees:\n";
    print_r($rows);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
