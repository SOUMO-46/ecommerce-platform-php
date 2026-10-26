# E-commerce Backend + Admin Panel

## Stack
PHP OOP, MySQLi, MySQL, REST API, HTML, CSS, JavaScript, XAMPP, Postman.

## XAMPP setup

1. Extract this folder.
2. Rename the extracted folder to `ecommerce` (recommended).
3. Put it in:
   `C:\xampp\htdocs\ecommerce`
4. Start Apache and MySQL in XAMPP.
5. Open phpMyAdmin.
6. Import:
   `database/ecommerce.sql`
7. Open the API:
   `http://localhost/ecommerce/backend/`
8. Open products API:
   `http://localhost/ecommerce/backend/index.php/products`
9. Open customer frontend:
   `http://localhost/ecommerce/frontend/`
10. Open admin panel:
   `http://localhost/ecommerce/admin/`

## Create an admin account

1. Open:
   `http://localhost/ecommerce/frontend/register.html`
2. Register normally.
3. In phpMyAdmin run:

   UPDATE users
   SET role='admin'
   WHERE email='your@email.com';

4. Login again. Admin users are sent to the Admin Dashboard.

## Important

Opening `/backend/` alone is NOT an error. In this fixed version it returns:
`E-commerce API is running.`

## Main API endpoints

GET    /backend/index.php/products
GET    /backend/index.php/products/{id}
POST   /backend/index.php/products
PUT    /backend/index.php/products/{id}
DELETE /backend/index.php/products/{id}

GET    /backend/index.php/categories
POST   /backend/index.php/categories
PUT    /backend/index.php/categories/{id}
DELETE /backend/index.php/categories/{id}

POST   /backend/index.php/register
POST   /backend/index.php/login
POST   /backend/index.php/logout
GET    /backend/index.php/me

GET    /backend/index.php/cart
POST   /backend/index.php/cart
PUT    /backend/index.php/cart/{itemId}
DELETE /backend/index.php/cart/{itemId}

GET    /backend/index.php/orders
POST   /backend/index.php/orders
PUT    /backend/index.php/orders/{id}

GET    /backend/index.php/users  (admin only)
