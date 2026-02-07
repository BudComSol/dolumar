# Dependency Management in Dolumar

## Overview

**Dolumar does NOT use Composer or a `vendor/` folder.** Instead, all dependencies are bundled directly in the repository and loaded via a custom autoloader.

## Where Are the Dependencies?

If you're looking for dependencies, they are located in these directories:

### 1. `/lib/` - Third-Party Libraries
Contains bundled external libraries that would typically be in `vendor/`:
- **PHPMailer** (`lib/PHPMailer/PHPMailer/`) - Email functionality (v6.9.1)
  - Namespace: `PHPMailer\PHPMailer`
  - Files: PHPMailer.php, SMTP.php, Exception.php, etc.
- **php-openid** (`lib/openid/`) - OpenID authentication
  - Namespace: `Auth_*` classes
  - Files: Auth/OpenID/*.php

### 2. `/packages/` - Internal Packages
Contains first-party packages:
- **dolumar-engine** (`packages/dolumar-engine/`) - The game engine
  - Source: `packages/dolumar-engine/src/`

### 3. `/pear/` - PEAR Libraries
Contains legacy PEAR libraries:
- Console, Image, OS, System utilities
- PEAR base classes

### 4. `/src/` - Application Code
Main application classes:
- `Neuron_*` classes
- `Dolumar_*` classes
- Application-specific code

## Where Is the Autoloader?

**The autoloader is NOT at `vendor/autoload.php`.**

Instead, it's located at:
```
bootstrap/autoload.php
```

This custom autoloader handles:
- Namespace-based classes (PSR-4 style): `PHPMailer\PHPMailer\PHPMailer`
- Underscore-based classes (PEAR style): `Neuron_Core_Database` → `Neuron/Core/Database.php`
- Special cases: `Auth_*` classes for OpenID

## How to Use Dependencies in Your Code

All dependencies are automatically loaded. Just use them:

```php
<?php
// In any entry point (e.g., public/index.php)
require_once '../bootstrap/bootstrap.php';

// Now you can use any class
use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer();

// Or underscore-based classes
$db = Neuron_Core_Database::__getInstance();
```

## Why No Composer?

Dolumar is designed as a **standalone application** with these benefits:

1. **Simple Deployment** - Just clone and run, no build step
2. **No Dependency Conflicts** - All dependencies are versioned with the code
3. **Predictable** - Same versions everywhere, always
4. **Fast Setup** - No waiting for `composer install`
5. **Version Control** - Dependencies are tracked in git

## Adding or Updating Dependencies

### To Add a New Library:

1. Download the library source code
2. Place it in the appropriate location:
   - Third-party libraries → `lib/LibraryName/`
   - Internal packages → `packages/package-name/`
3. Ensure the directory structure matches the namespace
4. The autoloader will automatically find it

### To Update an Existing Library:

#### PHPMailer:
```bash
# Download from https://github.com/PHPMailer/PHPMailer/releases
cd lib/PHPMailer
# Extract new version, ensuring structure is: PHPMailer/PHPMailer/*.php
```

#### php-openid:
```bash
# Download from https://github.com/openid/php-openid
cd lib/openid
# Extract Auth/ directory to lib/openid/Auth/
```

## Compatibility Note

If you're used to Composer and expecting:
- ❌ `vendor/` folder → Dependencies are in `lib/`, `packages/`, `pear/`
- ❌ `vendor/autoload.php` → Use `bootstrap/autoload.php` instead
- ❌ `composer.json` → Not used (intentionally excluded in `.gitignore`)
- ❌ `composer install` → Not needed, everything is included

## For IDE Autocomplete

Most modern IDEs can scan the `lib/`, `packages/`, and `src/` directories for classes. Configure your IDE to include these paths for autocomplete:

### PhpStorm:
Settings → PHP → Include Paths → Add:
- `/path/to/dolumar/lib`
- `/path/to/dolumar/packages`
- `/path/to/dolumar/src`

### VS Code:
Add to `settings.json`:
```json
{
    "php.suggest.basic": true,
    "intelephense.environment.includePaths": [
        "/path/to/dolumar/lib",
        "/path/to/dolumar/packages",
        "/path/to/dolumar/src"
    ]
}
```

## Troubleshooting

### "Class not found" errors:
1. Check that `bootstrap/bootstrap.php` is loaded at the top of your entry file
2. Verify the class file exists in the expected location
3. Ensure the namespace/class name matches the file path
4. Check file permissions (files must be readable)

### "Cannot find autoload.php":
- Don't look for `vendor/autoload.php`
- Use `bootstrap/autoload.php` (or better, `bootstrap/bootstrap.php`)

## Questions?

See also:
- `STANDALONE.md` - Detailed standalone setup guide
- `README.md` - Quick start and setup instructions
- `bootstrap/autoload.php` - The actual autoloader implementation
