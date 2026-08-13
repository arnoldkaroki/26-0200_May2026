<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Customer Management
 * ============================================================
 */

require_once __DIR__ . '/auth.php';
require_login();

require_once __DIR__ . '/layout.php';

/*==============================================================
    LOAD REGISTERED CUSTOMERS
==============================================================*/

$users = db()->query(

    "SELECT

        id,
        full_name,
        email,
        phone,
        gender,
        created_at

     FROM users

     ORDER BY id DESC"

)->fetchAll();

/*==============================================================
    PAGE HEADER
==============================================================*/

render_header(
    "Customers",
    "users"
);

?>

<p class="count">

    <?= count($users) ?>

    Registered Customer<?= count($users) == 1 ? '' : 's' ?>

</p>

<?php if (!$users): ?>

<p class="empty">

    No customers have registered yet.

</p>

<?php else: ?>

<table class="admin-table">

<thead>

<tr>

<th>ID</th>

<th>Customer Name</th>

<th>Email Address</th>

<th>Phone Number</th>

<th>Gender</th>

<th>Registration Date</th>

</tr>

</thead>

<tbody>

<?php foreach ($users as $user): ?>

<tr>

<td>

<?= (int)$user['id'] ?>

</td>

<td>

<?= h($user['full_name']) ?>

</td>

<td>

<?= h($user['email']) ?>

</td>

<td>

<?= h($user['phone']) ?>

</td>

<td>

<?= ucfirst(h($user['gender'])) ?>

</td>

<td>

<?= h($user['created_at']) ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php endif; ?>

<?php

render_footer();

?>
