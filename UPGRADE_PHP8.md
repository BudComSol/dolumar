# Upgrade Guide: PHP 8.x Compatibility

## Overview

This guide documents the changes made to update the Dolumar codebase to support PHP 8.1+ as a standalone application without external dependency managers.

## Changes Made

### 1. PHP Version Requirement
- **Updated**: `php: ^7.1.0` → `php: ^8.1.0`
- The codebase now requires PHP 8.1 or higher

### 2. Removed Composer Dependency
- **Removed**: All composer dependencies
- **Added**: Custom autoloader (`bootstrap/autoload.php`)
- **Added**: Simple environment loader (`bootstrap/env_loader.php`)
- **Vendored**: PHPMailer v6.9.1 and php-openid libraries directly in `lib/` directory
- The application is now completely standalone with no external package manager required

### 3. Security and Error Tracking
- **Removed**: Airbrake dependency (too complex with sub-dependencies)
- Error logging now uses PHP's built-in error_log function
- For production error tracking, consider implementing a custom lightweight solution

### 4. Environment Variables
- **Replaced**: vlucas/phpdotenv with custom simple .env file loader
- Supports basic KEY=VALUE pairs in .env files
- Comments (lines starting with #) are ignored

## Known Issues and Workarounds

### PHPMailer Security

✅ **RESOLVED** - PHPMailer has been upgraded to v6.9.1!

The project now uses PHPMailer v6.9.1 bundled in the `lib/` directory, resolving all known security vulnerabilities that were present in v5.2.x.

Benefits of PHPMailer v6.x:
- **Security**: Fixes all known security vulnerabilities from v5.2.x
- **Modern PHP**: Compatible with PHP 8.x namespaces and features
- **Better Error Handling**: Improved exception handling and debugging
- **Active Maintenance**: Continues to receive security updates

### Database Layer

The database layer has been migrated from MDB2/MySQLi to PDO:
- Uses native `Neuron_DB_Database`, `Neuron_DB_MySQL`, and `Neuron_DB_Result` classes in `src/Neuron/DB/`
- All database operations use PDO with proper error handling
- Requires `ext-pdo` and `ext-pdo_mysql` extensions

Benefits of PDO:
- Native PHP extension (no external dependencies)
- Better security with prepared statements support
- Consistent interface across different database types
- Better exception handling
- PHP 8.x compatibility

## Installation Instructions

### Fresh Installation

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your environment variables
3. Point your web server to the `public/` directory
4. Configure your database settings in the `.env` file
5. Run the setup script if available

No composer installation required - all dependencies are included!

### Updating from Previous Version

1. Back up your current installation
2. Pull the latest changes
3. Update your `.env` file with any new required variables
4. Test thoroughly in a development environment first

## Testing

After upgrading, test the following:
- Database connectivity (PDO-based database layer)
- Email functionality (PHPMailer)
- Environment variable loading (.env file)
- Application functionality

## Future Improvements

Consider these modernization steps for long-term maintenance:
1. ✅ **COMPLETED**: Migrated from MDB2 to PDO for database operations
2. ✅ **COMPLETED**: Upgraded PHPMailer to v6.x (resolves all security vulnerabilities)
3. ✅ **COMPLETED**: Removed composer dependency, made standalone
4. Add proper unit tests
5. Consider migrating from monolithic to service-oriented architecture
6. Implement custom lightweight error tracking if needed

## Support

For questions or issues, please open an issue on the GitHub repository.
