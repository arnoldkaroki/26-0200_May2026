#!/bin/bash
# ───────────────────────────────────────────────
# QUICKBITE FOOD DELIVERY
# phpMyAdmin Setup Script
#
# Sets up phpMyAdmin for the QuickBite database.
#
# Database:
#     quick-bite
#
# Usage:
#     ./setup-phpmyadmin.sh
# ───────────────────────────────────────────────

set -e

# Always run from the QuickBite project directory
cd "$(dirname "$0")"


# ═══════════════════════════════════════════════
# XAMPP phpMyAdmin location
# ═══════════════════════════════════════════════

SRC="/Applications/XAMPP/xamppfiles/phpmyadmin"

DEST="./phpmyadmin"


# Check if XAMPP phpMyAdmin exists
if [ ! -d "$SRC" ]; then

    echo "✗ Could not find phpMyAdmin at:"
    echo "  $SRC"
    echo ""
    echo "Make sure XAMPP is installed."

    exit 1

fi


# ═══════════════════════════════════════════════
# QUICKBITE DATABASE SETTINGS
# ═══════════════════════════════════════════════

DB_NAME="quick-bite"

DB_HOST="127.0.0.1"

DB_PORT="3306"


# ═══════════════════════════════════════════════
# READ DATABASE PASSWORD
# ═══════════════════════════════════════════════

if [ ! -f "backend/.env" ]; then

    echo "✗ backend/.env was not found."

    echo "Create backend/.env with your database credentials."

    exit 1

fi


DB_PASS=$(grep -E '^DB_PASS=' backend/.env | head -1 | cut -d= -f2-)


if [ -z "$DB_PASS" ]; then

    echo "✗ DB_PASS was not found in backend/.env"

    exit 1

fi


# ═══════════════════════════════════════════════
# COPY phpMyAdmin
# ═══════════════════════════════════════════════

echo "→ Copying phpMyAdmin from XAMPP..."

rm -rf "$DEST"

cp -R "$SRC" "$DEST"


# Create phpMyAdmin temporary directory
mkdir -p "$DEST/tmp"

chmod 777 "$DEST/tmp"


# ═══════════════════════════════════════════════
# GENERATE SECURITY SECRET
# ═══════════════════════════════════════════════

SECRET=$(openssl rand -hex 16)


# ═══════════════════════════════════════════════
# CREATE phpMyAdmin CONFIGURATION
# ═══════════════════════════════════════════════

echo "→ Creating QuickBite phpMyAdmin configuration..."


cat > "$DEST/config.inc.php" <<PHP
<?php

declare(strict_types=1);

/*
 * QuickBite Food Delivery
 * phpMyAdmin configuration
 */

\$cfg['blowfish_secret'] = '${SECRET}quickbite';

\$i = 0;

\$i++;


/*
 * MySQL Server
 */

\$cfg['Servers'][\$i]['auth_type'] = 'config';

\$cfg['Servers'][\$i]['user'] = 'root';

\$cfg['Servers'][\$i]['password'] = '${DB_PASS}';

\$cfg['Servers'][\$i]['host'] = '${DB_HOST}';

\$cfg['Servers'][\$i]['port'] = '${DB_PORT}';

\$cfg['Servers'][\$i]['connect_type'] = 'tcp';

\$cfg['Servers'][\$i]['compress'] = false;

\$cfg['Servers'][\$i]['AllowNoPassword'] = false;


/*
 * Only display the QuickBite database
 */

\$cfg['Servers'][\$i]['only_db'] = ['quick-bite'];


/*
 * phpMyAdmin temporary directory
 */

\$cfg['TempDir'] = __DIR__ . '/tmp';


/*
 * Default server
 */

\$cfg['ServerDefault'] = 1;

PHP


# ═══════════════════════════════════════════════
# FINISHED
# ═══════════════════════════════════════════════

echo ""
echo "✓ QuickBite phpMyAdmin setup completed successfully."

echo ""
echo "Database: $DB_NAME"
echo "Host:     $DB_HOST"
echo "Port:     $DB_PORT"

echo ""
echo "Start QuickBite using:"
echo "  /Applications/XAMPP/xamppfiles/bin/php -S localhost:8000"

echo ""
echo "Then open:"
echo "  http://localhost:8000/phpmyadmin/"

echo ""
echo "✓ QuickBite is ready."
