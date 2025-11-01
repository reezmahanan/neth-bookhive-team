<?php
/**
 * Email Helper - Handles email notifications for orders
 * Uses PHP's mail() function or can be configured for SMTP
 */

class EmailHelper {
    private static $from_email = 'noreply@nethbookhive.com';
    private static $from_name = 'NETH Bookhive';
    private static $admin_email = 'admin@nethbookhive.com';

    /**
     * Send order confirmation email
     * @param array $order_data Order details
     * @param array $items Order items
     * @return bool Success status
     */
    public static function sendOrderConfirmation($order_data, $items) {
        $to = $order_data['email'];
        $subject = 'Order Confirmation - ' . $order_data['order_number'];
        
        $message = self::buildOrderConfirmationHTML($order_data, $items);
        
        $headers = self::getEmailHeaders();
        
        // Send to customer
        $customer_sent = mail($to, $subject, $message, $headers);
        
        // Send to admin
        $admin_subject = 'New Order Received - ' . $order_data['order_number'];
        $admin_sent = mail(self::$admin_email, $admin_subject, $message, $headers);
        
        // Log email
        self::logEmail($to, $subject, $customer_sent);
        
        return $customer_sent;
    }

    /**
     * Send order status update email
     * @param array $order_data Order details
     * @param string $status New status
     * @return bool Success status
     */
    public static function sendStatusUpdate($order_data, $status) {
        $to = $order_data['email'];
        $subject = 'Order Status Update - ' . $order_data['order_number'];
        
        $message = self::buildStatusUpdateHTML($order_data, $status);
        
        $headers = self::getEmailHeaders();
        
        $sent = mail($to, $subject, $message, $headers);
        
        self::logEmail($to, $subject, $sent);
        
        return $sent;
    }

    /**
     * Send shipping confirmation email
     * @param array $order_data Order details
     * @param string $tracking_number Tracking number
     * @return bool Success status
     */
    public static function sendShippingConfirmation($order_data, $tracking_number = '') {
        $to = $order_data['email'];
        $subject = 'Your Order Has Been Shipped - ' . $order_data['order_number'];
        
        $message = self::buildShippingConfirmationHTML($order_data, $tracking_number);
        
        $headers = self::getEmailHeaders();
        
        $sent = mail($to, $subject, $message, $headers);
        
        self::logEmail($to, $subject, $sent);
        
        return $sent;
    }

    /**
     * Build HTML email for order confirmation
     */
    private static function buildOrderConfirmationHTML($order_data, $items) {
        $items_html = '';
        foreach ($items as $item) {
            $item_subtotal = $item['quantity'] * $item['price'];
            $items_html .= '<tr>
                <td style="padding: 10px; border-bottom: 1px solid #ecf0f1;">
                    ' . htmlspecialchars($item['title']) . '<br>
                    <small style="color: #7f8c8d;">by ' . htmlspecialchars($item['author']) . '</small>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ecf0f1; text-align: center;">' . $item['quantity'] . '</td>
                <td style="padding: 10px; border-bottom: 1px solid #ecf0f1; text-align: right;">Rs ' . number_format($item['price'], 2) . '</td>
                <td style="padding: 10px; border-bottom: 1px solid #ecf0f1; text-align: right;">Rs ' . number_format($item_subtotal, 2) . '</td>
            </tr>';
        }

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0;">NETH Bookhive</h1>
        <p style="margin: 10px 0 0 0; font-size: 1.2em;">Order Confirmation</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #ecf0f1;">
        <h2 style="color: #667eea; margin-top: 0;">Thank you for your order!</h2>
        <p>Hi ' . htmlspecialchars($order_data['full_name']) . ',</p>
        <p>Your order has been received and is being processed. Here are your order details:</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Order Number:</strong> ' . htmlspecialchars($order_data['order_number']) . '</p>
            <p style="margin: 5px 0;"><strong>Order Date:</strong> ' . date('F j, Y', strtotime($order_data['created_at'])) . '</p>
            <p style="margin: 5px 0;"><strong>Order Status:</strong> <span style="color: #f39c12;">' . ucfirst($order_data['status']) . '</span></p>
            <p style="margin: 5px 0;"><strong>Payment Status:</strong> <span style="color: #27ae60;">' . ucfirst($order_data['payment_status']) . '</span></p>
        </div>
        
        <h3 style="color: #667eea;">Order Items</h3>
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; text-align: left; border-bottom: 2px solid #667eea;">Item</th>
                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #667eea;">Qty</th>
                    <th style="padding: 10px; text-align: right; border-bottom: 2px solid #667eea;">Price</th>
                    <th style="padding: 10px; text-align: right; border-bottom: 2px solid #667eea;">Total</th>
                </tr>
            </thead>
            <tbody>
                ' . $items_html . '
            </tbody>
        </table>
        
        <div style="text-align: right; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Subtotal:</strong> Rs ' . number_format($order_data['subtotal'], 2) . '</p>
            <p style="margin: 5px 0;"><strong>Shipping:</strong> Rs ' . number_format($order_data['shipping_cost'], 2) . '</p>
            <p style="margin: 5px 0;"><strong>Tax:</strong> Rs ' . number_format($order_data['tax'], 2) . '</p>
            <p style="margin: 10px 0 0 0; font-size: 1.2em; color: #667eea;"><strong>Total:</strong> Rs ' . number_format($order_data['total_amount'], 2) . '</p>
        </div>
        
        <h3 style="color: #667eea;">Shipping Address</h3>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['full_name']) . '</p>
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['address_line1']) . '</p>
            ' . ($order_data['address_line2'] ? '<p style="margin: 5px 0;">' . htmlspecialchars($order_data['address_line2']) . '</p>' : '') . '
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['city']) . ', ' . htmlspecialchars($order_data['state']) . ' ' . htmlspecialchars($order_data['postal_code']) . '</p>
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['country']) . '</p>
            <p style="margin: 5px 0;">Phone: ' . htmlspecialchars($order_data['phone']) . '</p>
        </div>
        
        <p style="margin-top: 30px;">We will send you a shipping confirmation email with tracking information once your order ships.</p>
        <p>If you have any questions, please contact us at <a href="mailto:' . self::$admin_email . '" style="color: #667eea;">' . self::$admin_email . '</a></p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
        <p style="margin: 0; color: #7f8c8d; font-size: 0.9em;">© ' . date('Y') . ' NETH Bookhive. All rights reserved.</p>
        <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.9em;">Thank you for shopping with us!</p>
    </div>
