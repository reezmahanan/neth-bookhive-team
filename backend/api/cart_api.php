<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Cart.php';

session_start();

$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);

// Get user ID or session ID
$cart->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$cart->session_id = session_id();

$method = $_SERVER['REQUEST_METHOD'];

// GET: Get cart items
if ($method === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'count') {
        // Get cart count
        $count = $cart->getCartCount();
        echo json_encode([
            'success' => true,
            'count' => $count
        ]);
    } else {
        // Get all cart items
        $stmt = $cart->getCartItems();
        $items = [];
        $subtotal = 0;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itemTotal = floatval($row['price']) * intval($row['quantity']);
            $subtotal += $itemTotal;
            
            $items[] = [
                'id' => $row['id'],
                'book_id' => $row['book_id'],
                'title' => $row['title'],
                'author' => $row['author'],
                'price' => floatval($row['price']),
                'image_url' => $row['image_url'],
                'quantity' => intval($row['quantity']),
                'stock_quantity' => intval($row['stock_quantity']),
                'category' => $row['category'],
                'item_total' => $itemTotal
            ];
        }
        
        echo json_encode([
            'success' => true,
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => count($items)
        ]);
    }
}

// POST: Add to cart
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->book_id)) {
        $cart->book_id = htmlspecialchars($data->book_id);
        $cart->quantity = isset($data->quantity) ? intval($data->quantity) : 1;
        
        if ($cart->addToCart()) {
            echo json_encode([
                'success' => true,
                'message' => 'Item added to cart',
                'count' => $cart->getCartCount()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing book_id'
        ]);
    }
}

// PUT: Update cart item quantity
elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->book_id) && isset($data->quantity)) {
        $cart->book_id = htmlspecialchars($data->book_id);
        $cart->quantity = intval($data->quantity);
        
        if ($cart->quantity <= 0) {
            // Remove item if quantity is 0 or less
            if ($cart->removeFromCart()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'count' => $cart->getCartCount()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to remove item'
                ]);
            }
        } else {
            // Update quantity
            if ($cart->updateQuantity()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Quantity updated',
                    'count' => $cart->getCartCount()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update quantity'
                ]);
            }
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required parameters'
        ]);
    }
}

// DELETE: Remove from cart
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (isset($_GET['action']) && $_GET['action'] === 'clear') {
        // Clear entire cart
        if ($cart->clearCart()) {
            echo json_encode([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to clear cart'
            ]);
        }
    } elseif (!empty($data->book_id)) {
        // Remove specific item
        $cart->book_id = htmlspecialchars($data->book_id);
        
        if ($cart->removeFromCart()) {
            echo json_encode([
                'success' => true,
                'message' => 'Item removed from cart',
                'count' => $cart->getCartCount()
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to remove item'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing book_id'
        ]);
    }
}

else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
