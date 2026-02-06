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

**UPDATE (2026-02-06)**: ✅ **RESOLVED** - PHPMailer has been upgraded to v6.x!

The project has been successfully updated to use PHPMailer v6.12.0, resolving all known security vulnerabilities that were present in v5.2.x.

**Previously Affected Security Advisories (Now Fixed)**: PKSA-rh9h-fj14-12r3, PKSA-35kn-2ddp-d3p4, PKSA-m8by-bb7v-7qt5, PKSA-8sw7-9x88-c8bx, PKSA-g8hj-dw43-q8td, PKSA-dn5d-4vy3-wsfy, PKSA-y9zp-7yqg-8bmt, PKSA-mjxt-24k3-8rt7, PKSA-5nj1-dvnw-7cyx, PKSA-nm9v-1tjm-2cvc

#### Solution Implemented: PHPMailer v6.x Upgrade

The upgrade process included:

1. **Created Local dolumar-engine Package**
   - Cloned `dolumar-engine` to `packages/dolumar-engine/`
   - Updated composer.json to use local path repository

2. **Updated PHPMailer Dependency**
   - Changed from `phpmailer/phpmailer: ~5.2` to `^6.9` in dolumar-engine
   - Successfully installed PHPMailer v6.12.0

3. **Modernized Email Code** (`dolumar-engine/src/connect.php`)
   - Updated to use namespaced class: `\PHPMailer\PHPMailer\PHPMailer`
   - Changed deprecated methods: `IsSMTP()` → `isSMTP()`, `AddAddress()` → `addAddress()`
   - Updated `From`/`FromName` properties to use `setFrom()` method
   - Added proper exception handling for `\PHPMailer\PHPMailer\Exception`

4. **Removed Incompatible Dependencies**
   - Removed MDB2 dependencies (replaced by PDO in main codebase)
   - Removed ivan-novakov/php-openid-connect-client (PHP 5-7 only)

5. **Updated Repository Configuration**
   - Changed repository URL from SSH to HTTPS format
   - Removed security advisories from `audit.ignore` configuration

Benefits of PHPMailer v6.x:
- **Security**: Fixes all 10 known security vulnerabilities from v5.2.x
- **Modern PHP**: Compatible with PHP 8.x namespaces and features
- **Better Error Handling**: Improved exception handling and debugging
- **Active Maintenance**: Continues to receive security updates

### PEAR Package Dependencies

The `catlabinteractive/dolumar-engine` package previously required PEAR packages that are no longer directly supported by Composer 2.x:
- `pear-pear/MDB2`
- `pear-pear/MDB2_Driver_mysqli`

#### Solution Implemented: PDO Migration

The database layer has been migrated from MDB2/MySQLi to PDO:
- Created local `Neuron_DB_Database`, `Neuron_DB_MySQL`, and `Neuron_DB_Result` classes in `src/Neuron/DB/`
- These classes override the engine's MySQLi-based implementation
- All database operations now use PDO with proper error handling
- Removed MDB2 dependencies from composer.json
- Added `ext-pdo` and `ext-pdo_mysql` as requirements

Benefits of PDO:
- Native PHP extension (no external dependencies)
- Better security with prepared statements support
- Consistent interface across different database types
- Better exception handling
- PHP 8.x compatibility

### Other Legacy Dependencies

- **phpmailer**: ✅ **UPGRADED to v6.12.0** - All security vulnerabilities resolved
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
- Database connectivity (PDO-based database layer)
- Email functionality (PHPMailer)
- Environment variable loading (phpdotenv)
- Error reporting (Airbrake)

## Future Improvements

Consider these modernization steps for long-term maintenance:
1. **COMPLETED**: Migrated from MDB2 to PDO for database operations
2. **COMPLETED**: Upgraded PHPMailer to v6.x (resolves all security vulnerabilities)
3. Update Airbrake to latest version if newer features are needed
4. Replace Zend Framework components with modern alternatives (Symfony, Laravel components)
5. Add proper unit tests
6. Consider migrating from monolithic to service-oriented architecture

## Support

For questions or issues, please open an issue on the GitHub repository.
