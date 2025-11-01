# NETH BookHive - Online Bookstore

[![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=flat&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue?style=flat&logo=mysql)](https://www.mysql.com/)
[![HTML5](https://img.shields.io/badge/HTML5-orange?style=flat&logo=html5)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-blue?style=flat&logo=css3)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-yellow?style=flat&logo=javascript)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

A complete e‑commerce bookstore website project built with vanilla PHP, MySQL, HTML, CSS and JavaScript. Ideal for college/university web development projects and portfolios.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Folder Structure](#folder-structure)
- [Installation & Run](#installation--run)
- [Database Setup](#database-setup)
- [Demo Credentials](#demo-credentials)
- [Configuration](#configuration)
- [Troubleshooting](#troubleshooting)
- [Security](#security)
- [Design & UX](#design--ux)
- [Contributing](#contributing)
- [License & Credits](#license--credits)
- [Contact & Team](#contact--team)

---

## Project Overview

NETH BookHive is a fully functional online bookstore web application where users can browse books, search, add items to the cart, register/login, and place orders. The project demonstrates full-stack fundamentals using PHP (PDO), MySQL, and vanilla frontend technologies.

---

## Features

User-facing:
- Browse book catalog with images and prices
- Search books
- User registration and secure login
- Shopping cart with real-time updates
- Checkout and order placement
- User profile, order history and wishlist
- Mobile-responsive UI

Admin:
- Admin dashboard (manage books, view orders, user management)

Technical:
- PHP with PDO (prepared statements)
- Password hashing (bcrypt)
- CSRF protection on forms
- XSS prevention and input sanitization
- AJAX for smoother UX
- No frameworks — plain PHP and vanilla JS

---

## Tech Stack

- Frontend: HTML5, CSS3, JavaScript (ES6+), Font Awesome
- Backend: PHP 7.4+
- Database: MySQL 8.0
- Server: Apache (XAMPP recommended)

---

## Folder Structure (high level)

NETH Bookhive/
- frontend/        ← user-facing pages (index, shop, cart, checkout, etc.)
  - css/
  - js/
  - images/
- backend/
  - api/            ← API endpoints (auth, books, cart, orders, wishlist)
  - config/         ← database.php
  - models/         ← Book.php, User.php, Cart.php, Order.php, ...
  - views/          ← header.php, footer.php
  - security/       ← Security helpers
- database/
  - bookstore.sql
- admin/            ← admin dashboard pages
- splash.html
- setup_demo_users.php
- health_check.php
- README.md

---

## Installation & Run

Prerequisites:
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Edge)
- Text editor (VS Code recommended)

Steps:
1. Install XAMPP and start Apache and MySQL.
2. Copy the project folder `NETH Bookhive` to your XAMPP `htdocs` folder:
   - Example path: `C:\xampp\htdocs\NETH Bookhive\`
3. Open the provided automatic setup script in your browser to create the database and sample data:
   - http://localhost/NETH%20Bookhive/backend/setup.php
   - Or run: http://localhost/NETH%20Bookhive/setup_demo_users.php
4. Open the site:
   - Splash screen: `http://localhost/NETH%20Bookhive/splash.html`
   - Frontend homepage: `http://localhost/NETH%20Bookhive/frontend/index.php`

---

## Database Setup

Database name: `bookstore`

Tables included:
- users (id, name, email, password, ...)
- books (id, title, author, price, image_url, description, ...)
- cart (id, user_id, book_id, quantity)
- orders (id, user_id, total_amount, status, created_at)
- order_items (id, order_id, book_id, quantity, price)

Options to set up DB:
- Automatic: Visit `backend/setup.php` or `setup_demo_users.php` in browser — creates DB, tables and seed data.
- Manual: Import `database/bookstore.sql` via phpMyAdmin.

---

## Demo Credentials

Regular user:
- Email: user@nethbookhive.com
- Password: user123

Admin user:
- Email: admin@nethbookhive.com
- Password: admin123

(These demo accounts are created by `setup_demo_users.php`.)

---

## Configuration

Edit database configuration at:
```
backend/config/database.php
```

Sample configuration values:
```php
private $host = "localhost:3306";   // or "localhost:3307" depending on your XAMPP
private $db_name = "bookstore";
private $username = "root";
private $password = "";             // default XAMPP MySQL password is empty
```

If your MySQL listens on a custom port, update the host (e.g., `localhost:3307`).

---

## Troubleshooting

Problem: Page not found / 404
- Ensure Apache is running in XAMPP.
- Confirm the project folder is at `C:\xampp\htdocs\NETH Bookhive\`.
- Use exact URL encoding spaces: `http://localhost/NETH%20Bookhive/frontend/index.php`.

Problem: Database connection failed
- Start MySQL in XAMPP.
- Check `backend/config/database.php` for correct host, port, username, password.
- Verify `bookstore` exists in phpMyAdmin.

Problem: No books showing
- Run setup script to seed sample data, or import `database/bookstore.sql`.

Problem: Login/Register failing
- Check sessions are enabled in PHP.
- Run setup script to ensure `users` table exists.

Problem: Images not loading
- Images load from Unsplash by default (requires internet).
- To use local images: create `frontend/images/` and update `image_url` in DB.

Use `health_check.php` to diagnose environment (PHP version, extensions, DB connection, file permissions, etc.):
```
http://localhost/NETH%20Bookhive/health_check.php
```

---

## Security

- Passwords hashed using bcrypt
- PDO prepared statements to prevent SQL injection
- CSRF tokens on important forms
- Input validation and output escaping for XSS protection
- Session management best practices (session_regenerate_id, secure flags where applicable)
- Optional rate-limiting for login attempts

---

## Design & UX

- Modern gradient color palette (purple → pink accents)
- Responsive layout (mobile-first)
- Glassmorphism UI elements and smooth hover animations
- Font Awesome icons and accessible design considerations

---

## Contributing

This is a student project — contributions are welcome.

How to contribute:
1. Fork the repository
2. Create a branch for your feature/fix
3. Make changes and test locally
4. Open a pull request with a clear description of changes

Please keep code commented and maintain consistent formatting.

---

## License & Credits

- License: Open for educational and non-commercial use. Do not claim as entirely your own work or commercialize without permission.
- Images: Unsplash (used for book covers)
- Tools: XAMPP, VS Code, Font Awesome

Credits:
- M. Reezma Hanan
- AJ. Raeef
- NM. Mahuroos
- NM. Asrar

---

## Contact & Team

Team Members:
- M. Reezma Hanan
- AJ. Raeef
- NM. Mahuroos
- NM. Asrar

For issues, check the Troubleshooting section and review server logs:
- Apache error log: `C:\xampp\apache\logs\error.log`

---

## Quick Start Checklist

- [ ] XAMPP installed and Apache & MySQL running
- [ ] Project folder copied to `C:\xampp\htdocs\NETH Bookhive\`
- [ ] Database created (run setup script)
- [ ] Homepage loads: `http://localhost/NETH%20Bookhive/frontend/index.php`
- [ ] Can register and login
- [ ] Books visible on Shop page
- [ ] Can add to cart and checkout

---

Last Updated: November 2, 2025

Happy coding! 📚✨
