# Quick Testing Guide

## Setup Steps

1. **Install Stripe SDK:**
   ```bash
   composer require stripe/stripe-php
   ```

2. **Add to .env:**
   ```env
   STRIPE_PUBLIC_KEY=pk_test_YOUR_KEY
   STRIPE_SECRET_KEY=sk_test_YOUR_KEY
   ```

3. **Run migration:**
   ```bash
   php artisan migrate
   ```

4. **Rebuild assets:**
   ```bash
   npm run dev
   ```

## Test Cards

| Purpose | Card Number | Result |
|---------|-------------|--------|
| Success | 4242 4242 4242 4242 | Payment succeeds |
| Auth Required | 4000 0025 0000 3155 | Requires 3D Secure |
| Declined | 4000 0000 0000 9995 | Card declined |
| Insufficient Funds | 4000 0000 0000 9995 | Insufficient funds |

**For all test cards:**
- Use any future expiry date (e.g., 12/25)
- Use any 3-digit CVC (e.g., 123)
- Use any postal code (e.g., 12345)

## Testing Flow

1. Login to your account
2. Add burgers/items to cart
3. Go to cart page
4. Click "Vormista tellimus"
5. Select "Kohapeal" or "Kaasa"
6. You should be on `/payment/checkout`
7. Fill in test card: 4242 4242 4242 4242
8. Click "Maksa €X.XX"
9. Should redirect to order confirmation

## Common Issues

### Issue: "Stripe not found"
**Solution:** Run `composer require stripe/stripe-php`

### Issue: Cart price still shows +3 euros
**Solution:** Clear cache: `npm run build` or restart `npm run dev`

### Issue: Payment page shows undefined for Stripe key
**Solution:** 
```bash
php artisan config:clear
php artisan config:cache
```

### Issue: Card element not showing
**Solution:** Check browser console for errors. Stripe.js needs to load from CDN.
