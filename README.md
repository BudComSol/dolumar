# Dolumar
Play now at http://www.dolumar.com/

## Requirements
- PHP 8.1 or higher
- MySQL/MariaDB database
- Memcached extension (optional, for improved caching)
- Required PHP extensions: bcmath, gd, mbstring, pdo, pdo_mysql

## Quick Setup
Dolumar is a standalone PHP web application that can be run on any server with the required dependencies. No external package manager (composer) is required - all dependencies are included in the repository.

```bash
git clone https://github.com/BudComSol/dolumar.git
cd dolumar
cp .env.example .env
# Edit .env with your configuration
# Set up database, web server, and cron jobs (see detailed instructions below)
```

## Comprehensive Configuration Instructions

### 1. Prerequisites

Ensure your system has the required software installed:

**On Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install php8.1 php8.1-cli php8.1-bcmath php8.1-gd php8.1-mbstring php8.1-pdo php8.1-mysql
sudo apt-get install mysql-server
```

**On other systems:**
- Install PHP 8.1 or higher with the required extensions
- Install MySQL 5.7+ or MariaDB 10.3+

**Optional (for improved caching):**
```bash
sudo apt-get install php8.1-memcached memcached
```

### 2. Database Setup

Create a new MySQL/MariaDB database and user:

```bash
# Log into MySQL
mysql -u root -p

# Create database
CREATE DATABASE dolumar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user and grant privileges
CREATE USER 'dolumar_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON dolumar.* TO 'dolumar_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Import the database schema:

```bash
mysql -u dolumar_user -p dolumar < dump/dump.sql
```

### 3. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Edit the `.env` file with your settings:

#### Required Settings:

**Database Connection:**
```
DATABASE_URL=mysql://dolumar_user:your_secure_password@localhost/dolumar
```

**SMTP Email Settings** (required for user registration and email validation):
```
EMAIL_SMTP_SERVER=smtp.example.com
EMAIL_SMTP_PORT=587
EMAIL_SMTP_SECURE=tls
EMAIL_SMTP_USERNAME=your_smtp_username
EMAIL_SMTP_PASSWORD=your_smtp_password
```

**Protocol** (use https in production):
```
PROTOCOL=https
```

#### Optional Settings:

**Memcached** (for improved caching):
```
MEMCACHIER_SERVERS=localhost:11211
MEMCACHIER_USERNAME=
MEMCACHIER_PASSWORD=
```

**CatLab Credits** (for paid features):
```
CREDITS_GAME_TOKEN=your_game_token
CREDITS_PRIVATE_KEY=/path/to/private_key.pem
```

**Server List** (to track multiple game servers):
```
SERVERLIST_URL=https://your-serverlist-api.com
SERVERLIST_PUBLIC_KEY=/path/to/public_key.pem
SERVERLIST_PRIVATE_KEY=/path/to/private_key.pem
```

**Error Tracking** (optional):
```
AIRBRAKE_HOST=
AIRBRAKE_TOKEN=
```

**Email Debugging** (use level 0 in production, 4 for full debug):
```
EMAIL_DEBUG_LEVEL=0
EMAIL_LOGGING=0
```

**Wiki Guide URL:**
```
WIKI_GUIDE_URL=http://wiki.dolumar.com/
```

**Login Redirect:**
```
NOLOGIN_REDIRECT=
```

### 4. Web Server Configuration

Configure your web server to serve the application from the `public/` directory.

#### Apache Configuration

Enable required modules:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**Example Virtual Host** (`/etc/apache2/sites-available/dolumar.conf`):
```apache
<VirtualHost *:80>
    ServerName dolumar.example.com
    DocumentRoot /var/www/dolumar/public

    <Directory /var/www/dolumar/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dolumar_error.log
    CustomLog ${APACHE_LOG_DIR}/dolumar_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite dolumar.conf
sudo systemctl reload apache2
```

#### Nginx Configuration

**Example Server Block** (`/etc/nginx/sites-available/dolumar`):
```nginx
server {
    listen 80;
    server_name dolumar.example.com;
    root /var/www/dolumar/public;
    
    index index.php;

    location / {
        try_files $uri $uri/ /dispatch.php?module=$uri&$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/dolumar /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. Cron Job Setup

Dolumar requires cron jobs to process game events, battles, and other periodic tasks.

Add these entries to your crontab (`crontab -e`):

```cron
# Run constantly (every minute) - processes game events
* * * * * /usr/bin/php /var/www/dolumar/cron/constantly.php >> /var/log/dolumar-constantly.log 2>&1

