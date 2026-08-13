<?php
/**
 * ============================================================
 * QUICKBITE DELIVERY
 * POST /backend/order.php
 * ============================================================
 *
 * Creates a new food order.
 *
 * The client sends ONLY:
 * - Customer information
 * - Menu item IDs
 * - Quantities
 * - Optional promo code
 *
 * Prices are NEVER accepted from the browser.
 * All prices are calculated from the database.
 *
 * Request JSON
 *
 * {
 *   "customer":{
 *      "name":"",
 *      "email":"",
 *      "phone":"",
 *      "address":"",
 *      "delivery_time":""
 *   },
 *
 *   "items":[
 *      {
 *          "id":1,
 *          "quantity":2
 *      }
 *   ],
 *
 *   "promo_code":"WELCOME10"
 * }
 */

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

/*==============================================================
    DELIVERY SETTINGS
==============================================================*/

const DELIVERY_FEE = 200.00;

const FREE_DELIVERY_THRESHOLD = 2500.00;

/*==============================================================
    ALLOW ONLY POST
==============================================================*/

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {

    json_response(
        405,
        [
            'ok' => false,
            'message' => 'Method not allowed. Use POST.'
        ]
    );

}

/*==============================================================
    READ REQUEST
==============================================================*/

$input = read_input();

$customer = is_array($input['customer'] ?? null)
    ? $input['customer']
    : [];

$items = is_array($input['items'] ?? null)
    ? $input['items']
    : [];

$promoInput = strtoupper(
    trim(
        (string)($input['promo_code'] ?? '')
    )
);

/*==============================================================
    CUSTOMER DETAILS
==============================================================*/

$name = trim(
    (string)($customer['name'] ?? '')
);

$email = trim(
    (string)($customer['email'] ?? '')
);

$phone = trim(
    (string)($customer['phone'] ?? '')
);

$address = trim(
    (string)($customer['address'] ?? '')
);

$deliveryTime = trim(
    (string)($customer['delivery_time'] ?? '')
);

/*==============================================================
    VALIDATION
==============================================================*/

$errors = [];

if (!valid_name($name)) {

    $errors['name'] =
        'Enter a valid customer name.';

}

if (!valid_email($email)) {

    $errors['email'] =
        'Enter a valid email address.';

}

if (!valid_phone($phone)) {

    $errors['phone'] =
        'Enter a valid phone number.';

}

if (strlen($address) < 6) {

    $errors['address'] =
        'Enter a delivery address.';

}

if (empty($items)) {

    $errors['items'] =
        'Your cart is empty.';

}

if ($errors) {

    json_response(

        400,

        [

            'ok' => false,

            'errors' => $errors

        ]

    );

}

/*==============================================================
    NORMALIZE CART
==============================================================*/

$cart = [];

foreach ($items as $item) {

    $id = (int)($item['id'] ?? 0);

    $quantity = (int)($item['quantity'] ?? 0);

    if ($id <= 0 || $quantity <= 0) {

        continue;

    }

    $quantity = min($quantity, 99);

    $cart[$id] =
        ($cart[$id] ?? 0) + $quantity;

}

if (!$cart) {

    json_response(

        400,

        [

            'ok' => false,

            'message' =>
                'No valid menu items found.'

        ]

    );

}

/*==============================================================
    LOAD MENU ITEMS
==============================================================*/

