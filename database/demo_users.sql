-- =====================================================
-- NETH BookHive Demo Users Setup
-- =====================================================
-- This script creates demo admin and user accounts
-- Run this after setting up the main database
-- =====================================================

USE bookstore;

-- Add is_admin column if it doesn't exist
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0 AFTER password;

-- =====================================================
-- DEMO ADMIN ACCOUNT
-- =====================================================
-- Email: admin@nethbookhive.com
-- Password: admin123
-- (Password hash generated with: password_hash('admin123', PASSWORD_DEFAULT))
-- =====================================================

INSERT INTO users (name, email, password, is_admin, created_at) 
VALUES (
    'Admin User',
    'admin@nethbookhive.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    1,
    NOW()
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    is_admin = 1;

-- =====================================================
-- DEMO USER ACCOUNT
-- =====================================================
-- Name: NETH User
-- Email: user@nethbookhive.com
-- Password: user123
-- (Password hash generated with: password_hash('user123', PASSWORD_DEFAULT))
-- =====================================================

INSERT INTO users (name, email, password, is_admin, created_at) 
VALUES (
    'NETH User',
    'user@nethbookhive.com',
    '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
    0,
    NOW()
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
    name = 'NETH User';

-- =====================================================
-- VERIFY ACCOUNTS
-- =====================================================

SELECT 
    id,
    name,
    email,
    CASE 
        WHEN is_admin = 1 THEN 'Admin'
        ELSE 'User'
    END AS role,
    created_at
FROM users 
WHERE email IN ('admin@nethbookhive.com', 'user@nethbookhive.com')
ORDER BY is_admin DESC;

-- =====================================================
-- CREDENTIALS SUMMARY
-- =====================================================
-- 
-- ADMIN LOGIN:
-- Username: admin@nethbookhive.com
-- Password: admin123
-- URL: http://localhost/NETH%20Bookhive/admin_login.php
--
-- USER LOGIN:
-- Name: NETH User
-- Email: user@nethbookhive.com
-- Password: user123
-- URL: http://localhost/NETH%20Bookhive/frontend/login.html
--
-- =====================================================
