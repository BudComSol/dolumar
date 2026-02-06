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

The `catlabinteractive/dolumar-engine` dependency has been updated to use PHPMailer v6.x with the following changes:

1. **Updated PHPMailer**: Changed from `~5.2` to `^6.9` in dolumar-engine's composer.json
2. **Code Modernization**: Updated email sending code to use PHPMailer v6 namespaced classes and methods
3. **Removed Legacy Dependencies**: Removed MDB2 dependencies in favor of PDO (already implemented in main codebase)
4. **Local Package**: dolumar-engine is now included as a local package in `packages/dolumar-engine` with all necessary updates

### Implementation Details

The dolumar-engine package now:
- Uses `\PHPMailer\PHPMailer\PHPMailer` with proper namespace imports
- Employs modern PHPMailer v6 method names (`isSMTP()`, `addAddress()`, `setFrom()`)
- Handles exceptions properly with `\PHPMailer\PHPMailer\Exception`
- Maintains backward compatibility with existing email configuration

## Current Security Posture

✅ **No Known Security Vulnerabilities**: As of the last update (2026-02-06), composer audit reports no security vulnerabilities.

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
   - Keep PHPMailer updated to the latest version
   - Run `composer audit` regularly to check for new vulnerabilities
   - Subscribe to security advisories for dependencies

## Reporting Security Issues

If you discover a security vulnerability in this codebase, please report it by opening a confidential issue on GitHub or contacting the maintainers directly.

## Additional Resources

- [PHPMailer Security Advisories](https://github.com/PHPMailer/PHPMailer/security/advisories)
- [Composer Audit Documentation](https://getcomposer.org/doc/03-cli.md#audit)
- [UPGRADE_PHP8.md](UPGRADE_PHP8.md) - Full upgrade documentation

## Last Updated

This security notice was last updated on 2026-02-06 when the PHP 8.x compatibility updates were made.
