# Security Notice

## Known Security Issues

### PHPMailer v5.2.x Vulnerabilities

This project currently uses PHPMailer v5.2.x (via the `catlabinteractive/dolumar-engine` dependency), which has **multiple known security vulnerabilities**.

**Affected Security Advisories:**
- PKSA-rh9h-fj14-12r3
- PKSA-35kn-2ddp-d3p4
- PKSA-m8by-bb7v-7qt5
- PKSA-8sw7-9x88-c8bx
- PKSA-g8hj-dw43-q8td
- PKSA-dn5d-4vy3-wsfy
- PKSA-y9zp-7yqg-8bmt
- PKSA-mjxt-24k3-8rt7
- PKSA-5nj1-dvnw-7cyx
- PKSA-nm9v-1tjm-2cvc

### Why These Vulnerabilities Are Present

The `catlabinteractive/dolumar-engine` package requires PHPMailer v5.2.x. Upgrading PHPMailer to v6.x+ would require updating the dolumar-engine package, which is maintained separately.

### Mitigation Strategies

Until the dolumar-engine package can be updated to use PHPMailer v6.x+, consider these mitigations:

1. **Restrict Email Functionality**
   - Limit email sending to trusted administrators only
   - Implement strict input validation for all email fields
   - Sanitize all user input before passing to PHPMailer

2. **Network Segmentation**
   - Run the application in a segmented network
   - Restrict outbound SMTP connections to known mail servers only

3. **Monitor Email Activity**
   - Log all email sending operations
   - Alert on unusual email patterns or volumes
   - Regularly review email logs for suspicious activity

4. **Regular Security Audits**
   - Perform regular security audits of email handling code
   - Keep track of new security advisories
   - Have an incident response plan ready

### Recommended Actions

**For Production Environments:**
1. Fork the `catlabinteractive/dolumar-engine` repository
2. Update it to use PHPMailer v6.x+
3. Test thoroughly
4. Update your composer.json to use your forked version

**For Development/Testing:**
- Accept the risk but ensure no sensitive data is processed
- Use a development-only SMTP server
- Do not expose to the internet

### Composer Configuration

The `audit.ignore` configuration in composer.json has been configured to suppress these warnings during `composer install/update`. This is necessary to allow installation but **does not fix the underlying security issues**.

## Reporting Security Issues

If you discover a security vulnerability in this codebase, please report it by opening a confidential issue on GitHub or contacting the maintainers directly.

## Additional Resources

- [PHPMailer Security Advisories](https://github.com/PHPMailer/PHPMailer/security/advisories)
- [Composer Audit Documentation](https://getcomposer.org/doc/03-cli.md#audit)
- [UPGRADE_PHP8.md](UPGRADE_PHP8.md) - Full upgrade documentation

## Last Updated

This security notice was last updated on 2026-02-06 when the PHP 8.x compatibility updates were made.
