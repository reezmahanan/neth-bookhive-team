# 📚 NETH BookHive - Online Bookstore# 📚 NETH Bookhive - Online Bookstore



> **A Complete E-Commerce Website for Students****A Complete Online Bookstore Website Project**



![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=flat&logo=php)

![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue?style=flat&logo=mysql)

![HTML5](https://img.shields.io/badge/HTML5-orange?style=flat&logo=html5)This is a fully functional bookstore website where users can browse books, add them to cart, and place orders. Perfect for college/university web development projects.

![CSS3](https://img.shields.io/badge/CSS3-blue?style=flat&logo=css3)

![JavaScript](https://img.shields.io/badge/JavaScript-yellow?style=flat&logo=javascript)---



---## 🎯 What Can This Website Do?



## 🎯 What Is This Project?✅ Show books with pictures and prices  

✅ Let users search for books  

NETH BookHive is a **fully functional online bookstore** where users can:✅ Users can create accounts (Register/Login)  

- 📖 Browse and search books✅ Add books to shopping cart  

- 🛒 Add books to shopping cart✅ Place orders  

- 👤 Create accounts (Register & Login)✅ Works on mobile phones too!

- 💳 Place orders

- 📱 Use on mobile phones (responsive design)---



**Perfect for:** College projects, portfolio, learning PHP & MySQL## �️ How to Open the Website



---**Start Page (Splash Screen):**  

```

## ✨ Key Featureshttp://localhost/NETH%20Bookhive/splash.html

```

### Customer Features

- ✅ Beautiful book catalog with search**Main Website:**  

- ✅ Shopping cart with real-time updates```

- ✅ User registration & secure loginhttp://localhost/NETH%20Bookhive/frontend/index.html

- ✅ Order placement & tracking```

- ✅ User profile & wishlist

- ✅ Mobile-responsive design---



### Admin Features## � What Technologies Are Used?

- ✅ Admin dashboard

- ✅ Manage books (add/edit/delete)**Frontend (What You See):**

- ✅ View all orders- HTML - For page structure

- ✅ User management- CSS - For styling and design

- JavaScript - For interactive features

### Technical Features- Font Awesome - For icons

- ✅ PHP with PDO (secure database access)

- ✅ Password hashing (bcrypt)**Backend (Behind the Scenes):**

- ✅ Session management- PHP - Server-side programming

- ✅ AJAX for smooth experience- MySQL - Database to store data

- ✅ Beautiful gradient UI design- Apache - Web server (comes with XAMPP)

- ✅ Security features (CSRF protection, XSS prevention)

**No Frameworks Used!** - Pure vanilla code, easy to understand for beginners

---

---

## 🛠️ Technologies Used

## � Folder Structure (Simple Explanation)

| Technology | Purpose | Why It's Used |

|------------|---------|---------------|```

| **PHP 7.4+** | Backend logic | Server-side programming |NETH Bookhive/

| **MySQL 8.0** | Database | Store books, users, orders |│

| **HTML5** | Page structure | Create web pages |├── frontend/           ← All the pages users see (HTML, CSS, JS)

| **CSS3** | Styling | Make it look beautiful |│   ├── index.html      ← Homepage

| **JavaScript** | Interactivity | Dynamic features |│   ├── shop.html       ← Book listing page

| **Apache** | Web server | Run PHP (comes with XAMPP) |│   ├── cart.html       ← Shopping cart page

│   ├── login.html      ← Login page

**No frameworks!** Pure vanilla code - easy to learn and understand! 🎓│   ├── register.html   ← Sign up page

│   ├── css/            ← Styling files

---│   └── js/             ← JavaScript files

│

## 📋 Before You Start├── backend/            ← Server-side code (PHP)

│   ├── api/            ← API files (connect frontend to database)

Make sure you have:│   ├── config/         ← Database connection settings

- ✅ **XAMPP** (includes Apache, MySQL, PHP)│   └── models/         ← Code to work with database tables

- ✅ **Web Browser** (Chrome, Firefox, Edge)│

- ✅ **Text Editor** (VS Code recommended)├── database/           ← SQL file to create database

- ✅ **Basic knowledge** of HTML, CSS, PHP│

└── splash.html         ← Cool loading screen (opens first!)

---```



## 🚀 Installation Guide---



### Step 1: Install XAMPP## � How to Run This Project (Step by Step)



1. **Download XAMPP**### Step 1: Install XAMPP

   - Visit: [https://www.apachefriends.org](https://www.apachefriends.org)1. Download XAMPP from [apachefriends.org](https://www.apachefriends.org/)

   - Download the latest version for Windows2. Install it (just keep clicking Next)

3. Open XAMPP Control Panel

2. **Install XAMPP**

   - Run the installer### Step 2: Start Services

   - Use default settings (just click Next)1. In XAMPP, click **Start** next to "Apache"

   - Install location: `C:\xampp` (recommended)2. Click **Start** next to "MySQL"

3. Both should show green "Running" status

3. **Open XAMPP Control Panel**

   - Find XAMPP icon on desktop or Start menu### Step 3: Put Project in Right Folder

   - Run as Administrator1. Copy the `NETH Bookhive` folder

2. Paste it inside `C:\xampp\htdocs\`

### Step 2: Start Services3. Final path should be: `C:\xampp\htdocs\NETH Bookhive\`



1. In XAMPP Control Panel:### Step 4: Setup Database (ONE TIME ONLY)

   - Click **Start** button next to **Apache**1. Open your browser

   - Click **Start** button next to **MySQL**2. Type this URL: `http://localhost/NETH%20Bookhive/backend/setup.php`

   - Both should show **green** background when running3. Press Enter

4. Wait for green checkmarks ✅

### Step 3: Copy Project Files5. Database is ready!



1. **Download/Copy** this project### Step 5: Open the Website

2. **Paste** the entire `NETH Bookhive` folder into:**Option A - Cool Loading Screen:**

   ``````

   C:\xampp\htdocs\http://localhost/NETH%20Bookhive/splash.html

   ``````

3. **Final path should be:**

   ```**Option B - Direct Homepage:**

   C:\xampp\htdocs\NETH Bookhive\```

   ```http://localhost/NETH%20Bookhive/frontend/index.html

```

### Step 4: Setup Database

**That's it! Your website is running! 🎉**

**Method 1: Automatic Setup (Recommended)**

---

1. Open browser and go to:

   ```## 🗄️ Database Info (For Your Report)

   http://localhost/NETH%20Bookhive/setup_demo_users.php

   ```**Database Name:** bookstore  

2. This will automatically:**Tables Created:**

   - Create database- `users` - Stores user account info

   - Create tables- `books` - Stores all book details

   - Add sample books- `cart` - Stores items in shopping cart

   - Create demo users- `orders` - Stores customer orders

- `order_items` - Stores individual items in each order

**Method 2: Manual Setup**

**Sample Data:** 4 books are automatically added when you run setup.php

1. Open browser and go to: `http://localhost/phpmyadmin`

2. Click **New** (left sidebar)---

3. Database name: `bookstore`

4. Click **Create**## � How to Use the Website (For Testing)

5. Select `bookstore` database

6. Click **Import** tab### 1. Create an Account

7. Choose file: `database/bookstore.sql`- Click "Register" in the menu

8. Click **Go**- Fill in your name, email, and password

- Click "Create Account"

### Step 5: Configure Database (Optional)

### 2. Login

**Only if your MySQL uses a different port:**- Click "Login" in the menu

- Enter your email and password

1. Open file: `backend/config/database.php`- Click "Login"

2. Find this line:

   ```php### 3. Browse Books

   private $host = "localhost:3307";- Go to "Shop" page

   ```- See all available books

3. Change `3307` to your MySQL port (usually `3306`)- Use search bar to find specific books



### Step 6: Access the Website### 4. Add to Cart

- Click on any book

**🎬 Splash Screen (Recommended Start):**- Click "Add to Cart" button

```- Cart icon shows number of items

http://localhost/NETH%20Bookhive/splash.html

```### 5. Checkout

- Click cart icon

**🏠 Homepage:**- Review your items

```- Click "Proceed to Checkout"

http://localhost/NETH%20Bookhive/frontend/index.php- Fill in delivery details

```- Place order



**🔐 Admin Login:**---

```

http://localhost/NETH%20Bookhive/admin_login.php## ❗ Common Problems & Solutions

```

### Problem 1: "Page Not Found" Error

---**Solution:**

- Make sure XAMPP Apache is running (green in control panel)

## 🔑 Demo Login Credentials- Check folder is in `C:\xampp\htdocs\NETH Bookhive\`

- Type URL exactly as shown above

### 👤 Regular User

```### Problem 2: No Books Showing

Email:    user@nethbookhive.com**Solution:**

Password: user123- Run setup.php first: `http://localhost/NETH%20Bookhive/backend/setup.php`

```- Wait for green checkmarks

- Refresh the homepage

### 🛡️ Admin User

```### Problem 3: Can't Login/Register

Email:    admin@nethbookhive.com**Solution:**

Password: admin123- Make sure MySQL is running in XAMPP

```- Run setup.php to create database

- Check if database "bookstore" exists in phpMyAdmin

**Note:** Run `setup_demo_users.php` to create these accounts automatically!

### Problem 4: Images Not Showing

---**FIXED!** Books now use real images from Unsplash

- ✅ Beautiful, professional book images

## 📁 Project Structure Explained- ✅ Free high-quality photos from Unsplash.com

- ✅ No image files needed in project folder

```- ⚠️ Needs internet connection to load images

NETH Bookhive/- 💡 If offline: CSS gradient covers will show as fallback

│- To use your own images: See section below

├── 📂 frontend/                   # All user-facing pages

│   ├── index.php                  # Homepage (main page)---

│   ├── shop.php                   # Browse all books

│   ├── about.php                  # About us page## � Website Pages (All Working Links)

│   ├── cart.php                   # Shopping cart

│   ├── book-details.php           # Individual book page| Page | URL |

│   ├── checkout.php               # Complete purchase|------|-----|

│   ├── contact.php                # Contact form| **Loading Screen** | http://localhost/NETH%20Bookhive/splash.html |

│   ├── login.html                 # User login| **Homepage** | http://localhost/NETH%20Bookhive/frontend/index.html |

│   ├── register.html              # Create account| **Shop** | http://localhost/NETH%20Bookhive/frontend/shop.html |

│   ├── profile.html               # User profile| **Login** | http://localhost/NETH%20Bookhive/frontend/login.html |

│   ├── wishlist.html              # Saved books| **Register** | http://localhost/NETH%20Bookhive/frontend/register.html |

│   │| **Cart** | http://localhost/NETH%20Bookhive/frontend/cart.html |

│   ├── 📂 css/                    # Stylesheets| **About** | http://localhost/NETH%20Bookhive/frontend/about.html |

│   │   ├── style.css              # Main styles

│   │   ├── premium-design.css     # UI components---

│   │   ├── responsive.css         # Mobile styles

│   │   └── accessibility.css      # Accessibility features## 🎓 Perfect for College/University Projects!

│   │

│   └── 📂 js/                     # JavaScript files**What Makes This Good for Submission:**

│       ├── main.js                # Core functionality- ✅ Complete working website

│       ├── shop.js                # Shop page logic- ✅ Database integration

│       ├── cart.js                # Cart management- ✅ User authentication (login/register)

│       ├── checkout.js            # Checkout process- ✅ CRUD operations (Create, Read, Update, Delete)

│       ├── auth.js                # Login/Register- ✅ Responsive design (works on mobile)

│       └── book-details.js        # Book details page- ✅ Clean, commented code

│- ✅ Professional looking interface

├── 📂 backend/                    # Server-side code- ✅ Real-world functionality

│   │

│   ├── 📂 api/                    # API endpoints

│   │   ├── auth.php               # Login/Register API---

│   │   ├── books.php              # Books data API

│   │   ├── cart.php               # Cart operations## 🖼️ About Book Images

│   │   ├── orders.php             # Order processing

│   │   └── wishlist.php           # Wishlist operations**Current Setup:**

│   │- Images are loaded from **Unsplash.com** (free stock photos)

│   ├── 📂 config/                 # Configuration- Requires internet connection to display

│   │   └── database.php           # Database connection- Automatic fallback to CSS gradient covers if offline

│   │

│   ├── 📂 models/                 # Database models (OOP)**Want to Use Your Own Images?**

│   │   ├── User.php               # User operations

│   │   ├── Book.php               # Book operations**Option 1: Local Images (No Internet Needed)**

│   │   ├── Cart.php               # Cart operations1. Create folder: `frontend/images/`

│   │   └── Order.php              # Order operations2. Add your book images (book1.jpg, book2.jpg, etc.)

│   │3. Update database:

│   ├── 📂 views/                  # Reusable components   - Open: `http://localhost/phpmyadmin`

│   │   ├── header.php             # Site header/navbar   - Database: `bookstore` → Table: `books`

│   │   └── footer.php             # Site footer   - Edit `image_url` to: `images/book1.jpg`

│   │

│   ├── 📂 security/               # Security features**Option 2: Different Unsplash Images**

│   │   └── SecurityHelper.php     # Security functions1. Go to: `https://unsplash.com`

│   │2. Search for book images you like

│   └── 📂 monitoring/             # Performance tools3. Copy the image URL

│       └── PerformanceMonitor.php # Track performance4. Update database `image_url` with new Unsplash URL

│

├── 📂 database/                   # Database files**Image Tips:**

│   └── bookstore.sql              # Complete database- Recommended size: 200x280 pixels (book cover ratio)

│- Formats: JPG, PNG, WEBP

├── 📂 admin/                      # Admin panel (optional)- Keep file sizes under 200KB for faster loading

│   └── dashboard.php              # Admin dashboard

│---

├── admin_login.php                # Admin login page## Team Members

├── splash.html                    # Landing/loading page

├── health_check.php               # System status checker- M.Reezma Hanan

├── setup_demo_users.php           # Auto-create demo accounts- AJ.Raeef

├── .htaccess                      # Apache configuration- NM.Mahuroos

│- NM.Asrar

└── README.md                      # This file!---

```

## 🎉 Credits

---

**Project:** NETH Bookhive  

## 🌐 All Website Pages**Type:** Online Bookstore Website  

**Made For:** Fundamentals Of Software Engineering   

| Page Name | File | URL |**Date:**31 October 2025  

|-----------|------|-----|

| 🎬 Splash Screen | `splash.html` | `http://localhost/NETH%20Bookhive/splash.html` |---

| 🏠 Homepage | `frontend/index.php` | `http://localhost/NETH%20Bookhive/frontend/index.php` |

| 📚 Shop | `frontend/shop.php` | `http://localhost/NETH%20Bookhive/frontend/shop.php` |## � Quick Summary

| 📖 Book Details | `frontend/book-details.php` | Add `?id=1` to URL |

| 🛒 Cart | `frontend/cart.php` | `http://localhost/NETH%20Bookhive/frontend/cart.php` |- **Frontend:** HTML, CSS, JavaScript

| 💳 Checkout | `frontend/checkout.php` | `http://localhost/NETH%20Bookhive/frontend/checkout.php` |- **Backend:** PHP

| ℹ️ About | `frontend/about.php` | `http://localhost/NETH%20Bookhive/frontend/about.php` |- **Database:** MySQL

| 📧 Contact | `contact.php` | `http://localhost/NETH%20Bookhive/contact.php` |- **Server:** Apache (XAMPP)

| 🔐 Login | `frontend/login.html` | `http://localhost/NETH%20Bookhive/frontend/login.html` |- **Features:** Browse books, Shopping cart, User login, Order placement

| 📝 Register | `frontend/register.html` | `http://localhost/NETH%20Bookhive/frontend/register.html` |- **Total Pages:** 8 (including splash screen)

| 👤 Profile | `frontend/profile.html` | `http://localhost/NETH%20Bookhive/frontend/profile.html` |- **Database Tables:** 5

| ❤️ Wishlist | `frontend/wishlist.html` | `http://localhost/NETH%20Bookhive/frontend/wishlist.html` |



------



## 🗄️ Database Information**Start URL:** http://localhost/NETH%20Bookhive/splash.html


**Database Name:** `bookstore`

**Tables:**

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `users` | Store user accounts | id, name, email, password |
| `books` | Store book information | id, title, author, price, image_url |
| `cart` | Store cart items | id, user_id, book_id, quantity |
| `orders` | Store orders | id, user_id, total_amount, status |
| `order_items` | Store order details | id, order_id, book_id, quantity |

**Sample Data:** The setup script adds 6 sample books automatically!

---

## 📖 How to Use the Website (Testing Guide)

### For Students Testing Features:

**1. Create a User Account**
   - Click **Register** in menu
   - Fill in: Name, Email, Password
   - Click **Create Account**
   - You'll be logged in automatically

**2. Browse Books**
   - Click **Shop** in menu
   - See all books with images and prices
   - Use search bar to find specific books

**3. View Book Details**
   - Click on any book card
   - See full description
   - See author, price, and image
   - Click **Add to Cart**

**4. Shopping Cart**
   - Click cart icon (top right)
   - View items in cart
   - Change quantities with +/- buttons
   - Remove items if needed
   - See total price

**5. Checkout**
   - Click **Proceed to Checkout**
   - Fill in delivery information
   - Review order summary
   - Click **Place Order**
   - See order confirmation

**6. Profile & Orders**
   - Click profile icon
   - View your account details
   - See order history
   - Manage wishlist

---

## 🔧 Configuration

### Database Settings

Edit `backend/config/database.php`:

```php
private $host = "localhost:3307";      // MySQL host & port
private $db_name = "bookstore";        // Database name
private $username = "root";            // MySQL username (default: root)
private $password = "";                // MySQL password (usually empty)
```

**Common Ports:**
- Most XAMPP: `3306`
- Some XAMPP: `3307`
- Check XAMPP Control Panel for your port

---

## 🐛 Troubleshooting Common Issues

### ❌ Problem 1: "Page Not Found" or 404 Error

**Causes:**
- Apache not running
- Wrong folder location
- Wrong URL

**Solutions:**
1. Check XAMPP Control Panel - Apache should be **green**
2. Verify project is in: `C:\xampp\htdocs\NETH Bookhive\`
3. Use correct URL: `http://localhost/NETH%20Bookhive/frontend/index.php`

---

### ❌ Problem 2: "Database Connection Failed"

**Causes:**
- MySQL not running
- Wrong database name
- Wrong port number

**Solutions:**
1. Check XAMPP Control Panel - MySQL should be **green**
2. Verify database exists:
   - Go to: `http://localhost/phpmyadmin`
   - Check if `bookstore` database exists
3. Check port in `backend/config/database.php`:
   - Try: `localhost:3306`
   - Or try: `localhost:3307`

---

### ❌ Problem 3: No Books Showing on Shop Page

**Causes:**
- Database not imported
- Tables are empty

**Solutions:**
1. Run: `http://localhost/NETH%20Bookhive/setup_demo_users.php`
2. Or manually import: `database/bookstore.sql` in phpMyAdmin
3. Refresh shop page

---

### ❌ Problem 4: Can't Login/Register

**Causes:**
- Database connection issue
- Users table doesn't exist

**Solutions:**
1. Ensure MySQL is running
2. Run setup script: `setup_demo_users.php`
3. Check browser console (F12) for JavaScript errors

---

### ❌ Problem 5: Cart Not Working

**Causes:**
- Session not started
- JavaScript errors

**Solutions:**
1. Clear browser cache (Ctrl + Shift + Delete)
2. Check browser console (F12) for errors
3. Make sure you're logged in

---

### ❌ Problem 6: Images Not Loading

**Causes:**
- Incorrect image paths
- Images don't exist

**Solutions:**
- Images are loaded from Unsplash.com (requires internet)
- If offline, CSS gradient covers will show instead
- To use local images: Create `frontend/images/` folder and update database

---

## 🎨 Design Features

### Color Scheme
- **Primary:** Purple gradient (`#667eea` → `#764ba2`)
- **Accent:** Pink/Red gradients for special sections
- **Text:** Dark for readability, white for contrast

### UI Effects
- ✨ Glassmorphism (frosted glass effect)
- 🎨 Gradient backgrounds
- 🌊 Smooth animations on hover
- 💫 Box shadows for depth
- 📱 Fully responsive design

### Typography
- **Headings:** Bold, large, eye-catching
- **Body:** Clean, readable sans-serif
- **Icons:** Font Awesome for consistency

---

## 🔒 Security Features

This project includes professional security:

1. **Password Security**
   - Bcrypt hashing (industry standard)
   - Never stores plain text passwords

2. **SQL Injection Prevention**
   - PDO prepared statements
   - All queries are parameterized

3. **XSS Protection**
   - Input sanitization
   - Output escaping

4. **CSRF Protection**
   - Token-based form validation
   - Prevents cross-site attacks

5. **Session Security**
   - Secure session management
   - Session hijacking prevention

6. **Rate Limiting**
   - Prevents brute force attacks
   - Limits login attempts

---

## 📊 System Health Check

Monitor your system status:

```
http://localhost/NETH%20Bookhive/health_check.php
```

**This checks:**
- ✅ PHP version (requires 7.4+)
- ✅ Database connection
- ✅ Required PHP extensions
- ✅ File permissions
- ✅ Disk space
- ✅ Memory usage

---

## 🎓 Perfect for College/University Projects!

### Why This Project Stands Out:

✅ **Complete E-Commerce Features**
- Not just a simple website
- Real shopping cart, checkout, orders
- User authentication system

✅ **Database Integration**
- MySQL database with relationships
- CRUD operations (Create, Read, Update, Delete)
- Demonstrates SQL knowledge

✅ **Modern Technologies**
- PHP 7.4+ features
- Object-Oriented Programming (OOP)
- RESTful API structure

✅ **Professional Design**
- Beautiful, modern UI
- Mobile responsive
- Professional color scheme

✅ **Well Documented**
- Clean, commented code
- README with full instructions
- Easy to understand structure

✅ **Security Features**
- Password hashing
- SQL injection prevention
- Session management

### What You Can Learn:

1. **Frontend Development**
   - HTML5 semantic elements
   - CSS3 modern features (Grid, Flexbox, gradients)
   - JavaScript DOM manipulation
   - AJAX/Fetch API

2. **Backend Development**
   - PHP programming
   - Object-Oriented Programming
   - Session management
   - File handling

3. **Database**
   - MySQL database design
   - SQL queries (SELECT, INSERT, UPDATE, DELETE)
   - Database relationships
   - PDO usage

4. **Web Security**
   - Password hashing
   - SQL injection prevention
   - XSS protection
   - Session security

5. **Web Design**
   - Responsive design
   - User experience (UX)
   - Color theory
   - Typography

---

## 📝 For Your Project Report

### Project Details

**Project Name:** NETH BookHive  
**Project Type:** E-Commerce Website  
**Domain:** Online Bookstore  

**Technologies:**
- Frontend: HTML5, CSS3, JavaScript (ES6+)
- Backend: PHP 7.4+
- Database: MySQL 8.0
- Server: Apache (XAMPP)

**Features:**
- User registration and authentication
- Book catalog with search
- Shopping cart functionality
- Order management system
- Admin dashboard
- Responsive design

**Database Tables:** 5 (users, books, cart, orders, order_items)  
**Total Pages:** 12+  
**Lines of Code:** 5000+ (approximately)

---

## 👥 Team Information

**Project:** NETH BookHive  
**Team Members:**
- M. Reezma Hanan
- AJ. Raeef
- NM. Mahuroos
- NM. Asrar

**Course:** Fundamentals Of Software Engineering  
**Date:** 31 October 2025

---

## 🤝 Contributing

This is a student project, but contributions are welcome!

**How to contribute:**
1. Fork the repository
2. Create a new branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## 📞 Support

**Having issues?**

1. ✅ Check the **Troubleshooting** section above
2. ✅ Run `health_check.php` to diagnose issues
3. ✅ Check XAMPP error logs: `C:\xampp\apache\logs\error.log`
4. ✅ Review browser console (F12) for JavaScript errors

---

## 📚 Additional Resources

**Learn More:**
- [PHP Official Docs](https://www.php.net/docs.php)
- [MySQL Tutorial](https://www.mysqltutorial.org/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [W3Schools](https://www.w3schools.com/)

**Tools Used:**
- [XAMPP](https://www.apachefriends.org/)
- [VS Code](https://code.visualstudio.com/)
- [Font Awesome](https://fontawesome.com/)
- [Unsplash](https://unsplash.com/) (for book images)

---

## ✅ Quick Start Checklist

Before submitting your project, verify:

- [ ] XAMPP installed and both Apache & MySQL running
- [ ] Project copied to `C:\xampp\htdocs\NETH Bookhive\`
- [ ] Database `bookstore` created
- [ ] Database imported (run `setup_demo_users.php`)
- [ ] Homepage loads: `http://localhost/NETH%20Bookhive/frontend/index.php`
- [ ] Can register new user
- [ ] Can login with demo credentials
- [ ] Books showing on shop page
- [ ] Can add books to cart
- [ ] Can complete checkout
- [ ] All pages working without errors

---

## 📝 License

This project is open source and available for educational purposes.

**Usage Rights:**
- ✅ Use for college/university projects
- ✅ Modify for learning purposes
- ✅ Use as portfolio project
- ✅ Share with classmates
- ❌ Do not claim as entirely your own work
- ❌ Do not sell or commercialize

---

## 🎉 Final Notes

**Congratulations!** You now have a fully functional e-commerce website running!

**Next Steps:**
1. Explore all features
2. Customize colors/design
3. Add your own books
4. Take screenshots for your report
5. Test all functionality
6. Prepare project documentation

**Tips for Success:**
- Test every feature before submission
- Take screenshots of key features
- Document any changes you make
- Understand how the code works
- Be prepared to explain your project

---

## 🌟 Project Highlights

✨ **Professional Quality**  
✨ **Complete Functionality**  
✨ **Modern Design**  
✨ **Well Documented**  
✨ **Easy to Setup**  
✨ **Perfect for Students**

---

**🚀 Start Here:**  
```
http://localhost/NETH%20Bookhive/splash.html
```

**Happy Coding! 📚✨**

---

*Last Updated: November 2, 2025*
