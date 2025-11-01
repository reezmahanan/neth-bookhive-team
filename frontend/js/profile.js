// Profile Page JavaScript
const API_BASE = 'http://localhost/NETH%20Bookhive/backend/api';

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!user) {
        // Redirect to login if not authenticated
        window.location.href = 'login.html';
        return;
    }

    // Initialize profile page
    initializeProfile(user);
    loadUserOrders(user.id);
    setupTabSwitching();
});

// Initialize Profile
function initializeProfile(user) {
    // Set user avatar (first letter of name)
    const userAvatar = document.getElementById('userAvatar');
    if (userAvatar && user.name) {
        userAvatar.textContent = user.name.charAt(0).toUpperCase();
    }

    // Set profile header info
    document.getElementById('profileName').textContent = user.name;
    document.getElementById('profileEmail').textContent = user.email;
    
    // Set member since date (use current date if not available)
    const memberDate = user.created_at ? new Date(user.created_at) : new Date();
    document.getElementById('memberSince').textContent = formatDate(memberDate);

    // Set account details
    document.getElementById('accountName').textContent = user.name;
    document.getElementById('accountEmail').textContent = user.email;
    document.getElementById('accountId').textContent = user.id;
    document.getElementById('accountMemberSince').textContent = formatDate(memberDate);

    // Setup logout button
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            localStorage.removeItem('user');
            window.location.href = 'login.html';
        }
    });
}

// Load User Orders
async function loadUserOrders(userId) {
    const ordersContainer = document.getElementById('ordersContainer');
    
    console.log('Loading orders for user ID:', userId); // Debug log
    
    try {
        const url = `${API_BASE}/orders.php?user_id=${userId}`;
        console.log('Fetching from:', url); // Debug log
        
        const response = await fetch(url);
        
        console.log('Response status:', response.status); // Debug log
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error response:', errorText); // Debug log
            throw new Error('Failed to fetch orders');
        }

        const orders = await response.json();
        console.log('Orders received:', orders); // Debug log
        
        if (!orders || orders.length === 0) {
            ordersContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Orders Yet</h3>
                    <p>You haven't placed any orders yet. Start exploring our collection!</p>
                    <br>
                    <a href="shop.html" class="btn-view" style="text-decoration: none;">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            `;
            // Update stats to zero
            document.getElementById('totalOrders').textContent = '0';
            document.getElementById('totalSpent').textContent = 'Rs. 0';
            document.getElementById('activeOrders').textContent = '0';
            return;
        }

        // Display orders
        displayOrders(orders, ordersContainer);
        
    } catch (error) {
        console.error('Error loading orders:', error);
        ordersContainer.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error Loading Orders</h3>
                <p>Unable to load your orders. Please try again later.</p>
                <p style="color: #e74c3c; font-size: 0.9rem; margin-top: 1rem;">Error: ${error.message}</p>
                <br>
                <button class="btn btn-primary" onclick="location.reload()">Retry</button>
            </div>
        `;
    }
}

// Display Orders
function displayOrders(orders, container) {
    container.innerHTML = '';
    
    // Sort orders by date (newest first)
    orders.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    
    // Calculate stats
    updateProfileStats(orders);
    
    orders.forEach(order => {
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';
        
        // Get order status class
        const statusClass = getOrderStatusClass(order.status);
        const statusIcon = getOrderStatusIcon(order.status);
        
        orderCard.innerHTML = `
            <div class="order-header">
                <div class="order-info">
                    <div class="order-number">
                        <i class="fas fa-receipt"></i>
                        Order #${order.order_number || order.id}
                    </div>
                    <div class="order-date">
                        <i class="fas fa-clock"></i>
                        ${formatDate(new Date(order.created_at))}
                    </div>
                </div>
                <span class="order-status ${statusClass}">
                    <i class="${statusIcon}"></i>
                    ${capitalizeFirst(order.status)}
                </span>
            </div>
            <div class="order-items">
                <div class="order-item">
                    <span class="item-name"><i class="fas fa-boxes"></i> ${order.item_count || 0} item(s)</span>
                    <span class="item-price"><i class="fas fa-credit-card"></i> ${capitalizeFirst(order.payment_method || 'N/A')}</span>
                </div>
                <div class="order-item">
                    <span class="item-name"><i class="fas fa-info-circle"></i> Payment Status</span>
                    <span class="item-price">${capitalizeFirst(order.payment_status || 'pending')}</span>
                </div>
            </div>
            <div class="order-total">
                <span><i class="fas fa-calculator"></i> Total Amount:</span>
                <span>Rs ${parseFloat(order.total_amount || 0).toFixed(2)}</span>
            </div>
            <div class="order-actions">
                <button class="btn-view" onclick="viewOrderDetails('${order.order_number || order.id}')">
                    <i class="fas fa-eye"></i> View Details
                </button>
                ${order.status === 'pending' || order.status === 'processing' ? `
                    <button class="btn-cancel" onclick="cancelOrder('${order.order_number || order.id}')">
                        <i class="fas fa-times"></i> Cancel Order
                    </button>
                ` : ''}
            </div>
        `;
        
        container.appendChild(orderCard);
    });
}

