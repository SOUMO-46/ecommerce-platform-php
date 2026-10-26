# E-commerce Platform

A professional full-stack **E-commerce Platform** built with **PHP OOP, MySQLi, REST API, HTML, CSS and JavaScript**.

The project includes a customer-facing e-commerce frontend, authentication system, product and category management, shopping cart, order management and a dedicated admin dashboard.

---

## 🚀 Features

### 🛍️ Customer Features

- Customer registration
- Customer login
- Customer logout
- Product browsing
- Product listing
- Product details
- Category management
- Shopping cart
- Add products to cart
- Update cart quantity
- Remove products from cart
- Order management
- Customer order history
- User session/authentication handling

### 👨‍💼 Admin Dashboard

- Admin authentication
- Admin dashboard
- Product management
- Category management
- Order management
- User management
- Product creation
- Product updating
- Product deletion
- Category creation
- Category updating
- Category deletion
- Order status management

### 🔌 REST API

The project includes a PHP REST API for communication between the frontend and backend.

Supported API operations include:

```text
GET
POST
PUT
DELETE
```

### 🔐 Authentication

The application includes:

- User registration
- Login
- Logout
- Session-based authentication
- Admin authorization
- Protected admin endpoints

---

## 🛠️ Technologies Used

### Frontend

- HTML5
- CSS3
- JavaScript
- Fetch API

### Backend

- PHP
- PHP OOP
- REST API
- MySQLi Object-Oriented

### Database

- MySQL
- Prepared Statements
- Relational Database

### Tools

- XAMPP
- phpMyAdmin
- Postman
- VS Code
- Git
- GitHub

---

## 📁 Project Structure

```text
ecommerce-platform-php/
│
├── admin/
│   ├── css/
│   │   └── admin.css
│   │
│   ├── js/
│   │   ├── categories.js
│   │   ├── dashboard.js
│   │   ├── orders.js
│   │   ├── products.js
│   │   ├── ui.js
│   │   └── users.js
│   │
│   ├── categories.html
│   ├── index.html
│   ├── orders.html
│   ├── products.html
│   └── users.html
│
├── backend/
│   ├── config/
│   │   └── Database.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CategoryController.php
│   │   ├── OrderController.php
│   │   └── ProductController.php
│   │
│   ├── helpers/
│   │   ├── Auth.php
│   │   └── Response.php
│   │
│   ├── models/
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Product.php
│   │   └── User.php
│   │
│   ├── .htaccess
│   └── index.php
│
├── database/
│   └── ecommerce_with_30_demo_products.sql
│
├── frontend/
│   ├── css/
│   │   └── style.css
│   │
│   ├── js/
│   │   ├── auth.js
│   │   ├── cart.js
│   │   ├── orders.js
│   │   ├── products.js
│   │   └── ui.js
│   │
│   ├── cart.html
│   ├── index.html
│   ├── login.html
│   ├── orders.html
│   ├── products.html
│   └── register.html
│
├── uploads/
│   └── products/
│       └── .gitkeep
│
└── README.md
```

---

## 🔄 Application Architecture

The application follows a layered PHP OOP architecture.

```text
Customer / Admin
       │
       ▼
HTML + CSS + JavaScript
       │
       ▼
Fetch API
       │
       ▼
REST API
       │
       ▼
backend/index.php
       │
       ▼
Controllers
       │
       ├── ProductController
       ├── CategoryController
       ├── AuthController
       ├── CartController
       └── OrderController
       │
       ▼
Models
       │
       ├── Product
       ├── Category
       ├── User
       ├── Cart
       └── Order
       │
       ▼
MySQL Database
```

---

## 🗄️ Database

The project includes a complete SQL database file:

```text
database/ecommerce_with_30_demo_products.sql
```

The database contains the required e-commerce structure and includes **30 demo products**.

---

