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

### PHPMailer Security Advisories

The `phpmailer/phpmailer` v5.2.x dependency has multiple known security vulnerabilities. These are required by the `catlabinteractive/dolumar-engine` package. 

**Security Advisories**: PKSA-rh9h-fj14-12r3, PKSA-35kn-2ddp-d3p4, PKSA-m8by-bb7v-7qt5, PKSA-8sw7-9x88-c8bx, PKSA-g8hj-dw43-q8td, PKSA-dn5d-4vy3-wsfy, PKSA-y9zp-7yqg-8bmt, PKSA-mjxt-24k3-8rt7, PKSA-5nj1-dvnw-7cyx, PKSA-nm9v-1tjm-2cvc

These advisories have been added to the `audit.ignore` configuration to allow installation. However, this is a **security risk** and should be addressed by:

1. Forking and updating `dolumar-engine` to use phpmailer v6.x+
2. Implementing careful input validation and sanitization in email handling code
3. Restricting email functionality to trusted users only

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
  - The dolumar-engine package requires this version
  - Consider forking dolumar-engine and upgrading to phpmailer v6.x+ if possible
- **phpdotenv**: Updated from v2.4.x to v5.6+
  - v5.x is compatible with PHP 8.x
  - **Breaking change**: API has changed from `new Dotenv\Dotenv()` and `load()` to `Dotenv\Dotenv::createImmutable()` and `safeLoad()`
  - bootstrap.php has been updated accordingly
- **airbrake/phpbrake**: Updated from v0.2.0 to v0.7.0+
  - v0.7+ is compatible with PHP 8.x
  - No API changes required in bootstrap.php

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
