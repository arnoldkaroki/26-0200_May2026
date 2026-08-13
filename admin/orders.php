<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Admin - Manage Customer Orders
 * ============================================================
 */

require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/layout.php';

$pdo = db();
$notice = '';

/*==============================================================
    UPDATE ORDER STATUS
==============================================================*/

$VALID_STATUSES = [
    'pending',
    'confirmed',
    'delivered',
    'cancelled'
];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    $orderId = (int)($_POST['order_id'] ?? 0);
    $status  = (string)($_POST['status'] ?? '');

    if ($orderId > 0 && in_array($status, $VALID_STATUSES, true)) {

        $stmt = $pdo->prepare(
            "UPDATE orders
             SET status = ?
             WHERE id = ?"
        );

        $stmt->execute([$status, $orderId]);

        $notice = "Order #{$orderId} has been updated to \"" .
                  ucfirst($status) . "\".";

    } else {

        $notice = "Invalid order update.";

    }
}

/*==============================================================
    LOAD ALL ORDERS
==============================================================*/

$orders = $pdo->query(
    "SELECT *
     FROM orders
     ORDER BY id DESC"
)->fetchAll();

/*==============================================================
    LOAD ORDER ITEMS
==============================================================*/

$itemsByOrder = [];

if ($orders) {

    $ids = array_column($orders, 'id');

    $placeholders = implode(
        ',',
        array_fill(0, count($ids), '?')
    );

    $stmt = $pdo->prepare(
        "SELECT *
         FROM order_items
         WHERE order_id IN ($placeholders)
         ORDER BY id"
    );

    $stmt->execute($ids);

    foreach ($stmt->fetchAll() as $item) {

        $itemsByOrder[(int)$item['order_id']][] = $item;

    }
}

/*==============================================================
    PAGE HEADER
==============================================================*/

render_header(
    'Customer Orders',
    'orders'
);

if ($notice):
?>

<div class="notice">
    <?= h($notice) ?>
</div>

<?php endif; ?>

<p class="count">

    <?= count($orders) ?>

    Customer Order<?= count($orders) == 1 ? '' : 's' ?>

</p>

<?php if (!$orders): ?>

<p class="empty">

    No customer orders have been placed yet.

</p>

<?php else: ?>

<?php foreach ($orders as $order):

    $orderID = (int)$order['id'];

?>

<div class="order-card">

    <div class="order-head">

        <div>

            <strong>

                Order #<?= $orderID ?>

            </strong>

            •

            <?= h($order['customer_name']) ?>

            •

            <span class="badge badge-<?= h($order['status']) ?>">

                <?= ucfirst(h($order['status'])) ?>

            </span>

        </div>

        <div class="order-total">

            <?= kes($order['total']) ?>

        </div>

    </div>

    <!-- Customer Information -->

    <div class="order-meta">

        <span>
            📧 <?= h($order['customer_email']) ?>
        </span>

        <span>
            📞 <?= h($order['customer_phone']) ?>
        </span>

        <span>
            📍 <?= h($order['delivery_address']) ?>
        </span>

        <?php if ($order['delivery_time']): ?>

        <span>
            🚚 Delivery Time:
            <?= h($order['delivery_time']) ?>
        </span>

        <?php endif; ?>

        <span>
            🗓 Ordered:
            <?= h($order['created_at']) ?>
        </span>

    </div>

    <!-- Ordered Food Items -->

    <table class="admin-table compact">

        <thead>

        <tr>

            <th>Food Item</th>

            <th>Price</th>

            <th>Quantity</th>

            <th>Total</th>

        </tr>

        </thead>

        <tbody>

        <?php foreach ($itemsByOrder[$orderID] ?? [] as $item): ?>

        <tr>

            <td>

                <?= h($item['product_name']) ?>

            </td>

            <td>

                <?= kes($item['unit_price']) ?>

            </td>

            <td>

                <?= (int)$item['quantity'] ?>

            </td>

            <td>

                <?= kes($item['line_total']) ?>

            </td>

        </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    <!-- Order Summary -->

    <div class="order-summary">

        <span>

            Food Total:
            <?= kes($order['subtotal']) ?>

        </span>

        <?php if ((float)$order['discount'] > 0): ?>

        <span>

            Discount

            <?= $order['promo_code']
                ? '(' . h($order['promo_code']) . ')'
                : ''
            ?>

            :

            -<?= kes($order['discount']) ?>

        </span>

        <?php endif; ?>

        <span>

            Delivery Fee:

            <?= (float)$order['delivery_fee'] > 0

                ? kes($order['delivery_fee'])

                : 'FREE'

            ?>

        </span>

        <span class="grand">

            Grand Total:

            <?= kes($order['total']) ?>

        </span>

    </div>

    <!-- Update Order Status -->

    <form
        class="status-form"
        method="post"
        action="orders.php">

        <input
            type="hidden"
            name="order_id"
            value="<?= $orderID ?>">

        <label>

            Order Status

        </label>

        <select name="status">

            <?php foreach ($VALID_STATUSES as $status): ?>

            <option
                value="<?= $status ?>"
                <?= $status === $order['status']
                    ? 'selected'
                    : ''
                ?>>

                <?= ucfirst($status) ?>

            </option>

            <?php endforeach; ?>

        </select>

        <button type="submit">

            Update Status

        </button>

    </form>

</div>

<?php endforeach; ?>

<?php endif; ?>

<?php

render_footer();

?>
