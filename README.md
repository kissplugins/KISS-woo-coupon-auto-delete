# KISS Coupon Cleanup

A WordPress plugin that automatically deletes expired WooCommerce coupons.

## What It Does

This plugin runs once per minute and automatically deletes expired coupons from your WooCommerce store in small batches (20 at a time). This prevents expired coupons from accumulating in your database.

## How It Works

1. **Automatic Cleanup**: Runs every minute via WordPress cron
2. **Batch Processing**: Processes up to 20 expired coupons per run to avoid performance issues
3. **Simple Logic**: If a coupon has an expiration date and it's expired, it gets deleted
4. **Logging**: All deletions are logged using WooCommerce's built-in logger

## Requirements

- WordPress 5.8 or higher
- WooCommerce 6.0 or higher
- PHP 7.4 or higher

## Installation

1. Upload the `neochrome-coupon-cleanup` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! The plugin starts working automatically

## Configuration

The plugin works out of the box with these default settings:

- **Batch Size**: 20 coupons per minute
- **Logging**: Enabled (logs to WooCommerce logs)

To adjust these settings, edit the constants in `neochrome-coupon-cleanup.php`:

```php
define( 'NCC_BATCH_SIZE', 20 );        // Coupons per run
define( 'NCC_ENABLE_LOGGING', true );  // Enable/disable logging
```

## Viewing Logs

Deletion logs can be viewed in **WooCommerce → Status → Logs**. Look for logs with the source `neochrome-coupon-cleanup`.

## What Gets Deleted

The plugin deletes:
- ✅ Any coupon with an expiration date that has passed
- ✅ All coupon types (fixed cart, percentage, etc.)
- ✅ Coupons created by any source (theme, plugin, admin)

The plugin does NOT delete:
- ❌ Coupons without expiration dates
- ❌ Coupons that haven't expired yet

## Performance

The plugin is designed to be lightweight:
- Processes only 20 coupons per minute
- Uses efficient database queries
- Minimal server impact
- Works perfectly with WP Engine's cron system

## Troubleshooting

**Plugin not working?**
- Make sure WooCommerce is installed and activated
- Check that WordPress cron is working (some hosts disable it)
- Review WooCommerce logs for any error messages

**Want to verify it's running?**
- Create a test coupon with an expiration date in the past
- Wait 1-2 minutes
- Check if the coupon was deleted
- Check WooCommerce logs for the deletion entry

## Version

**Current Version**: 1.0.0

## Author

Neochrome

## License

This plugin is proprietary software.
