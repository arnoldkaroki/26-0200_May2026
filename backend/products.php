<?php
/**
 * ============================================================
 * QUICKBITE DELIVERY
 * GET /backend/products.php
 * ============================================================
 *
 * Returns all menu items as JSON so the website can display
 * the menu directly from the database.
 *
 * Optional:
 * ?kind=food
 * ?kind=drink
 *
 * Response:
 *
 * 200
 * {
 *   "ok": true,
 *   "products": [
 *     {
 *       "id":1,
 *       "name":"Beef Burger",
 *       "kind":"food",
 *       "category":"Burgers",
 *       "price":650,
 *       "availability":"in_stock",
 *       "food_type":"Fast Food",
 *       "main_ingredient":"Beef",
 *       "preparation_time":"20 mins",
 *       "spice_level":"Medium",
 *       "image_url":"images/burger.jpg",
 *       "is_popular":true
 *     }
 *   ]
 * }
 */

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

/*==============================================================
    ALLOW ONLY GET
==============================================================*/

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {

    json_response(

        405,

        [
            'ok' => false,
            'message' => 'Method not allowed. Use GET.'
        ]

    );

}

/*==============================================================
    OPTIONAL FILTER
==============================================================*/

$kind = $_GET['kind'] ?? '';

try {

    $sql =

        "SELECT

            p.id,

            p.name,

            p.kind,

            c.name AS category,

            p.price,

            p.availability,

            p.food_type,

            p.main_ingredient,

            p.preparation_time,

            p.spice_level,

            p.image_url,

            p.is_popular

        FROM products p

        LEFT JOIN categories c

            ON c.id = p.category_id";

    $params = [];

    if ($kind === 'food' || $kind === 'drink') {

        $sql .= " WHERE p.kind = ?";

        $params[] = $kind;

    }

    $sql .= " ORDER BY p.kind, p.price ASC";

    $stmt = db()->prepare($sql);

    $stmt->execute($params);

    $products = array_map(

        static function (array $row): array {

            return [

                'id' => (int)$row['id'],

                'name' => $row['name'],

                'kind' => $row['kind'],

                'category' => $row['category'],

                'price' => (float)$row['price'],

                'availability' => $row['availability'],

                'food_type' => $row['food_type'],

                'main_ingredient' => $row['main_ingredient'],

                'preparation_time' => $row['preparation_time'],

                'spice_level' => $row['spice_level'],

                'image_url' => $row['image_url'],

                'is_popular' => (bool)$row['is_popular']

            ];

        },

        $stmt->fetchAll()

    );

}

catch (PDOException $e) {

    error_log(

        'QuickBite Products Error: ' .

        $e->getMessage()

    );

    json_response(

        500,

        [

            'ok' => false,

            'message' =>

                'Could not load menu items.'

        ]

    );

}

/*==============================================================
    SUCCESS RESPONSE
==============================================================*/

json_response(

    200,

    [

        'ok' => true,

        'products' => $products

    ]

);
