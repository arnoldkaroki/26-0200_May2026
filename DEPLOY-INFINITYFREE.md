# Deploying QuickBite Food Delivery to InfinityFree (Free PHP + MySQL)

This guide explains how to deploy the **QuickBite Food Delivery** website online using **InfinityFree**. The project includes:

* Public website
* Customer registration
* Product catalogue
* Shopping cart
* Checkout & ordering system
* Contact form
* Admin dashboard

The deployment package should contain:

```
quickbite-deploy.zip
```

Inside it you'll find:

```
sql/deploy_hosted.sql
```

which contains the complete database schema and sample data.

---

# 1. Create an InfinityFree Account

1. Visit **[https://infinityfree.com](https://infinityfree.com)**
2. Click **Sign Up**
3. Verify your email.
4. Create a free hosting account.
5. Choose a free subdomain such as:

```
quickbite.infinityfreeapp.com
```

or connect your own domain.

Wait until the hosting account becomes active.

---

# 2. Create the Database

Open the InfinityFree Control Panel.

Go to

```
MySQL Databases
```

Create a database.

After creation you'll receive:

```
Database Host
Database Name
Database Username
Database Password
```

They usually look similar to:

```
Host:
sql210.infinityfree.com

Database:
epiz_12345678_quickbite

Username:
epiz_12345678

Password:
********
```

Keep these details.

---

# 3. Configure the Environment

Open

```
backend/.env
```

Replace the placeholders with your own values.

Example:

```env
DB_HOST=sql210.infinityfree.com
DB_PORT=3306
DB_NAME=epiz_12345678_quickbite
DB_USER=epiz_12345678
DB_PASS=your_database_password

ADMIN_PASSWORD=QuickBite2026
```

Replace the administrator password with one only you know.

---

# 4. Upload the Website

Use **FileZilla** or InfinityFree's File Manager.

Connect using your FTP credentials.

Open the folder

```
htdocs
```

Upload **everything inside your QuickBite project**.

The final structure should resemble:

```
htdocs/
│
├── index.html
├── menu.html
├── about.html
├── contact.html
├── register.html
├── cart.html
├── checkout.html
├── css/
├── js/
├── images/
│
├── backend/
│   ├── admin/
│   ├── auth.php
│   ├── config.php
│   ├── db.php
│   ├── helpers.php
│   ├── products.php
│   ├── order.php
│   ├── signup.php
│   ├── contact.php
│   └── .env
│
└── sql/
    └── deploy_hosted.sql
```

---

# 5. Import the Database

Open

```
Control Panel
```

↓

```
phpMyAdmin
```

Select your QuickBite database.

Click

```
Import
```

Choose

```
sql/deploy_hosted.sql
```

Click

```
Go
```

The database will automatically create all required tables including:

* users
* categories
* products
* orders
* order_items
* promotions
* contact_messages
* departments

It will also insert sample food categories, menu items, promotions, and department information.

---

# 6. Select the PHP Version

Inside the hosting control panel choose

```
Select PHP Version
```

Select

```
PHP 8.0+
```

PHP 8.1 or newer is recommended.

Save the changes.

---

# 7. Test the Website

Visit your new website:

```
https://your-subdomain.infinityfreeapp.com
```

Test the following features:

* Browse the menu
* Register a customer account
* View products
* Add meals to the cart
* Place an order
* Submit the contact form

---

# 8. Access the Admin Dashboard

Open:

```
https://your-subdomain.infinityfreeapp.com/backend/admin/
```

Log in using the administrator password specified in:

```
backend/.env
```

From the dashboard you can:

* Manage menu items
* Add new food products
* Edit products
* Delete products
* Manage customer registrations
* View orders
* Monitor the QuickBite catalogue

---

# Project Structure

```
QuickBite/
│
├── backend/
│   ├── admin/
│   ├── auth.php
│   ├── config.php
│   ├── db.php
│   ├── helpers.php
│   ├── signup.php
│   ├── products.php
│   ├── order.php
│   ├── contact.php
│   └── .env
│
├── css/
├── js/
├── images/
├── sql/
│   ├── schema.sql
│   ├── seed.sql
│   └── deploy_hosted.sql
│
├── index.html
├── menu.html
├── about.html
├── contact.html
├── register.html
├── cart.html
├── checkout.html
└── README.md
```

---

# Important Notes

* Always access the site using the InfinityFree URL or your custom domain. PHP will not work if you simply open the HTML files directly from your computer.
* Do **not** upload your local XAMPP database. Instead, import `deploy_hosted.sql` using the host's phpMyAdmin.
* On InfinityFree, the database host is **not** `localhost`. Always use the host name provided in the MySQL Databases section (for example, `sql210.infinityfree.com`).
* Keep your `.env` file private because it contains your database credentials and administrator password.
* InfinityFree is suitable for demonstrations, coursework, and small projects, but it has resource limits and may pause inactive sites.
