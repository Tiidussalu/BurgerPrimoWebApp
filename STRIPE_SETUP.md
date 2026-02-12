# Burger Primo - Stripe Payment Integration

## Installation Instructions

### 1. Install Stripe PHP SDK

Run the following command in your project root:

```bash
composer require stripe/stripe-php
```

### 2. Add Stripe Keys to .env

Add these lines to your `.env` file with your Stripe sandbox keys:

```env
STRIPE_PUBLIC_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET_KEY=sk_test_your_secret_key_here
```

**To get your Stripe keys:**
1. Go to https://dashboard.stripe.com/test/apikeys
2. Copy your "Publishable key" (starts with `pk_test_`)
3. Copy your "Secret key" (starts with `sk_test_`)

### 3. Run Database Migration

Run the migration to add payment fields to the orders table:

```bash
php artisan migrate
```

### 4. Build Frontend Assets

Rebuild your Vue.js assets to include the new payment page:

```bash
npm run build
# or for development
npm run dev
```

### 5. Test the Integration

1. Add items to your cart
2. Click "Vormista tellimus" in the cart
3. Select delivery method (Kohapeal or Kaasa)
4. You'll be redirected to the payment page
5. Use Stripe test card numbers:
   - Success: `4242 4242 4242 4242`
   - Requires authentication: `4000 0025 0000 3155`
   - Declined: `4000 0000 0000 9995`
   - Use any future expiry date, any 3-digit CVC, and any postal code

## Changes Made

### Backend Changes

1. **Database Migration**: `2025_02_12_000001_add_payment_fields_to_orders_table.php`
   - Added `payment_intent_id`, `payment_status`, `payment_method`, `paid_at` fields

2. **PaymentController**: `app/Http/Controllers/PaymentController.php`
   - `checkout()`: Shows payment page
   - `createIntent()`: Creates Stripe Payment Intent
   - `processPayment()`: Processes successful payment and creates order

3. **Order Model**: Updated fillable fields and casts

4. **Configuration**: Added Stripe keys to `config/services.php`

5. **Routes**: Added payment routes in `routes/web.php`

### Frontend Changes

1. **Payment Checkout Page**: `resources/js/pages/Payment/Checkout.vue`
   - Stripe Elements integration
   - Card payment form
   - Delivery method selection
   - Customer notes field

2. **Cart Page**: Modified `resources/js/pages/Cart/Index.vue`
   - Fixed pricing bug (removed +3 euros)
   - Updated checkout to redirect to payment page

## Payment Flow

1. User adds items to cart
2. User clicks "Vormista tellimus" → redirected to `/payment/checkout`
3. User selects delivery method and enters payment details
4. Frontend calls `/api/payment/create-intent` to create Stripe Payment Intent
5. Stripe.js confirms payment with card details
6. Frontend calls `/api/payment/process` to create order in database
7. User is redirected to order confirmation page

## Security Notes

- Never commit your actual Stripe secret keys to version control
- Use test keys (pk_test_* and sk_test_*) for development
- In production, use live keys (pk_live_* and sk_live_*)
- Stripe handles all sensitive card data - it never touches your server

## Troubleshooting

### "Stripe not found" error
Make sure you've installed the Stripe PHP SDK:
```bash
composer require stripe/stripe-php
```

### Payment page shows "undefined"
Make sure your `.env` file has the STRIPE_PUBLIC_KEY set and run:
```bash
php artisan config:clear
php artisan config:cache
```

### Card element not displaying
Check browser console for errors. Make sure Stripe.js is loading properly.

## Next Steps (Optional Enhancements)

1. **Add webhook handling** for payment events (refunds, disputes, etc.)
2. **Save payment methods** for returning customers
3. **Add Apple Pay / Google Pay** using Stripe's Payment Request Button
4. **Email receipts** after successful payment
5. **Admin dashboard** to view payment status and refund orders
