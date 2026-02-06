# Upgrade Guide: PHP 8.x and Composer 2.x Compatibility

## Overview

This guide documents the changes made to update the Dolumar codebase to support PHP 8.1+ and Composer 2.x.

## Changes Made

### 1. PHP Version Requirement
- **Updated**: `php: ^7.1.0` → `php: ^8.1.0`
- The codebase now requires PHP 8.1 or higher

### 2. Repository URLs
- **Updated**: Changed from SSH format (`git@github.com:...`) to HTTPS format (`https://github.com/...`)
- This improves compatibility with environments that don't have SSH keys configured

### 3. Composer Configuration
- **Removed**: `secure-http: false` (deprecated in Composer 2.x)
- **Added**: `allow-plugins` configuration for Composer 2.x plugin support
  - `composer/installers`: true
  - `pear/pear_installer`: true

### 4. PEAR Repository Removed
- **Removed**: PEAR repository configuration (no longer supported in Composer 2.x)
- **Note**: PEAR packages (MDB2, MDB2_Driver_mysqli) are still required but must be handled differently

## Known Issues and Workarounds

### PEAR Package Dependencies

The `catlabinteractive/dolumar-engine` package requires PEAR packages that are no longer directly supported by Composer 2.x:
- `pear-pear/MDB2`
- `pear-pear/MDB2_Driver_mysqli`

#### Recommended Solutions:

1. **Option 1: Use dolumar-engine v1.0.6** (if available)
   - Version 1.0.6 uses `silverorange/mdb2` and `bondas83/mdb2_driver_mysqli` as alternatives
   - May require adjusting `minimum-stability` to `beta`

2. **Option 2: Manual PEAR Package Installation**
   - Install PEAR packages manually in the `pear/` directory
   - Configure autoloader to include PEAR classes

3. **Option 3: Fork and Update dolumar-engine**
   - Fork the dolumar-engine repository
   - Update it to use modern alternatives to MDB2 (e.g., PDO)
   - Update the composer requirement to point to your fork

### Other Legacy Dependencies

- **phpmailer**: Currently using v5.2.x which has known security vulnerabilities
  - Consider upgrading to v6.x+ if possible
- **phpdotenv**: Currently using v2.4.x
  - v5.x is available and compatible with PHP 8.x
  - May require updating usage from `$dotenv->load()` to `$dotenv->safeLoad()`

## Installation Instructions

### Fresh Installation

Since PEAR repositories are not supported in Composer 2.x, you may need to:

1. Use Composer 1.x for initial installation:
   ```bash
   composer self-update --1
   composer install
   composer self-update --2
   ```

2. Or manually install dependencies after updating composer.json

### Updating from Previous Version

1. Back up your current `vendor/` directory
2. Pull the latest changes
3. Run `composer update` (may require Composer 1.x workaround above)

## Testing

After upgrading, test the following:
- Database connectivity (MDB2 usage)
- Email functionality (PHPMailer)
- Environment variable loading (phpdotenv)
- Error reporting (Airbrake)

## Future Improvements

Consider these modernization steps for long-term maintenance:
1. Migrate from MDB2 to PDO for database operations
2. Update PHPMailer to v6.x+
3. Update phpdotenv to v5.x
4. Update Airbrake to latest version
5. Replace Zend Framework components with modern alternatives
6. Add proper unit tests

## Support

For questions or issues, please open an issue on the GitHub repository.
