<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Admin Dashboard
 * ============================================================
 */

require_once __DIR__ . '/auth.php';
require_login();

require_once __DIR__ . '/layout.php';

$pdo = db();

/*==============================================================
    DASHBOARD STATISTICS
==============================================================*/
$stats = [

    // Registered customers
    'users' => (int) $pdo->query(
        "SELECT COUNT(*) FROM users"
    )->fetchColumn(),

    // Total food orders
    'orders' => (int) $pdo->query(
        "SELECT COUNT(*) FROM orders"
    )->fetchColumn(),

    // Orders waiting to be processed
    'pending' => (int) $pdo->query(
        "SELECT COUNT(*) FROM orders WHERE status='pending'"
    )->fetchColumn(),

    // Menu items available
    'products' => (int) $pdo->query(
        "SELECT COUNT(*) FROM products"
    )->fetchColumn(),

    // Customer contact messages
    'messages' => (int) $pdo->query(
        "SELECT COUNT(*) FROM contact_messages"
    )->fetchColumn(),

    // Total revenue (excluding cancelled orders)
    'revenue' => (float) $pdo->query(
        "SELECT COALESCE(SUM(total),0)
         FROM orders
         WHERE status <> 'cancelled'"
    )->fetchColumn()
];

/*==============================================================
    RECENT FOOD ORDERS
==============================================================*/
$recent = $pdo->query(
    "SELECT
        id,
        customer_name,
        total,
        status,
        created_at
     FROM orders
     ORDER BY id DESC
     LIMIT 5"
)->fetchAll();

/*==============================================================
    PAGE HEADER
==============================================================*/
render_header('QuickBite Dashboard', 'dashboard');
?>

<!-- ==========================================================
     DASHBOARD CARDS
=========================================================== -->

<div class="stat-grid">

    <div class="stat-card">
        <span class="stat-num"><?= $stats['users'] ?></span>
        <span class="stat-label">Registered Customers</span>
    </div>

    <div class="stat-card">
        <span class="stat-num"><?= $stats['orders'] ?></span>
        <span class="stat-label">Total Orders</span>
    </div>

    <div class="stat-card stat-warn">
        <span class="stat-num"><?= $stats['pending'] ?></span>
        <span class="stat-label">Pending Orders</span>
    </div>

    <div class="stat-card stat-money">
        <span class="stat-num"><?= kes($stats['revenue']) ?></span>
        <span class="stat-label">Total Revenue</span>
    </div>

    <div class="stat-card">
        <span class="stat-num"><?= $stats['products'] ?></span>
        <span class="stat-label">Menu Items</span>
    </div>

    <div class="stat-card">
        <span class="stat-num"><?= $stats['messages'] ?></span>
        <span class="stat-label">Customer Messages</span>
    </div>

</div>

<!-- ==========================================================
     RECENT ORDERS
=========================================================== -->

<h3>Recent Food Orders</h3>

<?php if (!$recent): ?>

    <p class="empty">
        No customer orders have been placed yet.
    </p>

<?php else: ?>

<table class="admin-table">

    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Order Date</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($recent as $order): ?>

        <tr>

            <td>#<?= (int)$order['id'] ?></td>

            <td><?= h($order['customer_name']) ?></td>

            <td><?= kes($order['total']) ?></td>

            <td>
                <span class="badge badge-<?= h($order['status']) ?>">
                    <?= ucfirst(h($order['status'])) ?>
                </span>
            </td>

            <td><?= h($order['created_at']) ?></td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<p>
    <a class="btn-link" href="orders.php">
        View All Orders →
    </a>
</p>

<?php endif; ?>

<?php
/*==============================================================
    PAGE FOOTER
==============================================================*/
render_footer();
?>
