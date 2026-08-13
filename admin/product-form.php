<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Add / Edit Menu Item
 * ============================================================
 */

require_once __DIR__ . '/auth.php';
require_login();

require_once __DIR__ . '/layout.php';

$pdo = db();

/*==============================================================
    DROPDOWN OPTIONS
==============================================================*/

$FOOD_TYPES = [
    'food',
    'drink',
    'dessert'
];

$SPICE_LEVELS = [
    'mild',
    'medium',
    'hot',
    'extra_hot'
];

$AVAILABILITY = [
    'in_stock',
    'limited',
    'out_of_stock'
];

/*==============================================================
    LOAD FOOD CATEGORIES
==============================================================*/

$categories = $pdo->query(
    "SELECT id, name
     FROM categories
     ORDER BY name"
)->fetchAll();

/*==============================================================
    GET PRODUCT ID
==============================================================*/

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

/*==============================================================
    DEFAULT MENU ITEM
==============================================================*/

$product = [

    'id' => 0,

    'name' => '',

    'food_type' => 'food',

    'category_id' => '',

    'description' => '',

    'main_ingredient' => '',

    'cuisine' => '',

    'spice_level' => '',

    'preparation_time' => '',

    'price' => '',

    'availability' => 'in_stock',

    'image_url' => '',

    'is_best_seller' => 0,

    'is_featured' => 0

];

$errors = [];

/*==============================================================
    LOAD EXISTING FOOD ITEM
==============================================================*/

