# Dolumar Standalone Setup

Dolumar is now a completely standalone PHP application with no external dependency managers required.

## What Changed

### Removed Dependencies
- **Composer**: No longer required for installation or updates
- **Airbrake**: Removed due to complex dependencies (optional error tracking)
- **phpdotenv**: Replaced with simple custom environment loader

### Added Components
- **Custom Autoloader** (`bootstrap/autoload.php`): Handles both namespaced classes and underscore-based classes
- **Simple Environment Loader** (`bootstrap/env_loader.php`): Loads `.env` files without external dependencies
- **Bundled Libraries** (in `lib/` directory):
  - PHPMailer v6.9.1 - Email functionality
  - php-openid - OpenID authentication

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/BudComSol/dolumar.git
   cd dolumar
   ```

2. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

3. Configure your environment variables in `.env`:
   - Database connection (DATABASE_URL)
   - SMTP settings for email
   - Other optional settings

4. Point your web server to the `public/` directory

5. Access the application through your web browser

That's it! No `composer install` required.

## Directory Structure

```
dolumar/
├── bootstrap/           # Bootstrap and configuration
│   ├── autoload.php    # Custom class autoloader
│   ├── env_loader.php  # Environment variable loader
│   └── bootstrap.php   # Main bootstrap file
├── lib/                # Bundled third-party libraries
│   ├── PHPMailer/     # Email library
│   └── openid/        # OpenID authentication
├── packages/           # Local packages
│   └── dolumar-engine/ # Game engine
├── public/            # Web-accessible directory
│   └── index.php      # Main entry point
├── src/               # Application source code
└── .env               # Environment configuration (create from .env.example)
```

## Updating Libraries

When security updates are available for bundled libraries:

1. **PHPMailer**: Download the latest version from [PHPMailer releases](https://github.com/PHPMailer/PHPMailer/releases)
   - Extract to `lib/PHPMailer/PHPMailer/`
   - Ensure the namespace structure matches: `PHPMailer\PHPMailer`

2. **php-openid**: Download from [php-openid repository](https://github.com/openid/php-openid)
   - Extract the `Auth/` directory to `lib/openid/Auth/`

## Troubleshooting

### Classes not loading
- Check that `bootstrap/autoload.php` is being loaded first
- Verify the class name matches the file path (underscores become slashes)
- For namespaced classes, ensure the directory structure matches the namespace

### Environment variables not working
- Ensure `.env` file exists and is readable
- Check that `bootstrap/env_loader.php` is loaded before accessing environment variables
- Verify the format: `KEY=VALUE` (one per line)

### Email not sending
- Verify SMTP settings in `.env` file
- Check that PHPMailer files exist in `lib/PHPMailer/PHPMailer/`
- Review error logs for specific PHPMailer errors

## Benefits of Standalone Setup

- **Simpler Deployment**: Just clone and configure
- **No Build Step**: No `composer install` required
- **Version Control**: All dependencies are versioned with the code
- **Predictable**: No dependency resolution conflicts
- **Faster Setup**: Get running in minutes, not hours

## Support

For issues or questions, please open an issue on the GitHub repository.