try {

    $pdo = db();

    $ids = array_keys($cart);

    $placeholders = implode(
        ',',
        array_fill(
            0,
            count($ids),
            '?'
        )
    );

    $stmt = $pdo->prepare(

        "SELECT

            id,

            name,

            price,

            availability

        FROM products

        WHERE id IN ($placeholders)"

    );

    $stmt->execute($ids);

    $products = [];

    foreach ($stmt->fetchAll() as $row) {

        $products[(int)$row['id']] = $row;

    }

    /*==========================================================
        VERIFY MENU ITEMS
    ==========================================================*/

    foreach ($ids as $id) {

        if (!isset($products[$id])) {

            json_response(

                400,

                [

                    'ok' => false,

                    'message' =>
                        "Menu item #{$id} no longer exists."

                ]

            );

        }

        if (
            $products[$id]['availability']
            ===
            'out_of_stock'
        ) {

            json_response(

                409,

                [

                    'ok' => false,

                    'message' =>
                        $products[$id]['name'] .
                        ' is currently unavailable.'

                ]

            );

        }

    }

    /*==========================================================
        BUILD ORDER
    ==========================================================*/

    $orderItems = [];

    $subtotal = 0;

    foreach ($cart as $id => $quantity) {

        $price =
            (float)$products[$id]['price'];

        $lineTotal =
            $price * $quantity;

        $subtotal +=
            $lineTotal;

        $orderItems[] = [

            'product_id' => $id,

            'product_name' =>
                $products[$id]['name'],

            'unit_price' => $price,

            'quantity' => $quantity,

            'line_total' => $lineTotal

        ];

            /*==========================================================
        APPLY PROMO CODE
    ==========================================================*/

    $discount = 0.00;

    $promoCode = null;

    if ($promoInput !== '') {

        $promoStmt = $pdo->prepare(

            "SELECT

                code,
                discount_percent,
                min_order_amount

            FROM promotions

            WHERE code = ?

            AND is_active = 1

            AND (starts_at IS NULL OR starts_at <= CURDATE())

            AND (ends_at IS NULL OR ends_at >= CURDATE())"

        );

        $promoStmt->execute([$promoInput]);

        $promo = $promoStmt->fetch();

        if (!$promo) {

            json_response(

                400,

                [

                    'ok' => false,

                    'errors' => [

                        'promo_code' =>
                            'Invalid promo code.'

                    ]

                ]

            );

        }

        if ($subtotal < (float)$promo['min_order_amount']) {

            json_response(

                400,

                [

                    'ok' => false,

                    'errors' => [

                        'promo_code' =>

                        'Minimum order value is KES ' .

                        number_format(

                            (float)$promo['min_order_amount']

                        )

                    ]

                ]

            );

        }

        $discount = round(

            $subtotal *

            (

                (float)$promo['discount_percent']

                / 100

            ),

            2

        );

        $promoCode = $promo['code'];

    }

    /*==========================================================
        DELIVERY CHARGE
    ==========================================================*/

    if ($subtotal >= FREE_DELIVERY_THRESHOLD) {

        $deliveryFee = 0.00;

    } else {

        $deliveryFee = DELIVERY_FEE;

    }

    $total =

        $subtotal

        - $discount

        + $deliveryFee;

    /*==========================================================
        SAVE ORDER
    ==========================================================*/

    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare(

        "INSERT INTO orders

        (

            customer_name,

            customer_email,

            customer_phone,

            delivery_address,

            delivery_time,

            promo_code,

            subtotal,

            discount,

            delivery_fee,

            total,

            status

        )

        VALUES

        (

            :name,

            :email,

            :phone,

            :address,

            :time,

            :promo,

            :subtotal,

            :discount,

            :delivery,

            :total,

            :status

        )"

    );

    $orderStmt->execute([

        ':name' => $name,

        ':email' => $email,

        ':phone' => $phone,

        ':address' => $address,

        ':time' =>

            $deliveryTime !== ''

            ? $deliveryTime

            : null,

        ':promo' => $promoCode,

        ':subtotal' => $subtotal,

        ':discount' => $discount,

        ':delivery' => $deliveryFee,

        ':total' => $total,

        ':status' => 'pending'

    ]);

    $orderId =

        (int)$pdo->lastInsertId();

    /*==========================================================
        PREPARE ORDER ITEMS
    ==========================================================*/

    $itemStmt = $pdo->prepare(

        "INSERT INTO order_items

        (

            order_id,

            product_id,

            product_name,

            unit_price,

            quantity,

            line_total

        )

        VALUES

        (

            ?, ?, ?, ?, ?, ?

        )"

    );

            /*==========================================================
        SAVE ORDER ITEMS
    ==========================================================*/

    foreach ($orderItems as $item) {

        $itemStmt->execute([

            $orderId,

            $item['product_id'],

            $item['product_name'],

            $item['unit_price'],

            $item['quantity'],

            $item['line_total']

        ]);

    }

    /*==========================================================
        COMMIT TRANSACTION
    ==========================================================*/

    $pdo->commit();

}

/*==============================================================
    DATABASE ERROR
==============================================================*/

catch (PDOException $e) {

    if (isset($pdo) && $pdo->inTransaction()) {

        $pdo->rollBack();

    }

    error_log(

        'QuickBite Order Error: ' .

        $e->getMessage()

    );

    json_response(

        500,

        [

            'ok' => false,

            'message' =>

                'Unable to place your order. Please try again later.'

        ]

    );

}

/*==============================================================
    SUCCESS RESPONSE
==============================================================*/

json_response(

    201,

    [

        'ok' => true,

        'message' =>

            'Your order has been placed successfully!',

        'order_id' => $orderId,

        'subtotal' => $subtotal,

        'discount' => $discount,

        'delivery_fee' => $deliveryFee,

        'total' => $total,

        'status' => 'pending'

    ]

);

    }