## 💻 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/ecommerce-platform-php.git
```

Move into the project directory:

```bash
cd ecommerce-platform-php
```

---

### 2. Install XAMPP

Install **XAMPP** and start:

- Apache
- MySQL

Place the project inside:

```text
C:\xampp\htdocs\
```

Example:

```text
C:\xampp\htdocs\ecommerce-platform-php
```

---

## 🗃️ Database Setup

### 1. Open phpMyAdmin

```text
http://localhost/phpmyadmin
```

### 2. Create Database

Create a database named:

```text
ecommerce
```

The project database configuration already uses:

```text
Database: ecommerce
Username: root
Password: empty
Host: localhost
```

For production, use secure environment-based credentials instead of default credentials.

### 3. Import SQL

Import:

```text
database/ecommerce_with_30_demo_products.sql
```

This will create the required tables and demo product data.

---

## ⚙️ Backend Configuration

The database connection is handled by:

```text
backend/config/Database.php
```

The default local configuration is:

```php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ecommerce';
```

Update these values if your local MySQL configuration is different.

---

## 🌐 Run the Application

### Customer Frontend

Open:

```text
http://localhost/ecommerce-platform-php/frontend/
```

### Customer Registration

```text
http://localhost/ecommerce-platform-php/frontend/register.html
```

### Customer Login

```text
http://localhost/ecommerce-platform-php/frontend/login.html
```

### Admin Panel

```text
http://localhost/ecommerce-platform-php/admin/
```

### REST API

```text
http://localhost/ecommerce-platform-php/backend/
```

When the backend root is opened, it returns:

```text
E-commerce API is running.
```

This confirms that the API is available.

---

## 🔌 REST API Endpoints

### Products

```text
GET     /backend/index.php/products
GET     /backend/index.php/products/{id}
POST    /backend/index.php/products
PUT     /backend/index.php/products/{id}
DELETE  /backend/index.php/products/{id}
```

### Categories

```text
GET     /backend/index.php/categories
POST    /backend/index.php/categories
PUT     /backend/index.php/categories/{id}
DELETE  /backend/index.php/categories/{id}
```

### Authentication

```text
POST    /backend/index.php/register
POST    /backend/index.php/login
POST    /backend/index.php/logout
GET     /backend/index.php/me
```

### Cart

```text
GET     /backend/index.php/cart
POST    /backend/index.php/cart
PUT     /backend/index.php/cart/{itemId}
DELETE  /backend/index.php/cart/{itemId}
```

### Orders

```text
GET     /backend/index.php/orders
POST    /backend/index.php/orders
PUT     /backend/index.php/orders/{id}
```

### Users

```text
GET     /backend/index.php/users
```

The users endpoint is restricted to administrators.

---

## 🧪 API Testing

You can test the REST API using **Postman**.

Example:

```text
GET http://localhost/ecommerce-platform-php/backend/index.php/products
```

For POST and PUT requests, send JSON data with the appropriate request headers.

---

## 👤 Creating an Admin Account

First register a normal account from:

```text
http://localhost/ecommerce-platform-php/frontend/register.html
```

Then open phpMyAdmin and update the user's role:

```sql
UPDATE users
SET role = 'admin'
WHERE email = 'your@email.com';
```

After changing the role, log in again.

The admin user will have access to the Admin Dashboard.

---

## 🔐 Security

The project uses a structured backend architecture with:

- PHP OOP
- MySQLi Object-Oriented
- Prepared SQL statements
- Authentication helpers
- Admin authorization
- JSON API responses
- Separate controllers and models
- Database connection abstraction

For production deployment, additional security should be implemented, including:

- HTTPS
- Environment variables
- CSRF protection
- Strong password policies
- API authentication tokens
- Rate limiting
- Input/output sanitization
- Secure session configuration
- File upload validation

---

## 📦 Product Uploads

Product-related uploads are stored under:

```text
uploads/products/
```

The `.gitkeep` file keeps the directory available in Git when it is empty.

Uploaded product images should be handled carefully in production with proper:

- File type validation
- File size validation
- Filename sanitization
- Permission controls

---

## 🎯 Project Purpose

This project demonstrates practical full-stack web development using:

- PHP OOP
- MySQLi
- REST API
- CRUD operations
- Authentication
- Authorization
- E-commerce architecture
- Shopping cart functionality
- Order management
- Admin dashboard
- JavaScript Fetch API
- Database integration
- Git and GitHub

It can be used as a portfolio project and as a foundation for a larger production e-commerce application.

---

## 🔮 Future Improvements

Possible future enhancements:

- Payment gateway integration
- Product reviews and ratings
- Wishlist
- Coupon and discount system
- Inventory management
- Order tracking
- Product search and advanced filtering
- Product pagination
- Customer profile management
- Password reset
- Email notifications
- Admin analytics
- Sales reports
- PDF invoices
- Excel export
- JWT/API token authentication
- Role-based permission management

---

## 👨‍💻 Author

**Soumo**

Full-Stack Web Developer

### Skills

```text
HTML
CSS
JavaScript
PHP
PHP OOP
MySQL
MySQLi
REST API
Git
GitHub
```

---

## 📄 License

This project was developed for learning, portfolio and development purposes.