# Run daily (at 3:00 AM) - cleanup and maintenance tasks
0 3 * * * /usr/bin/php /var/www/dolumar/cron/daily.php >> /var/log/dolumar-daily.log 2>&1
```

**Note:** Adjust the paths to match your installation directory and PHP binary location.

Create log directories if needed:
```bash
sudo mkdir -p /var/log
sudo touch /var/log/dolumar-constantly.log /var/log/dolumar-daily.log
sudo chown www-data:www-data /var/log/dolumar-*.log
```

### 6. File Permissions

Ensure the web server has appropriate permissions:

```bash
# Set ownership to web server user
sudo chown -R www-data:www-data /var/www/dolumar

# Set directory permissions
sudo find /var/www/dolumar -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/dolumar -type f -exec chmod 644 {} \;

# Make cron scripts executable
sudo chmod +x /var/www/dolumar/cron/*.php

# Ensure cache directory is writable
sudo mkdir -p /tmp/dolumar
sudo chown www-data:www-data /tmp/dolumar
sudo chmod 755 /tmp/dolumar
```

### 7. First-Time Setup

1. Access your server in a web browser: `http://dolumar.example.com`
2. If a setup page exists, follow the on-screen instructions
3. Otherwise, you can manually access `http://dolumar.example.com/setup.php` if available
4. Create your first admin user account
5. Test email functionality by registering a new user

### 8. SSL/HTTPS Configuration (Recommended for Production)

For production deployments, use Let's Encrypt for free SSL certificates:

```bash
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d dolumar.example.com
```

Or for Nginx:
```bash
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d dolumar.example.com
```

Update your `.env` file:
```
PROTOCOL=https
```

### 9. Optional Features

#### Memcached Configuration

If you installed Memcached, configure it in `.env`:
```
MEMCACHIER_SERVERS=localhost:11211
```

Start the Memcached service:
```bash
sudo systemctl start memcached
sudo systemctl enable memcached
```

#### CatLab Credits Setup

If you want to offer paid features on your server, you will need to setup an account on the CatLab credits framework. Contact us at support@catlab.be in order to get you up and running, or you can write your own payment gateway.

Once configured, add to `.env`:
```
CREDITS_GAME_TOKEN=your_game_token
CREDITS_PRIVATE_KEY=/path/to/private_key.pem
```

### 10. Troubleshooting

#### Database Connection Issues
- Verify DATABASE_URL format: `mysql://username:password@host/database`
- Check database credentials and ensure the database exists
- Verify MySQL/MariaDB is running: `sudo systemctl status mysql`

#### Email Not Sending
- Verify SMTP settings in `.env`
- Test SMTP connection with a simple PHP script
- Check EMAIL_DEBUG_LEVEL=4 for detailed error messages
- Review web server error logs

#### Blank Page or 500 Error
- Check PHP error logs: `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
- Verify all required PHP extensions are installed: `php -m`
- Check file permissions
- Ensure `.env` file exists and is readable

#### Cron Jobs Not Running
- Verify cron jobs are scheduled: `crontab -l`
- Check cron execution logs
- Ensure PHP binary path is correct
- Verify file permissions on cron scripts

#### Rewrite Rules Not Working
- Apache: Ensure `mod_rewrite` is enabled
- Verify `.htaccess` file exists in `public/` directory
- Check AllowOverride directive in Apache configuration

#### Cache Issues
- Clear cache directory: `rm -rf /tmp/dolumar/*`
- Verify cache directory is writable
- If using Memcached, restart the service: `sudo systemctl restart memcached`

## Local Development Setup

For a local (development) setup:

1. Use PHP's built-in server for quick testing:
   ```bash
   cd public
   php -S localhost:8000
   ```

2. Or use Docker (if you prefer containerization):
   ```bash
   # Example docker-compose.yml setup (create this file)
   # docker-compose up -d
   ```

3. Set development-friendly `.env` values:
   ```
   PROTOCOL=http
   EMAIL_DEBUG_LEVEL=4
   ```