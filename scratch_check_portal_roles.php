<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=i2u2_db_laravel', 'root', '');

echo "--- Portal Roles (portal_roles) ---\n";
try {
    $stmt = $pdo->query('SELECT * FROM portal_roles');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
