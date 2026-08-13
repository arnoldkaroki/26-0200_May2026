<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Admin Login
 * ============================================================
 */

require_once __DIR__ . '/auth.php';

/*-------------------------------------------------------------
    Already logged in?
--------------------------------------------------------------*/
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

/*-------------------------------------------------------------
    Process Login
--------------------------------------------------------------*/
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {

    $entered = (string) ($_POST['password'] ?? '');
    $actual  = admin_password();

    // No password configured
    if ($actual === '') {

        $error = 'No administrator password has been configured. Please set ADMIN_PASSWORD in backend/.env.';

    }
    // Correct password
    elseif (hash_equals($actual, $entered)) {

        session_regenerate_id(true);

        $_SESSION['admin_authed'] = true;

        header('Location: index.php');
        exit;

    }
    // Wrong password
    else {

        $error = 'Incorrect administrator password.';

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>QuickBite Admin Login</title>

    <link rel="stylesheet" href="admin.css">

</head>

<body class="login-body">

<form
    class="login-box"
    method="post"
    action="login.php">

    <h1>🍔 QuickBite Admin</h1>

    <p>
        Food Delivery Management Dashboard
    </p>

    <?php if ($error): ?>

        <div class="login-error">

            <?= h($error) ?>

        </div>

    <?php endif; ?>

    <input
        type="password"
        name="password"
        placeholder="Enter administrator password"
        autofocus
        required>

    <button type="submit">

        Login

    </button>

</form>

</body>

</html>
