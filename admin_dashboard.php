<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

include_once 'backend/config/database.php';
include_once 'backend/models/Book.php';

$database = new Database();
$db = $database->getConnection();
$book = new Book($db);
$stmt = $book->read();
$books = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $books[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NETH BookHive</title>
    <link rel="stylesheet" href="frontend/css/style.css">
    <link rel="stylesheet" href="frontend/css/premium-design.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .admin-container { 
            max-width: 1400px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        .admin-topbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .admin-topbar h1 {
            color: white;
            font-size: 1.8rem;
            margin: 0;
            background: linear-gradient(135deg, #ffffff, #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .admin-topbar .admin-user {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        .admin-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .admin-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px;
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .admin-table { 
            width: 100%; 
            background: #fff; 
            border-radius: 15px; 
            overflow: hidden; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
        }
        .admin-table th { 
            background: linear-gradient(135deg, #e74c3c, #e67e22); 
            color: #fff; 
            padding: 18px; 
            text-align: left;
            font-weight: 600;
        }
        .admin-table td { 
            padding: 16px 18px; 
            border-bottom: 1px solid #f0f0f0; 
        }
        .admin-table tr:hover { 
            background: #f8f9fa; 
        }
        .low-stock { 
            background: #fff3cd !important; 
        }
        .action-btn { 
            padding: 8px 16px; 
            margin: 0 4px; 
            border-radius: 8px; 
            border: none; 
            cursor: pointer; 
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-edit { 
            background: #3498db; 
            color: #fff; 
        }
        .btn-edit:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .btn-delete { 
            background: #e74c3c; 
            color: #fff; 
        }
        .btn-delete:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .btn {
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .modal { 
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
            z-index: 9999; 
        }
        .modal-content { 
            background: #fff; 
            margin: 50px auto; 
            padding: 40px; 
            border-radius: 20px; 
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="admin-topbar">
        <div>
            <h1><i class="fas fa-shield-alt"></i> NETH BookHive Admin</h1>
            <p class="admin-user">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
        </div>
        <div class="admin-actions">
            <a href="frontend/index.html" class="btn" style="background: #27ae60; color: #fff;">
                <i class="fas fa-home"></i> Homepage
            </a>
            <a href="admin_logout.php" class="btn" style="background: #e74c3c; color: #fff;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h2 style="margin: 0; color: #2c3e50;"><i class="fas fa-boxes"></i> Inventory Management</h2>
                <p style="margin: 5px 0 0 0; color: #7f8c8d;">Manage your book collection</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="admin_stock_monitor.php" class="btn" style="background: #f39c12; color: #fff;">
                    <i class="fas fa-chart-line"></i> Stock Monitor
                </a>
                <button class="btn" style="background: #27ae60; color: #fff;" onclick="openAddBookModal()">
                    <i class="fas fa-plus"></i> Add New Book
                </button>
            </div>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                <tr class="<?php echo ($b['stock_quantity'] < 5) ? 'low-stock' : ''; ?>">
                    <td><?php echo $b['id']; ?></td>
                    <td><?php echo htmlspecialchars($b['title']); ?></td>
                    <td><?php echo htmlspecialchars($b['author']); ?></td>
                    <td>Rs <?php echo htmlspecialchars($b['price']); ?></td>
                    <td>
                        <?php echo $b['stock_quantity']; ?>
                        <?php if ($b['stock_quantity'] < 5): ?>
                            <span style="color:#e74c3c;font-weight:bold;"> ⚠️ Low Stock</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($b['category']); ?></td>
                    <td>
                        <button class="action-btn btn-edit" onclick="editBook(<?php echo $b['id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                        <button class="action-btn btn-delete" onclick="deleteBook(<?php echo $b['id']; ?>)"><i class="fas fa-trash"></i> Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Book Modal -->
    <div id="addBookModal" class="modal">
        <div class="modal-content">
            <h2>Add New Book</h2>
            <form id="addBookForm">
                <input type="text" name="title" placeholder="Title" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <input type="text" name="author" placeholder="Author" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <textarea name="description" placeholder="Description" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;"></textarea>
                <input type="number" name="price" placeholder="Price" required step="0.01" style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <input type="text" name="image_url" placeholder="Image URL" style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <input type="text" name="category" placeholder="Category" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <input type="number" name="stock_quantity" placeholder="Stock Quantity" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:6px;">
                <button type="submit" class="btn btn-primary">Add Book</button>
                <button type="button" class="btn btn-secondary" onclick="closeAddBookModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openAddBookModal() {
            document.getElementById('addBookModal').style.display = 'block';
        }
        function closeAddBookModal() {
            document.getElementById('addBookModal').style.display = 'none';
            document.getElementById('addBookForm').reset();
        }
        
        // Handle add book form submission
        document.getElementById('addBookForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = {
                title: formData.get('title'),
                author: formData.get('author'),
                description: formData.get('description'),
                price: formData.get('price'),
                image_url: formData.get('image_url'),
                category: formData.get('category'),
                stock_quantity: formData.get('stock_quantity')
            };
            
            try {
                const response = await fetch('admin_book_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    alert('Book added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Error adding book: ' + error.message);
            }
        });
        
        async function editBook(id) {
            // Fetch book details
            try {
                const response = await fetch('admin_book_api.php?id=' + id);
                const book = await response.json();
                
                const newTitle = prompt('Title:', book.title);
                const newAuthor = prompt('Author:', book.author);
                const newPrice = prompt('Price:', book.price);
                const newStock = prompt('Stock:', book.stock_quantity);
                
                if (newTitle && newAuthor && newPrice && newStock) {
                    const updateResponse = await fetch('admin_book_api.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id: id,
                            title: newTitle,
                            author: newAuthor,
                            description: book.description,
                            price: newPrice,
                            image_url: book.image_url,
                            category: book.category,
                            stock_quantity: newStock
                        })
                    });
                    const result = await updateResponse.json();
                    if (result.success) {
                        alert('Book updated successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                }
            } catch (error) {
                alert('Error editing book: ' + error.message);
            }
        }
        
        async function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                try {
                    const response = await fetch('admin_book_api.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Book deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (error) {
                    alert('Error deleting book: ' + error.message);
                }
            }
        }
    </script>
</body>
</html>
