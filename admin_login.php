<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - NETH BookHive</title>
    <link rel="stylesheet" href="frontend/css/style.css">
    <link rel="stylesheet" href="frontend/css/premium-design.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(231, 76, 60, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(230, 126, 34, 0.15) 0%, transparent 50%);
        }
        .admin-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .admin-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 50px 45px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
        }
        .admin-icon {
            text-align: center;
            margin-bottom: 25px;
        }
        .admin-icon i {
            font-size: 4rem;
            background: linear-gradient(135deg, #e74c3c, #e67e22);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .admin-card h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 0 10px 0;
            text-align: center;
            background: linear-gradient(135deg, #ffffff, #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .admin-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            font-size: 1.05rem;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 1rem;
        }
        .form-group input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: #e74c3c;
            box-shadow: 0 0 20px rgba(231, 76, 60, 0.3);
        }
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .btn-admin {
            width: 100%;
            padding: 16px 40px;
            font-size: 1.15rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.4s ease;
            background: linear-gradient(135deg, #e74c3c 0%, #e67e22 100%);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 15px 35px rgba(231, 76, 60, 0.4);
        }
        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 45px rgba(231, 76, 60, 0.5);
        }
        .back-home {
            text-align: center;
            margin-top: 25px;
        }
        .back-home a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }
        .back-home a:hover {
            color: white;
        }
        #loginMessage {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
        }
        .error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-card">
            <div class="admin-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Admin Access</h2>
            <p class="admin-subtitle">NETH BookHive Admin Panel</p>
            
            <!-- Demo Credentials Box -->
            <div style="background: rgba(46, 204, 113, 0.15); border: 1px solid rgba(46, 204, 113, 0.3); border-radius: 15px; padding: 20px; margin: 20px 0; text-align: left;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <i class="fas fa-info-circle" style="color: #2ecc71; font-size: 1.3rem;"></i>
                    <h3 style="color: #2ecc71; margin: 0; font-size: 1.1rem;">Demo Credentials</h3>
                </div>
                <div style="background: rgba(0,0,0,0.2); border-radius: 10px; padding: 15px; font-family: 'Courier New', monospace;">
                    <div style="color: rgba(255,255,255,0.9); margin-bottom: 8px;">
                        <strong style="color: #3498db;">Admin:</strong>
                    </div>
                    <div style="color: rgba(255,255,255,0.8); margin-bottom: 5px; padding-left: 10px;">
                        <i class="fas fa-envelope" style="width: 20px; color: #e74c3c;"></i> admin@nethbookhive.com
                    </div>
                    <div style="color: rgba(255,255,255,0.8); margin-bottom: 15px; padding-left: 10px;">
                        <i class="fas fa-key" style="width: 20px; color: #e74c3c;"></i> admin123
                    </div>
                    
                    <div style="color: rgba(255,255,255,0.9); margin-bottom: 8px;">
                        <strong style="color: #3498db;">User:</strong>
                    </div>
                    <div style="color: rgba(255,255,255,0.8); margin-bottom: 5px; padding-left: 10px;">
                        <i class="fas fa-user" style="width: 20px; color: #f39c12;"></i> NETH User
                    </div>
                    <div style="color: rgba(255,255,255,0.8); margin-bottom: 5px; padding-left: 10px;">
                        <i class="fas fa-envelope" style="width: 20px; color: #f39c12;"></i> user@nethbookhive.com
                    </div>
                    <div style="color: rgba(255,255,255,0.8); padding-left: 10px;">
                        <i class="fas fa-key" style="width: 20px; color: #f39c12;"></i> user123
                    </div>
                </div>
                <div style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-top: 10px; font-style: italic;">
                    <i class="fas fa-lightbulb"></i> Use these credentials for testing
                </div>
            </div>
            
            <form id="adminLoginForm" method="POST" action="admin_auth.php">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter admin username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter admin password" required>
                </div>
                
                <button type="submit" class="btn-admin">
                    <i class="fas fa-sign-in-alt"></i> Access Dashboard
                </button>
            </form>
            
            <div id="loginMessage"></div>
            
            <div class="back-home">
                <a href="frontend/index.html"><i class="fas fa-arrow-left"></i> Back to Homepage</a>
            </div>
        </div>
    </div>

    <script>
        // Check for error messages in URL
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const detail = urlParams.get('detail');
            const messageDiv = document.getElementById('loginMessage');
            
            if (error && messageDiv) {
                let errorMessage = '';
                
                switch(error) {
                    case 'invalid':
                        errorMessage = '<i class="fas fa-exclamation-triangle"></i> Invalid username or password. Please check your credentials.';
                        break;
                    case 'empty':
                        errorMessage = '<i class="fas fa-exclamation-circle"></i> Please enter both username and password.';
                        break;
                    case 'system':
                        if (detail === 'connection') {
                            errorMessage = '<i class="fas fa-database"></i> Database connection failed. Please ensure XAMPP MySQL is running on port 3307.';
                        } else if (detail === 'database') {
                            errorMessage = '<i class="fas fa-exclamation-triangle"></i> Database error. Please check if the "bookstore" database exists.';
                        } else {
                            errorMessage = '<i class="fas fa-exclamation-triangle"></i> System error occurred. Please contact administrator.';
                        }
                        break;
                    default:
                        errorMessage = '<i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.';
                }
                
                messageDiv.className = 'error';
                messageDiv.innerHTML = errorMessage;
                messageDiv.style.display = 'block';
                
                // Add fade-in animation
                messageDiv.style.animation = 'fadeIn 0.3s ease';
                
                // Auto-hide after 8 seconds for system errors, 5 seconds for others
                const hideDelay = error === 'system' ? 8000 : 5000;
                setTimeout(() => {
                    messageDiv.style.opacity = '0';
                    messageDiv.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                        messageDiv.style.opacity = '1';
                        // Clear URL parameter
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }, 500);
                }, hideDelay);
            }
        });

        // Add loading state on form submit
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-admin');
            const messageDiv = document.getElementById('loginMessage');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            
            // Clear any existing messages
            if (messageDiv) {
                messageDiv.style.display = 'none';
            }
        });

        // Add fade-in keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            #loginMessage {
                display: none;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
