# QuickBite Delivery — Backend (PHP + MySQL)

A lightweight PHP backend for the **QuickBite Delivery** food ordering website. It handles customer registration, menu management, food orders, contact messages, and the admin dashboard using a **MySQL** database. Built for a standard **XAMPP** installation.

---

# Files

| File                | Purpose                                                                                 |
| ------------------- | --------------------------------------------------------------------------------------- |
| `../sql/schema.sql` | Creates the `quick_bite` database and all required tables.                      |
| `config.php`        | Database configuration (XAMPP defaults; can be overridden using environment variables). |
| `db.php`            | Creates and returns a shared PDO database connection.                                   |
| `helpers.php`       | Common helper functions, JSON responses and server-side validation.                     |
| `signup.php`        | Registers new customer accounts.                                                        |
| `login.php`         | Authenticates customers.                                                                |
| `orders.php`        | Handles customer food orders.                                                           |
| `contact.php`       | Saves customer contact messages.                                                        |
| `admin/`            | QuickBite Admin Dashboard for managing customers, orders, menu items and messages.      |

---

# Features

* Customer Registration
* Customer Login
* Online Food Ordering
* Menu Management
* Order Tracking
* Contact Form
* Admin Dashboard
* MySQL Database Integration
* Secure PHP Backend
* Server-side Validation

---

# Setup

## 1. Start Apache and MySQL

Open the **XAMPP Control Panel** and start:

* Apache
* MySQL

The backend requires MySQL to be running.

---

## 2. Create the Database

Import the database using the command line:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root < sql/schema.sql
```

or

Open **phpMyAdmin**

```
http://localhost/phpmyadmin
```

Create a database named

```text
quickbite_delivery
```

Then import

```
sql/schema.sql
```

---

## 3. Run the Website

Navigate to the project folder and start PHP's built-in server.

```bash
cd ~/Documents/QuickBite/quickbite-delivery
/Applications/XAMPP/xamppfiles/bin/php -S localhost:8000
```

Open

```
http://localhost:8000
```

Alternatively, place the project inside

```
/Applications/XAMPP/xamppfiles/htdocs/
```

and visit

```
http://localhost/quickbite-delivery/
```

---

# Database Tables

The QuickBite Delivery system uses the following tables:

* users
* products
* categories
* orders
* order_items
* contact_messages

---

# API Endpoints

## POST `/backend/signup.php`

Registers a new customer.

### Required Fields

| Field  | Description           |
| ------ | --------------------- |
| name   | Customer full name    |
| email  | Email address         |
| phone  | Phone number          |
| gender | Male, Female or Other |

### Successful Response

```json
{
    "ok": true,
    "message": "Registration successful.",
    "name": "John Doe",
    "email": "john@example.com"
}
```

---

## POST `/backend/login.php`

Logs an existing customer into the system.

Required fields

* Email
* Password

---

## POST `/backend/orders.php`

Creates a new food order.

Example fields

* customer_name
* customer_email
* customer_phone
* delivery_address
* delivery_time
* subtotal
* delivery_fee
* total
* order_items

---

## POST `/backend/contact.php`

Stores customer enquiries and feedback.

Fields

* name
* email
* subject
* message

---

# Response Codes

| Status  | Meaning                        |
| ------- | ------------------------------ |
| **201** | Resource created successfully  |
| **200** | Request completed successfully |
| **400** | Validation failed              |
| **401** | Authentication failed          |
| **404** | Resource not found             |
| **405** | Invalid HTTP method            |
| **409** | Duplicate record               |
| **500** | Internal server error          |

---

# Example Registration Request

```bash
curl -X POST http://localhost:8000/backend/signup.php \
-H "Content-Type: application/json" \
-d '{
"name":"Arnold Karoki",
"email":"arnold@example.com",
"phone":"0712345678",
"gender":"male"
}'
```

---

# Admin Dashboard

The QuickBite Admin Dashboard allows administrators to:

* View registered customers
* Manage menu items
* Add new food items
* Edit menu items
* Delete menu items
* View all customer orders
* Update order status
* View customer messages
* Monitor sales and revenue

---

# Security Features

* Prepared SQL statements (prevents SQL Injection)
* Server-side input validation
* HTML output escaping
* Session-based administrator authentication
* Password protection for the admin dashboard
* PDO database connection
* Flash session messages for user feedback

---

# Technologies Used

* PHP 8+
* MySQL
* PDO
* HTML5
* CSS3
* JavaScript
* XAMPP
* Apache

---

# Project

**QuickBite Delivery** is a food delivery web application developed as a university project. The system enables customers to browse menu items, register, place food orders online, and communicate with the restaurant through a modern web interface, while providing administrators with a secure dashboard to manage customers, menu items, orders, and messages.
