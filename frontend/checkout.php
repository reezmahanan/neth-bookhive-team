<?php
$page_title = "Checkout - NETH Bookhive";
$extra_js = ['js/checkout.js'];

// Add inline styles for checkout page
$extra_css_inline = '
<style>
    .checkout-section {
        padding: 120px 0 80px;
        margin-top: 60px;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
    }

    .checkout-form {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .checkout-form h2 {
        margin-bottom: 2rem;
        color: #2c3e50;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #ecf0f1;
    }

    .form-section h3 {
        margin-bottom: 1rem;
        color: #3498db;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .order-summary {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: start;
        padding: 1rem 0;
        border-bottom: 1px solid #ecf0f1;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-info h4 {
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .order-item-info p {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .order-totals {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #ecf0f1;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .grand-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
        border-top: 1px solid #ecf0f1;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }

    .field-error {
        color: #e74c3c;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    input.error, select.error {
        border-color: #e74c3c !important;
    }

    .order-success {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .success-icon {
        font-size: 4rem;
        color: #27ae60;
        margin-bottom: 1rem;
    }

    .order-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 5px;
        margin: 2rem 0;
        text-align: left;
    }

    .success-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .order-summary {
            position: static;
        }

        .success-actions {
            flex-direction: column;
        }
    }
</style>
';

include 'includes/header.php';
echo $extra_css_inline;
?>

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="container">
            <h1>Checkout</h1>
            <div class="checkout-content">
                <div class="checkout-form">
                    <form id="checkoutForm">
                        <!-- Shipping Information -->
                        <div class="form-section">
                            <h3>Shipping Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullName">Full Name *</label>
                                    <input type="text" id="fullName" name="fullName" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address *</label>
                                    <input type="text" id="address" name="address" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">City *</label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="state">State *</label>
                                    <select id="state" name="state" required>
                                        <option value="">Select State</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="zipCode">ZIP Code *</label>
                                    <input type="text" id="zipCode" name="zipCode" required>
                                </div>
                                <div class="form-group">
                                    <label for="country">Country *</label>
                                    <select id="country" name="country" required>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="UK">United Kingdom</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="form-section">
                            <h3>Payment Information</h3>
                            <div class="form-group">
                                <label for="paymentMethod">Payment Method *</label>
                                <select id="paymentMethod" name="paymentMethod" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="credit">Credit Card</option>
                                    <option value="debit">Debit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>
                            
                            <div id="creditCardFields">
                                <div class="form-group">
                                    <label for="cardNumber">Card Number *</label>
                                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="expiryDate">Expiry Date *</label>
                                        <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                                    </div>
                                    <div class="form-group">
                                        <label for="cvv">CVV *</label>
                                        <input type="text" id="cvv" name="cvv" placeholder="123">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cardName">Name on Card *</label>
                                    <input type="text" id="cardName" name="cardName">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            Place Order
                        </button>
                    </form>
                </div>

                <div class="order-summary">
                    <div id="orderSummary">
                        <!-- Order summary will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