// Update Profile Stats
function updateProfileStats(orders) {
    const totalOrders = orders.length;
    const totalSpent = orders.reduce((sum, order) => sum + parseFloat(order.total_amount || 0), 0);
    const activeOrders = orders.filter(order => 
        order.status === 'pending' || order.status === 'processing' || order.status === 'shipped'
    ).length;
    
    document.getElementById('totalOrders').textContent = totalOrders;
    document.getElementById('totalSpent').textContent = `Rs. ${totalSpent.toFixed(2)}`;
    document.getElementById('activeOrders').textContent = activeOrders;
}

// Get Order Status Icon
function getOrderStatusIcon(status) {
    const icons = {
        'pending': 'fas fa-clock',
        'processing': 'fas fa-cog',
        'shipped': 'fas fa-shipping-fast',
        'delivered': 'fas fa-check-circle',
        'cancelled': 'fas fa-times-circle'
    };
    return icons[status] || 'fas fa-info-circle';
}

// View Order Details
function viewOrderDetails(orderNumber) {
    window.location.href = `order-confirmation.html?order_number=${orderNumber}`;
}

// Get Order Status Class
function getOrderStatusClass(status) {
    const statusMap = {
        'pending': 'pending',
        'processing': 'processing',
        'shipped': 'shipped',
        'delivered': 'delivered',
        'cancelled': 'cancelled'
    };
    return statusMap[status.toLowerCase()] || 'pending';
}

// Setup Tab Switching
function setupTabSwitching() {
    const menuTabs = document.querySelectorAll('.menu-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    menuTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all tabs
            menuTabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.style.display = 'none');
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(`${tabName}-tab`).style.display = 'block';
        });
    });
}

// Upload Profile Picture
function uploadProfilePicture() {
    const fileInput = document.getElementById('profilePicture');
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (!fileInput.files.length) {
        alert('Please select a file to upload.');
        return;
    }
    
    const formData = new FormData();
    formData.append('profile_picture', fileInput.files[0]);
    formData.append('user_id', user.id);

    fetch('backend/api/upload_profile_picture.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile picture updated successfully!');
            // Optionally, update the profile picture on the page
            const userAvatar = document.getElementById('userAvatar');
            userAvatar.src = URL.createObjectURL(fileInput.files[0]);
        } else {
            throw new Error(data.message || 'Failed to upload profile picture');
        }
    })
    .catch(error => {
        console.error('Error uploading profile picture:', error);
        alert('Error uploading profile picture: ' + error.message);
    });
}

// Cancel order from profile page:
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        fetch(`backend/api/orders.php?action=cancel&order_id=${orderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                reason: 'Customer changed mind'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order cancelled successfully!');
                location.reload(); // Reload the page to update orders list
            } else {
                throw new Error(data.message || 'Failed to cancel order');
            }
        })
        .catch(error => {
            console.error('Error cancelling order:', error);
            alert('Error cancelling order: ' + error.message);
        });
    }
}

// Utility Functions
function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}
