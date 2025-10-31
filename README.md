# 📚 NETH Bookhive - Online Bookstore

**A Complete Online Bookstore Website Project**



This is a fully functional bookstore website where users can browse books, add them to cart, and place orders. Perfect for college/university web development projects.

---

## 🎯 What Can This Website Do?

✅ Show books with pictures and prices  
✅ Let users search for books  
✅ Users can create accounts (Register/Login)  
✅ Add books to shopping cart  
✅ Place orders  
✅ Works on mobile phones too!

---

## �️ How to Open the Website

**Start Page (Splash Screen):**  
```
http://localhost/NETH%20Bookhive/splash.html
```

**Main Website:**  
```
http://localhost/NETH%20Bookhive/frontend/index.html
```

---

## � What Technologies Are Used?

**Frontend (What You See):**
- HTML - For page structure
- CSS - For styling and design
- JavaScript - For interactive features
- Font Awesome - For icons

**Backend (Behind the Scenes):**
- PHP - Server-side programming
- MySQL - Database to store data
- Apache - Web server (comes with XAMPP)

**No Frameworks Used!** - Pure vanilla code, easy to understand for beginners

---

## � Folder Structure (Simple Explanation)

```
NETH Bookhive/
│
├── frontend/           ← All the pages users see (HTML, CSS, JS)
│   ├── index.html      ← Homepage
│   ├── shop.html       ← Book listing page
│   ├── cart.html       ← Shopping cart page
│   ├── login.html      ← Login page
│   ├── register.html   ← Sign up page
│   ├── css/            ← Styling files
│   └── js/             ← JavaScript files
│
├── backend/            ← Server-side code (PHP)
│   ├── api/            ← API files (connect frontend to database)
│   ├── config/         ← Database connection settings
│   └── models/         ← Code to work with database tables
│
├── database/           ← SQL file to create database
│
└── splash.html         ← Cool loading screen (opens first!)
```

---

## � How to Run This Project (Step by Step)

### Step 1: Install XAMPP
1. Download XAMPP from [apachefriends.org](https://www.apachefriends.org/)
2. Install it (just keep clicking Next)
3. Open XAMPP Control Panel

### Step 2: Start Services
1. In XAMPP, click **Start** next to "Apache"
2. Click **Start** next to "MySQL"
3. Both should show green "Running" status

### Step 3: Put Project in Right Folder
1. Copy the `NETH Bookhive` folder
2. Paste it inside `C:\xampp\htdocs\`
3. Final path should be: `C:\xampp\htdocs\NETH Bookhive\`

### Step 4: Setup Database (ONE TIME ONLY)
1. Open your browser
2. Type this URL: `http://localhost/NETH%20Bookhive/backend/setup.php`
3. Press Enter
4. Wait for green checkmarks ✅
5. Database is ready!

### Step 5: Open the Website
**Option A - Cool Loading Screen:**
```
http://localhost/NETH%20Bookhive/splash.html
```

**Option B - Direct Homepage:**
```
http://localhost/NETH%20Bookhive/frontend/index.html
```

**That's it! Your website is running! 🎉**

---

## 🗄️ Database Info (For Your Report)

**Database Name:** bookstore  
**Tables Created:**
- `users` - Stores user account info
- `books` - Stores all book details
- `cart` - Stores items in shopping cart
- `orders` - Stores customer orders
- `order_items` - Stores individual items in each order

**Sample Data:** 4 books are automatically added when you run setup.php

---

## � How to Use the Website (For Testing)

### 1. Create an Account
- Click "Register" in the menu
- Fill in your name, email, and password
- Click "Create Account"

### 2. Login
- Click "Login" in the menu
- Enter your email and password
- Click "Login"

### 3. Browse Books
- Go to "Shop" page
- See all available books
- Use search bar to find specific books

### 4. Add to Cart
- Click on any book
- Click "Add to Cart" button
- Cart icon shows number of items

### 5. Checkout
- Click cart icon
- Review your items
- Click "Proceed to Checkout"
- Fill in delivery details
- Place order

---

## ❗ Common Problems & Solutions

### Problem 1: "Page Not Found" Error
**Solution:**
- Make sure XAMPP Apache is running (green in control panel)
- Check folder is in `C:\xampp\htdocs\NETH Bookhive\`
- Type URL exactly as shown above

### Problem 2: No Books Showing
**Solution:**
- Run setup.php first: `http://localhost/NETH%20Bookhive/backend/setup.php`
- Wait for green checkmarks
- Refresh the homepage

### Problem 3: Can't Login/Register
**Solution:**
- Make sure MySQL is running in XAMPP
- Run setup.php to create database
- Check if database "bookstore" exists in phpMyAdmin

### Problem 4: Images Not Showing
**FIXED!** Books now use real images from Unsplash
- ✅ Beautiful, professional book images
- ✅ Free high-quality photos from Unsplash.com
- ✅ No image files needed in project folder
- ⚠️ Needs internet connection to load images
- 💡 If offline: CSS gradient covers will show as fallback
- To use your own images: See section below

---

## � Website Pages (All Working Links)

| Page | URL |
|------|-----|
| **Loading Screen** | http://localhost/NETH%20Bookhive/splash.html |
| **Homepage** | http://localhost/NETH%20Bookhive/frontend/index.html |
| **Shop** | http://localhost/NETH%20Bookhive/frontend/shop.html |
| **Login** | http://localhost/NETH%20Bookhive/frontend/login.html |
| **Register** | http://localhost/NETH%20Bookhive/frontend/register.html |
| **Cart** | http://localhost/NETH%20Bookhive/frontend/cart.html |
| **About** | http://localhost/NETH%20Bookhive/frontend/about.html |

---

## 🎓 Perfect for College/University Projects!

**What Makes This Good for Submission:**
- ✅ Complete working website
- ✅ Database integration
- ✅ User authentication (login/register)
- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ Responsive design (works on mobile)
- ✅ Clean, commented code
- ✅ Professional looking interface
- ✅ Real-world functionality


---

## 🖼️ About Book Images

**Current Setup:**
- Images are loaded from **Unsplash.com** (free stock photos)
- Requires internet connection to display
- Automatic fallback to CSS gradient covers if offline

**Want to Use Your Own Images?**

**Option 1: Local Images (No Internet Needed)**
1. Create folder: `frontend/images/`
2. Add your book images (book1.jpg, book2.jpg, etc.)
3. Update database:
   - Open: `http://localhost/phpmyadmin`
   - Database: `bookstore` → Table: `books`
   - Edit `image_url` to: `images/book1.jpg`

**Option 2: Different Unsplash Images**
1. Go to: `https://unsplash.com`
2. Search for book images you like
3. Copy the image URL
4. Update database `image_url` with new Unsplash URL

**Image Tips:**
- Recommended size: 200x280 pixels (book cover ratio)
- Formats: JPG, PNG, WEBP
- Keep file sizes under 200KB for faster loading

---
## Team Members

- M.Reezma Hanan
- AJ.Raeef
- NM.Mahuroos
- NM.Asrar
---

## 🎉 Credits

**Project:** NETH Bookhive  
**Type:** Online Bookstore Website  
**Made For:** Fundamentals Of Software Engineering
**Date:**31 October 2025  

---

## � Quick Summary

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP)
- **Features:** Browse books, Shopping cart, User login, Order placement
- **Total Pages:** 8 (including splash screen)
- **Database Tables:** 5


---

**Start URL:** http://localhost/NETH%20Bookhive/splash.html
