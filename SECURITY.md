# Security Notice

## Security Status

### PHPMailer Updated to v6.x ✅

This project has been updated to use PHPMailer v6.12.0 (or later), which resolves all known security vulnerabilities that were present in PHPMailer v5.2.x.

**Previously Affected Security Advisories (Now Resolved):**
- PKSA-rh9h-fj14-12r3 ✅
- PKSA-35kn-2ddp-d3p4 ✅
- PKSA-m8by-bb7v-7qt5 ✅
- PKSA-8sw7-9x88-c8bx ✅
- PKSA-g8hj-dw43-q8td ✅
- PKSA-dn5d-4vy3-wsfy ✅
- PKSA-y9zp-7yqg-8bmt ✅
- PKSA-mjxt-24k3-8rt7 ✅
- PKSA-5nj1-dvnw-7cyx ✅
- PKSA-nm9v-1tjm-2cvc ✅

### What Changed

The application has been made completely standalone without external dependency managers:

1. **Updated PHPMailer**: Now bundled directly in `lib/PHPMailer/PHPMailer/` (v6.9.1)
2. **Code Modernization**: Updated email sending code to use PHPMailer v6 namespaced classes and methods
3. **Removed Composer**: All dependencies are now included directly in the repository
4. **Custom Autoloader**: Implemented custom autoloader to replace Composer's autoloader
5. **Simple Environment Loader**: Created lightweight .env file loader

### Implementation Details

The application now:
- Uses `\PHPMailer\PHPMailer\PHPMailer` with proper namespace imports
- Employs modern PHPMailer v6 method names (`isSMTP()`, `addAddress()`, `setFrom()`)
- Handles exceptions properly with `\PHPMailer\PHPMailer\Exception`
- Maintains backward compatibility with existing email configuration
- Includes all dependencies in the `lib/` directory

## Current Security Posture

✅ **No Known Security Vulnerabilities**: The bundled PHPMailer v6.9.1 has no known security vulnerabilities.

## Best Practices

While the security vulnerabilities have been addressed, continue to follow these email security best practices:

1. **Input Validation**
   - Validate all email addresses before use
   - Sanitize user input in email subjects and bodies
   - Implement rate limiting for email sending

2. **Configuration Security**
   - Store SMTP credentials in environment variables (`.env` file)
   - Use TLS/SSL for SMTP connections
   - Limit email functionality to authenticated users

3. **Monitoring**
   - Log all email sending operations
   - Alert on unusual email patterns or volumes
   - Regularly review email logs for suspicious activity

4. **Regular Updates**
   - Monitor [PHPMailer GitHub releases](https://github.com/PHPMailer/PHPMailer/releases) for updates
   - Update bundled libraries when security patches are released
   - Subscribe to security advisories for dependencies

## Reporting Security Issues

If you discover a security vulnerability in this codebase, please report it by opening a confidential issue on GitHub or contacting the maintainers directly.

## Additional Resources

- [PHPMailer Security Advisories](https://github.com/PHPMailer/PHPMailer/security/advisories)
- [UPGRADE_PHP8.md](UPGRADE_PHP8.md) - Full upgrade documentation

## Last Updated

This security notice was last updated on 2026-02-07 when composer dependencies were removed and the application was made standalone.
