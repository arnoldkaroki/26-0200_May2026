<?php
/**
 * One-time database installer for QuickBite Food Delivery.
 *
 * Visit:
 * http://your-site/install.php?run=quickbite
 *
 * Run this ONCE in your browser to create the database tables
 * and load the QuickBite food catalogue.
 *
 * After successful installation, DELETE this file from the server.
 *
 * The installer runs on the hosting server, allowing it to connect
 * to the host's MySQL database.
 *
 * Safe to re-run:
 * - Tables use IF NOT EXISTS
 * - Seed data uses INSERT IGNORE
 */

require __DIR__ . '/backend/db.php';

header('Content-Type: text/plain; charset=utf-8');


// ============================================================
// SECURITY CHECK
// ============================================================

if (($_GET['run'] ?? '') !== 'quickbite') {

    echo "To install the QuickBite database, add ?run=quickbite to this URL.\n";

    exit;
}


// ============================================================
// SQL FILE
// ============================================================

$sqlFile = __DIR__ . '/sql/deploy_hosted.sql';

if (!is_readable($sqlFile)) {

    http_response_code(500);

    echo "ERROR: Cannot read SQL installation file:\n";
    echo $sqlFile . "\n";

    exit;
}


// ============================================================
// READ SQL FILE
// ============================================================

// Remove SQL comment lines before executing statements.

$sql = preg_replace(
    '/^\s*--.*$/m',
    '',
    file_get_contents($sqlFile)
);


// Split the SQL file into individual statements.

$statements = array_filter(
    array_map(
        'trim',
        explode(';', $sql)
    ),
    static fn($statement) => $statement !== ''
);


// ============================================================
// INSTALL DATABASE
// ============================================================

try {

    $pdo = db();

    $count = 0;


    foreach ($statements as $statement) {

        $pdo->exec($statement);

        $count++;
    }


    // ========================================================
    // INSTALLATION SUCCESS
    // ========================================================

    echo "============================================\n";
    echo " QUICKBITE DATABASE INSTALLATION\n";
    echo "============================================\n\n";

    echo "✓ Database installation completed successfully.\n";

    echo "✓ Executed {$count} SQL statements.\n\n";


    // ========================================================
    // PRODUCT COUNT
    // ========================================================

    $productCount = $pdo
        ->query('SELECT COUNT(*) FROM products')
        ->fetchColumn();

    echo "Food items in catalogue: {$productCount}\n";


    // ========================================================
    // TABLE LIST
    // ========================================================

    $tables = $pdo
        ->query('SHOW TABLES')
        ->fetchAll(PDO::FETCH_COLUMN);

    echo "\nDatabase tables:\n";

    foreach ($tables as $table) {

        echo " - {$table}\n";
    }


    // ========================================================
    // FINAL MESSAGE
    // ========================================================

    echo "\n============================================\n";
    echo " QUICKBITE INSTALLATION COMPLETE\n";
    echo "============================================\n\n";

    echo "Your QuickBite database is ready.\n";

    echo "IMPORTANT: DELETE install.php from the server now.\n";


} catch (Throwable $e) {

    // ========================================================
    // INSTALLATION ERROR
    // ========================================================

    http_response_code(500);

    echo "============================================\n";
    echo " QUICKBITE DATABASE ERROR\n";
    echo "============================================\n\n";

    echo "ERROR: " . $e->getMessage() . "\n\n";

    echo "Please check:\n";
    echo "1. Your database name.\n";
    echo "2. Your database username.\n";
    echo "3. Your database password.\n";
    echo "4. Your database host.\n";
    echo "5. backend/.env DB credentials.\n";
    echo "6. sql/deploy_hosted.sql exists on the server.\n";
}
?>
