<?php
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");

echo json_encode([
    'status' => 'success',
    'message' => 'NETH Bookhive API is running',
    'version' => '1.0',
    'endpoints' => [
        'auth' => '/api/auth.php',
        'books' => '/api/books.php',
        'cart' => '/api/cart.php',
        'orders' => '/api/orders.php'
    ]
]);
?>
