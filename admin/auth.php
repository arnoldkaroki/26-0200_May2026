<?php
/**
 * ============================================================
 * QUICKBITE FOOD DELIVERY
 * Admin Authentication & Helper Functions
 * ============================================================
 *
 * This file is included on every QuickBite admin page.
 * It:
 *  - Starts the admin session
 *  - Loads environment variables
 *  - Connects to the database
 *  - Verifies administrator login
 *  - Provides helper functions for the admin dashboard
 *
 * Available Functions:
 * --------------------
 * require_login()   → Redirects to the login page if the admin
 *                     is not authenticated.
 *
 * admin_password()  → Returns the administrator password stored
 *                     in the .env file.
 *
 * is_logged_in()    → Checks whether an admin is logged in.
 *
 * h()               → Escapes HTML output for security.
 *
 * kes()             → Formats numbers as Kenyan Shillings (KES).
 *
 * ============================================================
 */

require_once __DIR__ . '/../backend/env.php';
require_once __DIR__ . '/../backend/db.php';

/*-------------------------------------------------------------
    Start Session
--------------------------------------------------------------*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*-------------------------------------------------------------
    Get Admin Password
--------------------------------------------------------------*/
function admin_password(): string
{
    return (string) env_get('ADMIN_PASSWORD', '');
}

/*-------------------------------------------------------------
    Check Login Status
--------------------------------------------------------------*/
function is_logged_in(): bool
{
    return !empty($_SESSION['admin_authed']);
}

/*-------------------------------------------------------------
    Require Administrator Login
--------------------------------------------------------------*/
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/*-------------------------------------------------------------
    Escape HTML Output
--------------------------------------------------------------*/
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/*-------------------------------------------------------------
    Format Currency (Kenyan Shillings)
--------------------------------------------------------------*/
function kes($amount): string
{
    return 'KES ' . number_format((float) $amount, 2);
}
