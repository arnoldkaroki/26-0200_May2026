<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Admin Layout
 * ============================================================
 *
 * Shared layout for all QuickBite admin pages.
 * Provides:
 *  - Header
 *  - Navigation menu
 *  - Footer
 *
 * Usage:
 * render_header('Page Title', 'active_menu');
 *      // Page Content
 * render_footer();
 * ============================================================
 */

function render_header(string $title, string $active = ''): void
{
    $nav = [

        'dashboard' => ['index.php', '📊 Dashboard'],

        'users' => ['users.php', '👥 Customers'],

        'orders' => ['orders.php', '🍔 Orders'],

        'products' => ['products.php', '🍕 Menu Items'],

        'messages' => ['messages.php', '💬 Messages']

    ];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= h($title) ?> | QuickBite Admin</title>

    <link rel="stylesheet" href="admin.css">

</head>

<body>

<header class="admin-header">

    <h1>🍔 QuickBite Food Delivery Admin</h1>

    <a href="logout.php" class="logout">
        Log Out →
    </a>

</header>

<nav class="admin-nav">

    <?php foreach ($nav as $key => [$href, $label]): ?>

        <a
            href="<?= $href ?>"
            class="<?= $key === $active ? 'active' : '' ?>">

            <?= $label ?>

        </a>

    <?php endforeach; ?>

</nav>

<main class="admin-main">

    <h2><?= h($title) ?></h2>

<?php
}

/*==============================================================
    FOOTER
==============================================================*/

function render_footer(): void
{
?>

</main>

<footer class="admin-footer">

    <p>
        © <?= date('Y') ?> QuickBite Food Delivery |
        Admin Dashboard
    </p>

</footer>

</body>

</html>

<?php
}
?>
