<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Menu Management
 * ============================================================
 */

require_once __DIR__ . '/auth.php';
require_login();

require_once __DIR__ . '/layout.php';

$pdo = db();

/*==============================================================
    DELETE MENU ITEM
==============================================================*/

if (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && ($_POST['action'] ?? '') === 'delete'
) {

    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {

        $stmt = $pdo->prepare(
            "DELETE FROM products WHERE id=?"
        );

        $stmt->execute([$id]);

        $_SESSION['flash'] =
            "Menu item #{$id} deleted successfully.";

    }

    header("Location: products.php");
    exit;
}

/*==============================================================
    FLASH MESSAGE
==============================================================*/

$flash = $_SESSION['flash'] ?? '';

unset($_SESSION['flash']);

/*==============================================================
    LOAD MENU ITEMS
==============================================================*/

$products = $pdo->query(

"SELECT

p.id,

p.name,

p.food_type,

c.name AS category,

p.cuisine,

p.price,

p.availability,

p.is_best_seller,

p.is_featured

FROM products p

LEFT JOIN categories c

ON c.id = p.category_id

ORDER BY

p.food_type,

p.price DESC

"

)->fetchAll();

/*==============================================================
    PAGE HEADER
==============================================================*/

render_header(
    "Menu Items",
    "products"
);

?>

<?php if($flash): ?>

<div class="notice">

    <?= h($flash) ?>

</div>

<?php endif; ?>

<div class="toolbar">

<p class="count">

<?= count($products) ?>

Menu Item<?= count($products)==1 ? '' : 's' ?>

Available

</p>

<a

class="btn-add"

href="product-form.php"

>

+ Add Menu Item

</a>

</div>

<table class="admin-table">

<thead>

<tr>

<th>ID</th>

<th>Food Name</th>

<th>Type</th>

<th>Category</th>

<th>Cuisine</th>

<th>Price</th>

<th>Availability</th>

<th>Best Seller</th>

<th>Featured</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php foreach($products as $product): ?>

<tr>

<td>

<?= (int)$product['id'] ?>

</td>

<td>

<?= h($product['name']) ?>

</td>

<td>

<?= ucfirst(h($product['food_type'])) ?>

</td>

<td>

<?= h($product['category'] ?? 'None') ?>

</td>

<td>

<?= h($product['cuisine'] ?: '-') ?>

</td>

<td>

<?= kes($product['price']) ?>

</td>

<td>

<span class="badge badge-<?= h($product['availability']) ?>">

<?= ucfirst(str_replace('_',' ',$product['availability'])) ?>

</span>

</td>

<td>

<?= $product['is_best_seller'] ? '⭐' : '-' ?>

</td>

<td>

<?= $product['is_featured'] ? '🔥' : '-' ?>

</td>

<td class="actions">

<a

class="btn-edit"

href="product-form.php?id=<?= (int)$product['id'] ?>"

>

Edit

</a>

<form

method="post"

action="products.php"

onsubmit="return confirm(

'Delete <?= h(addslashes($product['name'])) ?>?'

);"

>

<input

type="hidden"

name="action"

value="delete"

>

<input

type="hidden"

name="id"

value="<?= (int)$product['id'] ?>"

>

<button

type="submit"

class="btn-delete"

>

Delete

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

<?php if(empty($products)): ?>

<tr>

<td colspan="10">

No menu items found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

<?php

render_footer();

?>
