<?php
/**
 * Payment Helper - Handles payment processing with Stripe
 * 
 * SETUP INSTRUCTIONS:
 * 1. Install Stripe PHP library: composer require stripe/stripe-php
 * 2. Get your API keys from: https://dashboard.stripe.com/test/apikeys
 * 3. Update STRIPE_SECRET_KEY and STRIPE_PUBLISHABLE_KEY below
 */

class PaymentHelper {
    private static $stripe_secret_key = 'sk_test_YOUR_SECRET_KEY_HERE'; // Replace with your Stripe secret key
    private static $stripe_publishable_key = 'pk_test_YOUR_PUBLISHABLE_KEY_HERE'; // Replace with your Stripe publishable key
    private static $currency = 'lkr'; // Sri Lankan Rupee

    /**
     * Initialize Stripe
     */
    private static function initStripe() {
        if (class_exists('\Stripe\Stripe')) {
            \Stripe\Stripe::setApiKey(self::$stripe_secret_key);
            return true;
        }
        return false;
    }

    /**
     * Get publishable key for frontend
     */
    public static function getPublishableKey() {
        return self::$stripe_publishable_key;
    }

    /**
     * Create a payment intent
     * @param float $amount Amount in rupees
     * @param array $metadata Additional data to attach
     * @return array Payment intent data or error
     */
    public static function createPaymentIntent($amount, $metadata = []) {
        try {
            if (!self::initStripe()) {
                return [
                    'success' => false,
                    'error' => 'Stripe library not installed. Run: composer require stripe/stripe-php'
                ];
            }

            // Convert amount to cents (Stripe uses smallest currency unit)
            $amount_cents = (int)($amount * 100);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount_cents,
                'currency' => self::$currency,
                'payment_method_types' => ['card'],
                'metadata' => $metadata,
                'description' => 'NETH Bookhive Order'
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];
        } catch (\Stripe\Exception\CardException $e) {
            return [
                'success' => false,
                'error' => $e->getError()->message
            ];
        } catch (\Stripe\Exception\RateLimitException $e) {
            return [
                'success' => false,
                'error' => 'Too many requests. Please try again later.'
            ];
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [
                'success' => false,
                'error' => 'Invalid request parameters.'
            ];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return [
                'success' => false,
                'error' => 'Authentication with Stripe failed.'
            ];
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return [
                'success' => false,
                'error' => 'Network error. Please check your connection.'
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'success' => false,
                'error' => 'Payment processing error. Please try again.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'An unexpected error occurred.'
            ];
        }
    }

    /**
     * Verify payment status
     * @param string $payment_intent_id Payment intent ID
     * @return array Payment status data
     */
    public static function verifyPayment($payment_intent_id) {
        try {
            if (!self::initStripe()) {
                return [
                    'success' => false,
                    'error' => 'Stripe library not installed.'
                ];
            }

            $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'payment_method' => $paymentIntent->payment_method,
                'charges' => $paymentIntent->charges->data
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process refund
     * @param string $payment_intent_id Payment intent ID
     * @param float $amount Amount to refund (optional, full refund if not specified)
     * @return array Refund result
     */
    public static function processRefund($payment_intent_id, $amount = null) {
        try {
            if (!self::initStripe()) {
                return [
                    'success' => false,
                    'error' => 'Stripe library not installed.'
                ];
            }

            $refund_data = ['payment_intent' => $payment_intent_id];
            
            if ($amount !== null) {
                $refund_data['amount'] = (int)($amount * 100);
            }

            $refund = \Stripe\Refund::create($refund_data);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'status' => $refund->status,
                'amount' => $refund->amount / 100
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Mock payment processing (for testing without Stripe)
     * Remove this in production
     */
    public static function mockPayment($amount, $metadata = []) {
        // Simulate payment processing delay
        usleep(500000); // 0.5 seconds

        return [
            'success' => true,
            'client_secret' => 'mock_cs_' . bin2hex(random_bytes(16)),
            'payment_intent_id' => 'mock_pi_' . bin2hex(random_bytes(16)),
            'mock' => true
        ];
    }

    /**
     * Validate card details (basic validation)
     */
    public static function validateCard($card_number, $exp_month, $exp_year, $cvc) {
        $errors = [];

        // Remove spaces and dashes
        $card_number = preg_replace('/[\s-]/', '', $card_number);

        // Validate card number (Luhn algorithm)
        if (!self::luhnCheck($card_number)) {
            $errors[] = 'Invalid card number';
        }

        // Validate expiration
        $current_year = (int)date('Y');
        $current_month = (int)date('m');
        
        if ($exp_year < $current_year || ($exp_year == $current_year && $exp_month < $current_month)) {
            $errors[] = 'Card has expired';
        }

        // Validate CVC
        if (!preg_match('/^\d{3,4}$/', $cvc)) {
            $errors[] = 'Invalid CVC';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Luhn algorithm for credit card validation
     */
    private static function luhnCheck($number) {
        $sum = 0;
        $alt = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int)$number[$i];

            if ($alt) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $alt = !$alt;
        }

        return $sum % 10 === 0;
    }

    /**
     * Get card brand from number
     */
    public static function getCardBrand($card_number) {
        $card_number = preg_replace('/[\s-]/', '', $card_number);

        $patterns = [
            'visa' => '/^4/',
            'mastercard' => '/^5[1-5]/',
            'amex' => '/^3[47]/',
            'discover' => '/^6(?:011|5)/',
        ];

        foreach ($patterns as $brand => $pattern) {
            if (preg_match($pattern, $card_number)) {
                return $brand;
            }
        }

        return 'unknown';
    }

    /**
     * Format amount for display
     */
    public static function formatAmount($amount) {
        return 'Rs ' . number_format($amount, 2);
    }
}
?>