</body>
</html>';
    }

    /**
     * Build HTML email for status update
     */
    private static function buildStatusUpdateHTML($order_data, $status) {
        $status_messages = [
            'processing' => 'Your order is being processed and will be shipped soon.',
            'shipped' => 'Great news! Your order has been shipped and is on its way.',
            'delivered' => 'Your order has been delivered. We hope you enjoy your books!',
            'cancelled' => 'Your order has been cancelled. If you did not request this, please contact us.'
        ];

        $message = isset($status_messages[$status]) ? $status_messages[$status] : 'Your order status has been updated.';

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0;">NETH Bookhive</h1>
        <p style="margin: 10px 0 0 0; font-size: 1.2em;">Order Status Update</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #ecf0f1;">
        <h2 style="color: #667eea; margin-top: 0;">Order Update</h2>
        <p>Hi ' . htmlspecialchars($order_data['full_name']) . ',</p>
        <p>' . $message . '</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Order Number:</strong> ' . htmlspecialchars($order_data['order_number']) . '</p>
            <p style="margin: 5px 0;"><strong>New Status:</strong> <span style="color: #667eea; font-size: 1.1em;">' . ucfirst($status) . '</span></p>
        </div>
        
        <p>You can track your order status anytime by visiting your account.</p>
        <p>If you have any questions, please contact us at <a href="mailto:' . self::$admin_email . '" style="color: #667eea;">' . self::$admin_email . '</a></p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
        <p style="margin: 0; color: #7f8c8d; font-size: 0.9em;">© ' . date('Y') . ' NETH Bookhive. All rights reserved.</p>
    </div>
</body>
</html>';
    }

    /**
     * Build HTML email for shipping confirmation
     */
    private static function buildShippingConfirmationHTML($order_data, $tracking_number) {
        $tracking_html = $tracking_number ? 
            '<p style="margin: 5px 0;"><strong>Tracking Number:</strong> <span style="color: #667eea; font-family: monospace;">' . htmlspecialchars($tracking_number) . '</span></p>' : 
            '';

        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0;">NETH Bookhive</h1>
        <p style="margin: 10px 0 0 0; font-size: 1.2em;">🚚 Your Order Has Shipped!</p>
    </div>
    
    <div style="background: white; padding: 30px; border: 1px solid #ecf0f1;">
        <h2 style="color: #667eea; margin-top: 0;">Good news!</h2>
        <p>Hi ' . htmlspecialchars($order_data['full_name']) . ',</p>
        <p>Your order is on its way to you!</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Order Number:</strong> ' . htmlspecialchars($order_data['order_number']) . '</p>
            ' . $tracking_html . '
            <p style="margin: 5px 0;"><strong>Estimated Delivery:</strong> 3-5 business days</p>
        </div>
        
        <h3 style="color: #667eea;">Shipping To:</h3>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['full_name']) . '</p>
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['address_line1']) . '</p>
            <p style="margin: 5px 0;">' . htmlspecialchars($order_data['city']) . ', ' . htmlspecialchars($order_data['state']) . '</p>
        </div>
        
        <p style="margin-top: 30px;">Thank you for choosing NETH Bookhive!</p>
        <p>If you have any questions, please contact us at <a href="mailto:' . self::$admin_email . '" style="color: #667eea;">' . self::$admin_email . '</a></p>
    </div>
    
    <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
        <p style="margin: 0; color: #7f8c8d; font-size: 0.9em;">© ' . date('Y') . ' NETH Bookhive. All rights reserved.</p>
    </div>
</body>
</html>';
    }

    /**
     * Get email headers
     */
    private static function getEmailHeaders() {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::$from_name . " <" . self::$from_email . ">\r\n";
        $headers .= "Reply-To: " . self::$admin_email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        return $headers;
    }

    /**
     * Log email attempts
     */
    private static function logEmail($to, $subject, $success) {
        $log_file = __DIR__ . '/../../logs/email_log.txt';
        $log_dir = dirname($log_file);
        
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        $status = $success ? 'SUCCESS' : 'FAILED';
        $log_entry = date('Y-m-d H:i:s') . " | $status | To: $to | Subject: $subject\n";
        
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }
}
?>