if ($id > 0 && ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {

    $stmt = $pdo->prepare(

        "SELECT *
         FROM products
         WHERE id=?"

    );

    $stmt->execute([$id]);

    $food = $stmt->fetch();

    if (!$food) {

        $_SESSION['flash'] = "Food item #{$id} was not found.";

        header("Location: products.php");

        exit;
    }

    $product = $food;
}

/*==============================================================
    SAVE MENU ITEM
==============================================================*/

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    $product = [

        'id' => $id,

        'name' => trim($_POST['name'] ?? ''),

        'food_type' => $_POST['food_type'] ?? 'food',

        'category_id' => $_POST['category_id'] !== ''
            ? (int)$_POST['category_id']
            : '',

        'description' => trim($_POST['description'] ?? ''),

        'main_ingredient' => trim($_POST['main_ingredient'] ?? ''),

        'cuisine' => trim($_POST['cuisine'] ?? ''),

        'spice_level' => $_POST['spice_level'] ?? '',

        'preparation_time' => trim($_POST['preparation_time'] ?? ''),

        'price' => trim($_POST['price'] ?? ''),

        'availability' => $_POST['availability'] ?? 'in_stock',

        'image_url' => trim($_POST['image_url'] ?? ''),

        'is_best_seller' => isset($_POST['is_best_seller']) ? 1 : 0,

        'is_featured' => isset($_POST['is_featured']) ? 1 : 0

    ];

    /*==========================================================
        VALIDATION
    ==========================================================*/

    if (strlen($product['name']) < 2) {

        $errors['name'] =
            "Food name must contain at least 2 characters.";

    }

    if (!is_numeric($product['price']) ||
        (float)$product['price'] < 0) {

        $errors['price'] =
            "Enter a valid price.";

    }

    if (!in_array(
        $product['food_type'],
        $FOOD_TYPES,
        true
    )) {

        $errors['food_type'] =
            "Invalid food type.";

    }

    if (!in_array(
        $product['availability'],
        $AVAILABILITY,
        true
    )) {

        $errors['availability'] =
            "Invalid availability.";

    }

    if ($product['spice_level'] != '' &&
        !in_array(
            $product['spice_level'],
            $SPICE_LEVELS,
            true
        )) {

        $errors['spice_level'] =
            "Invalid spice level.";

    }

    /*==========================================================
        SAVE TO DATABASE
    ==========================================================*/

    if (!$errors) {

        $params = [

            ':name' => $product['name'],

            ':food_type' => $product['food_type'],

            ':category_id' => $product['category_id'] != ''
                ? $product['category_id']
                : null,

            ':description' => $product['description'] != ''
                ? $product['description']
                : null,

            ':main_ingredient' => $product['main_ingredient'] != ''
                ? $product['main_ingredient']
                : null,

            ':cuisine' => $product['cuisine'] != ''
                ? $product['cuisine']
                : null,

            ':spice_level' => $product['spice_level'] != ''
                ? $product['spice_level']
                : null,

            ':preparation_time' => $product['preparation_time'] != ''
                ? $product['preparation_time']
                : null,

            ':price' => (float)$product['price'],

            ':availability' => $product['availability'],

            ':image_url' => $product['image_url'] != ''
                ? $product['image_url']
                : null,

            ':is_best_seller' => $product['is_best_seller'],

            ':is_featured' => $product['is_featured']

        ];

        try {

            if ($id > 0) {

                $params[':id'] = $id;

                $sql = "

                UPDATE products SET

                name=:name,

                food_type=:food_type,

                category_id=:category_id,

                description=:description,

                main_ingredient=:main_ingredient,

                cuisine=:cuisine,

                spice_level=:spice_level,

                preparation_time=:preparation_time,

                price=:price,

                availability=:availability,

                image_url=:image_url,

                is_best_seller=:is_best_seller,

                is_featured=:is_featured

                WHERE id=:id

                ";

                $pdo->prepare($sql)->execute($params);

                $_SESSION['flash'] =
                    "Menu item updated successfully.";

            } else {

                $sql = "

                INSERT INTO products

                (

                name,

                food_type,

                category_id,

                description,

                main_ingredient,

                cuisine,

                spice_level,

                preparation_time,

                price,

                availability,

                image_url,

                is_best_seller,

                is_featured

                )

                VALUES

                (

                :name,

                :food_type,

                :category_id,

                :description,

                :main_ingredient,

                :cuisine,

                :spice_level,

                :preparation_time,

                :price,

                :availability,

                :image_url,

                :is_best_seller,

                :is_featured

                )

                ";

                $pdo->prepare($sql)->execute($params);

                $_SESSION['flash'] =
                    "New menu item added successfully.";

            }

            header("Location: products.php");

            exit;

        } catch (PDOException $e) {

            if ($e->getCode() == '23000') {

                $errors['name'] =
                    "A menu item with that name already exists.";

            } else {

                $errors['name'] =
                    "Unable to save menu item.";

                error_log($e->getMessage());

            }

        }

    }

  <?php

$isEdit = $id > 0;

render_header(
    $isEdit ? 'Edit Menu Item' : 'Add Menu Item',
    'products'
);

/*--------------------------------------------------------------
    Display Validation Errors
--------------------------------------------------------------*/

$err = fn($field) =>
    isset($errors[$field])
        ? '<span class="error-msg">' .
            h($errors[$field]) .
          '</span>'
        : '';

?>

<p>

    <a class="btn-link" href="products.php">

        ← Back to Menu Items

    </a>

</p>

<form

    class="product-form"

    method="post"

    action="product-form.php<?= $isEdit ? '?id='.$id : '' ?>"

>

<input

    type="hidden"

    name="id"

    value="<?= (int)$product['id'] ?>"

>

<div class="pf-grid">

<!-- ==========================================================
     FOOD NAME
=========================================================== -->

<label>

Food Name *

<input

    type="text"

    name="name"

    value="<?= h($product['name']) ?>"

    required

>

<?= $err('name') ?>

</label>

<!-- ==========================================================
     PRICE
=========================================================== -->

<label>

Price (KES) *

<input

    type="number"

    name="price"

    step="0.01"

    min="0"

    value="<?= h($product['price']) ?>"

    required

>

<?= $err('price') ?>

</label>

<!-- ==========================================================
     FOOD TYPE
=========================================================== -->

<label>

Food Type

<select name="food_type">

<?php foreach($FOOD_TYPES as $type): ?>

<option

value="<?= $type ?>"

<?= $product['food_type']==$type ? 'selected':'' ?>

>

<?= ucfirst($type) ?>

</option>

<?php endforeach; ?>

</select>

</label>

<!-- ==========================================================
     CATEGORY
=========================================================== -->

<label>

Category

<select name="category_id">

<option value="">

-- Select Category --

</option>

<?php foreach($categories as $category): ?>

<option

value="<?= (int)$category['id'] ?>"

<?=

(string)$product['category_id']==

(string)$category['id']

?

'selected'

:

''

?>

>

<?= h($category['name']) ?>

</option>

<?php endforeach; ?>

</select>

</label>

<!-- ==========================================================
     MAIN INGREDIENT
=========================================================== -->

<label>

Main Ingredient

<input

type="text"

name="main_ingredient"

value="<?= h($product['main_ingredient']) ?>"

placeholder="Chicken, Beef, Cheese..."

>

</label>

<!-- ==========================================================
     CUISINE
=========================================================== -->

<label>

Cuisine

<input

type="text"

name="cuisine"

value="<?= h($product['cuisine']) ?>"

placeholder="Italian, Kenyan, Chinese..."

>

</label>

<!-- ==========================================================
     SPICE LEVEL
=========================================================== -->

<label>

Spice Level

<select name="spice_level">

<option value="">

None

</option>

<?php foreach($SPICE_LEVELS as $level): ?>

<option

value="<?= $level ?>"

<?=

$product['spice_level']==$level

?

'selected'

:

''

?>

>

<?= ucfirst(str_replace('_',' ',$level)) ?>

</option>

<?php endforeach; ?>

</select>

</label>

<!-- ==========================================================
     PREPARATION TIME
=========================================================== -->

<label>

Preparation Time

<input

type="text"

name="preparation_time"

value="<?= h($product['preparation_time']) ?>"

placeholder="15 mins"

>

</label>

<!-- ==========================================================
     AVAILABILITY
=========================================================== -->

<label>

Availability

<select name="availability">

<?php foreach($AVAILABILITY as $status): ?>

<option

value="<?= $status ?>"

<?=

$product['availability']==$status

?

'selected'

:

''

?>

>

<?= ucfirst(str_replace('_',' ',$status)) ?>

</option>

<?php endforeach; ?>

</select>

</label>

<!-- ==========================================================
     IMAGE URL
=========================================================== -->

<label>

Food Image URL

<input

type="text"

name="image_url"

value="<?= h($product['image_url']) ?>"

placeholder="https://example.com/image.jpg"

>

</label>

</div>

<!-- ==========================================================
     DESCRIPTION
=========================================================== -->

<label class="pf-full">

Description

<textarea

name="description"

rows="5"

placeholder="Describe the menu item..."

><?= h($product['description']) ?></textarea>

</label>

<!-- ==========================================================
     FEATURED OPTIONS
=========================================================== -->

<div class="pf-checks">

<label class="pf-check">

<input

type="checkbox"

name="is_best_seller"

<?= $product['is_best_seller'] ? 'checked':'' ?>

>

⭐ Best Seller

</label>

<label class="pf-check">

<input

type="checkbox"

name="is_featured"

<?= $product['is_featured'] ? 'checked':'' ?>

>

🔥 Featured Menu Item

</label>

</div>

<button

type="submit"

class="btn-save"

>

<?=

$isEdit

?

'Update Menu Item'

:

'Add Menu Item'

?>

</button>

</form>

<?php
/*==============================================================
    PAGE FOOTER
==============================================================*/

render_footer();
?>

}
